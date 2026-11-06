{{-- WYSIWYG & Smart Clipboard HTML Editor for CARGO Journalism --}}
<div class="editeur-article-modulaire" style="border:1px solid #D1D5DB; background:#fff; border-radius:4px; overflow:hidden;">
    {{-- Top Switcher & Modes --}}
    <div style="background:#14151C; color:#fff; padding:8px 12px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:8px; border-bottom:2px solid #D4201A;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:11px; font-weight:800; text-transform:uppercase; color:#FFC93C; letter-spacing:0.06em;">Article Editor</span>
            <span style="font-size:11px; color:#9CA3AF; background:#2B2E3A; padding:2px 8px; border-radius:3px;">Beginner Friendly · Auto Heading Detection</span>
        </div>
        <div style="display:flex; gap:4px;">
            <button type="button" id="btn-mode-visuel" onclick="basculerModeEditeur('visuel')" style="background:#D4201A; color:#fff; font-size:12px; font-weight:700; padding:5px 12px; border-radius:3px; border:none; cursor:pointer;">Visual Mode (Easy)</button>
            <button type="button" id="btn-mode-code" onclick="basculerModeEditeur('code')" style="background:#2B2E3A; color:#C9CCD6; font-size:12px; font-weight:700; padding:5px 12px; border-radius:3px; border:none; cursor:pointer;">HTML Code (Expert)</button>
        </div>
    </div>

    {{-- Beginner WYSIWYG Visual Toolbar --}}
    <div id="barre-outils-visuelle" style="background:#F9FAFB; border-bottom:1px solid #E5E7EB; padding:8px 12px; display:flex; flex-wrap:wrap; align-items:center; gap:6px;">
        {{-- Format Selector (Headings H1, H2, H3, Paragraph) --}}
        <select id="selecteur-format" onchange="appliquerFormat(this.value)" style="height:32px; font-size:12px; font-weight:600; border:1px solid #D1D5DB; border-radius:3px; padding:0 8px; background:#fff; color:#14151C; cursor:pointer;">
            <option value="p">Paragraph (Normal)</option>
            <option value="h1">Heading 1 (Main Title)</option>
            <option value="h2">Heading 2 (Section Subtitle)</option>
            <option value="h3">Heading 3 (Subsection)</option>
            <option value="blockquote">Blockquote (Quote)</option>
        </select>

        <span style="width:1px; height:20px; background:#D1D5DB; margin:0 4px;"></span>

        {{-- Basic Styles --}}
        <button type="button" onclick="executerCommande('bold')" title="Bold" style="width:30px; height:30px; border:1px solid #D1D5DB; background:#fff; font-weight:bold; border-radius:3px; cursor:pointer;">B</button>
        <button type="button" onclick="executerCommande('italic')" title="Italic" style="width:30px; height:30px; border:1px solid #D1D5DB; background:#fff; font-style:italic; border-radius:3px; cursor:pointer;">I</button>
        <button type="button" onclick="executerCommande('underline')" title="Underline" style="width:30px; height:30px; border:1px solid #D1D5DB; background:#fff; text-decoration:underline; border-radius:3px; cursor:pointer;">U</button>

        <span style="width:1px; height:20px; background:#D1D5DB; margin:0 4px;"></span>

        {{-- Lists --}}
        <button type="button" onclick="executerCommande('insertUnorderedList')" title="Bullet List" style="padding:4px 8px; height:30px; border:1px solid #D1D5DB; background:#fff; font-size:12px; font-weight:600; border-radius:3px; cursor:pointer;">• List</button>
        <button type="button" onclick="executerCommande('insertOrderedList')" title="Numbered List" style="padding:4px 8px; height:30px; border:1px solid #D1D5DB; background:#fff; font-size:12px; font-weight:600; border-radius:3px; cursor:pointer;">1. List</button>

        <span style="width:1px; height:20px; background:#D1D5DB; margin:0 4px;"></span>

        {{-- Insert Links, Tables, Images --}}
        <button type="button" onclick="insererLienVisuel()" style="padding:4px 8px; height:30px; border:1px solid #D1D5DB; background:#fff; font-size:12px; font-weight:600; border-radius:3px; cursor:pointer;">Link</button>
        <button type="button" onclick="insererTableauVisuel()" style="padding:4px 8px; height:30px; border:1px solid #D1D5DB; background:#fff; font-size:12px; font-weight:600; border-radius:3px; cursor:pointer;">Table</button>
        <button type="button" onclick="insererKpiVisuel()" style="padding:4px 8px; height:30px; border:1px solid #D1D5DB; background:#fff; font-size:12px; font-weight:600; border-radius:3px; color:#047857; cursor:pointer;">KPI Box</button>

        <span style="width:1px; height:20px; background:#D1D5DB; margin:0 4px;"></span>

        {{-- Smart Clipboard Paste Sanitizer Button --}}
        <button type="button" onclick="nettoyerCollerPressePapier()" title="Paste & Clean Headings (Auto H1/H2 preserved without white text)" style="padding:4px 10px; height:30px; border:1px solid #D4201A; background:#FFF1F1; font-size:12px; font-weight:700; border-radius:3px; color:#D4201A; cursor:pointer; display:flex; align-items:center; gap:4px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
            Clean Paste (H1/H2)
        </button>
    </div>

    {{-- Notification Toast Bar for Paste Action --}}
    <div id="toast-coller" style="display:none; background:#ECFDF5; border-bottom:1px solid #A7F3D0; color:#065F46; padding:6px 14px; font-size:12px; font-weight:600;">
        Clipboard content sanitized: Headings (H1, H2, H3) and structures preserved without corrupt inline styles!
    </div>

    {{-- Visual WYSIWYG Editable Zone (Beginner Mode) --}}
    <div id="zone-editeur-visuel" contenteditable="true" style="min-height:360px; padding:20px 24px; font-family:'IBM Plex Sans',Arial,sans-serif; font-size:17px; line-height:1.75; color:#1F2128; background:#fff; outline:none; box-sizing:border-box;">{!! $contenu ?? '<p>Write your article here or paste clipboard content directly (H1, H2, text, lists)...</p>' !!}</div>

    {{-- Raw Code Zone (Hidden by Default, Synced on Switch) --}}
    <div id="zone-editeur-code" style="display:none;">
        <textarea id="champ-contenu-html" name="contenu" required rows="16" style="width:100%; box-sizing:border-box; padding:16px; font-family:'Courier New', monospace; font-size:14px; line-height:1.6; border:none; outline:none; background:#FAF9F6; color:#14151C; min-height:360px; resize:vertical;">{{ $contenu ?? '' }}</textarea>
    </div>

    {{-- Bottom Status Bar --}}
    <div style="background:#F3F4F6; padding:8px 14px; font-size:12px; color:#4B505C; display:flex; justify-content:space-between; align-items:center; border-top:1px solid #E5E7EB;">
        <span style="display:flex; align-items:center; gap:6px;">
            <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10B981;"></span>
            Automatic HTML sanitization active · Safe typography
        </span>
        <span id="compteur-mots-html">0 words</span>
    </div>
