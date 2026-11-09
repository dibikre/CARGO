import React, { useState } from 'react';
import { 
  CheckCircle, ArrowUpRight, 
  ShieldCheck, Lock, RefreshCw, AlertTriangle
} from 'lucide-react';

interface JourStatistique {
  nom: string;
  nomComplet: string;
  date: string;
  couleur: string;
  publications: number;
  vues: number;
  estAujourdhui: boolean;
  estPasse: boolean;
  estFutur: boolean;
  articleTop: {
    id: number;
    titre: string;
    categorie: string;
    auteur: string;
    vuesJour: number;
    vuesTotales: number;
    slug: string;
  } | null;
}

// Palette éditoriale sobre et cohérente basée sur l'identité CARGO (noir, rouge d'accent, nuances de gris)
const STATS_SEMAINE_MOCK: JourStatistique[] = [
  {
    nom: 'Mon',
    nomComplet: 'Monday',
    date: '2026-11-02',
    couleur: '#14151C',
    publications: 2,
    vues: 590,
    estAujourdhui: false,
    estPasse: true,
    estFutur: false,
    articleTop: {
      id: 6,
      titre: 'Alliances maritimes 2026 : La recomposition Gemini et Ocean Alliance rebat les cartes portuaires',
      categorie: 'Fret Maritime & Ports',
      auteur: 'Claire Delamare',
      vuesJour: 380,
      vuesTotales: 1420,
      slug: 'alliances-maritimes-2026',
    },
  },
  {
    nom: 'Tue',
    nomComplet: 'Tuesday',
    date: '2026-11-03',
    couleur: '#D4201A',
    publications: 2,
    vues: 640,
    estAujourdhui: false,
    estPasse: true,
    estFutur: false,
    articleTop: {
      id: 8,
      titre: 'Flottes lourdes : L’arrivée des chargeurs Megawatt (MCS) lève le frein de l’autonomie longue distance',
      categorie: 'Transport Routier',
      auteur: 'Jean-Baptiste Vian',
      vuesJour: 450,
      vuesTotales: 1280,
      slug: 'flottes-lourdes-megawatt-mcs',
    },
  },
  {
    nom: 'Wed',
    nomComplet: 'Wednesday',
    date: '2026-11-04',
    couleur: '#2B2E3A',
    publications: 1,
    vues: 520,
    estAujourdhui: false,
    estPasse: true,
    estFutur: false,
    articleTop: {
      id: 10,
      titre: 'Fret ferroviaire : Ouverture de la liaison de semi-remorques non-accompagnées Bayonne-Cherbourg',
      categorie: 'Ferroviaire & Intermodal',
      auteur: 'Marc Lemaître',
      vuesJour: 520,
      vuesTotales: 960,
      slug: 'fret-ferroviaire-bayonne-cherbourg',
    },
  },
  {
    nom: 'Thu',
    nomComplet: 'Thursday',
    date: '2026-11-05',
    couleur: '#4B505C',
    publications: 2,
    vues: 950,
    estAujourdhui: false,
    estPasse: true,
    estFutur: false,
    articleTop: {
      id: 11,
      titre: 'Tours de contrôle supply chain : Déploiement massif de balises IoT et d’analyses prédictives d’ETA',
      categorie: 'Supply Chain Tech',
      auteur: 'Sophie Renard',
      vuesJour: 640,
      vuesTotales: 1540,
      slug: 'tours-controle-supply-chain-iot',
    },
  },
  {
    nom: 'Fri',
    nomComplet: 'Friday',
    date: '2026-11-06',
    couleur: '#6B7280',
    publications: 3,
    vues: 1450,
    estAujourdhui: false,
    estPasse: true,
    estFutur: false,
    articleTop: {
      id: 1,
      titre: 'Fret aérien mondial : L’explosion des volumes e-commerce transfrontaliers sature les terminaux cargo',
      categorie: 'Fret Aérien & Express',
      auteur: 'Claire Delamare',
      vuesJour: 820,
      vuesTotales: 2180,
      slug: 'fret-aerien-mondial-ecommerce',
    },
  },
  {
    nom: 'Sat',
    nomComplet: 'Saturday',
    date: '2026-11-07',
    couleur: '#8E95A5',
    publications: 1,
    vues: 430,
    estAujourdhui: false,
    estPasse: true,
    estFutur: false,
    articleTop: {
      id: 4,
      titre: 'Capacités en soute : La multiplication des vols long-courriers stabilise les rendements du belly-cargo',
      categorie: 'Fret Aérien & Express',
      auteur: 'Amélie Dupont',
      vuesJour: 430,
      vuesTotales: 1110,
      slug: 'capacites-en-soute-belly-cargo',
    },
  },
  {
    nom: 'Sun',
    nomComplet: 'Sunday',
    date: '2026-11-08',
    couleur: '#B8BDC8',
    publications: 1,
    vues: 590,
    estAujourdhui: true,
    estPasse: false,
    estFutur: false,
    articleTop: {
      id: 5,
      titre: 'Aviation sans pilote : Premiers vols d’essais de drones cargo lourds pour le ravitaillement d’îles',
      categorie: 'Fret Aérien & Express',
      auteur: 'Sophie Renard',
      vuesJour: 590,
      vuesTotales: 890,
      slug: 'aviation-sans-pilote-drones-cargo',
    },
  },
];

