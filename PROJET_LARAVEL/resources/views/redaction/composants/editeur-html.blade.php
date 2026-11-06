{{-- Advanced HTML & Custom Scripting Editor for CARGO Journalism --}}
<div class="editeur-html-container" style="border: 1px solid #D1D5DB; background: #fff; border-radius: 4px; overflow: hidden;">
    {{-- Top Toolbar --}}
    <div style="background: #14151C; color: #fff; padding: 10px 14px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 8px; border-bottom: 2px solid #D4201A;">
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 6px;">
            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #FFC93C; margin-right: 6px; letter-spacing: 0.05em;">HTML Tools:</span>
            <button type="button" onclick="insererBalise('h2')" style="background:#2B2E3A; color:#fff; border:1px solid #4B505C; padding:4px 8px; font-size:12px; font-weight:bold; border-radius:3px; cursor:pointer;">H2</button>
            <button type="button" onclick="insererBalise('h3')" style="background:#2B2E3A; color:#fff; border:1px solid #4B505C; padding:4px 8px; font-size:12px; font-weight:bold; border-radius:3px; cursor:pointer;">H3</button>
            <button type="button" onclick="insererBalise('p')" style="background:#2B2E3A; color:#fff; border:1px solid #4B505C; padding:4px 8px; font-size:12px; font-weight:bold; border-radius:3px; cursor:pointer;">&lt;p&gt;</button>
            <button type="button" onclick="insererTableau()" style="background:#2B2E3A; color:#fff; border:1px solid #4B505C; padding:4px 10px; font-size:12px; font-weight:bold; border-radius:3px; cursor:pointer;">Table</button>
            <button type="button" onclick="insererImage()" style="background:#2B2E3A; color:#fff; border:1px solid #4B505C; padding:4px 10px; font-size:12px; font-weight:bold; border-radius:3px; cursor:pointer;">Image</button>
            <button type="button" onclick="insererCss()" style="background:#2B2E3A; color:#FFC93C; border:1px solid #4B505C; padding:4px 10px; font-size:12px; font-weight:bold; border-radius:3px; cursor:pointer;">&lt;style&gt;</button>
            <button type="button" onclick="insererJs()" style="background:#2B2E3A; color:#38BDF8; border:1px solid #4B505C; padding:4px 10px; font-size:12px; font-weight:bold; border-radius:3px; cursor:pointer;">&lt;script&gt;</button>
            <button type="button" onclick="insererCitation()" style="background:#2B2E3A; color:#fff; border:1px solid #4B505C; padding:4px 8px; font-size:12px; font-weight:bold; border-radius:3px; cursor:pointer;">Quote</button>
            <button type="button" onclick="insererKpi()" style="background:#2B2E3A; color:#34D399; border:1px solid #4B505C; padding:4px 8px; font-size:12px; font-weight:bold; border-radius:3px; cursor:pointer;">KPI Grid</button>
        </div>

        {{-- View Mode Toggle --}}
        <div style="display: flex; gap: 4px;">
            <button type="button" id="btn-mode-code" onclick="basculerVueEditeur('code')" style="background:#D4201A; color:#fff; font-size:12px; font-weight:700; padding:5px 12px; border-radius:3px; border:none; cursor:pointer;">HTML Code</button>
            <button type="button" id="btn-mode-apercu" onclick="basculerVueEditeur('apercu')" style="background:#2B2E3A; color:#C9CCD6; font-size:12px; font-weight:700; padding:5px 12px; border-radius:3px; border:none; cursor:pointer;">Live Preview</button>
        </div>
    </div>

    {{-- Editor Area (HTML Source) --}}
    <div id="zone-editeur-code" style="display: block;">
        <textarea id="champ-contenu-html" name="contenu" required rows="16" placeholder="Enter clean HTML, custom <style> rules, <table> structures, <img> figures, and <script> interactions..." style="width:100%; box-sizing:border-box; padding:16px; font-family:'Courier New', monospace; font-size:14px; line-height:1.6; border:none; outline:none; background:#FAF9F6; color:#14151C; min-height:360px; resize:vertical;">{{ $contenu ?? '' }}</textarea>
    </div>

    {{-- Live Preview Area --}}
    <div id="zone-editeur-apercu" style="display: none; padding: 24px; background: #fff; min-height: 360px; box-sizing: border-box; overflow-y: auto;">
        <div id="rendu-apercu-html" style="font-family:'IBM Plex Sans', sans-serif; font-size:17px; line-height:1.8; color:#1F2128;"></div>
    </div>

    {{-- Helper Footer Bar --}}
    <div style="background: #F3F4F6; padding: 8px 14px; font-size: 12px; color: #4B505C; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #E5E7EB;">
        <span>Full HTML5 tags, CSS stylesheets, tables, and JavaScript interactions enabled.</span>
        <span id="compteur-mots-html">0 characters</span>
    </div>
