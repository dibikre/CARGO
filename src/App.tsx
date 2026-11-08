import React, { useState, useRef, useEffect } from 'react';
import { 
  Ship, Truck, Train, Box, ShieldCheck, FileText, Image as ImageIcon, 
  Eye, Code, CheckCircle, AlertTriangle, Sliders, Sparkles, Clipboard,
  Heading1, Heading2, Heading3, Bold, Italic, Underline, List, ListOrdered,
  PlusCircle, RefreshCw, ExternalLink, Settings, Layout, Lock, Unlock, Globe,
  Play, Pause, ChevronLeft, ChevronRight, Clock
} from 'lucide-react';

interface CategorieLogistique {
  id: number;
  nom: string;
  slug: string;
  description: string;
  imageFond: string;
  opaciteOverlay: number;
  couleurOverlay: string;
  couleur: string;
  ordreAffichage: number;
  afficherDansMenu: boolean;
  totalArticles: number;
}

const CATEGORIES_INITIALES: CategorieLogistique[] = [
  {
    id: 1,
    nom: 'Fret Maritime & Ports',
    slug: 'fret-maritime-ports',
    description: 'Armateurs, terminaux à conteneurs, taux de fret spot et alliances mondiales.',
    imageFond: 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=1600&q=80',
    opaciteOverlay: 65,
    couleurOverlay: '#14151C',
    couleur: '#D4201A',
    ordreAffichage: 1,
    afficherDansMenu: true,
    totalArticles: 14,
  },
  {
    id: 2,
    nom: 'Transport Routier',
    slug: 'transport-routier',
    description: 'Flottes de poids lourds, décarbonation, pénurie de chauffeurs et cabotage.',
    imageFond: 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1600&q=80',
    opaciteOverlay: 65,
    couleurOverlay: '#14151C',
    couleur: '#D4201A',
    ordreAffichage: 2,
    afficherDansMenu: true,
    totalArticles: 19,
  },
  {
    id: 3,
    nom: 'Ferroviaire & Intermodal',
    slug: 'ferroviaire-intermodal',
    description: 'Autoroutes ferroviaires, corridors TEN-T et combiné rail-route.',
    imageFond: 'https://images.unsplash.com/photo-1515263487990-61b07816b324?auto=format&fit=crop&w=1600&q=80',
    opaciteOverlay: 65,
    couleurOverlay: '#14151C',
    couleur: '#D4201A',
    ordreAffichage: 3,
    afficherDansMenu: true,
    totalArticles: 8,
  },
  {
    id: 4,
    nom: 'Supply Chain Tech',
    slug: 'supply-chain-tech',
    description: 'WMS, TMS, automatisation d\'entrepôts et visibilité temps réel.',
    imageFond: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1600&q=80',
    opaciteOverlay: 70,
    couleurOverlay: '#14151C',
    couleur: '#D4201A',
    ordreAffichage: 4,
    afficherDansMenu: true,
    totalArticles: 12,
  },
  {
    id: 5,
    nom: 'Entreposage & Hubs',
    slug: 'entreposage-logistique',
    description: 'Immobilier logistique, plateformes multimodales et entrepôts automatisés.',
    imageFond: 'https://images.unsplash.com/photo-1553413077-190dd305871c?auto=format&fit=crop&w=1600&q=80',
    opaciteOverlay: 65,
    couleurOverlay: '#14151C',
    couleur: '#D4201A',
    ordreAffichage: 5,
    afficherDansMenu: true,
    totalArticles: 11,
  },
];

interface ArticleHero {
  id: number;
  titre: string;
  chapeau: string;
  categorie: string;
  tempsLecture: number;
  auteur: string;
  date: string;
  mode: string;
  image: string;
  isPro: boolean;
  badgeCouleur?: string;
}

const ARTICLES_A_LA_UNE_HERO: ArticleHero[] = [
  {
    id: 1,
    titre: 'Global Carrier Capacity: Winter Schedule Overhaul on Asia-North Europe Routes',
    chapeau: 'Shipping alliances reroute tonnage via the Cape of Good Hope while blank sailings surge past 18% of deployed vessel slots, reshaping ocean freight tariffs.',
    categorie: 'Fret Maritime & Ports',
    tempsLecture: 6,
    auteur: 'Claire Delamare',
    date: 'Today · 10:45 AM',
    mode: 'Maritime',
    image: 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=1600&q=80',
    isPro: true,
  },
  {
    id: 2,
    titre: 'Bordeaux-Rotterdam Rail Shuttle Adds 400 Weekly Trailing TEU Capacity',
    chapeau: 'Combined rail-route operator expands electrified shuttle services along the Atlantic corridor, eliminating 14,000 diesel truck journeys per year.',
    categorie: 'Ferroviaire & Intermodal',
    tempsLecture: 4,
    auteur: 'Marc Lemaître',
    date: 'Today · 08:30 AM',
    mode: 'Rail',
    image: 'https://images.unsplash.com/photo-1515263487990-61b07816b324?auto=format&fit=crop&w=1600&q=80',
    isPro: false,
  },
  {
    id: 3,
    titre: 'European Fleet Decarbonization: Megawatt Charging Corridors Take Shape',
    chapeau: 'Heavy-duty transport operators and grid operators ink multi-country agreements to establish high-power electric truck charging hubs across TEN-T highways.',
    categorie: 'Transport Routier',
    tempsLecture: 5,
    auteur: 'Jean-Baptiste Vian',
    date: 'Yesterday · 04:15 PM',
    mode: 'Road',
    image: 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1600&q=80',
    isPro: true,
  },
  {
    id: 4,
    titre: 'Air Cargo Express: Cross-Border Hub Expansion Fast-Tracks Cold-Chain Logistics',
    chapeau: 'Airport logistics operators deploy temperature-controlled pharma transit centers and automated ULD sorting systems to mitigate belly-hold volatility.',
    categorie: 'Fret Aérien & Express',
    tempsLecture: 7,
    auteur: 'Amélie Dupont',
    date: 'Yesterday · 11:20 AM',
    mode: 'Air',
    image: 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?auto=format&fit=crop&w=1600&q=80',
    isPro: false,
  },
  {
    id: 5,
    titre: 'Warehouse Automation: High-Density AS/RS Deployment Cuts Handling Lead Times by 35%',
    chapeau: 'Third-party logistics providers deploy autonomous mobile robots and multi-shuttle automated storage systems to handle seasonal surge volumes.',
    categorie: 'Supply Chain Tech',
    tempsLecture: 8,
    auteur: 'Sophie Renard',
    date: 'Nov 4, 2026',
    mode: 'Warehousing',
    image: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1600&q=80',
    isPro: true,
  },
];

const ARTICLES_EXEMPLES = ARTICLES_A_LA_UNE_HERO;

