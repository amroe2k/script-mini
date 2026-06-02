<?= $this->extend('layouts/public') ?>

<?= $this->section('head_extra') ?>
<style>
  .hero {
    text-align: center; padding: 40px 20px 20px;
    max-width: 800px; margin: 0 auto;
    display: flex; flex-direction: column; align-items: center;
  }
  .hero h1 {
    font-size: 2.3rem; font-weight: 800; line-height: 1.15;
    margin-bottom: 12px; letter-spacing: -1px;
  }
  .gradient-text {
    background: linear-gradient(135deg, #60a5fa 10%, #3b82f6 50%, #34d399 90%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
  }
  .hero p {
    font-size: 0.95rem; color: #94a3b8; line-height: 1.6; margin-bottom: 20px; max-width: 600px;
  }

  /* ── Search Section ── */
  .search-section { width: 100%; max-width: 580px; margin-bottom: 15px; z-index: 10; position: relative; transition: max-width 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
  .search-section:focus-within { max-width: 620px; }
  .search-glow {
    position: absolute; inset: -15px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.18) 0%, rgba(16, 185, 129, 0.12) 55%, transparent 75%);
    border-radius: 60px; filter: blur(25px); opacity: 0; pointer-events: none;
    transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    transform: scale(0.92); z-index: -1;
  }
  .search-section:focus-within .search-glow { opacity: 1; transform: scale(1.04); }
  .search-wrapper {
    position: relative; border-radius: 50px; padding: 1.5px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.25) 0%, rgba(255, 255, 255, 0.03) 50%, rgba(16, 185, 129, 0.2) 100%);
    box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.4), 0 2px 10px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.05);
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), background-image 0.4s ease;
  }
  .search-wrapper:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.4) 0%, rgba(255, 255, 255, 0.06) 50%, rgba(16, 185, 129, 0.3) 100%);
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5), 0 0 20px rgba(59, 130, 246, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.08);
  }
  .search-wrapper:focus-within {
    transform: translateY(-3px) scale(1.015);
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.85) 0%, rgba(99, 102, 241, 0.45) 50%, rgba(16, 185, 129, 0.65) 100%);
    box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.3), 0 0 30px rgba(59, 130, 246, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.12);
  }
  .search-inner {
    position: relative; border-radius: 50px; overflow: hidden;
    background: rgba(13, 19, 36, 0.82); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    transition: background-color 0.3s ease;
  }
  .search-icon { position: absolute; left: 24px; top: 50%; transform: translateY(-50%); color: #475569; pointer-events: none; transition: color 0.3s ease, transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); z-index: 1; }
  .search-wrapper:focus-within .search-icon { color: #60a5fa; transform: translateY(-50%) scale(1.12) rotate(8deg); }
  #searchInput {
    width: 100%; padding: 18px 60px; border-radius: 50px; border: none; background: transparent;
    color: var(--text-color, #f8fafc); font-size: 1.02rem; font-family: inherit; outline: none; transition: color 0.2s ease;
  }
  #searchInput::placeholder { color: #475569; transition: color 0.2s ease; }
  .search-wrapper:focus-within #searchInput::placeholder { color: #64748b; }
  .search-kbd {
    position: absolute; right: 20px; top: 50%; transform: translateY(-50%);
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
    border: 1px solid rgba(255, 255, 255, 0.12); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1);
    color: #94a3b8; padding: 4px 11px; border-radius: 7px; font-size: 0.72rem; font-weight: 700;
    font-family: 'Fira Code', monospace; pointer-events: none; letter-spacing: 0.03em;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4); transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), transform 0.25s cubic-bezier(0.16, 1, 0.3, 1); z-index: 1;
  }
  .search-wrapper:focus-within .search-kbd { opacity: 0; transform: translateY(-50%) scale(0.8) translateX(10px); }

  .container { max-width: 1100px; margin: 0 auto; padding: 0 20px 100px; }
  .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding-bottom: 16px; }
  .section-title { font-size: 1.4rem; font-weight: 700; display: flex; align-items: center; gap: 12px; color: #f8fafc; }
  .section-title svg { color: #3b82f6; }
  .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
  .script-item { transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
  .empty-state { text-align: center; color: #64748b; padding: 80px 0; font-size: 1.05rem; background: rgba(255, 255, 255, 0.01); border: 1px dashed rgba(255, 255, 255, 0.06); border-radius: 20px; }

  /* ── Script Card Styles ── */
  .card {
    background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 18px; padding: 26px;
    display: flex; flex-direction: column; cursor: pointer; text-align: left; text-decoration: none; color: inherit;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
    position: relative; overflow: hidden;
  }
  .card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, transparent, var(--theme-color), transparent); opacity: 0; transition: opacity 0.3s ease;
  }
  .card:hover { transform: translateY(-6px); box-shadow: 0 16px 36px -12px var(--theme-shadow); border-color: rgba(255, 255, 255, 0.12); }
  .card:hover::before { opacity: 1; }
  .card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
  .card-icon {
    width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: transform 0.3s ease;
  }
  .card:hover .card-icon { transform: scale(1.08) rotate(4deg); }
  .card-actions { display: flex; align-items: center; gap: 8px; }
  .tag {
    font-size: 0.65rem; padding: 5px 10px; border-radius: 6px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.5px; background: rgba(255, 255, 255, 0.05); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.02);
  }
  .btn-card-copy {
    width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
    background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); color: #64748b; cursor: pointer; transition: all 0.2s ease;
  }
  .btn-card-copy:hover { background: rgba(255, 255, 255, 0.08); color: #f8fafc; border-color: rgba(255, 255, 255, 0.18); }
  .btn-card-copy:active { transform: scale(0.92); }
  .btn-card-copy.copied { background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3); color: #34d399; }
  .card h3 { font-size: 1.15rem; font-weight: 700; margin-bottom: 10px; color: var(--text-color); line-height: 1.4; }
  .card p { color: #94a3b8; font-size: 0.88rem; line-height: 1.6; margin-bottom: 24px; flex-grow: 1; }
  .card-footer { font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px; color: var(--theme-color); transition: gap 0.2s ease; }
  .card:hover .card-footer { gap: 10px; }
  .arrow-icon { transition: transform 0.2s ease; }
  .card:hover .arrow-icon { transform: translateX(2px); }

  @media (max-width: 768px) {
    .hero h1 { font-size: 2.4rem; }
    .hero p { font-size: 0.95rem; }
    .grid { grid-template-columns: 1fr; }
  }

  /* ── Light mode overrides ── */
  [data-theme="light"] .hero p { color: #475569; }
  [data-theme="light"] .search-glow { background: radial-gradient(circle, rgba(37, 99, 235, 0.14) 0%, rgba(5, 150, 105, 0.08) 55%, transparent 75%); }
  [data-theme="light"] .search-wrapper { background: linear-gradient(135deg, rgba(37, 99, 235, 0.12) 0%, rgba(0, 0, 0, 0.02) 50%, rgba(5, 150, 105, 0.08) 100%); box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.05), 0 2px 10px rgba(0, 0, 0, 0.02), inset 0 1px 0 rgba(255, 255, 255, 0.8); }
  [data-theme="light"] .search-wrapper:hover { background: linear-gradient(135deg, rgba(37, 99, 235, 0.18) 0%, rgba(0, 0, 0, 0.03) 50%, rgba(5, 150, 105, 0.14) 100%); box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 20px rgba(37, 99, 235, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.9); }
  [data-theme="light"] .search-wrapper:focus-within { background: linear-gradient(135deg, rgba(37, 99, 235, 0.65) 0%, rgba(99, 102, 241, 0.4) 50%, rgba(5, 150, 105, 0.45) 100%); box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.15), 0 0 25px rgba(37, 99, 235, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.9); }
  [data-theme="light"] .search-inner { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
  [data-theme="light"] #searchInput { color: #0f172a; }
  [data-theme="light"] #searchInput::placeholder { color: #94a3b8; }
  [data-theme="light"] .search-icon { color: #94a3b8; }
  [data-theme="light"] .search-wrapper:focus-within .search-icon { color: #2563eb; }
  [data-theme="light"] .search-kbd { background: linear-gradient(to bottom, #ffffff 0%, #f1f5f9 100%); border: 1px solid rgba(0, 0, 0, 0.12); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03), inset 0 1px 0 rgba(255, 255, 255, 0.9); color: #64748b; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); }
  [data-theme="light"] .section-header { border-bottom-color: rgba(0, 0, 0, 0.07); }
  [data-theme="light"] .section-title { color: #0f172a; }
  [data-theme="light"] .empty-state { color: #94a3b8; background: rgba(0, 0, 0, 0.015); border-color: rgba(0, 0, 0, 0.08); }
  [data-theme="light"] .card:hover { border-color: rgba(0, 0, 0, 0.14); box-shadow: 0 16px 36px -12px var(--theme-shadow); }
  [data-theme="light"] .tag { background: rgba(0, 0, 0, 0.05); border-color: rgba(0, 0, 0, 0.06); color: #64748b; }
  [data-theme="light"] .btn-card-copy { background: rgba(0, 0, 0, 0.04); border-color: rgba(0, 0, 0, 0.08); color: #94a3b8; }
  [data-theme="light"] .btn-card-copy:hover { background: rgba(0, 0, 0, 0.08); color: #0f172a; border-color: rgba(0, 0, 0, 0.16); }
  [data-theme="light"] .card p { color: #475569; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$icons = [
  'tool' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
  'pen' => '<path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>',
  'package' => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>',
  'refresh' => '<polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>',
  'server' => '<rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>',
];
$colorMap = [
  'blue'   => ['bg' => 'rgba(59, 130, 246, 0.12)',  'text' => '#60a5fa', 'shadow' => 'rgba(59, 130, 246, 0.22)'],
  'purple' => ['bg' => 'rgba(168, 85, 247, 0.12)', 'text' => '#c084fc', 'shadow' => 'rgba(168, 85, 247, 0.22)'],
  'green'  => ['bg' => 'rgba(16, 185, 129, 0.12)', 'text' => '#34d399', 'shadow' => 'rgba(16, 185, 129, 0.22)'],
];
?>

<section class="hero fade-up">
  <h1>Script <span class="gradient-text">Collection</span></h1>
  <p>Skrip otomatis untuk setup, deployment, dan troubleshooting server secara instan.</p>
  
  <div class="search-section">
    <div class="search-glow"></div>
    <div class="search-wrapper">
      <div class="search-inner">
        <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input id="searchInput" type="text" placeholder="Cari skrip... (misal: synchronizer)" autocomplete="off" />
        <span class="search-kbd">/</span>
      </div>
    </div>
  </div>
</section>

<section class="container fade-up" style="animation-delay: 0.1s;">
  <div class="section-header">
    <h2 class="section-title">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
      Daftar Skrip Terbaru
    </h2>
  </div>
  
  <div class="grid" id="scriptGrid">
    <?php foreach ($scripts as $s): 
      $c = $colorMap[$s['icon_color']] ?? $colorMap['blue'];
      $iconSvg = $icons[$s['icon']] ?? $icons['tool'];
    ?>
      <div class="script-item"
        data-title="<?= esc(strtolower($s['title'])) ?>"
        data-desc="<?= esc(strtolower($s['description'])) ?>">
        
        <a href="<?= base_url('scripts/' . $s['slug']) ?>" class="card" style="--theme-color: <?= $c['text'] ?>; --theme-shadow: <?= $c['shadow'] ?>;">
          <div class="card-top">
            <div class="card-icon" style="background: <?= $c['bg'] ?>; color: <?= $c['text'] ?>">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <?= $iconSvg ?>
              </svg>
            </div>
            <div class="card-actions">
              <span class="tag"><?= esc(strtoupper($s['tag'])) ?></span>
              <button class="btn-card-copy" data-code="<?= esc($s['command']) ?>" title="Salin Perintah" aria-label="Salin perintah">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
              </button>
            </div>
          </div>
          <h3><?= esc($s['title']) ?></h3>
          <p><?= esc($s['description']) ?></p>
          <div class="card-footer">
            <span>Lihat Detail</span>
            <svg class="arrow-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </div>
        </a>

      </div>
    <?php endforeach; ?>
  </div>
  
  <div id="emptyState" class="empty-state" style="display:none">
    Tidak ada skrip yang cocok dengan kriteria pencarian Anda.
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  // Search Logic
  const input = document.getElementById('searchInput');
  const items = document.querySelectorAll('.script-item');
  const empty = document.getElementById('emptyState');
  let currentSearch = '';

  function applyFilters() {
    let visibleCount = 0;
    items.forEach(item => {
      const title = item.getAttribute('data-title') || '';
      const desc = item.getAttribute('data-desc') || '';
      if (title.includes(currentSearch) || desc.includes(currentSearch)) {
        item.style.display = '';
        setTimeout(() => { item.style.opacity = '1'; item.style.transform = 'scale(1)'; }, 10);
        visibleCount++;
      } else {
        item.style.opacity = '0'; item.style.transform = 'scale(0.95)'; item.style.display = 'none';
      }
    });
    empty.style.display = visibleCount === 0 ? 'block' : 'none';
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === '/' && document.activeElement !== input) {
      e.preventDefault(); input?.focus();
    }
  });

  input?.addEventListener('input', () => {
    currentSearch = input.value.toLowerCase().trim();
    applyFilters();
  });

  // Copy Button Logic
  if (!window.__cardCopyInitialized) {
    window.__cardCopyInitialized = true;
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.btn-card-copy');
      if (!btn) return;
      e.preventDefault();
      e.stopPropagation();
      const code = btn.getAttribute('data-code') || '';
      navigator.clipboard.writeText(code).then(() => {
        const originalContent = btn.innerHTML;
        btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`;
        btn.classList.add('copied');
        setTimeout(() => { btn.innerHTML = originalContent; btn.classList.remove('copied'); }, 1500);
      });
    });
  }
</script>
<?= $this->endSection() ?>
