<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ServiceJetonSecuriteRedacteur
{
    /**
     * Durée de validité par défaut du jeton en secondes (1 heure).
     */
    public const DUREE_VALIDITE_SECONDES = 3600;

    /**
     * Génère l'empreinte numérique unique de l'appareil client du rédacteur.
     * Combine l'agent utilisateur, l'adresse IP et les en-têtes d'environnement client.
     */
    public function genererEmpreinteAppareil(Request $requete): string
    {
        $agentUtilisateur = $requete->userAgent() ?? 'agent_inconnu';
        $adresseIp = $requete->ip() ?? '127.0.0.1';
        $langueClient = $requete->header('Accept-Language', 'fr');
        $signatureNavigateur = $requete->header('Sec-Ch-Ua', '');

        $chaineBrute = implode('|', [
            $agentUtilisateur,
            $adresseIp,
            $langueClient,
            $signatureNavigateur,
        ]);

        return hash('sha256', $chaineBrute);
    }

    /**
     * Génère un jeton JWT sécurisé lié au login du rédacteur et à l'empreinte de son appareil.
     */
    public function genererJeton(User $utilisateur, Request $requete, int $dureeSecondes = self::DUREE_VALIDITE_SECONDES): string
    {
        $cleSecrete = $this->obtenirCleSecrete();
        $empreinteAppareil = $this->genererEmpreinteAppareil($requete);
        $tempsCourant = time();

        $enTete = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $chargeUtile = [
            'sub' => $utilisateur->id,
            'login' => $utilisateur->email,
            'role' => $utilisateur->role,
            'empreinte' => $empreinteAppareil,
            'iat' => $tempsCourant,
            'exp' => $tempsCourant + $dureeSecondes,
            'alea' => bin2hex(random_bytes(8)),
        ];

        $enTeteEncode = $this->encoderBase64Url(json_encode($enTete, JSON_THROW_ON_ERROR));
        $chargeUtileEncodee = $this->encoderBase64Url(json_encode($chargeUtile, JSON_THROW_ON_ERROR));

        $signature = hash_hmac('sha256', "{$enTeteEncode}.{$chargeUtileEncodee}", $cleSecrete, true);
        $signatureEncodee = $this->encoderBase64Url($signature);

        return "{$enTeteEncode}.{$chargeUtileEncodee}.{$signatureEncodee}";
    }

    /**
     * Valide rigoureusement le jeton JWT :
     * 1. Signature cryptographique conforme avec la clé serveur
     * 2. Expiration temporelle valide
     * 3. Concordance stricte avec le login de l'utilisateur authentifié
     * 4. Concordance stricte avec les caractéristiques de l'appareil appelant
     *
     * @return array<string, mixed>|null Retourne la charge utile si valide, null sinon.
     */
    public function validerJeton(string $jeton, User $utilisateur, Request $requete): ?array
    {
        try {
            $parties = explode('.', trim($jeton));
            if (count($parties) !== 3) {
                Log::warning('Format de jeton JWT invalide pour la rédaction', [
                    'ip' => $requete->ip(),
                    'user_id' => $utilisateur->id,
                ]);
                return null;
            }

            [$enTeteEncode, $chargeUtileEncodee, $signatureFournie] = $parties;

            // 1. Vérification de la signature cryptographique
            $cleSecrete = $this->obtenirCleSecrete();
            $signatureAttendue = $this->encoderBase64Url(
                hash_hmac('sha256', "{$enTeteEncode}.{$chargeUtileEncodee}", $cleSecrete, true)
            );

            if (! hash_equals($signatureAttendue, $signatureFournie)) {
                Log::warning('Tentative d\'accès API avec un jeton falsifié ou altéré', [
                    'ip' => $requete->ip(),
                    'user_id' => $utilisateur->id,
                ]);
                return null;
            }

            // 2. Décodage de la charge utile
            $chargeUtileJson = $this->decoderBase64Url($chargeUtileEncodee);
            $chargeUtile = json_decode($chargeUtileJson, true, 512, JSON_THROW_ON_ERROR);

            // 3. Vérification de l'expiration temporelle
            if (! isset($chargeUtile['exp']) || $chargeUtile['exp'] < time()) {
                Log::info('Jeton API rédaction expiré', [
                    'user_id' => $utilisateur->id,
                    'exp' => $chargeUtile['exp'] ?? null,
                ]);
                return null;
            }

            // 4. Vérification de la correspondance du login rédacteur
            if (! isset($chargeUtile['login'], $chargeUtile['sub']) ||
                $chargeUtile['login'] !== $utilisateur->email ||
                (int) $chargeUtile['sub'] !== (int) $utilisateur->id) {
                Log::warning('Divergence d\'identité entre le jeton et la session du rédacteur', [
                    'user_session' => $utilisateur->email,
                    'user_jeton' => $chargeUtile['login'] ?? null,
                    'ip' => $requete->ip(),
                ]);
                return null;
            }

            // 5. Vérification stricte des caractéristiques de l'appareil
            $empreinteActuelle = $this->genererEmpreinteAppareil($requete);
            if (! isset($chargeUtile['empreinte']) || ! hash_equals($chargeUtile['empreinte'], $empreinteActuelle)) {
                Log::warning('Tentative d\'utilisation d\'un jeton depuis un autre appareil ou navigateur', [
                    'user_id' => $utilisateur->id,
                    'ip' => $requete->ip(),
                    'agent' => $requete->userAgent(),
                ]);
                return null;
            }

            return $chargeUtile;
        } catch (Throwable $erreur) {
            Log::error('Erreur inattendue lors de la validation du jeton rédacteur : ' . $erreur->getMessage());
            return null;
        }
    }

    /**
     * Récupère la clé secrète de signature basée sur APP_KEY.
     */
    protected function obtenirCleSecrete(): string
    {
        $cle = config('app.key', 'cargo_defaut_secret_redaction_2026');
        if (str_starts_with($cle, 'base64:')) {
            return base64_decode(substr($cle, 7));
        }

        return $cle;
    }

    /**
     * Encode une chaîne en Base64 compatible URL (sans +, / ni =).
     */
    protected function encoderBase64Url(string $donnees): string
    {
        return rtrim(strtr(base64_encode($donnees), '+/', '-_'), '=');
    }

    /**
     * Décode une chaîne Base64 compatible URL.
     */
    protected function decoderBase64Url(string $donnees): string
    {
        $reste = strlen($donnees) % 4;
        if ($reste !== 0) {
            $donnees .= str_repeat('=', 4 - $reste);
        }

        return base64_decode(strtr($donnees, '-_', '+/'));
    }
}
