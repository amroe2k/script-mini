<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title ?? 'ScriptHub') ?></title>
  <meta name="description" content="<?= esc($desc ?? 'Kumpulan script otomatisasi yang berguna untuk deployment, sinkronisasi, dan perbaikan sistem.') ?>" />
  <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet" />

  <!-- Anti-flash: apply saved/preferred theme before paint -->
  <script>
    (function () {
      var saved = localStorage.getItem('theme');
      var preferred = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', saved || preferred);
    })();
  </script>

  <style>
    /* ── DARK theme (default) ── */
    :root, [data-theme="dark"] {
      --bg-color:      #0b0f19;
      --text-color:    #f8fafc;
      --text-muted:    #94a3b8;
      --primary:       #3b82f6;
      --primary-hov:   #60a5fa;
      --card-bg:       #131a2e;
      --card-border:   rgba(255, 255, 255, 0.05);
      --accent:        #10b981;
      --footer-bg:     rgba(11, 15, 25, 0.3);
      --toggle-bg:     rgba(255,255,255,0.06);
      --toggle-border: rgba(255,255,255,0.10);
      --grid-dot:      rgba(255, 255, 255, 0.02);
      --glow-1:        rgba(59,130,246,0.08);
      --glow-2:        rgba(16,185,129,0.05);
      --font-sans:     'Poppins', sans-serif;
      --font-display:  'Outfit', sans-serif;
    }

    /* ── LIGHT theme ── */
    [data-theme="light"] {
      --bg-color:      #f0f4f8;
      --text-color:    #0f172a;
      --text-muted:    #475569;
      --primary:       #2563eb;
      --primary-hov:   #1d4ed8;
      --card-bg:       #ffffff;
      --card-border:   rgba(0, 0, 0, 0.07);
      --accent:        #059669;
      --footer-bg:     rgba(240, 244, 248, 0.8);
      --toggle-bg:     rgba(0,0,0,0.05);
      --toggle-border: rgba(0,0,0,0.12);
      --grid-dot:      rgba(0, 0, 0, 0.03);
      --glow-1:        rgba(37,99,235,0.05);
      --glow-2:        rgba(5,150,105,0.04);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    /* Smooth theme transitions */
    body, .card-bg-el, header, footer, nav, section, aside {
      transition: background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease;
    }
    .fade-up { animation: none !important; }

    body {
      font-family: var(--font-sans);
      background-color: var(--bg-color);
      color: var(--text-color);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
      position: relative;
    }

    h1, h2, h3, h4, h5, h6 { font-family: var(--font-display); }

    /* ── Background decorations ── */
    .bg-grid {
      position: fixed; inset: 0;
      background-image: radial-gradient(var(--grid-dot) 1px, transparent 1px);
      background-size: 24px 24px;
      mask-image: radial-gradient(ellipse at center, black, transparent 90%);
      -webkit-mask-image: radial-gradient(ellipse at center, black, transparent 90%);
      z-index: -2; pointer-events: none;
    }
    .bg-glow {
      position: fixed; width: 800px; height: 800px;
      background: radial-gradient(circle, var(--glow-1) 0%, transparent 70%);
      top: -300px; left: -200px; border-radius: 50%; z-index: -1; pointer-events: none;
    }
    .bg-glow-2 {
      position: fixed; width: 600px; height: 600px;
      background: radial-gradient(circle, var(--glow-2) 0%, transparent 70%);
      bottom: -100px; right: -100px; border-radius: 50%; z-index: -1; pointer-events: none;
    }

    main { flex: 1; position: relative; z-index: 1; }

    footer {
      text-align: center; padding: 30px;
      border-top: 1px solid var(--card-border);
      color: var(--text-muted); font-size: 0.85rem;
      position: relative; z-index: 1; background: var(--footer-bg);
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both; }

    /* ── Theme Toggle Button ── */
    .theme-toggle {
      position: fixed; bottom: 24px; left: 24px; z-index: 1000;
      width: 42px; height: 42px; border-radius: 12px;
      border: 1px solid var(--toggle-border); background: var(--toggle-bg);
      color: var(--text-muted); cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
      box-shadow: 0 4px 16px rgba(0,0,0,0.15); transition: all 0.2s ease;
    }
    .theme-toggle:hover {
      color: var(--text-color); border-color: var(--primary);
      box-shadow: 0 4px 20px rgba(59,130,246,0.2); transform: translateY(-1px);
    }
    .theme-toggle:active { transform: scale(0.95) translateY(0); }

    [data-theme="dark"]  .icon-sun  { display: none; }
    [data-theme="light"] .icon-moon { display: none; }

    @media (max-width: 480px) { .theme-toggle { bottom: 16px; left: 16px; } }
  </style>

  <?= $this->renderSection('head_extra') ?>
</head>
<body>
  <div class="bg-grid"></div>
  <div class="bg-glow"></div>
  <div class="bg-glow-2"></div>

  <main>
    <?= $this->renderSection('content') ?>
  </main>

  <footer>
    <p>&copy; 2026 ScriptHub &mdash; Dibuat untuk otomatisasi yang lebih cepat dan efisien.</p>
  </footer>

  <!-- Theme Toggle Button -->
  <button id="themeToggle" class="theme-toggle" aria-label="Toggle dark/light mode" title="Toggle tema">
    <!-- Moon icon — shown in dark mode -->
    <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
    </svg>
    <!-- Sun icon — shown in light mode -->
    <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="5"/>
      <line x1="12" y1="1" x2="12" y2="3"/>
      <line x1="12" y1="21" x2="12" y2="23"/>
      <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
      <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
      <line x1="1" y1="12" x2="3" y2="12"/>
      <line x1="21" y1="12" x2="23" y2="12"/>
      <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
      <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
    </svg>
  </button>

  <script>
    const toggle = document.getElementById('themeToggle');
    toggle && toggle.addEventListener('click', function() {
      var current = document.documentElement.getAttribute('data-theme') || 'dark';
      var next = current === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      localStorage.setItem('theme', next);
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?= $this->include('components/swal_init') ?>
  <?= $this->renderSection('scripts') ?>
</body>
</html>
