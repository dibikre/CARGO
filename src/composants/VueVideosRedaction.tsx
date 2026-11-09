import React, { useState, useEffect, useMemo } from 'react';
import { 
  Play, 
  Trash2, 
  Search, 
  Plus, 
  Copy, 
  Check, 
  X, 
  ShieldCheck, 
  ShieldAlert, 
  Layers, 
  LayoutGrid, 
  List, 
  ChevronLeft, 
  ChevronRight, 
  ExternalLink,
  Video,
  Eye,
  AlertCircle
} from 'lucide-react';

interface VideoItem {
  id: number;
  titre: string;
  slug: string;
  url_embed: string;
  url_iframe: string;
  url_miniature: string | null;
  plateforme: 'youtube' | 'vimeo' | 'autre';
  source: string;
  duree: string;
  description: string;
  est_actif: boolean;
  date_creation: string;
}

const VIDEOS_INITIALES: VideoItem[] = [
  {
    id: 1,
    titre: 'Panama Canal Neopanamax Locks: Transit of 15,000 TEU Container Ship',
    slug: 'panama-canal-neopanamax-locks',
    url_embed: 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    url_iframe: 'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',
    url_miniature: 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=600&auto=format&fit=crop&q=80',
    plateforme: 'youtube',
    source: 'Panama Canal Authority',
    duree: '08:45',
    description: 'Detailed footage of lock filling and electric locomotives assisting a Neopanamax vessel.',
    est_actif: true,
    date_creation: 'Nov 08, 2026'
  },
  {
    id: 2,
    titre: 'Port of Singapore: Tuas Megaport Automated Guided Vehicles (AGV)',
    slug: 'tuas-megaport-agv-fleet',
    url_embed: 'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
    url_iframe: 'https://www.youtube-nocookie.com/embed/kJQP7kiw5Fk',
    url_miniature: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&auto=format&fit=crop&q=80',
    plateforme: 'youtube',
    source: 'PSA International',
    duree: '06:20',
    description: 'Autonomous logistics flow and high-density yard crane operations in Singapore.',
    est_actif: true,
    date_creation: 'Nov 07, 2026'
  },
  {
    id: 3,
    titre: 'Frankfurt CargoCity: High-Tech Pharma Hub Temperature-Controlled Ramp',
    slug: 'frankfurt-cargocity-pharma-hub',
    url_embed: 'https://www.youtube.com/watch?v=3JZ_D3ELwOQ',
    url_iframe: 'https://www.youtube-nocookie.com/embed/3JZ_D3ELwOQ',
    url_miniature: 'https://images.unsplash.com/photo-1542296332-2e4473faf563?w=600&auto=format&fit=crop&q=80',
    plateforme: 'youtube',
    source: 'Lufthansa Cargo Desk',
    duree: '11:15',
    description: 'Cold chain operations for biologics and vaccine air freight logistics.',
    est_actif: true,
    date_creation: 'Nov 06, 2026'
  },
  {
    id: 4,
    titre: 'CMA CGM Jacques Saadé: World\'s First 23,000 TEU LNG-Powered Containership',
    slug: 'cma-cgm-jacques-saade-lng',
    url_embed: 'https://www.youtube.com/watch?v=L_LUpnjgPso',
    url_iframe: 'https://www.youtube-nocookie.com/embed/L_LUpnjgPso',
    url_miniature: 'https://images.unsplash.com/photo-1518241353330-0f7941c2d9b5?w=600&auto=format&fit=crop&q=80',
    plateforme: 'youtube',
    source: 'CMA CGM Media',
    duree: '05:30',
    description: 'Bunkering liquefied natural gas at Rotterdam and sea trials in the North Sea.',
    est_actif: true,
    date_creation: 'Nov 05, 2026'
  },
  {
    id: 5,
    titre: 'BNSF Intermodal Rail: Double-Stack Container Corridor Logistics',
    slug: 'bnsf-intermodal-rail-corridor',
    url_embed: 'https://vimeo.com/76979871',
    url_iframe: 'https://player.vimeo.com/video/76979871',
    url_miniature: 'https://images.unsplash.com/photo-1474487548417-781cb71495f3?w=600&auto=format&fit=crop&q=80',
    plateforme: 'vimeo',
    source: 'FreightWaves TV',
    duree: '14:22',
    description: 'Transcontinental rail freight routing across the American Midwest corridor.',
    est_actif: true,
    date_creation: 'Nov 04, 2026'
  },
  {
    id: 6,
    titre: 'Antwerp-Bruges Port Hub: Hydrogen Fuel Cell Straddle Carrier Pilot',
    slug: 'antwerp-bruges-hydrogen-carrier',
    url_embed: 'https://vimeo.com/22439234',
    url_iframe: 'https://player.vimeo.com/video/22439234',
    url_miniature: 'https://images.unsplash.com/photo-1505705694340-019e1e335916?w=600&auto=format&fit=crop&q=80',
    plateforme: 'vimeo',
    source: 'Port of Antwerp-Bruges',
    duree: '07:40',
    description: 'Zero-emission port handling equipment trials and hydrogen refilling stations.',
    est_actif: true,
    date_creation: 'Nov 03, 2026'
  },
  {
    id: 7,
    titre: 'Dubai Al Maktoum International: DWC Mega Cargo Superhub Tour',
    slug: 'dubai-dwc-mega-cargo-superhub',
    url_embed: 'https://www.youtube.com/watch?v=9bZkp7q19f0',
    url_iframe: 'https://www.youtube-nocookie.com/embed/9bZkp7q19f0',
    url_miniature: 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=600&auto=format&fit=crop&q=80',
    plateforme: 'youtube',
    source: 'Emirates SkyCargo',
    duree: '12:05',
    description: 'Automated material handling system and high-capacity main deck freighter loading.',
    est_actif: false,
    date_creation: 'Nov 02, 2026'
  },
  {
    id: 8,
    titre: 'Gotthard Base Tunnel Freight Transit: Flat Rail Crossing of the Alps',
    slug: 'gotthard-base-tunnel-freight',
    url_embed: 'https://www.youtube.com/watch?v=uelHwf8o7_U',
    url_iframe: 'https://www.youtube-nocookie.com/embed/uelHwf8o7_U',
    url_miniature: 'https://images.unsplash.com/photo-1515263487990-61b07816b324?w=600&auto=format&fit=crop&q=80',
    plateforme: 'youtube',
    source: 'SBB Cargo International',
    duree: '10:45',
    description: 'Electric freight locomotives pulling intermodal trucks through the world\'s longest tunnel.',
    est_actif: true,
    date_creation: 'Nov 01, 2026'
  }
];

