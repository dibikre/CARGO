{{-- Barre de recherche extensible animée (CARGO) --}}
<div id="conteneur-recherche-extensible" class="conteneur-recherche-extensible" style="position: relative; display: flex; align-items: center;">
    <form id="formulaire-recherche-securisee" action="{{ route('recherche.initier') }}" method="POST" style="margin: 0; display: flex; align-items: center; position: relative;">
        @csrf
        <div id="champ-recherche-enveloppe" class="champ-recherche-enveloppe">
            <input
                type="text"
                id="champ-recherche-mots-cles"
                name="q"
                placeholder="Mots-clés, corridors, armateurs..."
                autocomplete="off"
                spellcheck="false"
                style="width: 260px; height: 42px; background: #20222B; color: #fff; border: 1px solid #4A4E5C; border-right: none; padding: 0 14px; font-family: var(--police-texte, sans-serif); font-size: 14px; outline: none; box-sizing: border-box;"
            >
        </div>

        <button
            type="button"
            id="bouton-declencheur-recherche"
            onclick="window.basculerBarreRechercheCARGO(event)"
            aria-label="Rechercher dans CARGO"
            title="Rechercher dans les archives"
            style="width: 42px; height: 42px; border: 1px solid #4A4E5C; background: #20222B; color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s, border-color 0.2s; padding: 0; flex-shrink: 0;"
        >
            <svg id="icone-loupe" aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"></circle>
                <path d="M20 20l-4-4"></path>
            </svg>
            <svg id="icone-chargement-recherche" style="display: none; animation: rotation-chargement 0.8s linear infinite;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFC93C" stroke-width="2.5">
                <circle cx="12" cy="12" r="9" stroke-opacity="0.25"></circle>
                <path d="M12 3a9 9 0 0 1 9 9"></path>
            </svg>
        </button>
    </form>
</div>

<style>
@keyframes rotation-chargement {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.champ-recherche-enveloppe {
    width: 0;
    max-width: 0;
    opacity: 0;
    overflow: hidden;
    transition: width 0.32s cubic-bezier(0.4, 0, 0.2, 1), max-width 0.32s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
    display: flex;
    align-items: center;
}
.conteneur-recherche-extensible.ouvert .champ-recherche-enveloppe {
    width: 260px !important;
    max-width: 260px !important;
    opacity: 1 !important;
}
.conteneur-recherche-extensible.ouvert #bouton-declencheur-recherche {
    background: var(--accent, #D4201A) !important;
    border-color: var(--accent, #D4201A) !important;
}
</style>

<script>
(function() {
    function obtenirElements() {
        return {
            conteneur: document.getElementById('conteneur-recherche-extensible'),
            bouton: document.getElementById('bouton-declencheur-recherche'),
            formulaire: document.getElementById('formulaire-recherche-securisee'),
            enveloppe: document.getElementById('champ-recherche-enveloppe'),
            input: document.getElementById('champ-recherche-mots-cles'),
            iconeLoupe: document.getElementById('icone-loupe'),
            iconeChargement: document.getElementById('icone-chargement-recherche')
        };
    }

    let enCoursDeSoumission = false;

    window.basculerBarreRechercheCARGO = function(e) {
        if (e && e.preventDefault) e.preventDefault();
        const el = obtenirElements();
        if (!el.conteneur || !el.input) return;

        const estOuvert = el.conteneur.classList.contains('ouvert');

        if (!estOuvert) {
            // Déplier la barre de recherche
            el.conteneur.classList.add('ouvert');
            el.bouton.setAttribute('aria-expanded', 'true');
            el.bouton.title = 'Lancer la recherche (ou Entrée)';
            setTimeout(function() {
                el.input.focus();
            }, 80);
        } else {
            // La barre est déjà ouverte
            const valeur = el.input.value.trim();
            if (valeur.length > 0) {
                lancerRechercheSecurisee(valeur);
            } else {
                // Fermer si aucun texte
                fermerBarre();
            }
        }
    };

    function fermerBarre() {
        if (enCoursDeSoumission) return;
        const el = obtenirElements();
        if (!el.conteneur) return;
        el.conteneur.classList.remove('ouvert');
        el.bouton.setAttribute('aria-expanded', 'false');
        el.bouton.title = 'Rechercher dans les archives';
    }

    // Déclenchement sur Entrée : AUCUNE REQUÊTE pendant la saisie
    document.addEventListener('keydown', function(e) {
        const el = obtenirElements();
        if (!el.conteneur || !el.conteneur.classList.contains('ouvert')) return;

        if (e.target === el.input) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const valeur = el.input.value.trim();
                if (valeur.length > 0) {
                    lancerRechercheSecurisee(valeur);
                }
            } else if (e.key === 'Escape') {
                e.preventDefault();
                fermerBarre();
            }
        }
    });

    // Fermer si clic à l'extérieur quand le champ est vide
    document.addEventListener('click', function(e) {
        const el = obtenirElements();
        if (!el.conteneur || !el.conteneur.classList.contains('ouvert')) return;

        if (!el.conteneur.contains(e.target)) {
            if (el.input.value.trim() === '') {
                fermerBarre();
            }
        }
    });

    async function lancerRechercheSecurisee(motsCles) {
        if (enCoursDeSoumission) return;
        enCoursDeSoumission = true;

        const el = obtenirElements();
        if (el.iconeLoupe) el.iconeLoupe.style.display = 'none';
        if (el.iconeChargement) el.iconeChargement.style.display = 'block';
        if (el.bouton) el.bouton.style.pointerEvents = 'none';
        if (el.input) el.input.disabled = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || el.formulaire?.querySelector('input[name="_token"]')?.value;

            const reponse = await fetch("{{ route('recherche.initier') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ q: motsCles })
            });

            if (!reponse.ok) {
                const erreurJson = await reponse.json().catch(() => null);
                const message = (erreurJson && erreurJson.message)
                    ? erreurJson.message
                    : 'Erreur lors de la préparation de la recherche. Veuillez réessayer.';
                alert(message);
                reinitialiserEtat();
                return;
            }

            const donnees = await reponse.json();
            if (donnees.url) {
                window.location.href = donnees.url;
            } else {
                reinitialiserEtat();
            }
        } catch (err) {
            console.error('Erreur appel recherche:', err);
            // Fallback soumission HTML classique
            if (el.formulaire) {
                el.input.disabled = false;
                el.formulaire.submit();
            } else {
                reinitialiserEtat();
            }
        }
    }

    function reinitialiserEtat() {
        enCoursDeSoumission = false;
        const el = obtenirElements();
        if (el.iconeLoupe) el.iconeLoupe.style.display = 'block';
        if (el.iconeChargement) el.iconeChargement.style.display = 'none';
        if (el.bouton) el.bouton.style.pointerEvents = 'auto';
        if (el.input) {
            el.input.disabled = false;
            el.input.focus();
        }
    }
})();
</script>
