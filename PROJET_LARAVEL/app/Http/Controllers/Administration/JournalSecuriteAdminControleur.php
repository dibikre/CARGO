<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JournalSecuriteAdminControleur extends Controller
{
    /**
     * Consulter l'historique d'audit et les journaux de sécurité.
     */
    public function index(Request $requete): View
    {
        $logFiltre = $requete->input('type_log');
        $evenementFiltre = $requete->input('evenement');

        $requeteLogs = JournalActivite::with('auteur')->latest('created_at');

        if (! empty($logFiltre)) {
            $requeteLogs->where('log_name', $logFiltre);
        }

        if (! empty($evenementFiltre)) {
            $requeteLogs->where('event', $evenementFiltre);
        }

        $journaux = $requeteLogs->paginate(20)->withQueryString();

        $typesLogs = JournalActivite::select('log_name')->distinct()->pluck('log_name');
        $evenements = JournalActivite::whereNotNull('event')->select('event')->distinct()->pluck('event');

        return view('administration.securite.index', compact('journaux', 'typesLogs', 'evenements', 'logFiltre', 'evenementFiltre'));
    }

    /**
     * Purger les journaux de sécurité antérieurs à 90 jours (réservé super_admin).
     */
    public function purger(Request $requete): RedirectResponse
    {
        $utilisateur = Auth::user();

        if ($utilisateur->role !== 'super_admin') {
            abort(403, 'Only super administrators can purge audit security archives.');
        }

        $nombreSupprimes = JournalActivite::where('created_at', '<', now()->subDays(90))->delete();

        JournalActivite::enregistrer(
            description: "Purged {$nombreSupprimes} security logs older than 90 days",
            nomLog: 'securite',
            evenement: 'purge_logs',
            auteur: $utilisateur,
            donnees: ['nombre_lignes' => $nombreSupprimes]
        );

        return back()->with('succes', "Successfully purged {$nombreSupprimes} archived log entries.");
    }
}
