{{-- Barre de recherche extensible animée (CARGO) --}}
<div id="conteneur-recherche-extensible" style="position: relative; display: flex; align-items: center;">
    <form id="formulaire-recherche-securisee" action="{{ route('recherche.initier') }}" method="POST" style="margin: 0; display: flex; align-items: center; position: relative;">
        @csrf
        <div id="champ-recherche-enveloppe" style="width: 0; opacity: 0; overflow: hidden; transition: width 0.32s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease; display: flex; align-items: center;">
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
#conteneur-recherche-extensible.ouvert #champ-recherche-enveloppe {
    width: 260px;
    opacity: 1;
}
#conteneur-recherche-extensible.ouvert #bouton-declencheur-recherche {
    background: var(--accent, #D4201A);
    border-color: var(--accent, #D4201A);
}
</style>

<script>
(function() {
    const conteneur = document.getElementById('conteneur-recherche-extensible');
    const bouton = document.getElementById('bouton-declencheur-recherche');
    const formulaire = document.getElementById('formulaire-recherche-securisee');
    const input = document.getElementById('champ-recherche-mots-cles');
    const iconeLoupe = document.getElementById('icone-loupe');
    const iconeChargement = document.getElementById('icone-chargement-recherche');

    let estOuvert = false;
    let estEnCoursDeSoumission = false;

    function ouvrirBarre() {
        estOuvert = true;
        conteneur.classList.add('ouvert');
        bouton.setAttribute('aria-expanded', 'true');
        bouton.title = 'Lancer la recherche (ou appuyer sur Entrée)';
        setTimeout(() => input.focus(), 120);
    }

    function fermerBarre() {
        if (estEnCoursDeSoumission) return;
        estOuvert = false;
        conteneur.classList.remove('ouvert');
        bouton.setAttribute('aria-expanded', 'false');
        bouton.title = 'Rechercher dans les archives';
    }

    bouton.addEventListener('click', function(e) {
        if (!estOuvert) {
            e.preventDefault();
            ouvrirBarre();
        } else {
            // Si la barre est déjà ouverte et contient du texte, déclencher la recherche
            const valeur = input.value.trim();
            if (valeur.length > 0) {
                lancerRechercheSecurisee(valeur);
            } else {
                fermerBarre();
            }
        }
    });

    // Déclenchement STRICTEMENT sur Entrée : AUCUNE REQUÊTE ni suggestion pendant la saisie
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const valeur = input.value.trim();
            if (valeur.length > 0) {
                lancerRechercheSecurisee(valeur);
            }
        } else if (e.key === 'Escape') {
            e.preventDefault();
            fermerBarre();
        }
    });

    // Fermer si clic en dehors du composant
    document.addEventListener('click', function(e) {
        if (estOuvert && !conteneur.contains(e.target)) {
            if (input.value.trim() === '') {
                fermerBarre();
            }
        }
    });

    async function lancerRechercheSecurisee(motsCles) {
        if (estEnCoursDeSoumission) return;
        estEnCoursDeSoumission = true;

        iconeLoupe.style.display = 'none';
        iconeChargement.style.display = 'block';
        bouton.style.pointerEvents = 'none';
        input.disabled = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                || formulaire.querySelector('input[name="_token"]')?.value;

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
            console.error('Erreur recherche:', err);
            // Fallback soumission formulaire classique
            formulaire.submit();
        }
    }

    function reinitialiserEtat() {
        estEnCoursDeSoumission = false;
        iconeLoupe.style.display = 'block';
        iconeChargement.style.display = 'none';
        bouton.style.pointerEvents = 'auto';
        input.disabled = false;
        input.focus();
    }
})();
</script>
