<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title ?? 'Admin') ?> | Admin Panel</title>
  <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet" />

  <!-- Anti-flash: apply saved/preferred theme before first paint -->
  <script>
    (function () {
      const saved = localStorage.getItem('theme');
      const preferred = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', saved || preferred);
    })();
  </script>

  <style>
    /* DARK (default) */
    :root,
    [data-theme="dark"] {
      --bg:           #080c17;
      --sidebar-bg:   #0b1121;
      --card-bg:      #111827;
      --border:       rgba(255,255,255,0.06);
      --border-mid:   rgba(255,255,255,0.10);
      --text:         #f1f5f9;
      --text-dim:     #94a3b8;
      --muted:        #64748b;
      --primary:      #3b82f6;
      --success:      #10b981;
      --danger:       #ef4444;
      --topbar-bg:    rgba(8,12,23,0.80);
      --sidebar-brand-border: rgba(255,255,255,0.06);
      --font-body:    'Poppins', sans-serif;
      --font-display: 'Outfit', sans-serif;
      --font-mono:    'Fira Code', monospace;
    }

    /* LIGHT */
    [data-theme="light"] {
      --bg:           #f1f5f9;
      --sidebar-bg:   #ffffff;
      --card-bg:      #ffffff;
      --border:       rgba(0,0,0,0.08);
      --border-mid:   rgba(0,0,0,0.13);
      --text:         #0f172a;
      --text-dim:     #475569;
      --muted:        #94a3b8;
      --primary:      #2563eb;
      --success:      #059669;
      --danger:       #dc2626;
      --topbar-bg:    rgba(241,245,249,0.85);
      --sidebar-brand-border: rgba(0,0,0,0.08);
    }

    /* Smooth transitions on theme switch (exclude animations) */
    *, *::before, *::after {
      transition:
        background-color 0.25s ease,
        border-color 0.25s ease,
        color 0.22s ease,
        box-shadow 0.25s ease;
    }
    .nav-link, .logout-btn, .btn, .form-control {
      transition: all 0.18s ease !important;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: var(--font-body);
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      overflow-x: hidden;
    }

    /* ── Card ── */
    .card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 24px;
    }

    /* ── Table ── */
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    thead th {
      text-align: left;
      padding: 11px 16px;
      color: var(--muted);
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.7px;
      border-bottom: 1px solid var(--border);
    }
    tbody td {
      padding: 14px 16px;
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr { transition: background 0.15s ease; }
    tbody tr:hover { background: rgba(255,255,255,0.025); }

    /* ── Buttons ── */
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 18px;
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      font-family: var(--font-body);
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .btn:active { transform: scale(0.97); }

    .btn-primary {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      color: white;
      box-shadow: 0 2px 8px rgba(59,130,246,0.25);
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #60a5fa, #3b82f6);
      box-shadow: 0 4px 14px rgba(59,130,246,0.35);
      transform: translateY(-1px);
    }

    .btn-secondary {
      background: rgba(255,255,255,0.06);
      color: var(--text);
      border: 1px solid var(--border);
    }
    .btn-secondary:hover {
      background: rgba(255,255,255,0.10);
      border-color: var(--border-mid);
    }

    .btn-danger {
      background: rgba(239,68,68,0.08);
      color: #f87171;
      border: 1px solid rgba(239,68,68,0.12);
    }
    .btn-danger:hover {
      background: rgba(239,68,68,0.16);
      color: #fca5a5;
    }

    .btn-sm { padding: 5px 12px; font-size: 0.78rem; border-radius: 8px; }

    /* ── Form ── */
    .form-group { margin-bottom: 22px; }
    .form-group label {
      display: block;
      font-size: 0.75rem;
      font-weight: 600;
      color: var(--text-dim);
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .form-control {
      width: 100%;
      background: rgba(8,12,23,0.7);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 11px 14px;
      color: var(--text);
      font-size: 0.9rem;
      font-family: var(--font-body);
      outline: none;
      transition: all 0.2s ease;
    }
    .form-control:focus {
      border-color: rgba(59,130,246,0.65);
      box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
      background: rgba(8,12,23,0.95);
    }
    textarea.form-control { min-height: 90px; resize: vertical; }
    select.form-control option { background: #111827; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }

    /* ── Tag badges ── */
    .tag-badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 6px;
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      background: rgba(255,255,255,0.06);
      color: var(--muted);
      border: 1px solid var(--border);
    }
    .tag-badge-ps   { background: rgba(59,130,246,0.10); color: #60a5fa;  border-color: rgba(59,130,246,0.20); }
    .tag-badge-bash { background: rgba(16,185,129,0.10); color: #34d399;  border-color: rgba(16,185,129,0.20); }
    .tag-badge-cmd  { background: rgba(168,85,247,0.10); color: #c084fc;  border-color: rgba(168,85,247,0.20); }

    /* ── Page header ── */
    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 28px;
      gap: 16px;
    }
    .page-header h2 {
      font-family: var(--font-display);
      font-size: 1.5rem;
      font-weight: 700;
    }
    .page-header .page-subtitle {
      font-size: 0.82rem;
      color: var(--muted);
      margin-top: 4px;
    }

    @media (max-width: 768px) {
      .form-grid { grid-template-columns: 1fr; }
    }

    /* ── Sidebar ── */
    .sidebar {
      width: 248px;
      min-height: 100vh;
      background: var(--sidebar-bg);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      position: sticky;
      top: 0;
      height: 100vh;
      z-index: 50;
    }
    .sidebar-brand {
      padding: 22px 20px 18px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .brand-icon {
      width: 36px; height: 36px;
      background: linear-gradient(135deg, rgba(59,130,246,0.2), rgba(16,185,129,0.12));
      border: 1px solid rgba(59,130,246,0.2);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      color: #60a5fa; flex-shrink: 0;
    }
    .brand-name {
      font-family: var(--font-display);
      font-size: 0.95rem; font-weight: 700;
      background: linear-gradient(135deg, #60a5fa, #34d399);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      line-height: 1.2;
    }
    .brand-label {
      font-size: 0.67rem; color: var(--muted);
      font-weight: 500; text-transform: uppercase; letter-spacing: 0.6px;
    }
    .sidebar-nav { padding: 14px 12px; flex: 1; overflow-y: auto; }
    .nav-section-label {
      font-size: 0.65rem; font-weight: 700; color: var(--muted);
      text-transform: uppercase; letter-spacing: 1px;
      padding: 0 10px; margin-bottom: 5px; margin-top: 18px;
    }
    .nav-section-label:first-child { margin-top: 4px; }
    .nav-link {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 12px; border-radius: 10px;
      color: var(--muted); text-decoration: none;
      font-size: 0.875rem; font-weight: 500;
      transition: all 0.18s ease; margin-bottom: 2px;
      position: relative; border: 1px solid transparent;
    }
    .nav-link:hover { background: rgba(255,255,255,0.05); color: var(--text); transform: translateX(2px); }
    .nav-link.active { background: rgba(59,130,246,0.10); color: #60a5fa; border-color: rgba(59,130,246,0.16); }
    .nav-link.active::before {
      content: '';
      position: absolute; left: -12px; top: 50%; transform: translateY(-50%);
      width: 3px; height: 18px;
      background: #3b82f6; border-radius: 0 3px 3px 0;
    }
    .nav-link svg { flex-shrink: 0; }
    .sidebar-footer { padding: 14px 12px; border-top: 1px solid var(--border); }
    .logout-btn {
      display: flex; align-items: center; justify-content: center; gap: 10px;
      width: 100%; padding: 9px 12px; border-radius: 10px;
      color: #f87171; text-decoration: none;
      font-size: 0.875rem; font-weight: 600;
      background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.1);
      transition: all 0.18s ease; font-family: var(--font-body); cursor: pointer;
    }
    .logout-btn:hover { background: rgba(239,68,68,0.14); border-color: rgba(239,68,68,0.25); color: #fca5a5; }

    /* ── Main area ── */
    .main {
      flex: 1; display: flex; flex-direction: column;
      min-height: 100vh; overflow-x: hidden; position: relative;
      background-image:
        linear-gradient(rgba(255,255,255,0.012) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.012) 1px, transparent 1px);
      background-size: 36px 36px;
    }
    .topbar {
      height: 62px; border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 32px;
      background: var(--topbar-bg);
      backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
      flex-shrink: 0; position: sticky; top: 0; z-index: 40;
    }
    .topbar-left  { display: flex; align-items: center; gap: 12px; }
    .topbar-right { display: flex; align-items: center; gap: 10px; }
    .topbar-title {
      font-family: var(--font-display);
      font-size: 1.05rem; font-weight: 600; color: var(--text);
    }
    .topbar-badge {
      font-size: 0.68rem; font-weight: 700; padding: 2px 8px; border-radius: 6px;
      background: rgba(59,130,246,0.12); color: #60a5fa;
      border: 1px solid rgba(59,130,246,0.2);
      text-transform: uppercase; letter-spacing: 0.5px;
    }
    .topbar-user {
      display: flex; align-items: center; gap: 8px;
      padding: 5px 12px 5px 6px; border-radius: 10px;
      background: rgba(255,255,255,0.04); border: 1px solid var(--border);
    }
    .user-avatar {
      width: 28px; height: 28px; border-radius: 8px;
      background: linear-gradient(135deg, #3b82f6, #10b981);
      display: flex; align-items: center; justify-content: center;
      font-size: 0.68rem; font-weight: 700; color: white;
      font-family: var(--font-display);
    }
    .user-name  { font-size: 0.8rem; font-weight: 600; color: var(--text-dim); }
    .status-dot {
      width: 7px; height: 7px; border-radius: 50%;
      background: #10b981; box-shadow: 0 0 6px rgba(16,185,129,0.6);
    }
    .content { padding: 32px; flex: 1; position: relative; z-index: 1; }

    /* ── Admin theme toggle button ── */
    .admin-theme-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 34px;
      height: 34px;
      border-radius: 9px;
      border: 1px solid var(--border);
      background: rgba(255,255,255,0.04);
      color: var(--text-dim);
      cursor: pointer;
      transition: all 0.2s ease !important;
    }
    .admin-theme-btn:hover {
      background: rgba(255,255,255,0.08);
      color: var(--text);
      border-color: var(--border-mid);
    }
    [data-theme="dark"]  .icon-sun  { display: none; }
    [data-theme="light"] .icon-moon { display: none; }
    /* Light mode sidebar & topbar adjustments */
    [data-theme="light"] .topbar-badge {
      background: rgba(37,99,235,0.10);
      border-color: rgba(37,99,235,0.20);
    }
    [data-theme="light"] .topbar-user {
      background: rgba(0,0,0,0.03);
    }
    [data-theme="light"] .nav-link.active {
      background: rgba(37,99,235,0.08);
    }
    [data-theme="light"] .main {
      background-image:
        linear-gradient(rgba(0,0,0,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,0,0,0.025) 1px, transparent 1px);
    }
    [data-theme="light"] .nav-link:hover {
      background: rgba(0, 0, 0, 0.04);
    }
    [data-theme="light"] .logout-btn {
      color: #dc2626;
      background: rgba(220, 38, 38, 0.05);
      border-color: rgba(220, 38, 38, 0.1);
    }
    [data-theme="light"] .logout-btn:hover {
      background: rgba(220, 38, 38, 0.1);
      border-color: rgba(220, 38, 38, 0.2);
      color: #b91c1c;
    }
    [data-theme="light"] .form-control {
      background: #ffffff;
      color: #0f172a;
    }
    [data-theme="light"] .form-control:focus {
      background: #ffffff;
      border-color: rgba(37, 99, 235, 0.65);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    [data-theme="light"] select.form-control option {
      background: #ffffff;
      color: #0f172a;
    }
    [data-theme="light"] .tag-badge {
      background: rgba(0, 0, 0, 0.04);
    }
    [data-theme="light"] .tag-badge-ps {
      background: rgba(37, 99, 235, 0.08);
      color: #2563eb;
      border-color: rgba(37, 99, 235, 0.15);
    }
    [data-theme="light"] .tag-badge-bash {
      background: rgba(5, 150, 105, 0.08);
      color: #059669;
      border-color: rgba(5, 150, 105, 0.15);
    }
    [data-theme="light"] .tag-badge-cmd {
      background: rgba(124, 58, 237, 0.08);
      color: #7c3aed;
      border-color: rgba(124, 58, 237, 0.15);
    }
    [data-theme="light"] tbody tr:hover {
      background: rgba(0, 0, 0, 0.015);
    }

    @media (max-width: 768px) {
      .sidebar   { display: none; }
      .content   { padding: 20px 16px; }
      .topbar    { padding: 0 20px; }
      .user-name { display: none; }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="brand-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="16 18 22 12 16 6"/>
          <polyline points="8 6 2 12 8 18"/>
        </svg>
      </div>
      <div>
        <div class="brand-name">ScriptHub</div>
        <div class="brand-label">Admin Panel</div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-section-label">Konten</div>
      <a href="/admin/scripts" class="nav-link <?= ($activeMenu ?? '') === 'scripts' ? 'active' : '' ?>">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="16 18 22 12 16 6"/>
          <polyline points="8 6 2 12 8 18"/>
        </svg>
        Scripts
      </a>
      <a href="/admin/scripts/files" class="nav-link <?= ($activeMenu ?? '') === 'files' ? 'active' : '' ?>">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="12" y1="18" x2="12" y2="12"/>
          <line x1="9" y1="15" x2="15" y2="15"/>
        </svg>
        Hosted Files
      </a>

      <div class="nav-section-label">Sistem</div>
      <a href="/" class="nav-link" target="_blank">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
          <polyline points="15 3 21 3 21 9"/>
          <line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
        Lihat Website ↗
      </a>
    </nav>

    <div class="sidebar-footer">
      <a href="/admin/logout" class="logout-btn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Logout
      </a>
    </div>
  </aside>

  <!-- Main -->
  <div class="main">
    <div class="topbar">
      <div class="topbar-left">
        <span class="topbar-title"><?= esc($title ?? 'Admin') ?></span>
        <span class="topbar-badge">Admin</span>
      </div>
      <div class="topbar-right">
        <!-- Theme toggle -->
        <button id="adminThemeToggle" class="admin-theme-btn" aria-label="Toggle tema">
          <!-- Moon: shown in dark mode -->
          <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
          </svg>
          <!-- Sun: shown in light mode -->
          <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"/>
            <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
            <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
          </svg>
        </button>
        <div class="topbar-user">
          <div class="user-avatar">AD</div>
          <span class="user-name">Administrator</span>
          <div class="status-dot"></div>
        </div>
      </div>
    </div>
    <div class="content">
      <?= $this->renderSection('content') ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?= $this->include('components/swal_init') ?>
  <!-- Theme toggle script -->
  <script>
    const btn = document.getElementById('adminThemeToggle');
    btn?.addEventListener('click', () => {
      const current = document.documentElement.getAttribute('data-theme') || 'dark';
      const next = current === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      localStorage.setItem('theme', next);
    });
  </script>
</body>
</html>