interface DonutSvgProps {
  data: { label: string; value: number; color: string }[];
  centerValue: string;
  centerLabel: string;
}

function DonutSvg({ data, centerValue, centerLabel }: DonutSvgProps) {
  const total = data.reduce((acc, curr) => acc + curr.value, 0) || 1;
  const radius = 38;
  const circumference = 2 * Math.PI * radius;
  let accumulated = 0;

  return (
    <div className="relative w-44 h-44 flex items-center justify-center shrink-0">
      <svg viewBox="0 0 100 100" className="w-full h-full -rotate-90">
        <circle cx="50" cy="50" r={radius} fill="transparent" stroke="#F1F3F5" strokeWidth="14" />
        {data.map((item, index) => {
          const ratio = item.value / total;
          const strokeDasharray = `${ratio * circumference} ${circumference}`;
          const strokeDashoffset = -(accumulated * circumference);
          accumulated += ratio;
          return (
            <circle
              key={index}
              cx="50"
              cy="50"
              r={radius}
              fill="transparent"
              stroke={item.color}
              strokeWidth="14"
              strokeDasharray={strokeDasharray}
              strokeDashoffset={strokeDashoffset}
              className="transition-all duration-300 hover:opacity-85 cursor-pointer"
            >
              <title>{`${item.label}: ${item.value} (${Math.round(ratio * 100)}%)`}</title>
            </circle>
          );
        })}
      </svg>
      <div className="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
        <span className="text-xl font-heading font-black text-gray-900 leading-none">{centerValue}</span>
        <span className="text-[10px] font-bold uppercase tracking-wider text-gray-400 mt-1">{centerLabel}</span>
      </div>
    </div>
  );
}