export default function VueVideosRedaction() {
  const [videos, setVideos] = useState<VideoItem[]>(VIDEOS_INITIALES);
  const [recherche, setRecherche] = useState('');
  const [plateforme, setPlateforme] = useState<'toutes' | 'youtube' | 'vimeo' | 'autre'>('toutes');
  const [statut, setStatut] = useState<'tous' | 'actif' | 'inactif'>('tous');
  const [tri, setTri] = useState<'recent' | 'ancien' | 'titre_az'>('recent');
  const [vue, setVue] = useState<'tableau' | 'grille'>('tableau');
  const [page, setPage] = useState(1);
  const parPage = 6;

  const [selectionIds, setSelectionIds] = useState<Set<number>>(new Set());
  const [modaleApercu, setModaleApercu] = useState<VideoItem | null>(null);
  const [modaleAjoutOuverte, setModaleAjoutOuverte] = useState(false);
  const [modaleSuppr, setModaleSuppr] = useState<{ type: 'unitaire' | 'lot'; ids: number[]; titre?: string } | null>(null);
  const [toastMessage, setToastMessage] = useState<string | null>(null);

  // Security Simulator state
  const [appareilSimule, setAppareilSimule] = useState<'legitime' | 'pirate'>('legitime');
  const [jetonSecret, setJetonSecret] = useState('cargo_jwt_' + Math.random().toString(36).substring(2, 10));

  const declencherToast = (msg: string) => {
    setToastMessage(msg);
    setTimeout(() => setToastMessage(null), 3000);
  };

  const verifierSecuriteAppareil = () => {
    if (appareilSimule === 'pirate') {
      declencherToast('HTTP 403: JETON_INVALIDE_OU_APPAREIL_NON_CONFORME. Device fingerprint mismatch!');
      return false;
    }
    return true;
  };

  // Keyboard shortcut '/'
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === '/' && document.activeElement?.tagName !== 'INPUT' && document.activeElement?.tagName !== 'TEXTAREA') {
        e.preventDefault();
        document.getElementById('input-search-video')?.focus();
      }
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, []);

  // Filtering and sorting
  const videosFiltrees = useMemo(() => {
    return videos.filter(v => {
      const matchRecherche = !recherche || 
        v.titre.toLowerCase().includes(recherche.toLowerCase()) ||
        v.source.toLowerCase().includes(recherche.toLowerCase()) ||
        v.description.toLowerCase().includes(recherche.toLowerCase());
      
      const matchPlateforme = plateforme === 'toutes' || v.plateforme === plateforme;
      const matchStatut = statut === 'tous' || (statut === 'actif' ? v.est_actif : !v.est_actif);

      return matchRecherche && matchPlateforme && matchStatut;
    }).sort((a, b) => {
      if (tri === 'ancien') return a.id - b.id;
      if (tri === 'titre_az') return a.titre.localeCompare(b.titre);
      return b.id - a.id;
    });
  }, [videos, recherche, plateforme, statut, tri]);

  const totalPages = Math.ceil(videosFiltrees.length / parPage) || 1;
  const videosPaginees = useMemo(() => {
    const debut = (page - 1) * parPage;
    return videosFiltrees.slice(debut, debut + parPage);
  }, [videosFiltrees, page, parPage]);

  // KPIs
  const kpis = useMemo(() => {
    const total = videos.length;
    const actifs = videos.filter(v => v.est_actif).length;
    const yt = videos.filter(v => v.plateforme === 'youtube').length;
    const vim = videos.filter(v => v.plateforme === 'vimeo' || v.plateforme === 'autre').length;
    return { total, actifs, yt, vim };
  }, [videos]);

  // Actions
  const toggleStatut = (id: number) => {
    if (!verifierSecuriteAppareil()) return;
    setVideos(prev => prev.map(v => v.id === id ? { ...v, est_actif: !v.est_actif } : v));
    declencherToast('Video visibility toggled on homepage sidebar.');
  };

  const copierCodeEmbed = (code: string) => {
    navigator.clipboard.writeText(code);
    declencherToast('Embed HTML snippet copied to clipboard');
  };

  const confirmerSuppression = () => {
    if (!verifierSecuriteAppareil()) return;
    if (!modaleSuppr) return;

    setVideos(prev => prev.filter(v => !modaleSuppr.ids.includes(v.id)));
    setSelectionIds(new Set());
    declencherToast(`${modaleSuppr.ids.length} video(s) permanently removed without reload.`);
    setModaleSuppr(null);
  };

  const ajouterVideo = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    if (!verifierSecuriteAppareil()) return;

    const form = e.currentTarget;
    const titre = (form.elements.namedItem('titre') as HTMLInputElement).value;
    const url = (form.elements.namedItem('url_embed') as HTMLInputElement).value;
    const source = (form.elements.namedItem('source') as HTMLInputElement).value;
    const duree = (form.elements.namedItem('duree') as HTMLInputElement).value;
    const description = (form.elements.namedItem('description') as HTMLTextAreaElement).value;
    const est_actif = (form.elements.namedItem('est_actif') as HTMLInputElement).checked;

    let plat: 'youtube' | 'vimeo' | 'autre' = 'autre';
    if (url.includes('youtube') || url.includes('youtu.be')) plat = 'youtube';
    else if (url.includes('vimeo')) plat = 'vimeo';

    const nouvelle: VideoItem = {
      id: Date.now(),
      titre,
      slug: titre.toLowerCase().replace(/[^a-z0-9]+/g, '-'),
      url_embed: url,
      url_iframe: url,
      url_miniature: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&auto=format&fit=crop&q=80',
      plateforme: plat,
      source: source || 'Editorial Desk',
      duree: duree || '05:00',
      description,
      est_actif,
      date_creation: 'Just now'
    };

    setVideos(prev => [nouvelle, ...prev]);
    setModaleAjoutOuverte(false);
    declencherToast('New video embed published to repository.');
  };

  return (
    <div className="space-y-6">
      {/* Editorial Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
          <div className="flex items-center gap-2">
            <span className="inline-block w-2.5 h-2.5 rounded-full bg-[#D4201A]"></span>
            <span className="text-xs font-bold text-gray-500 uppercase tracking-wider">Newsroom Desk &bull; Multimedia Intelligence</span>
          </div>
          <h1 className="text-2xl font-heading font-black text-gray-900 tracking-tight mt-1">
            Embedded Videos Repository (/redaction/videos)
          </h1>
        </div>

        {/* Security & Token Banner */}
        <div className="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg p-1.5 text-xs">
          <div className="flex items-center gap-1.5 px-2 py-1 bg-white rounded border border-gray-200 font-mono text-gray-700">
            {appareilSimule === 'legitime' ? (
              <ShieldCheck className="w-4 h-4 text-emerald-600" />
            ) : (
              <ShieldAlert className="w-4 h-4 text-red-600" />
            )}
            <span className="text-[11px] truncate max-w-[130px]">URL Secret: {jetonSecret}</span>
          </div>

          <button 
            type="button"
            onClick={() => {
              setAppareilSimule(prev => prev === 'legitime' ? 'pirate' : 'legitime');
              declencherToast(appareilSimule === 'legitime' ? 'Device Spoofed! API calls will now fail with 403.' : 'Legitimate Device restored. API unlocked.');
            }}
            className={`px-2.5 py-1 rounded text-[11px] font-bold uppercase tracking-wider transition ${
              appareilSimule === 'legitime' 
                ? 'bg-gray-200 hover:bg-gray-300 text-gray-700' 
                : 'bg-red-600 text-white animate-pulse'
            }`}
          >
            {appareilSimule === 'legitime' ? 'Simulate Device Mismatch' : 'Restore Valid Device'}
          </button>
        </div>
      </div>

      {/* 1. KPIs */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div className="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold uppercase tracking-wider text-gray-500">Total Embeds</span>
            <span className="w-2 h-2 rounded-full bg-[#14151C]"></span>
          </div>
          <div className="mt-2 flex items-baseline gap-2">
            <span className="text-2xl font-heading font-black text-gray-900">{kpis.total}</span>
            <span className="text-xs text-gray-400">multimedia assets</span>
          </div>
        </div>

        <div className="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold uppercase tracking-wider text-gray-500">Active On Homepage</span>
            <span className="w-2 h-2 rounded-full bg-[#D4201A]"></span>
          </div>
          <div className="mt-2 flex items-baseline gap-2">
            <span className="text-2xl font-heading font-black text-gray-900">{kpis.actifs}</span>
            <span className="text-xs text-gray-400">live in sidebar</span>
          </div>
        </div>

        <div className="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold uppercase tracking-wider text-gray-500">YouTube Embeds</span>
            <span className="w-2 h-2 rounded-full bg-gray-400"></span>
          </div>
          <div className="mt-2 flex items-baseline gap-2">
            <span className="text-2xl font-heading font-black text-gray-900">{kpis.yt}</span>
            <span className="text-xs text-gray-400">google media stream</span>
          </div>
        </div>

        <div className="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold uppercase tracking-wider text-gray-500">Vimeo & Other</span>
            <span className="w-2 h-2 rounded-full bg-gray-300"></span>
          </div>
          <div className="mt-2 flex items-baseline gap-2">
            <span className="text-2xl font-heading font-black text-gray-900">{kpis.vim}</span>
            <span className="text-xs text-gray-400">pro & direct embeds</span>
          </div>
        </div>
      </div>

      {/* 2. Toolbar & Instant Search */}
      <div className="bg-white rounded-xl border border-gray-100 shadow-sm p-4 space-y-4">
        <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
          <div className="relative flex-1 max-w-lg">
            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
              <Search className="w-4 h-4" />
            </div>
            <input 
              id="input-search-video"
              type="text" 
              value={recherche}
              onChange={e => { setRecherche(e.target.value); setPage(1); }}
              placeholder="Search by title, source channel, or synopsis..."
              className="w-full pl-9 pr-12 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#D4201A] focus:border-[#D4201A] transition"
            />
            <div className="absolute inset-y-0 right-0 pr-3 flex items-center gap-1.5">
              {recherche && (
                <button onClick={() => setRecherche('')} className="text-gray-400 hover:text-gray-600">
                  <X className="w-4 h-4" />
                </button>
              )}
              <kbd className="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-mono text-gray-400 bg-gray-100 border border-gray-200 rounded">/</kbd>
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-3">
            {selectionIds.size > 0 && (
              <button 
                onClick={() => setModaleSuppr({ type: 'lot', ids: Array.from(selectionIds) })}
                className="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold uppercase tracking-wider text-white bg-[#D4201A] hover:bg-red-700 rounded shadow-sm transition"
              >
                <Trash2 className="w-4 h-4" />
                <span>Delete Selected ({selectionIds.size})</span>
              </button>
            )}

            {/* View Switcher */}
            <div className="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-0.5">
              <button 
                onClick={() => setVue('tableau')}
                className={`px-2.5 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition flex items-center gap-1 ${
                  vue === 'tableau' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'
                }`}
              >
                <List className="w-3.5 h-3.5" />
                <span className="hidden sm:inline">Table</span>
              </button>
              <button 
                onClick={() => setVue('grille')}
                className={`px-2.5 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition flex items-center gap-1 ${
                  vue === 'grille' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'
                }`}
              >
                <LayoutGrid className="w-3.5 h-3.5" />
                <span className="hidden sm:inline">Grid</span>
              </button>
            </div>

            <button 
              onClick={() => setModaleAjoutOuverte(true)}
              className="bg-[#D4201A] hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider px-3.5 py-2 rounded shadow-sm transition flex items-center gap-1.5"
            >
              <Plus className="w-4 h-4" />
              <span>Add Video Embed</span>
            </button>
          </div>
        </div>

        {/* Filter Pills & Sorting */}
        <div className="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
          <div className="flex flex-wrap items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
            {(['toutes', 'youtube', 'vimeo', 'autre'] as const).map(p => (
              <button
                key={p}
                onClick={() => { setPlateforme(p); setPage(1); }}
                className={`px-3 py-1.5 rounded-lg border transition ${
                  plateforme === p 
                    ? 'bg-[#14151C] text-white border-[#14151C] shadow-sm' 
                    : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100'
                }`}
              >
                {p === 'toutes' ? 'All' : p === 'autre' ? 'Other Iframe' : p}
              </button>
            ))}
          </div>

          <div className="flex flex-wrap items-center gap-3 text-xs">
            <div className="flex items-center gap-1">
              <span className="text-gray-400 font-bold uppercase tracking-wider">Status:</span>
              <select 
                value={statut}
                onChange={e => { setStatut(e.target.value as any); setPage(1); }}
                className="bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-lg px-2 py-1.5 font-medium focus:outline-none focus:border-[#D4201A]"
              >
                <option value="tous">All Statuses</option>
                <option value="actif">Active on Homepage</option>
                <option value="inactif">Hidden from Homepage</option>
              </select>
            </div>

            <div className="flex items-center gap-1">
              <span className="text-gray-400 font-bold uppercase tracking-wider">Sort:</span>
              <select 
                value={tri}
                onChange={e => { setTri(e.target.value as any); setPage(1); }}
                className="bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-lg px-2 py-1.5 font-medium focus:outline-none focus:border-[#D4201A]"
              >
                <option value="recent">Newest First</option>
                <option value="ancien">Oldest First</option>
                <option value="titre_az">Title (A-Z)</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      {/* 3. Table View */}
      {vue === 'tableau' && (
        <div className="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-gray-700">
              <thead className="bg-[#14151C] text-white uppercase text-xs font-heading font-black tracking-wider">
                <tr>
                  <th scope="col" className="py-3.5 px-4 w-10 text-center">
                    <input 
                      type="checkbox"
                      checked={selectionIds.size === videosPaginees.length && videosPaginees.length > 0}
                      onChange={e => {
                        if (e.target.checked) setSelectionIds(new Set(videosPaginees.map(v => v.id)));
                        else setSelectionIds(new Set());
                      }}
                      className="rounded border-gray-400 text-[#D4201A] focus:ring-[#D4201A] cursor-pointer"
                    />
                  </th>
                  <th scope="col" className="py-3.5 px-4">Video Asset & Synopsis</th>
                  <th scope="col" className="py-3.5 px-4 w-32">Platform</th>
                  <th scope="col" className="py-3.5 px-4 w-44">Source Channel</th>
                  <th scope="col" className="py-3.5 px-4 w-28 text-center">Homepage</th>
                  <th scope="col" className="py-3.5 px-4 w-40 text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {videosPaginees.map(v => (
                  <tr key={v.id} className="hover:bg-gray-50/80 transition">
                    <td className="py-3 px-4 text-center">
                      <input 
                        type="checkbox"
                        checked={selectionIds.has(v.id)}
                        onChange={e => {
                          const next = new Set(selectionIds);
                          if (e.target.checked) next.add(v.id);
                          else next.delete(v.id);
                          setSelectionIds(next);
                        }}
                        className="rounded border-gray-300 text-[#D4201A] focus:ring-[#D4201A] cursor-pointer"
                      />
                    </td>
                    <td className="py-3 px-4">
                      <div className="flex items-start gap-3">
                        <div 
                          onClick={() => setModaleApercu(v)}
                          className="relative w-20 h-12 bg-gray-900 rounded overflow-hidden flex-shrink-0 group cursor-pointer"
                        >
                          <img src={v.url_miniature || ''} alt={v.titre} className="w-full h-full object-cover group-hover:scale-105 transition" />
                          <div className="absolute inset-0 bg-black/40 flex items-center justify-center opacity-70 group-hover:opacity-100 transition">
                            <div className="w-6 h-6 rounded-full bg-[#D4201A] text-white flex items-center justify-center shadow">
                              <Play className="w-3 h-3 ml-0.5 fill-white" />
                            </div>
                          </div>
                        </div>
                        <div className="min-w-0 flex-1">
                          <button 
                            onClick={() => setModaleApercu(v)}
                            className="text-left font-bold text-gray-900 hover:text-[#D4201A] transition text-sm leading-snug line-clamp-1"
                          >
                            {v.titre}
                          </button>
                          <div className="flex items-center gap-2 mt-1 text-xs text-gray-500">
                            <span>{v.date_creation}</span>
                            <span>&bull;</span>
                            <span className="truncate max-w-xs text-gray-400">{v.description}</span>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td className="py-3 px-4">
                      <span className="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700">
                        {v.plateforme}
                      </span>
                      <span className="block text-xs font-mono text-gray-500 mt-1">{v.duree}</span>
                    </td>
                    <td className="py-3 px-4 text-xs font-medium text-gray-700">
                      {v.source}
                    </td>
                    <td className="py-3 px-4 text-center">
                      <button 
                        onClick={() => toggleStatut(v.id)}
                        className={`relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none ${
                          v.est_actif ? 'bg-[#D4201A]' : 'bg-gray-300'
                        }`}
                      >
                        <span className={`pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200 ${
                          v.est_actif ? 'translate-x-4' : 'translate-x-0'
                        }`} />
                      </button>
                    </td>
                    <td className="py-3 px-4 text-right">
                      <div className="flex items-center justify-end gap-1.5">
                        <button 
                          onClick={() => copierCodeEmbed(`<iframe src="${v.url_iframe}" title="${v.titre}"></iframe>`)}
                          className="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition"
                          title="Copy embed snippet"
                        >
                          <Copy className="w-4 h-4" />
                        </button>
                        <button 
                          onClick={() => setModaleApercu(v)}
                          className="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition"
                          title="Preview video"
                        >
                          <Eye className="w-4 h-4" />
                        </button>
                        <button 
                          onClick={() => setModaleSuppr({ type: 'unitaire', ids: [v.id], titre: v.titre })}
                          className="p-1.5 text-gray-400 hover:text-[#D4201A] hover:bg-red-50 rounded transition"
                          title="Delete video"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
                {videosPaginees.length === 0 && (
                  <tr>
                    <td colSpan={6} className="py-12 text-center text-gray-500 font-bold">
                      No video embeds found matching criteria.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* 4. Grid View */}
      {vue === 'grille' && (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          {videosPaginees.map(v => (
            <div key={v.id} className="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm flex flex-col justify-between">
              <div>
                <div 
                  onClick={() => setModaleApercu(v)}
                  className="relative aspect-video bg-gray-900 overflow-hidden cursor-pointer group"
                >
                  <img src={v.url_miniature || ''} alt={v.titre} className="w-full h-full object-cover group-hover:scale-105 transition" />
                  <div className="absolute inset-0 bg-black/30 group-hover:bg-black/50 flex items-center justify-center transition">
                    <div className="w-10 h-10 rounded-full bg-[#D4201A] text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition">
                      <Play className="w-5 h-5 ml-0.5 fill-white" />
                    </div>
                  </div>
                  <span className="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] font-mono font-bold px-1.5 py-0.5 rounded">
                    {v.duree}
                  </span>
                  <span className="absolute top-2 left-2 bg-black/70 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">
                    {v.plateforme}
                  </span>
                </div>

                <div className="p-4 space-y-2">
                  <span className="text-xs font-bold uppercase tracking-wider text-[#D4201A] block">{v.source}</span>
                  <h3 
                    onClick={() => setModaleApercu(v)}
                    className="font-heading font-black text-gray-900 text-sm leading-snug line-clamp-2 hover:text-[#D4201A] cursor-pointer transition"
                  >
                    {v.titre}
                  </h3>
                  <p className="text-xs text-gray-500 line-clamp-2">{v.description}</p>
                </div>
              </div>

              <div className="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <div className="flex items-center gap-2">
                  <button 
                    onClick={() => toggleStatut(v.id)}
                    className={`relative inline-flex h-4 w-7 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none ${
                      v.est_actif ? 'bg-[#D4201A]' : 'bg-gray-300'
                    }`}
                  >
                    <span className={`pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow transition duration-200 ${
                      v.est_actif ? 'translate-x-3' : 'translate-x-0'
                    }`} />
                  </button>
                  <span className="text-[11px] font-bold uppercase text-gray-500">{v.est_actif ? 'Active' : 'Hidden'}</span>
                </div>

                <div className="flex items-center gap-1">
                  <button 
                    onClick={() => copierCodeEmbed(`<iframe src="${v.url_iframe}" title="${v.titre}"></iframe>`)}
                    className="p-1 text-gray-500 hover:text-gray-900 rounded transition"
                  >
                    <Copy className="w-4 h-4" />
                  </button>
                  <button 
                    onClick={() => setModaleSuppr({ type: 'unitaire', ids: [v.id], titre: v.titre })}
                    className="p-1 text-gray-400 hover:text-[#D4201A] rounded transition"
                  >
                    <Trash2 className="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          ))}
          {videosPaginees.length === 0 && (
            <div className="col-span-full py-12 text-center text-gray-500 font-bold bg-white rounded-xl border border-gray-100">
              No video embeds found.
            </div>
          )}
        </div>
      )}

      {/* 5. Pagination */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-gray-200 text-xs">
        <div className="text-gray-500">
          Showing <span className="font-bold text-gray-900">{(page - 1) * parPage + (videosFiltrees.length > 0 ? 1 : 0)}</span> to <span className="font-bold text-gray-900">{Math.min(page * parPage, videosFiltrees.length)}</span> of <span className="font-bold text-gray-900">{videosFiltrees.length}</span> embedded videos
        </div>

        <div className="flex items-center gap-1">
          <button 
            disabled={page <= 1}
            onClick={() => setPage(p => Math.max(1, p - 1))}
            className="px-2.5 py-1.5 rounded border border-gray-300 font-bold uppercase disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 flex items-center gap-1"
          >
            <ChevronLeft className="w-3.5 h-3.5" />
            <span>Prev</span>
          </button>

          {Array.from({ length: totalPages }, (_, i) => i + 1).map(p => (
            <button
              key={p}
              onClick={() => setPage(p)}
              className={`px-3 py-1.5 rounded border text-xs font-bold uppercase transition ${
                page === p ? 'bg-[#14151C] text-white border-[#14151C] shadow-sm' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
              }`}
            >
              {p}
            </button>
          ))}

          <button 
            disabled={page >= totalPages}
            onClick={() => setPage(p => Math.min(totalPages, p + 1))}
            className="px-2.5 py-1.5 rounded border border-gray-300 font-bold uppercase disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 flex items-center gap-1"
          >
            <span>Next</span>
            <ChevronRight className="w-3.5 h-3.5" />
          </button>
        </div>
      </div>

      {/* Preview Modal */}
      {modaleApercu && (
        <div className="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm flex items-center justify-center p-4">
          <div className="relative w-full max-w-3xl bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <div className="bg-[#14151C] text-white px-6 py-4 flex items-center justify-between">
              <div className="flex items-center gap-2 truncate pr-4">
                <span className="w-2.5 h-2.5 rounded-full bg-[#D4201A] flex-shrink-0"></span>
                <h3 className="font-heading font-black text-sm uppercase tracking-wider truncate">{modaleApercu.titre}</h3>
              </div>
              <button onClick={() => setModaleApercu(null)} className="text-gray-400 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <div className="relative aspect-video bg-black">
              <iframe 
                src={modaleApercu.url_iframe} 
                title={modaleApercu.titre}
                className="w-full h-full border-0" 
                allowFullScreen 
              />
            </div>

            <div className="p-6 bg-white space-y-4">
              <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-gray-100">
                <div>
                  <span className="text-xs font-bold uppercase tracking-wider text-[#D4201A]">{modaleApercu.source}</span>
                  <span className="text-xs font-mono text-gray-500 ml-2">{modaleApercu.duree}</span>
                </div>
                <button 
                  onClick={() => copierCodeEmbed(`<iframe src="${modaleApercu.url_iframe}" title="${modaleApercu.titre}"></iframe>`)}
                  className="px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-gray-700 bg-gray-100 hover:bg-gray-200 rounded transition flex items-center gap-1.5"
                >
                  <Copy className="w-4 h-4" />
                  <span>Copy Embed Code</span>
                </button>
              </div>
              <div className="text-xs text-gray-600">
                <span className="font-bold uppercase tracking-wider text-gray-400 block text-[11px] mb-1">Editorial Synopsis</span>
                <p className="leading-relaxed">{modaleApercu.description || 'No synopsis provided.'}</p>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Add Video Modal */}
      {modaleAjoutOuverte && (
        <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
          <div className="relative w-full max-w-xl bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden">
            <div className="bg-[#14151C] text-white px-6 py-4 flex items-center justify-between">
              <div className="flex items-center gap-2">
                <span className="w-2.5 h-2.5 rounded-full bg-[#D4201A]"></span>
                <h3 className="font-heading font-black text-base uppercase tracking-wider">Add Embedded Video Asset</h3>
              </div>
              <button onClick={() => setModaleAjoutOuverte(false)} className="text-gray-400 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={ajouterVideo} className="p-6 space-y-4">
              <div>
                <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Video Title *</label>
                <input 
                  type="text" 
                  name="titre" 
                  required 
                  placeholder="e.g. Automated Container Terminals: Maasvlakte II Operations"
                  className="w-full text-sm px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#D4201A]"
                />
              </div>

              <div>
                <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Embed URL or Video Link *</label>
                <input 
                  type="text" 
                  name="url_embed" 
                  required 
                  placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/..."
                  className="w-full text-sm px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#D4201A]"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Source / Channel</label>
                  <input 
                    type="text" 
                    name="source" 
                    placeholder="e.g. Port of Rotterdam"
                    className="w-full text-sm px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#D4201A]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Duration</label>
                  <input 
                    type="text" 
                    name="duree" 
                    placeholder="e.g. 06:45"
                    className="w-full text-sm px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#D4201A]"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Synopsis / Context Notes</label>
                <textarea 
                  name="description" 
                  rows={3}
                  placeholder="Brief editorial context on logistics operations shown..."
                  className="w-full text-sm px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#D4201A]"
                />
              </div>

              <div className="flex items-center gap-2">
                <input type="checkbox" id="modal-actif" name="est_actif" defaultChecked className="rounded border-gray-300 text-[#D4201A] focus:ring-[#D4201A]" />
                <label htmlFor="modal-actif" className="text-xs font-bold uppercase tracking-wider text-gray-700 cursor-pointer">
                  Enable display immediately on homepage media sidebar
                </label>
              </div>

              <div className="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                <button 
                  type="button" 
                  onClick={() => setModaleAjoutOuverte(false)}
                  className="px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-700 bg-gray-100 hover:bg-gray-200 rounded transition"
                >
                  Cancel
                </button>
                <button 
                  type="submit" 
                  className="px-5 py-2 text-xs font-bold uppercase tracking-wider text-white bg-[#D4201A] hover:bg-red-700 rounded shadow-sm transition"
                >
                  Publish Video Embed
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Delete Confirmation Modal */}
      {modaleSuppr && (
        <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
          <div className="relative w-full max-w-md bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden text-center p-6 space-y-4">
            <div className="w-12 h-12 rounded-full bg-red-100 text-[#D4201A] mx-auto flex items-center justify-center">
              <Trash2 className="w-6 h-6" />
            </div>
            <div>
              <h3 className="font-heading font-black text-lg text-gray-900">
                {modaleSuppr.type === 'lot' ? 'Bulk Delete Video Embeds' : 'Remove Video Embed'}
              </h3>
              <p className="text-xs text-gray-500 mt-2">
                {modaleSuppr.type === 'lot' 
                  ? `Are you sure you want to permanently delete ${modaleSuppr.ids.length} selected video embeds?`
                  : `Are you sure you want to remove "${modaleSuppr.titre}" from the newsroom repository?`}
              </p>
            </div>
            <div className="flex items-center justify-center gap-3 pt-2">
              <button 
                onClick={() => setModaleSuppr(null)}
                className="px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-700 bg-gray-100 hover:bg-gray-200 rounded transition"
              >
                Cancel
              </button>
              <button 
                onClick={confirmerSuppression}
                className="px-5 py-2 text-xs font-bold uppercase tracking-wider text-white bg-[#D4201A] hover:bg-red-700 rounded shadow-sm transition"
              >
                Delete Permanently
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Toast Notification */}
      {toastMessage && (
        <div className="fixed bottom-5 right-5 z-50 px-4 py-3 rounded-lg shadow-lg text-xs font-bold uppercase tracking-wider flex items-center gap-2 bg-[#14151C] text-white border-l-4 border-[#D4201A] animate-fade-in">
          <span>{toastMessage}</span>
        </div>
      )}
    </div>
  );
}