</div>

<script>
    function getEditeur() { return document.getElementById('champ-contenu-html'); }
    function insererTexte(avant, apres, defaut) {
        const t = getEditeur(), s = t.selectionStart, e = t.selectionEnd;
        const val = t.value.substring(s, e) || defaut;
        t.value = t.value.substring(0, s) + avant + val + apres + t.value.substring(e);
        t.focus();
        t.selectionStart = s + avant.length;
        t.selectionEnd = s + avant.length + val.length;
        mettreAJourCompteur();
    }
    function insererBalise(nom) { insererTexte('<' + nom + '>', '</' + nom + '>\n', 'Sample text here'); }
    function insererTableau() {
        const table = `\n<div style="overflow-x:auto; margin:20px 0;">
  <table style="width:100%; border-collapse:collapse; font-size:14px; background:#fff; border:1px solid #E2E5EB;">
    <thead>
      <tr style="background:#14151C; color:#fff;">
        <th style="padding:10px 14px; text-align:left;">Route / Corridor</th>
        <th style="padding:10px 14px; text-align:left;">Rate (USD/FEU)</th>
        <th style="padding:10px 14px; text-align:left;">Delta</th>
        <th style="padding:10px 14px; text-align:left;">Status</th>
      </tr>
    </thead>
    <tbody>
      <tr style="border-bottom:1px solid #E2E5EB;">
        <td style="padding:10px 14px; font-weight:600;">Shanghai &rarr; Rotterdam</td>
        <td style="padding:10px 14px; font-family:monospace; font-weight:bold;">$4,650</td>
        <td style="padding:10px 14px; color:#16A34A; font-weight:bold;">+4.8%</td>
        <td style="padding:10px 14px;"><span style="background:#DCFCE7; color:#166534; padding:2px 8px; font-weight:bold; font-size:11px;">Active</span></td>
      </tr>
      <tr style="border-bottom:1px solid #E2E5EB; background:#F9FAFB;">
        <td style="padding:10px 14px; font-weight:600;">Singapore &rarr; Genoa</td>
        <td style="padding:10px 14px; font-family:monospace; font-weight:bold;">$3,890</td>
        <td style="padding:10px 14px; color:#DC2626; font-weight:bold;">-1.5%</td>
        <td style="padding:10px 14px;"><span style="background:#FEF3C7; color:#92400E; padding:2px 8px; font-weight:bold; font-size:11px;">Congested</span></td>
      </tr>
    </tbody>
  </table>
</div>\n`;
        insererTexte(table, '', '');
    }
    function insererImage() {
        const url = prompt('Image URL:', 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=1200&q=80');
        if (!url) return;
        const caption = prompt('Caption:', 'Container terminal logistics operations.');
        const img = `\n<figure style="margin:24px 0; text-align:center;">
  <img src="${url}" alt="${caption}" style="width:100%; height:auto; display:block; border:1px solid #D5D8E0;">
  <figcaption style="font-size:13px; color:#6B7280; margin-top:8px; text-align:left; border-bottom:1px dashed #D1D5DB; padding-bottom:6px;">${caption}</figcaption>
</figure>\n`;
        insererTexte(img, '', '');
    }
    function insererCss() {
        const css = `\n<style>
  .kpi-box-custom { background: #FAF7F2; border-left: 5px solid #D4201A; padding: 18px; margin: 20px 0; }
  .kpi-box-custom h4 { margin: 0 0 6px; color: #14151C; font-size: 18px; font-weight: 800; }
  .kpi-box-custom p { margin: 0; font-size: 15px; color: #374151; }
</style>
<div class="kpi-box-custom">
  <h4>Key Strategic Takeaway</h4>
  <p>Supply chain leaders must diversify transshipment routes ahead of seasonal demand spikes.</p>
</div>\n`;
        insererTexte(css, '', '');
    }
    function insererJs() {
        const js = `\n<div id="interactive-tool" style="background:#14151C; color:#fff; padding:20px; margin:20px 0;">
  <div style="font-weight:800; font-size:15px; color:#FFC93C; margin-bottom:6px;">Operational Transit Duration</div>
  <p style="font-size:13px; color:#C9CCD6; margin-bottom:12px;">Select route to simulate average transit days:</p>
  <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
    <select id="route-input" style="height:36px; padding:0 10px; background:#fff; color:#14151C; font-weight:bold; border:none;">
      <option value="26">Asia to Europe via Cape: 26 Days</option>
      <option value="18">Asia to Europe via Suez: 18 Days</option>
      <option value="12">Transpacific Coast: 12 Days</option>
    </select>
    <button type="button" id="btn-run-calc" style="background:#D4201A; color:#fff; font-weight:700; height:36px; padding:0 14px; border:none; cursor:pointer;">Simulate</button>
    <span id="output-calc" style="font-size:14px; font-weight:bold; color:#38BDF8;"></span>
  </div>
</div>
<script>
  (function() {
    const b = document.getElementById('btn-run-calc'), s = document.getElementById('route-input'), o = document.getElementById('output-calc');
    if (b && s && o) {
      b.addEventListener('click', function() { o.textContent = 'Transit: ' + s.value + ' Days.'; });
    }
  })();
<\/script>\n`;
        insererTexte(js, '', '');
    }
    function insererCitation() {
        insererTexte('\n<blockquote style="border-left:4px solid #D4201A; padding:12px 18px; margin:20px 0; background:#F4F1EA; font-style:italic; font-size:18px; color:#1F2128;">\n  "', '"\n</blockquote>\n', 'Global freight resilience depends on real-time port visibility.');
    }
    function insererKpi() {
        const kpi = `\n<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px; margin:20px 0;">
  <div style="background:#fff; border:1px solid #E2E5EB; border-top:4px solid #D4201A; padding:16px;">
    <div style="font-size:28px; font-weight:900; color:#14151C;">24.2M</div>
    <div style="font-size:11px; font-weight:700; color:#6B7280; text-transform:uppercase;">Annual TEU Volume</div>
  </div>
  <div style="background:#fff; border:1px solid #E2E5EB; border-top:4px solid #FFC93C; padding:16px;">
    <div style="font-size:28px; font-weight:900; color:#14151C;">-8.4%</div>
    <div style="font-size:11px; font-weight:700; color:#6B7280; text-transform:uppercase;">Average Berth Wait Time</div>
  </div>
  <div style="background:#fff; border:1px solid #E2E5EB; border-top:4px solid #16A34A; padding:16px;">
    <div style="font-size:28px; font-weight:900; color:#14151C;">99.1%</div>
    <div style="font-size:11px; font-weight:700; color:#6B7280; text-transform:uppercase;">Digital Gate Compliance</div>
  </div>
</div>\n`;
        insererTexte(kpi, '', '');
    }
    function basculerVueEditeur(mode) {
        const c = document.getElementById('zone-editeur-code'), a = document.getElementById('zone-editeur-apercu');
        const bc = document.getElementById('btn-mode-code'), ba = document.getElementById('btn-mode-apercu'), r = document.getElementById('rendu-apercu-html');
        if (mode === 'apercu') {
            c.style.display = 'none'; a.style.display = 'block';
            bc.style.background = '#2B2E3A'; bc.style.color = '#C9CCD6';
            ba.style.background = '#D4201A'; ba.style.color = '#fff';
            r.innerHTML = getEditeur().value || '<p style="color:#9CA3AF; font-style:italic;">No content to preview yet.</p>';
            Array.from(r.querySelectorAll('script')).forEach(s => {
                const ns = document.createElement('script');
                Array.from(s.attributes).forEach(attr => ns.setAttribute(attr.name, attr.value));
                ns.appendChild(document.createTextNode(s.innerHTML));
                s.parentNode.replaceChild(ns, s);
            });
        } else {
            c.style.display = 'block'; a.style.display = 'none';
            bc.style.background = '#D4201A'; bc.style.color = '#fff';
            ba.style.background = '#2B2E3A'; ba.style.color = '#C9CCD6';
        }
    }
    function mettreAJourCompteur() {
        const val = getEditeur().value, comp = document.getElementById('compteur-mots-html');
        if (comp) comp.textContent = val.length + ' characters (' + val.split(/\s+/).filter(Boolean).length + ' words)';
    }
    document.addEventListener('DOMContentLoaded', function() {
        const ed = getEditeur();
        if (ed) { ed.addEventListener('input', mettreAJourCompteur); mettreAJourCompteur(); }
    });
</script>