export default function VueStatistiquesRedaction() {
  const [periode, setPeriode] = useState<'7' | '30' | '90'>('7');
  const [jourSurvole, setJourSurvole] = useState<string | null>(null);
  const [estEnChargement, setEstEnChargement] = useState<boolean>(false);
  const [modeAppareilInvalide, setModeAppareilInvalide] = useState<boolean>(false);

  const jetonExemple = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjIsImxvZ2luIjoiam91cm5hbGlzdGVAY2FyZ28tbG9naXN0aWNzLmNvbSIsImVtcHJlaW50ZSI6ImU5YWI4ZjJkY2E3MWI0MGU4MGY3YTEyOCIsImlhdCI6MTczMTE4NDAwMCwiZXhwIjoxNzMxMTg3NjAwfQ.s_H7yN_3eK9wJ1b8xXz';

  const changerPeriode = (nouvellePeriode: '7' | '30' | '90') => {
    if (nouvellePeriode === periode) return;
    setEstEnChargement(true);
    setTimeout(() => {
      setPeriode(nouvellePeriode);
      setEstEnChargement(false);
    }, 280);
  };

  const totalPublications = periode === '7' ? 12 : (periode === '30' ? 44 : 118);
  const totalVues = periode === '7' ? 5140 : (periode === '30' ? 18600 : 54200);

  const donneesDonutPubs = STATS_SEMAINE_MOCK.map((j) => ({
    label: j.nomComplet,
    value: j.publications,
    color: j.couleur,
  }));

  const donneesDonutVues = STATS_SEMAINE_MOCK.map((j) => ({
    label: j.nomComplet,
    value: j.vues,
    color: j.couleur,
  }));

  return (
    <div className="max-w-[1240px] mx-auto px-4 sm:px-8 py-6 space-y-6">
      {/* Security Gateway Badge: Device-Bound JWT Secret */}
      <div className="bg-[#14151C] text-white p-4 rounded border border-gray-800 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div className="space-y-1">
          <div className="flex items-center gap-2">
            <span className="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
            <span className="text-xs font-bold uppercase tracking-wider text-white flex items-center gap-1.5">
              <ShieldCheck className="w-3.5 h-3.5 text-emerald-400" />
              Device-Bound JWT Security Gateway Active
            </span>
            <span className="text-[10px] bg-white/10 text-gray-300 px-2 py-0.5 rounded font-mono">
              Zero Page Reload (SPA)
            </span>
          </div>
          <p className="text-xs text-gray-300">
            Authenticated as <strong className="text-white">journaliste@cargo-logistics.com</strong> · Bound to device fingerprint:
            <code className="ml-1 px-1.5 py-0.5 bg-black/50 text-[#FFC93C] rounded font-mono text-[11px]">sha256(UserAgent+IP+Sec-CH-UA)</code>
          </p>
          <div className="text-[11px] text-gray-400 font-mono truncate max-w-2xl">
            API URL with Device Secret: <span className="text-gray-300">/redaction/statistiques/api?periode={periode}&amp;jeton_securite={jetonExemple.slice(0, 32)}...</span>
          </div>
        </div>

        <button
          onClick={() => setModeAppareilInvalide(!modeAppareilInvalide)}
          className={`shrink-0 px-3 py-1.5 text-xs font-bold uppercase tracking-wider rounded border transition flex items-center gap-1.5 ${
            modeAppareilInvalide
              ? 'bg-[#D4201A] border-[#D4201A] text-white'
              : 'bg-white/10 hover:bg-white/20 border-white/20 text-gray-200'
          }`}
          title="Simulate calling the API from an unauthorized device to test security barrier"
        >
          <Lock className="w-3.5 h-3.5" />
          {modeAppareilInvalide ? 'Device Mismatch (Blocked)' : 'Simulate Untrusted Device Attempt'}
        </button>
      </div>

      {/* Security alert if untrusted device is simulated */}
      {modeAppareilInvalide && (
        <div className="bg-white border-2 border-[#14151C] text-gray-900 p-4 rounded text-xs flex items-center justify-between shadow-xs">
          <div className="flex items-center gap-2">
            <AlertTriangle className="w-4 h-4 text-[#D4201A] shrink-0" />
            <span>
              <strong>HTTP 403 Forbidden:</strong> The security token provided in the URL does not match this device&apos;s digital fingerprint. Access blocked without credential reproduction capability.
            </span>
          </div>
          <button onClick={() => setModeAppareilInvalide(false)} className="text-gray-900 hover:text-[#D4201A] font-bold text-sm ml-4">
            Reset
          </button>
        </div>
      )}

      {/* Header and Period Switcher with styling identical to /redaction/articles */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
          <div className="flex items-center gap-2">
            <span className="w-2.5 h-2.5 rounded-full bg-[#D4201A]"></span>
            <span className="text-xs font-bold text-gray-500 uppercase tracking-wider">
              Newsroom Desk · Audience &amp; Publications
            </span>
          </div>
          <h1 className="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1 flex items-center gap-3">
            <span>Audience &amp; Publications Analytics</span>
            {estEnChargement && (
              <span className="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-gray-100 text-gray-800 border border-gray-300 text-xs font-bold rounded">
                <RefreshCw className="w-3 h-3 animate-spin text-gray-700" />
                <span>Refreshing...</span>
              </span>
            )}
          </h1>
        </div>

        {/* Filter buttons modeled on /redaction/articles */}
        <div className="flex items-center gap-2 flex-wrap">
          {(['7', '30', '90'] as const).map((p) => (
            <button
              key={p}
              onClick={() => changerPeriode(p)}
              disabled={estEnChargement}
              className={`px-4 py-2 font-bold text-xs uppercase tracking-wider transition ${
                periode === p
                  ? 'bg-[#14151C] text-white'
                  : 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-50'
              }`}
            >
              Last {p} Days
            </button>
          ))}
        </div>
      </div>

      {/* Top 4 KPI Cards (Black typography & neutral gray metadata) */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div className="bg-white p-5 rounded border border-gray-200 shadow-xs relative overflow-hidden">
          <div className="text-xs font-bold text-gray-500 uppercase tracking-wider">Cumulative Unique Views</div>
          <div className="text-3xl font-heading font-black text-gray-900 mt-2">{totalVues.toLocaleString()}</div>
          <div className="text-xs text-gray-500 font-semibold mt-1 flex items-center gap-1.5">
            <span className="w-1.5 h-1.5 rounded-full bg-[#D4201A]"></span>
            <span>Live IP-verified readings</span>
          </div>
        </div>

        <div className="bg-white p-5 rounded border border-gray-200 shadow-xs relative overflow-hidden">
          <div className="text-xs font-bold text-gray-500 uppercase tracking-wider">Published in Period</div>
          <div className="text-3xl font-heading font-black text-gray-900 mt-2">{totalPublications}</div>
          <div className="text-xs text-gray-500 font-semibold mt-1">Articles published in period</div>
        </div>

        <div className="bg-white p-5 rounded border border-gray-200 shadow-xs relative overflow-hidden">
          <div className="text-xs font-bold text-gray-500 uppercase tracking-wider">All-Time Published</div>
          <div className="text-3xl font-heading font-black text-gray-900 mt-2">12</div>
          <div className="text-xs text-gray-500 font-semibold mt-1">12 articles in newsroom database</div>
        </div>

        <div className="bg-white p-5 rounded border border-gray-200 shadow-xs relative overflow-hidden">
          <div className="text-xs font-bold text-gray-500 uppercase tracking-wider">Editorial Pipeline</div>
          <div className="text-3xl font-heading font-black text-gray-900 mt-2">3</div>
          <div className="text-xs text-gray-500 font-semibold mt-1">2 under review · 1 draft</div>
        </div>
      </div>

      {/* 2 Circular Charts Side-by-Side (Clean, unified editorial palette) */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Diagram 1: Publication Volume by Day */}
        <div className="bg-white rounded border border-gray-200 p-5 shadow-xs space-y-4">
          <div className="flex items-start justify-between gap-3 pb-3 border-b border-gray-100">
            <div>
              <div className="flex items-center gap-2">
                <span className="w-2.5 h-2.5 rounded-full bg-[#D4201A]"></span>
                <span className="text-xs font-bold text-gray-500 uppercase tracking-wider">Weekly Output</span>
              </div>
              <h2 className="text-base font-heading font-black text-gray-900 mt-0.5">Publication Volume by Day</h2>
              <p className="text-xs text-gray-500">Distribution of articles published since Monday (Nov 02 – Nov 08)</p>
            </div>
            <div className="text-right shrink-0">
              <span className="text-xs text-gray-500 font-semibold block uppercase">Total Published</span>
              <span className="text-2xl font-heading font-black text-gray-900">{totalPublications}</span>
            </div>
          </div>

          <div className="flex flex-col sm:flex-row items-center gap-6 pt-2">
            <DonutSvg data={donneesDonutPubs} centerValue={totalPublications.toString()} centerLabel="Articles" />

            <div className="flex-1 w-full space-y-1">
              {STATS_SEMAINE_MOCK.map((jour) => {
                const pct = Math.round((jour.publications / totalPublications) * 100);
                return (
                  <div
                    key={jour.nom}
                    onMouseEnter={() => setJourSurvole(jour.nom)}
                    onMouseLeave={() => setJourSurvole(null)}
                    className={`flex items-center justify-between text-xs py-1.5 px-2 rounded transition ${
                      jour.estAujourdhui
                        ? 'bg-gray-100 font-bold'
                        : jourSurvole === jour.nom
                        ? 'bg-gray-50'
                        : 'hover:bg-gray-50'
                    }`}
                  >
                    <div className="flex items-center gap-2 min-w-0">
                      <span className="w-2.5 h-2.5 rounded-full shrink-0" style={{ backgroundColor: jour.couleur }}></span>
                      <span className="text-gray-900 truncate">
                        {jour.nomComplet}
                        {jour.estAujourdhui && (
                          <span className="ml-1 text-[9px] font-bold text-white bg-[#14151C] px-1.5 py-0.5 rounded uppercase">
                            Today
                          </span>
                        )}
                      </span>
                    </div>
                    <div className="flex items-center gap-3 shrink-0">
                      <span className="font-mono font-bold text-gray-900">
                        {jour.publications} <span className="text-gray-400 font-normal">arts</span>
                      </span>
                      <span className="font-mono text-xs text-gray-500 w-10 text-right">{pct}%</span>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </div>

        {/* Diagram 2: Views Distribution by Day */}
        <div className="bg-white rounded border border-gray-200 p-5 shadow-xs space-y-4">
          <div className="flex items-start justify-between gap-3 pb-3 border-b border-gray-100">
            <div>
              <div className="flex items-center gap-2">
                <span className="w-2.5 h-2.5 rounded-full bg-[#14151C]"></span>
                <span className="text-xs font-bold text-gray-500 uppercase tracking-wider">Weekly Readership</span>
              </div>
              <h2 className="text-base font-heading font-black text-gray-900 mt-0.5">Views Distribution by Day</h2>
              <p className="text-xs text-gray-500">Readership shares captured since Monday across all active articles</p>
            </div>
            <div className="text-right shrink-0">
              <span className="text-xs text-gray-500 font-semibold block uppercase">Total Views</span>
              <span className="text-2xl font-heading font-black text-gray-900">{totalVues.toLocaleString()}</span>
            </div>
          </div>

          <div className="flex flex-col sm:flex-row items-center gap-6 pt-2">
            <DonutSvg data={donneesDonutVues} centerValue={totalVues.toLocaleString()} centerLabel="Views" />

            <div className="flex-1 w-full space-y-1">
              {STATS_SEMAINE_MOCK.map((jour) => {
                const pct = Math.round((jour.vues / totalVues) * 100);
                return (
                  <div
                    key={jour.nom}
                    onMouseEnter={() => setJourSurvole(jour.nom)}
                    onMouseLeave={() => setJourSurvole(null)}
                    className={`flex items-center justify-between text-xs py-1.5 px-2 rounded transition ${
                      jour.estAujourdhui
                        ? 'bg-gray-100 font-bold'
                        : jourSurvole === jour.nom
                        ? 'bg-gray-50'
                        : 'hover:bg-gray-50'
                    }`}
                  >
                    <div className="flex items-center gap-2 min-w-0">
                      <span className="w-2.5 h-2.5 rounded-full shrink-0" style={{ backgroundColor: jour.couleur }}></span>
                      <span className="text-gray-900 truncate">
                        {jour.nomComplet}
                        {jour.estAujourdhui && (
                          <span className="ml-1 text-[9px] font-bold text-white bg-[#14151C] px-1.5 py-0.5 rounded uppercase">
                            Today
                          </span>
                        )}
                      </span>
                    </div>
                    <div className="flex items-center gap-3 shrink-0">
                      <span className="font-mono font-bold text-gray-900">
                        {jour.vues.toLocaleString()} <span className="text-gray-400 font-normal">views</span>
                      </span>
                      <span className="font-mono text-xs text-gray-500 w-10 text-right">{pct}%</span>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </div>

      {/* Most-Viewed Articles by Day of Current Week */}
      <div className="bg-white rounded border border-gray-200 p-5 shadow-xs space-y-4">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-gray-100">
          <div>
            <div className="flex items-center gap-2">
              <span className="w-2.5 h-2.5 rounded-full bg-[#D4201A]"></span>
              <span className="text-xs font-bold text-gray-500 uppercase tracking-wider">
                Day-by-Day Editorial Leaderboard
              </span>
            </div>
            <h2 className="text-base font-heading font-black text-gray-900 mt-0.5">
              Most-Viewed Articles by Day (This Week)
            </h2>
            <p className="text-xs text-gray-500">
              Highest-performing article identified for each individual day from Monday to Sunday
            </p>
          </div>
          <span className="px-2.5 py-1 text-xs font-bold text-gray-800 bg-gray-100 border border-gray-300 rounded uppercase tracking-wider self-start sm:self-auto">
            Weekly Leaderboard
          </span>
        </div>

        {/* 7 Days Cards Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-3 pt-2">
          {STATS_SEMAINE_MOCK.map((jour) => {
            const artTop = jour.articleTop;
            return (
              <div
                key={jour.nom}
                className={`flex flex-col justify-between rounded border ${
                  jour.estAujourdhui
                    ? 'border-2 border-[#14151C] bg-white shadow-xs'
                    : 'border-gray-200 bg-white hover:border-gray-300'
                } p-3 transition`}
              >
                {/* Day Header */}
                <div className="pb-2 border-b border-gray-100 mb-2">
                  <div className="flex items-center justify-between">
                    <div className="flex items-center gap-1.5">
                      <span className="w-2 h-2 rounded-full" style={{ backgroundColor: jour.couleur }}></span>
                      <span className="font-heading font-black text-xs text-gray-900">{jour.nomComplet}</span>
                    </div>
                    {jour.estAujourdhui ? (
                      <span className="text-[9px] font-bold uppercase tracking-wider bg-[#14151C] text-white px-1.5 py-0.5 rounded">
                        Today
                      </span>
                    ) : (
                      <span className="text-[10px] font-mono text-gray-500 font-bold">{jour.nom}</span>
                    )}
                  </div>
                  <div className="text-xs text-gray-500 mt-1 flex items-center justify-between">
                    <span>Day Traffic:</span>
                    <span className="font-mono font-bold text-gray-900">{jour.vues.toLocaleString()} views</span>
                  </div>
                </div>

                {/* Body: Top Article */}
                <div className="flex-1 flex flex-col justify-between space-y-2">
                  {artTop ? (
                    <div>
                      <span className="inline-block text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded bg-gray-100 text-gray-800 mb-1 border border-gray-200">
                        {artTop.categorie}
                      </span>

                      <h3 className="text-xs font-bold text-gray-900 line-clamp-3 hover:text-[#D4201A] leading-snug">
                        {artTop.titre}
                      </h3>

                      <div className="text-[11px] text-gray-500 mt-1 truncate">By {artTop.auteur}</div>
                    </div>
                  ) : null}

                  {artTop && (
                    <div className="pt-2 border-t border-gray-100 mt-2 space-y-1.5">
                      <div className="flex items-center justify-between text-xs">
                        <span className="text-gray-500">Day Views:</span>
                        <span className="font-mono font-bold text-gray-900">+{artTop.vuesJour}</span>
                      </div>
                      <div className="flex items-center justify-between text-[11px]">
                        <span className="text-gray-500">Total Views:</span>
                        <span className="font-mono text-gray-700 font-semibold">{artTop.vuesTotales.toLocaleString()}</span>
                      </div>

                      <div className="flex items-center justify-between pt-1">
                        <button className="text-xs font-bold text-gray-900 hover:underline">
                          Edit HTML
                        </button>
                        <button className="text-xs font-semibold text-gray-600 hover:text-gray-900 hover:underline flex items-center gap-0.5">
                          View
                          <ArrowUpRight className="w-2.5 h-2.5" />
                        </button>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}
