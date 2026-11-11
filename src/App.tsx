import React, { useState, useEffect, useRef } from 'react';

export default function App() {
  const [linksClickable, setLinksClickable] = useState(false);
  const [activeTab, setActiveTab] = useState<'journal' | 'redaction' | 'admin'>('journal');

  // Search Engine Simulator State
  const [searchOpen, setSearchOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [activeCode, setActiveCode] = useState<string | null>(null);
  const [ttl, setTtl] = useState<number>(0);
  const [isConsumed, setIsConsumed] = useState(false);
  const [showResults, setShowResults] = useState(false);
  const [searchError, setSearchError] = useState<string | null>(null);
  const searchInputRef = useRef<HTMLInputElement>(null);

  // Countdown timer for 10-second expiration
  useEffect(() => {
    if (ttl <= 0) return;
    const timer = setInterval(() => {
      setTtl((prev) => {
        if (prev <= 1) {
          clearInterval(timer);
          return 0;
        }
        return prev - 1;
      });
    }, 1000);
    return () => clearInterval(timer);
  }, [ttl]);

  const handleOpenSearch = () => {
    setSearchOpen(true);
    setTimeout(() => searchInputRef.current?.focus(), 100);
  };

  const handleLaunchSearch = (e?: React.FormEvent) => {
    if (e) e.preventDefault();
    const query = searchQuery.trim();
    if (!query) return;

    // Generate 16-character cryptographic hex code (server simulation)
    const array = new Uint8Array(8);
    window.crypto.getRandomValues(array);
    const code = Array.from(array, (byte) => byte.toString(16).padStart(2, '0')).join('');

    setActiveCode(code);
    setTtl(10);
    setIsConsumed(false);
    setShowResults(false);
    setSearchError(null);
  };

  const handleAccessUrl = () => {
    if (!activeCode) return;
    if (ttl <= 0 || isConsumed) {
      setSearchError('HTTP 410 : Votre session de recherche a expiré. Veuillez relancer une nouvelle recherche.');
      setShowResults(false);
      return;
    }

    // Atomic consumption
    setIsConsumed(true);
    setShowResults(true);
    setSearchError(null);
  };

  return (
    <div className="min-h-screen bg-[#EEF0F3] text-[#14151C] flex flex-col font-sans">
      {/* Top Banner */}
      <header className="bg-[#14151C] text-white border-b border-[#2B2E3A]">
        <div className="max-w-6xl mx-auto px-6 py-4 flex flex-wrap items-center justify-between gap-4">
          <div className="flex items-center gap-2">
            <span className="font-heading font-black text-3xl tracking-wider text-white flex items-center">
              CARG
              <span className="inline-block w-6 h-6 rounded-full bg-[#D4201A] border-4 border-[#14151C] ml-0.5"></span>
            </span>
            <span className="text-xs uppercase tracking-widest text-[#FFC93C] font-bold border-l border-[#2B2E3A] pl-3 ml-2">
              Logistics Journal &amp; Newsroom
            </span>
          </div>

          <div className="flex items-center gap-2 bg-[#20222B] p-1 rounded border border-[#323644] text-xs font-bold uppercase tracking-wider">
            <button
              onClick={() => setActiveTab('journal')}
              className={`px-3 py-1.5 rounded transition ${
                activeTab === 'journal' ? 'bg-[#FFC93C] text-[#14151C]' : 'text-gray-300 hover:text-white'
              }`}
            >
              Public Journal
            </button>
            <button
              onClick={() => setActiveTab('redaction')}
              className={`px-3 py-1.5 rounded transition ${
                activeTab === 'redaction' ? 'bg-[#FFC93C] text-[#14151C]' : 'text-gray-300 hover:text-white'
              }`}
            >
              Newsroom Desk
            </button>
            <button
              onClick={() => setActiveTab('admin')}
              className={`px-3 py-1.5 rounded transition ${
                activeTab === 'admin' ? 'bg-[#FFC93C] text-[#14151C]' : 'text-gray-300 hover:text-white'
              }`}
            >
              Admin Console
            </button>
          </div>
        </div>
      </header>

      {/* Main Content Area */}
      <main className="max-w-6xl mx-auto px-6 py-8 flex-1 w-full space-y-8">
        {/* Status / Dev Info Card */}
        <section className="bg-white p-6 border border-[#E2E5EB] shadow-sm">
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-[#E2E5EB]">
            <div>
              <div className="flex items-center gap-2">
                <span className="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span className="text-xs uppercase font-bold tracking-wider text-emerald-700">Moteur de recherche éphémère actif</span>
              </div>
              <h1 className="font-heading font-black text-2xl text-[#14151C] mt-1">
                CARGO &bull; Architecture Cryptographique &amp; Sécurisée
              </h1>
            </div>
            <div className="text-xs text-gray-500 font-mono bg-[#F4F6F8] px-3 py-2 border border-[#E2E5EB] rounded">
              Route: POST /recherche/initier &bull; TTL: 10s &bull; Usage Unique
            </div>
          </div>

          <p className="text-sm text-gray-600 mt-4 leading-relaxed">
            L'ancien moteur basé sur <code className="bg-gray-100 px-1.5 py-0.5 text-xs text-red-600 font-mono rounded">/recherche</code> a été remplacé par un système de recherche extensible directement intégré au bandeau supérieur, générant un jeton aléatoire de 16 caractères cryptographiquement sécurisé, lié à la session et consommé de manière atomique.
          </p>
        </section>

        {/* Live Simulator: Expandable Search Bar & 10s Ephemeral URL */}
        <section className="bg-white p-6 border border-[#E2E5EB] shadow-sm space-y-6">
          <div className="pb-4 border-b border-[#E2E5EB]">
            <span className="text-xs uppercase font-bold tracking-wider text-[#D4201A]">Démonstrateur Interactif</span>
            <h2 className="font-heading font-black text-xl text-[#14151C] mt-1">
              Barre extensible &amp; Cycle de vie du jeton (/recherche-&#123;code&#125;)
            </h2>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {/* Interactive Search Bar in simulated header */}
            <div className="bg-[#14151C] text-white p-6 rounded border border-[#2B2E3A] flex flex-col justify-between space-y-6">
              <div>
                <span className="text-xs uppercase font-bold tracking-wider text-[#FFC93C]">Simulation du bandeau supérieur</span>
                <p className="text-xs text-gray-400 mt-1">
                  Cliquez sur la loupe pour déployer le champ, saisissez vos mots-clés, puis appuyez sur <strong>Entrée</strong>.
                </p>

                <div className="mt-6 flex items-center justify-end">
                  <form onSubmit={handleLaunchSearch} className="flex items-center">
                    <div
                      style={{
                        width: searchOpen ? '260px' : '0px',
                        opacity: searchOpen ? 1 : 0,
                        overflow: 'hidden',
                        transition: 'width 0.32s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease',
                      }}
                    >
                      <input
                        ref={searchInputRef}
                        type="text"
                        value={searchQuery}
                        onChange={(e) => setSearchQuery(e.target.value)}
                        placeholder="Rechercher (ex: conteneurs, corridor...)"
                        className="w-[260px] h-[42px] bg-[#20222B] text-white text-sm px-3.5 border border-[#4A4E5C] border-r-0 outline-none"
                      />
                    </div>
                    <button
                      type="button"
                      onClick={() => {
                        if (!searchOpen) handleOpenSearch();
                        else if (searchQuery.trim()) handleLaunchSearch();
                        else setSearchOpen(false);
                      }}
                      className="w-[42px] h-[42px] bg-[#20222B] hover:bg-[#D4201A] text-white border border-[#4A4E5C] flex items-center justify-center transition"
                      title="Rechercher"
                    >
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="M20 20l-4-4"></path>
                      </svg>
                    </button>
                  </form>
                </div>
              </div>

              <div className="text-[11px] text-gray-400 border-t border-[#2B2E3A] pt-3">
                &bull; Aucune requête AJAX ou suggestion lors de la frappe. Déclenchement sur Entrée uniquement.
              </div>
            </div>

            {/* Generated Code & Security State */}
            <div className="bg-[#F8F9FA] p-6 border border-[#E2E5EB] flex flex-col justify-between space-y-4">
              <div>
                <span className="text-xs uppercase font-bold tracking-wider text-gray-500">Statut du Jeton Éphémère</span>
                {activeCode ? (
                  <div className="mt-3 space-y-3">
                    <div className="p-3 bg-white border border-[#E2E5EB] rounded font-mono text-xs">
                      <div className="text-gray-500 text-[11px] uppercase">URL Éphémère générée côté serveur :</div>
                      <div className="text-[#D4201A] font-bold mt-1 text-sm">
                        /recherche-{activeCode}
                      </div>
                    </div>

                    <div className="flex items-center justify-between text-xs">
                      <span>Délai d&apos;expiration restant :</span>
                      <span className={`font-mono font-bold px-2 py-0.5 rounded ${ttl > 3 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800 animate-pulse'}`}>
                        {ttl} seconde(s)
                      </span>
                    </div>

                    <div className="flex items-center justify-between text-xs">
                      <span>État de consommation :</span>
                      <span className={`font-bold px-2 py-0.5 rounded ${isConsumed ? 'bg-gray-200 text-gray-700' : 'bg-amber-100 text-amber-800'}`}>
                        {isConsumed ? 'Consommé définitivement (Atomique)' : 'Non consommé (Disponible)'}
                      </span>
                    </div>

                    <button
                      onClick={handleAccessUrl}
                      className="w-full mt-2 bg-[#14151C] hover:bg-[#2B2E3A] text-white py-2.5 px-4 text-xs font-bold uppercase tracking-wider rounded transition"
                    >
                      Simuler l&apos;accès à l&apos;URL (/recherche-{activeCode})
                    </button>
                  </div>
                ) : (
                  <p className="text-xs text-gray-500 mt-2 italic">
                    Aucune recherche initiée. Saisissez un terme dans la barre à gauche pour générer une URL temporaire sécurisée.
                  </p>
                )}
              </div>

              {searchError && (
                <div className="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded">
                  <strong>Refus d&apos;accès :</strong> {searchError}
                </div>
              )}

              {showResults && (
                <div className="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded">
                  <strong>HTTP 200 OK :</strong> Résultats affichés avec succès pour &laquo; {searchQuery} &raquo;. Le jeton a été immédiatement détruit du cache.
                </div>
              )}
            </div>
          </div>
        </section>

        {/* Existing Moderation Link Simulator */}
        <section className="bg-white p-6 border border-[#E2E5EB] shadow-sm space-y-6">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E2E5EB]">
            <div>
              <span className="text-xs uppercase font-bold tracking-wider text-[#D4201A]">Sécurité des Commentaires</span>
              <h2 className="font-heading font-black text-xl text-[#14151C] mt-1">
                Politique de liens cliquables vs nettoyés
              </h2>
            </div>
            <button
              onClick={() => setLinksClickable(!linksClickable)}
              className={`px-4 py-2 text-xs font-bold uppercase tracking-wider text-white rounded transition shadow-sm ${
                linksClickable ? 'bg-amber-600 hover:bg-amber-700' : 'bg-[#D4201A] hover:bg-red-700'
              }`}
            >
              {linksClickable ? 'Nettoyer les liens (Texte brut)' : 'Activer liens cliquables'}
            </button>
          </div>

          <div className="text-xs text-gray-600 leading-relaxed bg-[#F8F9FA] p-4 border border-[#E2E5EB]">
            {linksClickable
              ? 'Mode actif : les liens des commentaires sont convertis en balises hypertexte sécurisées avec rel="nofollow noopener noreferrer".'
              : 'Mode actif : les liens sont nettoyés en texte brut inoffensif, neutralisant les robots de spam SEO.'}
          </div>
        </section>
      </main>

      {/* Footer */}
      <footer className="bg-[#14151C] text-white border-t border-[#2B2E3A] py-6">
        <div className="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400">
          <div>
            &copy; 2026 CARGO &bull; Le Journal de la Logistique &bull; Tous droits réservés.
          </div>
          <div className="flex gap-4">
            <span className="hover:text-white transition">Architecture MVC</span>
            <span>&bull;</span>
            <span className="hover:text-white transition">Sécurité CSPRNG</span>
            <span>&bull;</span>
            <span className="hover:text-white transition">Laravel 11</span>
          </div>
        </div>
      </footer>
    </div>
  );
}