</div>

<script>
    function getZoneVisuelle() { return document.getElementById('zone-editeur-visuel'); }
    function getChampCode() { return document.getElementById('champ-contenu-html'); }

    function synchroniserVersCode() {
        const v = getZoneVisuelle(), c = getChampCode();
        if (v && c) {
            c.value = v.innerHTML;
            mettreAJourCompteur();
        }
    }

    function synchroniserVersVisuel() {
        const v = getZoneVisuelle(), c = getChampCode();
        if (v && c) {
            v.innerHTML = c.value;
            mettreAJourCompteur();
        }
    }

    function basculerModeEditeur(mode) {
        const zv = document.getElementById('zone-editeur-visuel');
        const zc = document.getElementById('zone-editeur-code');
        const bv = document.getElementById('btn-mode-visuel');
        const bc = document.getElementById('btn-mode-code');
        const bar = document.getElementById('barre-outils-visuelle');

        if (mode === 'code') {
            synchroniserVersCode();
            zv.style.display = 'none';
            zc.style.display = 'block';
            bar.style.display = 'none';
            bc.style.background = '#D4201A'; bc.style.color = '#fff';
            bv.style.background = '#2B2E3A'; bv.style.color = '#C9CCD6';
        } else {
            synchroniserVersVisuel();
            zc.style.display = 'none';
            zv.style.display = 'block';
            bar.style.display = 'flex';
            bv.style.background = '#D4201A'; bv.style.color = '#fff';
            bc.style.background = '#2B2E3A'; bc.style.color = '#C9CCD6';
        }
    }

    function executerCommande(cmd, val = null) {
        getZoneVisuelle().focus();
        document.execCommand(cmd, false, val);
        synchroniserVersCode();
    }

    function appliquerFormat(tag) {
        getZoneVisuelle().focus();
        if (tag === 'p') {
            document.execCommand('formatBlock', false, '<p>');
        } else if (tag.startsWith('h')) {
            document.execCommand('formatBlock', false, '<' + tag + '>');
        } else if (tag === 'blockquote') {
            document.execCommand('formatBlock', false, '<blockquote>');
        }
        synchroniserVersCode();
    }

    function insererLienVisuel() {
        const url = prompt('Enter URL (https://...):', 'https://');
        if (url && url !== 'https://') {
            executerCommande('createLink', url);
        }
    }

    function insererTableauVisuel() {
        const html = `\n<table style="width:100%; border-collapse:collapse; margin:16px 0; border:1px solid #E2E5EB;">
            <thead><tr style="background:#14151C; color:#fff;"><th style="padding:8px 12px; text-align:left;">Key Metric</th><th style="padding:8px 12px; text-align:left;">Current Value</th><th style="padding:8px 12px; text-align:left;">Status</th></tr></thead>
            <tbody><tr><td style="padding:8px 12px; border-bottom:1px solid #E2E5EB;">Port Transit Wait</td><td style="padding:8px 12px; border-bottom:1px solid #E2E5EB; font-weight:bold;">1.8 Days</td><td style="padding:8px 12px; border-bottom:1px solid #E2E5EB; color:#16A34A; font-weight:bold;">Optimal</td></tr></tbody>
        </table><p></p>`;
        document.execCommand('insertHTML', false, html);
        synchroniserVersCode();
    }

    function insererKpiVisuel() {
        const html = `\n<div style="background:#F9FAFB; border-left:4px solid #D4201A; padding:16px 20px; margin:18px 0;">
            <div style="font-size:12px; font-weight:800; text-transform:uppercase; color:#D4201A; letter-spacing:0.05em;">Key Operational Takeaway</div>
            <p style="margin:6px 0 0; font-size:16px; color:#14151C; font-weight:600;">Diversifying intermodal feeder links reduces critical supply chain disruptions by over 30%.</p>
        </div><p></p>`;
        document.execCommand('insertHTML', false, html);
        synchroniserVersCode();
    }

    // Smart Sanitizer for Clipboard / Paste: keeps H1, H2, H3, P, lists, strips white-on-white styles!
    function assainirHtmlPressePapier(brut) {
        // Nettoyer balises word / office
        let propre = brut.replace(/<!--[\s\S]*?-->/gi, '');
        propre = propre.replace(/<o:p>[\s\S]*?<\/o:p>/gi, '');
        // Détecter Markdown # Title et ## Subtitle si texte brut
        propre = propre.replace(/^#\s+(.+)$/gm, '<h1>$1</h1>');
        propre = propre.replace(/^##\s+(.+)$/gm, '<h2>$1</h2>');
        propre = propre.replace(/^###\s+(.+)$/gm, '<h3>$1</h3>');

        // Retirer les attributs style agressifs (notamment color:#fff ou background-color:#fff qui causent blanc sur blanc)
        propre = propre.replace(/style="[^"]*"/gi, function(match) {
            let s = match.replace(/color\s*:\s*(#fff|#ffffff|white|rgb\(255,\s*255,\s*255\))/gi, '');
            s = s.replace(/background(-color)?\s*:\s*[^;"]+;?/gi, '');
            s = s.replace(/font-family\s*:\s*[^;"]+;?/gi, '');
            return s.trim() === 'style=""' ? '' : s;
        });

        return propre;
    }

    function gererColler(e) {
        e.preventDefault();
        const clipboard = (e.clipboardData || window.clipboardData);
        let texteHtml = clipboard.getData('text/html');
        let texteBrut = clipboard.getData('text/plain');

        let contenuAssaini = '';
        if (texteHtml && texteHtml.trim().length > 0) {
            contenuAssaini = assainirHtmlPressePapier(texteHtml);
        } else if (texteBrut) {
            // Conversion paragraphes et titres markdown
            let htmlFormate = texteBrut
                .replace(/^#\s+(.+)$/gm, '<h1>$1</h1>')
                .replace(/^##\s+(.+)$/gm, '<h2>$1</h2>')
                .replace(/^###\s+(.+)$/gm, '<h3>$1</h3>')
                .split(/\n\s*\n/)
                .map(p => p.startsWith('<h') ? p : '<p>' + p.replace(/\n/g, '<br>') + '</p>')
                .join('');
            contenuAssaini = htmlFormate;
        }

        document.execCommand('insertHTML', false, contenuAssaini);
        synchroniserVersCode();
        afficherToastColler();
    }

    async function nettoyerCollerPressePapier() {
        try {
            if (navigator.clipboard && navigator.clipboard.readText) {
                const text = await navigator.clipboard.readText();
                if (text) {
                    let formatted = text
                        .replace(/^#\s+(.+)$/gm, '<h1>$1</h1>')
                        .replace(/^##\s+(.+)$/gm, '<h2>$1</h2>')
                        .replace(/^###\s+(.+)$/gm, '<h3>$1</h3>')
                        .split(/\n\s*\n/)
                        .map(p => p.startsWith('<h') ? p : '<p>' + p.replace(/\n/g, '<br>') + '</p>')
                        .join('');
                    getZoneVisuelle().focus();
                    document.execCommand('insertHTML', false, formatted);
                    synchroniserVersCode();
                    afficherToastColler();
                    return;
                }
            }
        } catch (err) {}
        alert('Paste shortcut: Press Ctrl+V or Cmd+V directly in the editor. Headings and structure will be automatically formatted and cleaned!');
    }

    function afficherToastColler() {
        const t = document.getElementById('toast-coller');
        if (t) {
            t.style.display = 'block';
            setTimeout(() => { t.style.display = 'none'; }, 4000);
        }
    }

    function mettreAJourCompteur() {
        const c = getChampCode();
        const comp = document.getElementById('compteur-mots-html');
        if (c && comp) {
            const txt = c.value.replace(/<[^>]*>/g, ' ').trim();
            const mots = txt ? txt.split(/\s+/).filter(Boolean).length : 0;
            comp.textContent = mots + ' words (' + c.value.length + ' chars)';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const zv = getZoneVisuelle();
        if (zv) {
            zv.addEventListener('input', synchroniserVersCode);
            zv.addEventListener('paste', gererColler);
        }
        const zc = getChampCode();
        if (zc) {
            zc.addEventListener('input', synchroniserVersVisuel);
            if (zc.value.trim().length > 0 && zv) {
                zv.innerHTML = zc.value;
            }
        }
        synchroniserVersCode();
    });
</script>