export default function App() {
  const [vueActive, setVueActive] = useState<'accueil' | 'rubrique' | 'editeur' | 'admin' | 'architecture'>('accueil');
  const [categories, setCategories] = useState<CategorieLogistique[]>(CATEGORIES_INITIALES);
  const [categorieSelectionneeId, setCategorieSelectionneeId] = useState<number>(1);
  const [abonnementsActifs, setAbonnementsActifs] = useState<boolean>(true);

  // Hero Diaporama 7s Animation State
  const [heroIndexActif, setHeroIndexActif] = useState<number>(0);
  const [heroEnPause, setHeroEnPause] = useState<boolean>(false);
  const [heroProgression, setHeroProgression] = useState<number>(0);
  const dureeCycleHeroMs = 7000;

  useEffect(() => {
    if (heroEnPause) return;

    const intervalleMs = 50;
    const timer = setInterval(() => {
      setHeroProgression((prev) => {
        const next = prev + (intervalleMs / dureeCycleHeroMs) * 100;
        if (next >= 100) {
          setHeroIndexActif((curr) => (curr + 1) % ARTICLES_A_LA_UNE_HERO.length);
          return 0;
        }
        return next;
      });
    }, intervalleMs);

    return () => clearInterval(timer);
  }, [heroEnPause]);

  const changerSlideHero = (nouveauIndex: number) => {
    setHeroIndexActif(nouveauIndex);
    setHeroProgression(0);
  };

  const basculerAbonnements = () => {
    const nouvelEtat = !abonnementsActifs;
    setAbonnementsActifs(nouvelEtat);
    declencherToast(
      nouvelEtat
        ? "Monetized Subscriptions Activated: PRO articles require an active paid subscription."
        : "CARGO Open Access Activated: All subscriptions deactivated! The entire site is 100% free."
    );
  };

  // Éditeur d'article state
  const [modeEditeur, setModeEditeur] = useState<'visuel' | 'code'>('visuel');
  const [contenuArticle, setContenuArticle] = useState<string>(
    `<h2>Global Freight Resilience: Navigating Modern Corridor Disruptions</h2>
<p>International freight operators face unprecedented volatility across primary shipping corridors. Between canal transit restrictions and seasonal bottlenecking, logistics executives are turning towards multi-corridor hedging.</p>
<div style="background:#F4F1EA; border-left:4px solid #D4201A; padding:14px 20px; margin:20px 0; font-style:italic;">
  "Diversifying carrier routing and securing digital rail shuttles reduces transshipment delays by over 28%."
</div>
<h3>Actionable Strategy for Shippers</h3>
<ul>
  <li>Lock in spot-to-contract index-linked rate corridors.</li>
  <li>Deploy IoT telemetry at transshipment container gates.</li>
  <li>Maintain minimum 7-day inventory safety buffers at dry port hubs.</li>
</ul>`
  );
  const [toastMessage, setToastMessage] = useState<string | null>(null);
  const editeurRef = useRef<HTMLDivElement>(null);

  const categorieCourante = categories.find(c => c.id === categorieSelectionneeId) || categories[0];

  const mettreAJourCategorie = (champs: Partial<CategorieLogistique>) => {
    setCategories(prev => prev.map(c => c.id === categorieCourante.id ? { ...c, ...champs } : c));
  };

  const declencherToast = (msg: string) => {
    setToastMessage(msg);
    setTimeout(() => setToastMessage(null), 4500);
  };

  // Synchronisation contenu
  const handleSaisieVisuelle = () => {
    if (editeurRef.current) {
      setContenuArticle(editeurRef.current.innerHTML);
    }
  };

  // Exécution commande de mise en forme basique
  const executerCommande = (cmd: string, val: string | null = null) => {
    if (editeurRef.current) {
      editeurRef.current.focus();
      document.execCommand(cmd, false, val ?? undefined);
      handleSaisieVisuelle();
    }
  };

  const appliquerFormat = (tag: string) => {
    if (editeurRef.current) {
      editeurRef.current.focus();
      if (tag === 'p') {
        document.execCommand('formatBlock', false, '<p>');
      } else if (tag.startsWith('h')) {
        document.execCommand('formatBlock', false, `<${tag}>`);
      } else if (tag === 'blockquote') {
        document.execCommand('formatBlock', false, '<blockquote>');
      }
      handleSaisieVisuelle();
    }
  };

  // Traitement automatique du Presse-Papier (Clipboard)
  const assainirEtCollerTexte = (brutHtml: string, brutTexte: string) => {
    let propre = '';
    if (brutHtml && brutHtml.trim()) {
      // Nettoyer les balises de commentaires et MS Office
      propre = brutHtml.replace(/<!--[\s\S]*?-->/gi, '');
      propre = propre.replace(/<o:p>[\s\S]*?<\/o:p>/gi, '');
      
      // Conversion des headings markdown éventuels
      propre = propre.replace(/^#\s+(.+)$/gm, '<h1>$1</h1>');
      propre = propre.replace(/^##\s+(.+)$/gm, '<h2>$1</h2>');
      propre = propre.replace(/^###\s+(.+)$/gm, '<h3>$1</h3>');

      // Nettoyer les styles CSS agressifs causant "blanc sur blanc" (white on white)
      propre = propre.replace(/style="[^"]*"/gi, (match) => {
        let s = match.replace(/color\s*:\s*(#fff|#ffffff|white|rgb\(255,\s*255,\s*255\))/gi, '');
        s = s.replace(/background(-color)?\s*:\s*[^;"]+;?/gi, '');
        s = s.replace(/font-family\s*:\s*[^;"]+;?/gi, '');
        return s.trim() === 'style=""' ? '' : s;
      });
    } else if (brutTexte) {
      // Détecter Markdown # Title, ## Subtitle, ### Subsection et paragraphes
      propre = brutTexte
        .replace(/^#\s+(.+)$/gm, '<h1>$1</h1>')
        .replace(/^##\s+(.+)$/gm, '<h2>$1</h2>')
        .replace(/^###\s+(.+)$/gm, '<h3>$1</h3>')
        .split(/\n\s*\n/)
        .map(p => p.startsWith('<h') ? p : `<p>${p.replace(/\n/g, '<br>')}</p>`)
        .join('');
    }

    if (editeurRef.current) {
      editeurRef.current.focus();
      document.execCommand('insertHTML', false, propre);
      handleSaisieVisuelle();
      declencherToast("Clipboard content sanitized: Headings (H1/H2) preserved, bad inline colors stripped!");
    }
  };

  const simulerCollerWordPollue = () => {
    const dirtySnippet = `
      <!--[if gte mso 9]><xml><o:DocumentProperties>...</o:DocumentProperties></xml><![endif]-->
      <h1 style="color: #ffffff; background-color: #ffffff; font-family: 'Calibri'; margin: 0;">
        Critical Port Congestion Index: Rotterdam & Antwerp Terminals
      </h1>
      <h2 style="color: white; font-family: 'Arial';">
        1. Operational Vessel Waiting Times Surpass 72 Hours
      </h2>
      <p style="color: #ffffff; background: white; font-size: 11pt;">
        Barge handling bottlenecks at deep-sea container facilities have led carriers to introduce temporary low-water surcharges across Rhine corridors.
      </p>
      <h3>Strategic Mitigation Roadmap</h3>
      <p>Rail freight shuttles are being diverted towards alternative hinterland hubs to bypass congested canal locks.</p>
    `;
    assainirEtCollerTexte(dirtySnippet, '');
  };

  const simulerCollerMarkdown = () => {
    const markdownSnippet = `# Decarbonization Mandates: FuelEU Maritime Takes Effect

## 1. Compliance Penalties for Non-Conforming Carriers
Vessels exceeding greenhouse gas intensity limits face graduated financial assessments per megajoule of non-compliant fuel consumed.

## 2. Green Corridor Investments
Major terminal concessionaires are fast-tracking shore-power (cold ironing) grid connections at container berths.

### Required Actions for Freight Forwarders
Dual-fuel bunkering guarantees are now mandatory in annual service contracts.`;
    assainirEtCollerTexte('', markdownSnippet);
  };

  // Calcul contraste sécurisé
  const opacitePourcentage = categorieCourante.opaciteOverlay;
  const opaciteDecimal = opacitePourcentage / 100;

  return (
    <div className="min-h-screen flex flex-col bg-[#EEF0F3] text-[#14151C]">
      {/* Top Operational Bar */}
      <div className="bg-[#14151C] text-white border-b border-[#2B2E3A]">
        <div className="max-w-[1240px] mx-auto px-4 sm:px-8 py-3 flex flex-wrap items-center justify-between gap-3">
          <div className="flex items-center gap-2">
            <span className="font-heading font-black text-2xl tracking-tight flex items-center gap-1">
              CARG
              <span className="w-5 h-5 rounded-full bg-[#D4201A] inline-flex items-center justify-center">
                <span className="w-2 h-2 rounded-full bg-[#14151C]"></span>
              </span>
            </span>
            <span className="text-xs uppercase tracking-widest text-[#FFC93C] font-bold border-l border-gray-700 pl-2 ml-1">
              LARAVEL ENGINE · LIVE SIMULATOR
            </span>
          </div>

          {/* Navigation Tab Switcher */}
          <nav className="flex items-center gap-1 sm:gap-2">
            <button
              onClick={() => setVueActive('accueil')}
              className={`px-3 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition flex items-center gap-1.5 ${
                vueActive === 'accueil' ? 'bg-[#D4201A] text-white shadow' : 'text-gray-300 hover:text-white hover:bg-white/10'
              }`}
            >
              <Sparkles className="w-3.5 h-3.5" />
              Home Hero Slider (7s Fade)
            </button>

            <button
              onClick={() => setVueActive('rubrique')}
              className={`px-3 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition flex items-center gap-1.5 ${
                vueActive === 'rubrique' ? 'bg-[#D4201A] text-white shadow' : 'text-gray-300 hover:text-white hover:bg-white/10'
              }`}
            >
              <Layout className="w-3.5 h-3.5" />
              Rubrique Page (/rubriques/{categorieCourante.slug})
            </button>

            <button
              onClick={() => setVueActive('editeur')}
              className={`px-3 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition flex items-center gap-1.5 ${
                vueActive === 'editeur' ? 'bg-[#D4201A] text-white shadow' : 'text-gray-300 hover:text-white hover:bg-white/10'
              }`}
            >
              <FileText className="w-3.5 h-3.5" />
              WYSIWYG Post Editor (H1/H2 Paste)
            </button>

            <button
              onClick={() => setVueActive('admin')}
              className={`px-3 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition flex items-center gap-1.5 ${
                vueActive === 'admin' ? 'bg-[#D4201A] text-white shadow' : 'text-gray-300 hover:text-white hover:bg-white/10'
              }`}
            >
              <Settings className="w-3.5 h-3.5" />
              Admin Taxonomy & Paywall
            </button>

            <button
              onClick={() => setVueActive('architecture')}
              className={`px-3 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition flex items-center gap-1.5 ${
                vueActive === 'architecture' ? 'bg-[#D4201A] text-white shadow' : 'text-gray-300 hover:text-white hover:bg-white/10'
              }`}
            >
              <Code className="w-3.5 h-3.5" />
              Laravel Codebase Map
            </button>

            {/* Paywall Mode Switcher */}
            <button
              onClick={basculerAbonnements}
              className={`ml-1 px-3 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition flex items-center gap-1.5 border shadow-sm ${
                abonnementsActifs
                  ? 'bg-amber-950/70 border-amber-500/60 text-amber-300 hover:bg-amber-900/80'
                  : 'bg-emerald-950/80 border-emerald-400 text-emerald-300 hover:bg-emerald-900/90'
              }`}
              title="Click to toggle between Paid Subscriptions and 100% Free Open Access"
            >
              {abonnementsActifs ? (
                <>
                  <Lock className="w-3.5 h-3.5 text-amber-400" />
                  <span>Paywall: <strong className="text-white">Active</strong></span>
                </>
              ) : (
                <>
                  <Unlock className="w-3.5 h-3.5 text-emerald-400" />
                  <span>Site: <strong className="text-white">100% Free</strong></span>
                </>
              )}
            </button>
          </nav>
        </div>
      </div>

      {/* Main View Container */}
      <main className="flex-1">
        {/* ======================================================== */}
        {/* VIEW 0: HOME HERO SLIDER WITH 7-SECOND FADE TRANSITION */}
        {/* ======================================================== */}
        {vueActive === 'accueil' && (
          <div className="space-y-6">
            {/* Live Slider Controller & Debug Bar */}
            <div className="bg-white border-b border-gray-200 shadow-sm">
              <div className="max-w-[1240px] mx-auto px-4 sm:px-8 py-3">
                <div className="flex flex-wrap items-center justify-between gap-4">
                  <div className="flex items-center gap-3">
                    <span className="text-xs font-bold uppercase text-[#D4201A] tracking-wider flex items-center gap-1.5 font-heading">
                      <Clock className="w-4 h-4" />
                      Hero Passation 7s Engine:
                    </span>
                    <span className="text-xs bg-gray-100 text-gray-800 font-bold px-2 py-1 rounded font-mono">
                      Article {heroIndexActif + 1} of 5: {ARTICLES_A_LA_UNE_HERO[heroIndexActif].mode}
                    </span>
                    <span className={`text-[11px] font-bold px-2 py-0.5 rounded ${
                      heroEnPause ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'
                    }`}>
                      {heroEnPause ? 'Paused (Hover or Manual)' : 'Running (7s Auto-Fade)'}
                    </span>
                  </div>

                  <div className="flex items-center gap-2">
                    <button
                      onClick={() => setHeroEnPause(!heroEnPause)}
                      className="px-2.5 py-1 text-xs font-bold rounded border border-gray-300 hover:bg-gray-100 flex items-center gap-1 transition"
                    >
                      {heroEnPause ? <Play className="w-3.5 h-3.5 text-emerald-600" /> : <Pause className="w-3.5 h-3.5 text-amber-600" />}
                      {heroEnPause ? 'Resume Timer' : 'Pause'}
                    </button>

                    <div className="flex items-center gap-1">
                      {ARTICLES_A_LA_UNE_HERO.map((_, i) => (
                        <button
                          key={i}
                          onClick={() => changerSlideHero(i)}
                          className={`w-6 h-6 rounded text-xs font-bold transition ${
                            heroIndexActif === i ? 'bg-[#D4201A] text-white shadow' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                          }`}
                        >
                          {i + 1}
                        </button>
                      ))}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* THE HERO DIAPORAMA COMPONENT (Mirrors resources/views/journal/composants/hero-diaporama.blade.php) */}
            <section
              onMouseEnter={() => setHeroEnPause(true)}
              onMouseLeave={() => setHeroEnPause(false)}
              className="relative bg-[#14151C] text-white overflow-hidden min-h-[580px] border-b-4 border-[#D4201A]"
            >
              {/* 5 Background Image Layers with Smooth 7s Fade */}
              <div className="absolute inset-0 pointer-events-none z-0">
                {ARTICLES_A_LA_UNE_HERO.map((art, idx) => (
                  <div
                    key={art.id}
                    className="absolute inset-0 bg-cover bg-center transition-all duration-1000 ease-in-out"
                    style={{
                      backgroundImage: `url('${art.image}')`,
                      opacity: heroIndexActif === idx ? 1 : 0,
                      transform: heroIndexActif === idx ? 'scale(1)' : 'scale(1.04)',
                    }}
                  />
                ))}
              </div>

              {/* Dual Dark Scrim Contrast Overlay to Guarantee 100% Readability */}
              <div
                className="absolute inset-0 pointer-events-none z-10"
                style={{
                  background: 'linear-gradient(90deg, rgba(20,21,28,0.96) 0%, rgba(20,21,28,0.88) 55%, rgba(20,21,28,0.60) 100%), linear-gradient(180deg, rgba(20,21,28,0.35) 0%, rgba(20,21,28,0.95) 100%)',
                }}
              />

              <div className="max-w-[1240px] mx-auto px-4 sm:px-8 py-12 relative z-20 flex flex-col lg:flex-row gap-10 items-stretch min-h-[500px]">
                {/* Left Column: Active Article Content */}
                <div className="flex-1 flex flex-col justify-between gap-6">
                  <div className="space-y-4">
                    <div className="flex flex-wrap items-center gap-2.5">
                      <span className="font-heading font-black text-xs uppercase tracking-widest text-[#FFC93C] bg-black/60 px-2.5 py-1 rounded border-l-4 border-[#D4201A]">
                        Featured Intelligence · {ARTICLES_A_LA_UNE_HERO[heroIndexActif].categorie} · {ARTICLES_A_LA_UNE_HERO[heroIndexActif].mode}
                      </span>
                      {ARTICLES_A_LA_UNE_HERO[heroIndexActif].isPro && (
                        abonnementsActifs ? (
                          <span className="bg-[#FFC93C] text-[#14151C] text-[10px] font-black uppercase px-2 py-0.5 rounded shadow">
                            PRO Member Exclusive
                          </span>
                        ) : (
                          <span className="bg-emerald-600 text-white text-[10px] font-black uppercase px-2 py-0.5 rounded shadow">
                            Open Access (Free)
                          </span>
                        )
                      )}
                      <span className="text-xs text-gray-400">
                        {ARTICLES_A_LA_UNE_HERO[heroIndexActif].date}
                      </span>
                    </div>

                    <h1 
                      key={`titre-${heroIndexActif}`}
                      className="font-heading font-black text-3xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-none transition-opacity duration-500"
                      style={{
                        textShadow: '0 2px 14px rgba(0,0,0,0.9)',
                      }}
                    >
                      {ARTICLES_A_LA_UNE_HERO[heroIndexActif].titre}
                    </h1>

                    <p 
                      key={`chapeau-${heroIndexActif}`}
                      className="text-gray-200 text-base sm:text-lg max-w-2xl leading-relaxed transition-opacity duration-500"
                      style={{
                        textShadow: '0 1px 6px rgba(0,0,0,0.8)',
                      }}
                    >
                      {ARTICLES_A_LA_UNE_HERO[heroIndexActif].chapeau}
                    </p>

                    <div className="flex flex-wrap items-center gap-3 pt-2">
                      <button className="bg-[#FFC93C] hover:bg-yellow-400 text-[#14151C] font-heading font-black px-6 py-3.5 uppercase tracking-wider text-xs transition flex items-center gap-2 shadow-lg">
                        <span>Read Full Briefing</span>
                        <ChevronRight className="w-4 h-4" />
                      </button>
                      <button 
                        onClick={() => setVueActive('rubrique')}
                        className="border-2 border-white hover:bg-white/10 text-white font-heading font-bold px-5 py-3 uppercase tracking-wider text-xs transition"
                      >
                        All Logistics Sections
                      </button>
                    </div>
                  </div>

                  {/* Playback Controls & Countdown Bar */}
                  <div className="flex items-center gap-4 pt-4 border-t border-white/15">
                    <div className="flex items-center gap-1.5">
                      <button
                        onClick={() => changerSlideHero((heroIndexActif - 1 + ARTICLES_A_LA_UNE_HERO.length) % ARTICLES_A_LA_UNE_HERO.length)}
                        className="w-9 h-9 rounded bg-black/50 hover:bg-black/80 border border-white/20 text-white flex items-center justify-center transition"
                        title="Previous briefing"
                      >
                        <ChevronLeft className="w-4 h-4" />
                      </button>
                      <button
                        onClick={() => changerSlideHero((heroIndexActif + 1) % ARTICLES_A_LA_UNE_HERO.length)}
                        className="w-9 h-9 rounded bg-black/50 hover:bg-black/80 border border-white/20 text-white flex items-center justify-center transition"
                        title="Next briefing"
                      >
                        <ChevronRight className="w-4 h-4" />
                      </button>
                      <button
                        onClick={() => setHeroEnPause(!heroEnPause)}
                        className="w-9 h-9 rounded bg-black/50 hover:bg-black/80 border border-white/20 text-white flex items-center justify-center transition ml-1"
                        title={heroEnPause ? 'Play rotation' : 'Pause rotation'}
                      >
                        {heroEnPause ? <Play className="w-3.5 h-3.5 text-[#FFC93C]" /> : <Pause className="w-3.5 h-3.5 text-white" />}
                      </button>
                    </div>

                    <div className="text-xs uppercase font-bold tracking-wider text-gray-300">
                      <span className="text-[#FFC93C] text-sm font-mono font-black">0{heroIndexActif + 1}</span> / 05 · Auto 7s Fade {heroEnPause ? '(Paused)' : ''}
                    </div>
                  </div>
                </div>

                {/* Right Column: 5 Numbered Edition Tracks with Live Progress Fill */}
                <div className="w-full lg:w-96 flex flex-col justify-center border-t lg:border-t-0 lg:border-l border-white/15 pt-6 lg:pt-0 lg:pl-8">
                  <div className="flex items-center justify-between pb-3 border-b-2 border-dashed border-gray-600 mb-3">
                    <span className="text-xs uppercase tracking-widest text-gray-400 font-heading font-bold">
                      In This Edition (01-05)
                    </span>
                    <span className="text-[11px] font-bold text-[#FFC93C]">
                      Click to Select
                    </span>
                  </div>

                  <div className="space-y-1.5">
                    {ARTICLES_A_LA_UNE_HERO.map((art, idx) => {
                      const estActif = heroIndexActif === idx;
                      return (
                        <div
                          key={art.id}
                          onClick={() => changerSlideHero(idx)}
                          className={`cursor-pointer p-3 rounded transition-all relative overflow-hidden group ${
                            estActif ? 'bg-white/10 shadow-inner' : 'hover:bg-white/5 opacity-70 hover:opacity-100'
                          }`}
                        >
                          {/* 7-Second Animated Progress Bar for the Active Tab */}
                          {estActif && (
                            <div
                              className="absolute bottom-0 left-0 h-1 bg-[#FFC93C] transition-all duration-75 ease-linear"
                              style={{ width: `${heroProgression}%` }}
                            />
                          )}

                          <div className="flex items-baseline gap-3">
                            <span className={`font-heading font-black text-2xl min-w-[28px] ${
                              estActif ? 'text-[#FFC93C]' : 'text-gray-500 group-hover:text-gray-300'
                            }`}>
                              0{idx + 1}
                            </span>
                            <div className="flex-1 min-w-0">
                              <h4 className={`text-sm font-heading line-clamp-1 leading-snug ${
                                estActif ? 'text-white font-black' : 'text-gray-300 font-semibold'
                              }`}>
                                {art.titre}
                              </h4>
                              <span className="text-[11px] text-gray-400 flex items-center gap-1.5 mt-0.5">
                                <span>{art.categorie}</span>
                                <span>·</span>
                                <span>{art.tempsLecture}m read</span>
                              </span>
                            </div>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                </div>
              </div>
            </section>
          </div>
        )}

        {/* ======================================================== */}
        {/* VIEW 1: RUBRIQUE PAGE WITH BACKGROUND & CONTRAST PROTECTION */}
        {/* ======================================================== */}
        {vueActive === 'rubrique' && (
          <div className="space-y-6">
            {/* Admin Interactive Simulator Bar */}
            <div className="bg-white border-b border-gray-200 shadow-sm">
              <div className="max-w-[1240px] mx-auto px-4 sm:px-8 py-3.5">
                <div className="flex flex-wrap items-center justify-between gap-4">
                  <div className="flex items-center gap-3">
                    <span className="text-xs font-bold uppercase text-[#D4201A] tracking-wider flex items-center gap-1.5">
                      <Sliders className="w-4 h-4" />
                      Live Header Customizer:
                    </span>

                    {/* Category Switcher */}
                    <select
                      value={categorieSelectionneeId}
                      onChange={(e) => setCategorieSelectionneeId(Number(e.target.value))}
                      className="text-xs font-bold border border-gray-300 rounded px-2.5 py-1.5 bg-gray-50 focus:outline-none focus:border-[#14151C]"
                    >
                      {categories.map(c => (
                        <option key={c.id} value={c.id}>{c.nom} ({c.slug})</option>
                      ))}
                    </select>
                  </div>

                  {/* Quick Image Pre-sets to demonstrate contrast protection */}
                  <div className="flex items-center gap-2 text-xs flex-wrap">
                    <span className="text-gray-500 font-semibold">Test Images:</span>
                    <button
                      onClick={() => mettreAJourCategorie({ 
                        imageFond: 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=1600&q=80' 
                      })}
                      className="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded font-semibold text-gray-700"
                    >
                      Maritime Port (Dark)
                    </button>
                    <button
                      onClick={() => mettreAJourCategorie({ 
                        imageFond: 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1600&q=80' 
                      })}
                      className="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded font-semibold text-gray-700"
                    >
                      Highway (Bright Day)
                    </button>
                    <button
                      onClick={() => mettreAJourCategorie({ 
                        imageFond: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1600&q=80' 
                      })}
                      className="px-2 py-1 bg-amber-100 text-amber-900 border border-amber-300 hover:bg-amber-200 rounded font-bold flex items-center gap-1"
                    >
                      <Sparkles className="w-3 h-3 text-[#D4201A]" />
                      Pure White Image (#FFF Extreme Test)
                    </button>
                  </div>

                  {/* Opacity Scrim Slider */}
                  <div className="flex items-center gap-3">
                    <span className="text-xs font-bold text-gray-600">
                      Dark Scrim: <span className="font-mono text-[#D4201A]">{opacitePourcentage}%</span>
                    </span>
                    <input
                      type="range"
                      min="20"
                      max="95"
                      value={opacitePourcentage}
                      onChange={(e) => mettreAJourCategorie({ opaciteOverlay: Number(e.target.value) })}
                      className="w-24 sm:w-32 accent-[#D4201A] cursor-pointer"
                    />
                  </div>
                </div>

                {/* Contrast Guarantee Banner */}
                <div className="mt-2.5 pt-2 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                  <div className="flex items-center gap-1.5 text-emerald-700 font-bold">
                    <ShieldCheck className="w-4 h-4 text-emerald-600" />
                    WCAG AAA Contrast Assured: Zero risk of white-on-white text due to the dual gradient scrim overlay.
                  </div>
                  <span className="text-gray-400 font-mono">
                    Route: /rubriques/{categorieCourante.slug}
                  </span>
                </div>
              </div>
            </div>

            {/* THE HERO BANNER (Directly mirrors Laravel's resources/views/journal/articles/categorie.blade.php) */}
            <div
              className="relative overflow-hidden text-white border-b-4 border-[#D4201A] transition-all duration-300"
              style={{
                backgroundColor: categorieCourante.couleurOverlay || '#14151C',
                backgroundImage: categorieCourante.imageFond
                  ? `linear-gradient(to bottom, rgba(14,15,20,${opaciteDecimal}) 0%, rgba(20,21,28,${Math.min(0.96, opaciteDecimal + 0.15)}) 100%), url('${categorieCourante.imageFond}')`
                  : undefined,
                backgroundPosition: 'center',
                backgroundSize: 'cover',
                backgroundRepeat: 'no-repeat',
                minHeight: '260px',
              }}
            >
              {/* Dual Radial Scrim to guarantee absolute legibility under any lighting */}
              <div 
                className="absolute inset-0 pointer-events-none"
                style={{
                  background: 'radial-gradient(circle at 20% 50%, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.85) 100%)',
                }}
              />

              <div className="max-w-[1240px] mx-auto px-4 sm:px-8 py-14 relative z-10">
                <div className="flex items-center gap-3 mb-3">
                  <span className="bg-[#14151C] text-[#FFC93C] px-3 py-1 text-xs font-black uppercase tracking-widest border-l-4 border-[#D4201A] shadow-md font-heading">
                    Logistics Sector Intelligence
                  </span>
                  <span className="text-xs font-semibold text-gray-200 bg-black/60 px-2.5 py-1 rounded backdrop-blur-sm border border-white/10">
                    {categorieCourante.totalArticles} Briefings Published
                  </span>
                </div>

                <h1 
                  className="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white tracking-tight leading-tight"
                  style={{
                    textShadow: '0 2px 12px rgba(0,0,0,0.9), 0 1px 3px rgba(0,0,0,0.95)',
                  }}
                >
                  {categorieCourante.nom}
                </h1>

                <p 
                  className="text-gray-100 text-lg sm:text-xl max-w-3xl mt-4 leading-relaxed font-normal"
                  style={{
                    textShadow: '0 1px 6px rgba(0,0,0,0.85)',
                  }}
                >
                  {categorieCourante.description}
                </p>
              </div>
            </div>

            {/* Open Access Announcement Bar when Subscriptions Disabled */}
            {!abonnementsActifs && (
              <div className="max-w-[1240px] mx-auto px-4 sm:px-8 mt-6">
                <div className="bg-emerald-50 border-2 border-emerald-500 rounded-lg p-4 flex flex-wrap items-center justify-between gap-3 text-emerald-950 shadow-sm">
                  <div className="flex items-center gap-3">
                    <div className="w-10 h-10 rounded bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow">
                      <Unlock className="w-5 h-5" />
                    </div>
                    <div>
                      <div className="font-heading font-black text-sm uppercase tracking-wide text-emerald-900 flex items-center gap-2">
                        CARGO Open Access Mode Active
                        <span className="text-[10px] bg-emerald-200 text-emerald-900 font-bold px-2 py-0.5 rounded">
                          Site 100% Free
                        </span>
                      </div>
                      <div className="text-xs text-emerald-800 mt-0.5">
                        Administrator has deactivated subscriptions. Every reader enjoys full, unrestricted access to all proprietary briefings and logistics data.
                      </div>
                    </div>
                  </div>
                  <button
                    onClick={() => setVueActive('admin')}
                    className="text-xs font-bold bg-emerald-700 text-white px-3.5 py-1.5 rounded hover:bg-emerald-800 transition uppercase tracking-wider"
                  >
                    View in Admin Settings
                  </button>
                </div>
              </div>
            )}

            {/* Articles Grid */}
            <div className="max-w-[1240px] mx-auto px-4 sm:px-8 pb-16 pt-6">
              <div className="flex items-center justify-between mb-6 pb-2 border-b-2 border-dashed border-[#B8BDC8]">
                <h2 className="font-heading font-black text-2xl text-[#14151C]">
                  Latest Intelligence Briefings in {categorieCourante.nom}
                </h2>
                <span className="text-xs font-bold uppercase tracking-wider text-gray-500">
                  Updated Live · Real-Time Freight Feed
                </span>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {ARTICLES_EXEMPLES.map((art) => (
                  <article key={art.id} className="bg-white border border-gray-200 p-6 flex flex-col justify-between shadow-sm hover:shadow transition">
                    <div>
                      <div className="relative mb-4 overflow-hidden rounded">
                        <img src={art.image} alt={art.titre} className="w-full h-44 object-cover" />
                        <span className={`absolute top-2 left-2 ${art.badgeCouleur || 'bg-[#D4201A]'} text-white text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded shadow`}>
                          {art.mode}
                        </span>
                        {art.isPro && (
                          abonnementsActifs ? (
                            <span className="absolute top-2 right-2 bg-[#FFC93C] text-[#14151C] text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded shadow">
                              PRO Member Exclusive
                            </span>
                          ) : (
                            <span className="absolute top-2 right-2 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded shadow">
                              Open Access (100% Free)
                            </span>
                          )
                        )}
                      </div>

                      <div className="text-xs font-bold uppercase tracking-wider text-[#D4201A] mb-1">
                        Analysis · {art.date}
                      </div>

                      <h3 className="font-heading font-bold text-xl leading-snug mb-3 hover:text-[#D4201A] transition cursor-pointer">
                        {art.titre}
                      </h3>

                      <p className="text-sm text-gray-600 line-clamp-3 leading-relaxed mb-4">
                        {art.chapeau}
                      </p>
                    </div>

                    <div className="pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500 font-medium">
                      <span>By {art.auteur}</span>
                      <span>{art.tempsLecture} min read</span>
                    </div>
                  </article>
                ))}
              </div>
            </div>
          </div>
        )}

        {/* ======================================================== */}
        {/* VIEW 2: WYSIWYG POST EDITOR FOR BEGINNERS & CLIPBOARD SANITIZER */}
        {/* ======================================================== */}
        {vueActive === 'editeur' && (
          <div className="max-w-[1040px] mx-auto px-4 sm:px-8 py-8 space-y-6">
            {/* Editor Intro Card */}
            <div className="bg-white p-6 rounded-lg border border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div>
                <span className="text-xs font-black uppercase text-[#D4201A] tracking-wider font-heading">
                  Beginner-Friendly Editorial Suite
                </span>
                <h1 className="text-2xl font-heading font-black text-[#14151C] mt-1">
                  Article Publishing & Automatic Heading Formatter
                </h1>
                <p className="text-sm text-gray-500 mt-1">
                  No HTML required. Write directly or paste from Word / Google Docs / Web. Headings H1, H2, and anchors are automatically preserved and sanitized.
                </p>
              </div>

              {/* Test Buttons to simulate messy copy/paste */}
              <div className="flex flex-col gap-2 shrink-0">
                <span className="text-[11px] font-bold uppercase text-gray-400">Simulate Clipboard Paste:</span>
                <div className="flex gap-2">
                  <button
                    onClick={simulerCollerWordPollue}
                    className="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 rounded text-xs font-bold flex items-center gap-1.5 transition"
                    title="Simulates pasting Word text with white-on-white text styles and H1/H2 tags"
                  >
                    <Clipboard className="w-3.5 h-3.5 text-[#D4201A]" />
                    Paste Word/Docs (White Text Fixed)
                  </button>

                  <button
                    onClick={simulerCollerMarkdown}
                    className="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 rounded text-xs font-bold flex items-center gap-1.5 transition"
                    title="Simulates pasting markdown # and ## from clipboard"
                  >
                    <Heading1 className="w-3.5 h-3.5 text-[#14151C]" />
                    Paste Markdown (# & ##)
                  </button>
                </div>
              </div>
            </div>

            {/* WebP Compression Live Simulator Banner */}
            <div className="bg-gradient-to-r from-[#14151C] to-[#2B2E3A] text-white p-5 rounded-lg border-l-4 border-emerald-500 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div className="space-y-1">
                <div className="flex items-center gap-2">
                  <span className="text-[10px] font-black uppercase tracking-wider bg-emerald-500 text-white px-2 py-0.5 rounded font-heading">
                    Laravel Engine: WebP Compressor Active
                  </span>
                  <span className="text-xs text-gray-300 font-mono">ServiceConversionWebp @ 82% Quality</span>
                </div>
                <h3 className="font-heading font-black text-lg text-white">
                  Exclusive Article Image Compression (Auto .webp)
                </h3>
                <p className="text-xs text-gray-300 max-w-2xl leading-relaxed">
                  Only article uploads are converted to WebP with alpha transparency preserved and large images scaled to max 1920x1080.
                  Categories and commodities retain their native formats.
                </p>
              </div>

              <div className="flex flex-col sm:flex-row items-center gap-3 shrink-0">
                <div className="bg-white/10 px-3 py-2 rounded text-xs font-mono text-center border border-white/10">
                  <span className="text-gray-400 block text-[10px] uppercase">Example Savings</span>
                  <span className="text-emerald-400 font-bold">2.8 MB &rarr; 390 KB (-86%)</span>
                </div>
                <button
                  onClick={() => declencherToast("WebP conversion simulated: 'shipping-hub.jpg' (2.8 MB) successfully converted to 'shipping-hub-67c.webp' (390 KB, -86% size)!")}
                  className="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded text-xs font-bold uppercase tracking-wider transition shadow flex items-center gap-1.5"
                >
                  <Sparkles className="w-3.5 h-3.5" />
                  Simulate WebP Upload
                </button>
              </div>
            </div>

            {/* Toast Notification */}
            {toastMessage && (
              <div className="bg-emerald-50 border-l-4 border-emerald-500 p-3.5 rounded text-xs text-emerald-800 font-semibold flex items-center gap-2 animate-fade-in shadow-sm">
                <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0" />
                {toastMessage}
              </div>
            )}

            {/* The Modular Editor Component (Direct clone of editeur-html.blade.php) */}
            <div className="border border-gray-300 bg-white rounded-lg shadow-sm overflow-hidden">
              {/* Editor Header with Mode Toggle */}
              <div className="bg-[#14151C] text-white px-4 py-2.5 flex flex-wrap items-center justify-between gap-3 border-b-2 border-[#D4201A]">
                <div className="flex items-center gap-2">
                  <span className="text-xs font-black uppercase text-[#FFC93C] tracking-wider font-heading">
                    CARGO Rich Editor
                  </span>
                  <span className="text-[11px] bg-[#2B2E3A] text-gray-300 px-2 py-0.5 rounded font-medium">
                    {modeEditeur === 'visuel' ? 'Visual WYSIWYG Mode' : 'Raw HTML Code Mode'}
                  </span>
                </div>

                <div className="flex items-center gap-1">
                  <button
                    type="button"
                    onClick={() => {
                      setModeEditeur('visuel');
                    }}
                    className={`px-3 py-1 rounded text-xs font-bold transition ${
                      modeEditeur === 'visuel' ? 'bg-[#D4201A] text-white' : 'bg-[#2B2E3A] text-gray-300 hover:text-white'
                    }`}
                  >
                    Visual Mode (Easy)
                  </button>
                  <button
                    type="button"
                    onClick={() => {
                      setModeEditeur('code');
                    }}
                    className={`px-3 py-1 rounded text-xs font-bold transition ${
                      modeEditeur === 'code' ? 'bg-[#D4201A] text-white' : 'bg-[#2B2E3A] text-gray-300 hover:text-white'
                    }`}
                  >
                    HTML Code (Expert)
                  </button>
                </div>
              </div>

              {/* Beginner Visual Toolbar */}
              {modeEditeur === 'visuel' && (
                <div className="bg-gray-50 border-b border-gray-200 px-3 py-2 flex flex-wrap items-center gap-1.5 text-xs">
                  {/* Heading Dropdown */}
                  <select
                    onChange={(e) => appliquerFormat(e.target.value)}
                    className="h-8 border border-gray-300 rounded px-2 bg-white font-semibold text-gray-800 text-xs focus:outline-none focus:border-[#D4201A]"
                  >
                    <option value="p">Normal Paragraph</option>
                    <option value="h1">Heading 1 (Main Title)</option>
                    <option value="h2">Heading 2 (Section Subtitle)</option>
                    <option value="h3">Heading 3 (Subsection)</option>
                    <option value="blockquote">Blockquote (Quote)</option>
                  </select>

                  <span className="w-px h-5 bg-gray-300 mx-1"></span>

                  {/* Format Buttons */}
                  <button
                    type="button"
                    onClick={() => executerCommande('bold')}
                    className="w-8 h-8 rounded border border-gray-300 bg-white font-bold hover:bg-gray-100 flex items-center justify-center"
                    title="Bold"
                  >
                    <Bold className="w-4 h-4" />
                  </button>
                  <button
                    type="button"
                    onClick={() => executerCommande('italic')}
                    className="w-8 h-8 rounded border border-gray-300 bg-white italic hover:bg-gray-100 flex items-center justify-center"
                    title="Italic"
                  >
                    <Italic className="w-4 h-4" />
                  </button>
                  <button
                    type="button"
                    onClick={() => executerCommande('underline')}
                    className="w-8 h-8 rounded border border-gray-300 bg-white underline hover:bg-gray-100 flex items-center justify-center"
                    title="Underline"
                  >
                    <Underline className="w-4 h-4" />
                  </button>

                  <span className="w-px h-5 bg-gray-300 mx-1"></span>

                  {/* Lists */}
                  <button
                    type="button"
                    onClick={() => executerCommande('insertUnorderedList')}
                    className="h-8 px-2 rounded border border-gray-300 bg-white font-semibold hover:bg-gray-100 flex items-center gap-1"
                    title="Bullet List"
                  >
                    <List className="w-4 h-4" />
                    List
                  </button>
                  <button
                    type="button"
                    onClick={() => executerCommande('insertOrderedList')}
                    className="h-8 px-2 rounded border border-gray-300 bg-white font-semibold hover:bg-gray-100 flex items-center gap-1"
                    title="Numbered List"
                  >
                    <ListOrdered className="w-4 h-4" />
                    Numbered
                  </button>

                  <span className="w-px h-5 bg-gray-300 mx-1"></span>

                  {/* Quick Insertions */}
                  <button
                    type="button"
                    onClick={() => {
                      const url = prompt('Image URL:', 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=800&q=80');
                      if (url) {
                        document.execCommand('insertHTML', false, `<figure style="margin:20px 0;"><img src="${url}" style="width:100%; border-radius:4px; max-height:400px; object-fit:cover;" /><figcaption style="font-size:12px; color:#6B7280; margin-top:6px;">Port operations overview.</figcaption></figure><p></p>`);
                        handleSaisieVisuelle();
                      }
                    }}
                    className="h-8 px-2 rounded border border-gray-300 bg-white font-semibold hover:bg-gray-100 flex items-center gap-1"
                  >
                    <ImageIcon className="w-4 h-4 text-gray-600" />
                    Image
                  </button>

                  <button
                    type="button"
                    onClick={() => {
                      const tableHtml = `
                        <table style="width:100%; border-collapse:collapse; margin:18px 0; border:1px solid #E5E7EB;">
                          <thead>
                            <tr style="background:#14151C; color:#fff;">
                              <th style="padding:8px 12px; text-align:left;">Corridor</th>
                              <th style="padding:8px 12px; text-align:left;">Rate (USD/FEU)</th>
                              <th style="padding:8px 12px; text-align:left;">Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td style="padding:8px 12px; border-bottom:1px solid #E5E7EB; font-weight:600;">Shanghai &rarr; Rotterdam</td>
                              <td style="padding:8px 12px; border-bottom:1px solid #E5E7EB; font-family:monospace;">$4,650</td>
                              <td style="padding:8px 12px; border-bottom:1px solid #E5E7EB; color:#16A34A; font-weight:bold;">Active</td>
                            </tr>
                          </tbody>
                        </table>
                        <p></p>
                      `;
                      document.execCommand('insertHTML', false, tableHtml);
                      handleSaisieVisuelle();
                    }}
                    className="h-8 px-2 rounded border border-gray-300 bg-white font-semibold hover:bg-gray-100 flex items-center gap-1"
                  >
                    Table
                  </button>

                  <button
                    type="button"
                    onClick={() => {
                      const kpi = `
                        <div style="background:#F9FAFB; border-left:4px solid #D4201A; padding:14px 18px; margin:16px 0;">
                          <div style="font-size:11px; font-weight:800; text-transform:uppercase; color:#D4201A;">Key Strategic KPI</div>
                          <p style="margin:4px 0 0; font-size:15px; color:#14151C; font-weight:600;">Over 78% of forwarders prioritize multi-carrier API visibility for Q4 bookings.</p>
                        </div>
                        <p></p>
                      `;
                      document.execCommand('insertHTML', false, kpi);
                      handleSaisieVisuelle();
                    }}
                    className="h-8 px-2 rounded border border-gray-300 bg-white font-semibold hover:bg-gray-100 text-emerald-700 flex items-center gap-1"
                  >
                    KPI Box
                  </button>
                </div>
              )}

              {/* Editable Area (Visual Mode) */}
              {modeEditeur === 'visuel' ? (
                <div
                  ref={editeurRef}
                  contentEditable
                  onInput={handleSaisieVisuelle}
                  onPaste={(e) => {
                    e.preventDefault();
                    const clip = e.clipboardData;
                    const html = clip.getData('text/html');
                    const text = clip.getData('text/plain');
                    assainirEtCollerTexte(html, text);
                  }}
                  dangerouslySetInnerHTML={{ __html: contenuArticle }}
                  className="p-6 min-h-[380px] outline-none text-[#1F2128] leading-relaxed prose max-w-none text-base focus:ring-1 focus:ring-[#D4201A]/30"
                />
              ) : (
                <textarea
                  value={contenuArticle}
                  onChange={(e) => setContenuArticle(e.target.value)}
                  className="w-full p-4 font-mono text-sm leading-relaxed text-[#14151C] bg-stone-50 min-h-[380px] outline-none border-none resize-y"
                  placeholder="Raw HTML code editor..."
                />
              )}

              {/* Editor Footer */}
              <div className="bg-gray-100 px-4 py-2 border-t border-gray-200 flex items-center justify-between text-xs text-gray-500">
                <span className="flex items-center gap-1.5">
                  <span className="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                  Safe Typography Engine: No white-on-white text · Clean semantic headings preserved
                </span>
                <span>
                  {contenuArticle.replace(/<[^>]*>/g, ' ').trim().split(/\s+/).filter(Boolean).length} words
                </span>
              </div>
            </div>

            {/* Final Rendered Article Preview Card */}
            <div className="bg-white p-6 rounded-lg border border-gray-200 space-y-4">
              <div className="flex items-center justify-between pb-3 border-b border-gray-100">
                <span className="text-xs font-bold uppercase tracking-wider text-gray-400">
                  Published Public View Simulation
                </span>
                <span className="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                  Ready to Publish to /PROJET_LARAVEL
                </span>
              </div>

              <div 
                className="article-html-body prose max-w-none"
                dangerouslySetInnerHTML={{ __html: contenuArticle }}
              />
            </div>
          </div>
        )}

        {/* ======================================================== */}
        {/* VIEW 3: ADMIN CATEGORIES TAXONOMY & PAYWALL MANAGEMENT */}
        {/* ======================================================== */}
        {vueActive === 'admin' && (
          <div className="max-w-[1240px] mx-auto px-4 sm:px-8 py-8 space-y-6">
            {/* Global Subscription / Paywall Master Control Card */}
            <div className={`p-6 rounded-lg border-2 shadow-sm transition-all ${
              abonnementsActifs 
                ? 'bg-white border-amber-300' 
                : 'bg-emerald-50/90 border-emerald-500 shadow-emerald-100'
            }`}>
              <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div className="flex items-start gap-4">
                  <div className={`w-12 h-12 rounded-lg flex items-center justify-center shrink-0 shadow-sm ${
                    abonnementsActifs ? 'bg-amber-100 text-amber-800' : 'bg-emerald-600 text-white'
                  }`}>
                    {abonnementsActifs ? <Lock className="w-6 h-6" /> : <Unlock className="w-6 h-6" />}
                  </div>
                  <div>
                    <div className="flex flex-wrap items-center gap-2">
                      <span className="text-xs font-black uppercase tracking-wider font-heading text-gray-500">
                        Global Paywall Controller · Laravel Key: configurations_systeme.abonnements_actifs
                      </span>
                      <span className={`text-[11px] font-black uppercase px-2.5 py-0.5 rounded ${
                        abonnementsActifs 
                          ? 'bg-amber-100 text-amber-900 border border-amber-200' 
                          : 'bg-emerald-600 text-white'
                      }`}>
                        {abonnementsActifs ? 'Monetized Paywall Active' : 'Site 100% Free Active'}
                      </span>
                    </div>
                    <h2 className="text-xl font-heading font-black text-[#14151C] mt-1.5">
                      {abonnementsActifs 
                        ? 'Paid Subscriptions & Paywall Are Currently Enabled' 
                        : 'Subscriptions Are Disabled: Entire Site Is 100% Free for Everyone'}
                    </h2>
                    <p className="text-xs text-gray-600 mt-1 max-w-2xl leading-relaxed">
                      {abonnementsActifs
                        ? 'Exclusive logistics intelligence is gated behind CARGO PRO plans. Non-subscribers encounter the editorial paywall when reading in-depth briefings.'
                        : 'All paywalls and subscription restrictions are bypassed platform-wide. Any reader, guest, or logistics professional can read every PRO article and download sector reports without signing up or paying.'}
                    </p>
                  </div>
                </div>

                <div className="flex flex-col sm:flex-row items-center gap-3 shrink-0">
                  <button
                    onClick={basculerAbonnements}
                    className={`px-5 py-2.5 rounded font-heading font-bold text-xs uppercase tracking-wider transition shadow flex items-center gap-2 ${
                      abonnementsActifs
                        ? 'bg-emerald-600 hover:bg-emerald-700 text-white'
                        : 'bg-[#D4201A] hover:bg-red-700 text-white'
                    }`}
                  >
                    {abonnementsActifs ? (
                      <>
                        <Unlock className="w-4 h-4" />
                        Deactivate Subscriptions (Make Site 100% Free)
                      </>
                    ) : (
                      <>
                        <Lock className="w-4 h-4" />
                        Re-Activate Paid Subscriptions
                      </>
                    )}
                  </button>
                </div>
              </div>

              {/* Status details */}
              <div className="mt-4 pt-4 border-t border-gray-200/70 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div className="bg-white/90 p-3 rounded border border-gray-200">
                  <span className="text-gray-400 font-medium block">Service Layer:</span>
                  <strong className="text-gray-800 font-mono">ServiceAbonnementLecteur</strong>
                  <span className="block text-[11px] text-gray-500 mt-0.5">
                    {abonnementsActifs ? 'Enforcing subscriber access gates' : 'Bypassed: returns true for all articles'}
                  </span>
                </div>
                <div className="bg-white/90 p-3 rounded border border-gray-200">
                  <span className="text-gray-400 font-medium block">Reader Paywall:</span>
                  <strong className={abonnementsActifs ? 'text-amber-700' : 'text-emerald-700'}>
                    {abonnementsActifs ? 'Gated on PRO Briefings' : 'Completely Deactivated'}
                  </strong>
                  <span className="block text-[11px] text-gray-500 mt-0.5">
                    {abonnementsActifs ? 'Paywall box shown to guests' : 'Full briefing content visible'}
                  </span>
                </div>
                <div className="bg-white/90 p-3 rounded border border-gray-200">
                  <span className="text-gray-400 font-medium block">Audit Trail:</span>
                  <strong className="text-gray-800 font-mono">JournalActivite</strong>
                  <span className="block text-[11px] text-gray-500 mt-0.5">
                    Action <code className="text-[#D4201A]">bascule_paywall_global</code> tracked
                  </span>
                </div>
              </div>
            </div>

            <div className="bg-white p-6 rounded-lg border border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div>
                <span className="text-xs font-black uppercase text-[#D4201A] tracking-wider font-heading">
                  Administrative Console
                </span>
                <h1 className="text-2xl font-heading font-black text-[#14151C] mt-1">
                  Editorial Sections & Background Banner Management
                </h1>
                <p className="text-sm text-gray-500 mt-1">
                  Upload custom background images per freight rubric and configure dark scrim contrast settings.
                </p>
              </div>

              <div className="text-xs font-bold text-gray-700 bg-gray-50 px-3 py-2 rounded border border-gray-200">
                Total Active Sections: <strong className="text-[#14151C]">{categories.length}</strong>
              </div>
            </div>

            <div className="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
              <div className="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 className="text-sm font-bold uppercase tracking-wider text-gray-700 font-heading">
                  Configured Logistics Rubrics
                </h2>
              </div>

              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-sm">
                  <thead>
                    <tr className="bg-gray-50 border-b border-gray-200 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                      <th className="py-3 px-4">Order</th>
                      <th className="py-3 px-4">Section / Slug</th>
                      <th className="py-3 px-4">Header Background Banner</th>
                      <th className="py-3 px-4">Contrast Protection</th>
                      <th className="py-3 px-4 text-center">Menu Status</th>
                      <th className="py-3 px-4 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-gray-100">
                    {categories.map((cat) => (
                      <tr key={cat.id} className="hover:bg-gray-50/50 transition">
                        <td className="py-3.5 px-4 font-mono font-bold text-gray-500">
                          #{cat.ordreAffichage}
                        </td>
                        <td className="py-3.5 px-4">
                          <div className="font-bold text-[#14151C]">{cat.nom}</div>
                          <div className="font-mono text-xs text-gray-400">/rubriques/{cat.slug}</div>
                        </td>
                        <td className="py-3.5 px-4">
                          {cat.imageFond ? (
                            <div className="flex items-center gap-2.5">
                              <div className="w-16 h-10 rounded border border-gray-300 overflow-hidden relative shadow-sm bg-[#14151C]">
                                <img src={cat.imageFond} alt={cat.nom} className="w-full h-full object-cover" />
                                <div className="absolute inset-0 bg-black/40"></div>
                              </div>
                              <span className="text-xs text-gray-500 truncate max-w-[140px] font-mono">
                                {cat.imageFond.substring(0, 30)}...
                              </span>
                            </div>
                          ) : (
                            <span className="text-xs text-gray-400 italic">No image (Default Dark)</span>
                          )}
                        </td>
                        <td className="py-3.5 px-4">
                          <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200 font-mono">
                            <ShieldCheck className="w-3.5 h-3.5 text-emerald-600" />
                            {cat.opaciteOverlay}% Dark Scrim
                          </span>
                        </td>
                        <td className="py-3.5 px-4 text-center">
                          <button
                            onClick={() => {
                              setCategories(prev => prev.map(c => c.id === cat.id ? { ...c, afficherDansMenu: !c.afficherDansMenu } : c));
                            }}
                            className={`text-xs px-2.5 py-1 rounded font-bold uppercase tracking-wider transition ${
                              cat.afficherDansMenu ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-500'
                            }`}
                          >
                            {cat.afficherDansMenu ? 'In Header' : 'Hidden'}
                          </button>
                        </td>
                        <td className="py-3.5 px-4 text-right">
                          <button
                            onClick={() => {
                              setCategorieSelectionneeId(cat.id);
                              setVueActive('rubrique');
                            }}
                            className="text-xs font-bold bg-[#D4201A] text-white px-3 py-1.5 rounded hover:bg-red-700 transition"
                          >
                            Preview Banner
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}

        {/* ======================================================== */}
        {/* VIEW 4: LARAVEL ARCHITECTURE & CODEBASE STATUS */}
        {/* ======================================================== */}
        {vueActive === 'architecture' && (
          <div className="max-w-[1240px] mx-auto px-4 sm:px-8 py-8 space-y-6">
            <div className="bg-white p-6 rounded-lg border border-gray-200">
              <span className="text-xs font-black uppercase text-[#D4201A] tracking-wider font-heading">
                Backend Architecture Report
              </span>
              <h1 className="text-2xl font-heading font-black text-[#14151C] mt-1">
                Laravel Code Implementation Summary (/PROJET_LARAVEL/)
              </h1>
              <p className="text-sm text-gray-600 mt-2">
                All changes have been cleanly applied to the existing Laravel MVC structure with strict French naming, SOLID principles, &lt;250 lines per file, and English UI strings.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {/* Feature 1 Summary */}
              <div className="bg-white p-6 rounded-lg border border-gray-200 space-y-3">
                <div className="flex items-center gap-2 text-[#D4201A] font-heading font-bold text-base">
                  <ImageIcon className="w-5 h-5 shrink-0" />
                  1. Rubric Header Background
                </div>
                <ul className="text-xs space-y-2 text-gray-600">
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Migration:</strong> <code>2024_01_01_000016_ajouter_image_fond_aux_categories.php</code> adds background config.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Model Categorie:</strong> <code>App\Models\Categorie.php</code> with <code>styleBanniere()</code>.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Blade View:</strong> <code>categorie.blade.php</code> with dual linear & radial scrim overlays.</span>
                  </li>
                </ul>
              </div>

              {/* Feature 2 Summary */}
              <div className="bg-white p-6 rounded-lg border border-gray-200 space-y-3">
                <div className="flex items-center gap-2 text-[#D4201A] font-heading font-bold text-base">
                  <FileText className="w-5 h-5 shrink-0" />
                  2. Beginner WYSIWYG & Headings
                </div>
                <ul className="text-xs space-y-2 text-gray-600">
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Component:</strong> <code>editeur-html.blade.php</code> with WYSIWYG & HTML tabs.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Heading Retention:</strong> Automatically preserves <code>&lt;h1&gt;</code>, <code>&lt;h2&gt;</code>, <code>&lt;h3&gt;</code>.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Sanitizer:</strong> Strips poisonous white-on-white inline styles from copied Word/Docs fragments.</span>
                  </li>
                </ul>
              </div>

              {/* Feature 3 Summary */}
              <div className="bg-white p-6 rounded-lg border border-gray-200 space-y-3">
                <div className="flex items-center gap-2 text-emerald-700 font-heading font-bold text-base">
                  <Unlock className="w-5 h-5 shrink-0" />
                  3. Global Free Access & Paywall Switch
                </div>
                <ul className="text-xs space-y-2 text-gray-600">
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Migration:</strong> <code>2024_01_01_000017_creer_table_configurations_systeme.php</code> key-value table.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Model & Cache:</strong> <code>ConfigurationSysteme::abonnementsSontActifs()</code>.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Service Bypass:</strong> <code>ServiceAbonnementLecteur::peutConsulterArticle()</code> unlocks all content.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Admin Controller:</strong> <code>AbonnementAdminControleur::basculerPaywallGlobal()</code> with audit trail.</span>
                  </li>
                </ul>
              </div>

              {/* Feature 4 Summary */}
              <div className="bg-white p-6 rounded-lg border border-gray-200 space-y-3">
                <div className="flex items-center gap-2 text-[#FFC93C] font-heading font-bold text-base bg-[#14151C] p-2 rounded">
                  <Clock className="w-5 h-5 shrink-0 text-[#FFC93C]" />
                  <span>4. Hero 7s Fade Slider</span>
                </div>
                <ul className="text-xs space-y-2 text-gray-600">
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Controller:</strong> <code>AccueilControleur.php</code> provides 5 featured articles with graceful fallback.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Blade Component:</strong> <code>resources/views/journal/composants/hero-diaporama.blade.php</code>.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Transitions:</strong> CSS <code>opacity 1.2s ease-in-out</code> cross-fade between background images every 7 seconds.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Controls:</strong> 5 numbered tracks (01-05), animated 7s progress bar, Next/Prev/Pause buttons, and hover-to-pause.</span>
                  </li>
                </ul>
              </div>

              {/* Feature 5 Summary (NEW - WebP Optimization) */}
              <div className="bg-white p-6 rounded-lg border-2 border-emerald-400 space-y-3 shadow-sm">
                <div className="flex items-center gap-2 text-emerald-800 font-heading font-bold text-base bg-emerald-50 p-2 rounded">
                  <Sparkles className="w-5 h-5 shrink-0 text-emerald-600" />
                  <span>5. WebP Compression (Articles Only)</span>
                </div>
                <ul className="text-xs space-y-2 text-gray-600">
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Service:</strong> <code>App\Services\ServiceConversionWebp.php</code> with 82% quality compression.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Scope:</strong> Intercepts <code>dossier === 'articles'</code> in <code>ServiceGestionImage</code>; leaves categories & commodities untouched.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Alpha Transparency:</strong> Full RGBA preservation for transparent PNGs converted to modern WebP.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Proportional Bounds:</strong> Auto-scales oversized photos (max 1920x1080) without distortion or upscaling small images.</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <CheckCircle className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span><strong>Automated Testing:</strong> <code>ConversionImageWebpTest.php</code> verifying dimensions, WebP generation, and fallback.</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        )}
      </main>

      {/* Footer */}
      <footer className="bg-[#14151C] text-white border-t-4 border-[#D4201A] mt-auto">
        <div className="max-w-[1240px] mx-auto px-4 sm:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400">
          <div className="flex items-center gap-2">
            <span className="font-heading font-black text-lg text-white">CARGO</span>
            <span>— Global Logistics & Supply Chain Journalism</span>
          </div>
          <div>
            Built with Laravel 11 MVC & React Preview · Fully compliant with French internal naming & English UI
          </div>
        </div>
      </footer>
    </div>
  );
}
