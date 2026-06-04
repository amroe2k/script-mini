<?= $this->extend('layouts/public') ?>

<?= $this->section('head_extra') ?>
<style>
  .detail-wrapper { max-width: 800px; margin: 0 auto; padding: 60px 20px 100px; }
  .back-link {
    display: inline-flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none;
    font-size: 0.9rem; font-weight: 500; margin-bottom: 28px; transition: all 0.2s ease;
  }
  .back-link:hover { color: #f8fafc; }
  .back-arrow { transition: transform 0.2s ease; }
  .back-link:hover .back-arrow { transform: translateX(-4px); }
  .detail-card {
    background: var(--card-bg); border: 1px solid var(--card-border);
    border-radius: 20px; padding: 44px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  }
  .detail-header { text-align: center; margin-bottom: 48px; display: flex; flex-direction: column; align-items: center; }
  .detail-icon {
    width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;
    background: var(--icon-bg); color: var(--icon-color); box-shadow: 0 0 20px var(--icon-shadow);
  }
  h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: 12px; color: #f8fafc; letter-spacing: -0.5px; }
  .subtitle { color: #94a3b8; font-size: 0.95rem; max-width: 540px; line-height: 1.6; }
  .steps { display: flex; flex-direction: column; gap: 24px; margin-bottom: 40px; }
  .step-card {
    background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255, 255, 255, 0.03);
    border-radius: 16px; padding: 24px; display: flex; flex-direction: column; gap: 14px;
  }
  .step-header { display: flex; align-items: center; gap: 12px; }
  .step-badge {
    background: #3b82f6; color: white; width: 32px; height: 32px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;
  }
  .step-body { padding-left: 44px; }
  .step-header h3 { font-size: 1.05rem; font-weight: 700; color: #f8fafc; margin: 0; }
  .step-body p { color: #94a3b8; font-size: 0.9rem; line-height: 1.6; }
  .step-body strong { color: var(--theme-color); }
  
  .terminal-mockup {
    background: #080c14; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.06);
    overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.4);
  }
  .terminal-header {
    display: flex; justify-content: space-between; align-items: center; padding: 10px 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05); background: rgba(255, 255, 255, 0.02);
  }
  .terminal-dots { display: flex; gap: 6px; }
  .dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
  .dot-red { background: #ef4444; } .dot-yellow { background: #f59e0b; } .dot-green { background: #10b981; }
  .terminal-title { font-size: 0.72rem; font-weight: 600; color: #475569; font-family: 'Fira Code', monospace; }
  .btn-copy {
    background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08); color: #94a3b8;
    padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; gap: 6px; transition: all 0.2s ease; font-family: inherit;
  }
  .btn-copy:hover { background: #3b82f6; border-color: #3b82f6; color: white; }
  .btn-copy.copied { background: #10b981; border-color: #10b981; color: white; }
  .terminal-body { padding: 18px 20px; font-family: 'Fira Code', monospace; font-size: 0.85rem; line-height: 1.6; overflow-x: auto; }
  .prompt-prefix { color: #475569; user-select: none; -webkit-user-select: none; }
  .cmd-text { white-space: pre-wrap; word-break: break-all; color: var(--theme-color); }
  kbd { background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 4px; padding: 2px 6px; font-family: inherit; font-size: 0.75rem; color: #f8fafc; }
  
  .info-box { display: flex; align-items: center; gap: 14px; border-radius: 12px; border: 1px solid rgba(59, 130, 246, 0.15); background: rgba(59, 130, 246, 0.04); padding: 16px 20px; }
  .info-icon { background: #3b82f6; color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0; }
  .info-box p { color: #94a3b8; font-size: 0.88rem; line-height: 1.5; }
  
  .shell-tabs { display: flex; gap: 8px; margin-bottom: 12px; }
  .shell-tab {
    display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.06); background: rgba(255, 255, 255, 0.03); color: #64748b;
    font-size: 0.82rem; font-weight: 600; font-family: inherit; cursor: pointer; transition: all 0.2s ease;
  }
  .shell-tab:hover { background: rgba(255, 255, 255, 0.06); color: #94a3b8; }
  .shell-tab.active { background: rgba(59, 130, 246, 0.12); border-color: rgba(59, 130, 246, 0.3); color: #60a5fa; }

  @media (max-width: 600px) {
    .detail-card { padding: 28px 20px; } h1 { font-size: 1.5rem; } .step-card { padding: 16px; gap: 10px; } .step-body { padding-left: 0; }
  }

  /* ── Shadcn/Sonner-style Copy Toast ── */
  .public-toast {
    position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; align-items: flex-start; gap: 12px;
    width: 320px; max-width: calc(100vw - 48px); padding: 14px 16px 18px; border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.3);
    background: rgba(10, 20, 16, 0.93); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.5), 0 0 0 1px rgba(16,185,129,0.08); color: #d1fae5; font-size: 0.875rem; cursor: pointer;
    overflow: hidden; opacity: 0; transform: translateX(110%); transition: opacity 0.32s cubic-bezier(0.16, 1, 0.3, 1), transform 0.36s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .public-toast--show { opacity: 1; transform: translateX(0); }
  .public-toast--exit { animation: ptExit 0.25s cubic-bezier(0.4, 0, 1, 1) forwards; }
  @keyframes ptExit { to { opacity: 0; transform: translateX(110%); } }
  .public-toast__icon { flex-shrink: 0; color: #10b981; margin-top: 1px; display: flex; }
  .public-toast__body { flex: 1; }
  .public-toast__title { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.9rem; margin-bottom: 2px; color: #ecfdf5; }
  .public-toast__msg { font-size: 0.78rem; opacity: 0.8; color: #a7f3d0; }
  .public-toast__progress { position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: rgba(16,185,129,0.15); }
  .public-toast__bar { height: 100%; background: #10b981; animation: ptProgress 3.5s linear forwards; transform-origin: left; }
  @keyframes ptProgress { from { transform: scaleX(1); } to { transform: scaleX(0); } }
  @media (max-width: 480px) { .public-toast { right: 12px; bottom: 16px; width: calc(100vw - 24px); } }

  /* ── Light mode overrides ── */
  [data-theme="light"] .detail-wrapper {
    --theme-color: var(--theme-color-light);
    --icon-color: var(--theme-color-light);
    --icon-shadow: rgba(0, 0, 0, 0.05);
  }
  [data-theme="light"] .back-link:hover { color: #0f172a; }
  [data-theme="light"] h1 { color: #0f172a; }
  [data-theme="light"] .step-header h3 { color: #0f172a; }
  [data-theme="light"] .step-card { background: rgba(0, 0, 0, 0.015); border-color: rgba(0, 0, 0, 0.05); }
  [data-theme="light"] .step-body p { color: #475569; }
  [data-theme="light"] .step-body strong { color: var(--theme-color-light); }
  [data-theme="light"] .terminal-mockup {
    background: #f8fafc; border-color: rgba(0, 0, 0, 0.08); box-shadow: 0 10px 30px rgba(0,0,0,0.06);
  }
  [data-theme="light"] .terminal-header {
    border-bottom-color: rgba(0, 0, 0, 0.06); background: rgba(0, 0, 0, 0.02);
  }
  [data-theme="light"] .terminal-title {
    color: #64748b;
  }
  [data-theme="light"] .btn-copy {
    background: rgba(0, 0, 0, 0.04); border-color: rgba(0, 0, 0, 0.08); color: #475569;
  }
  [data-theme="light"] .btn-copy:hover {
    background: #3b82f6; border-color: #3b82f6; color: white;
  }
  [data-theme="light"] .prompt-prefix {
    color: #94a3b8;
  }
  [data-theme="light"] kbd {
    background: rgba(0, 0, 0, 0.04); border-color: rgba(0, 0, 0, 0.1); color: #0f172a;
  }
  [data-theme="light"] .info-box {
    background: rgba(37, 99, 235, 0.05); border-color: rgba(37, 99, 235, 0.15);
  }
  [data-theme="light"] .info-box p {
    color: #1e3a8a;
  }

  /* ── Quill HTML rendering on public page ── */
  .step-body p { margin: 0; }
  .step-body p + p { margin-top: 6px; }
  .step-body ol, .step-body ul { padding-left: 1.5em; margin: 4px 0; }
  .step-body li { margin-bottom: 2px; }
  .step-body .ql-align-center  { text-align: center; }
  .step-body .ql-align-right   { text-align: right; }
  .step-body .ql-align-justify { text-align: justify; }
  .step-body .ql-indent-1:not(.ql-direction-rtl) { padding-left: 3em; }
  .step-body .ql-indent-2:not(.ql-direction-rtl) { padding-left: 6em; }
  .step-body .ql-indent-3:not(.ql-direction-rtl) { padding-left: 9em; }
  .step-body .ql-indent-4:not(.ql-direction-rtl) { padding-left: 12em; }
  .step-body .ql-indent-5:not(.ql-direction-rtl) { padding-left: 15em; }
  .step-body .ql-size-small  { font-size: 0.75em; }
  .step-body .ql-size-large  { font-size: 1.25em; }
  .step-body .ql-size-huge   { font-size: 1.7em; }
  .step-body .ql-font-serif     { font-family: Georgia, 'Times New Roman', serif; }
  .step-body .ql-font-monospace { font-family: 'Fira Code', 'Courier New', monospace; }
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
  'blue'   => ['bg' => 'rgba(59, 130, 246, 0.12)',  'text' => '#60a5fa', 'text_light' => '#2563eb', 'shadow' => 'rgba(59, 130, 246, 0.22)'],
  'purple' => ['bg' => 'rgba(168, 85, 247, 0.12)', 'text' => '#c084fc', 'text_light' => '#7c3aed', 'shadow' => 'rgba(168, 85, 247, 0.22)'],
  'green'  => ['bg' => 'rgba(16, 185, 129, 0.12)', 'text' => '#34d399', 'text_light' => '#059669', 'shadow' => 'rgba(16, 185, 129, 0.22)'],
];

$c = $colorMap[$script['icon_color']] ?? $colorMap['blue'];
$iconSvg = $icons[$script['icon']] ?? $icons['tool'];
$commandCmd = trim($script['command_cmd'] ?? '') ?: $script['command'];

// Step descriptions dengan fallback ke default
$step1_desc = trim($script['step1_desc'] ?? '');
$step2_desc = trim($script['step2_desc'] ?? '');
$step3_desc = trim($script['step3_desc'] ?? '');
if ($step1_desc === '') $step1_desc = 'Buka program <strong>PowerShell</strong> (untuk Windows) atau terminal/shell (untuk Linux/macOS) dengan hak akses penuh administrator.';
if ($step2_desc === '') $step2_desc = 'Pilih jenis terminal Anda, lalu salin perintah menggunakan tombol di kanan atas:';
if ($step3_desc === '') $step3_desc = 'Tempel perintah yang disalin ke terminal Anda menggunakan shortcut <kbd>Ctrl + V</kbd> atau klik kanan, lalu tekan tombol <kbd>ENTER</kbd>.';
?>

<div class="detail-wrapper fade-up" style="--theme-color: <?= $c['text'] ?>; --theme-color-light: <?= $c['text_light'] ?>; --icon-bg: <?= $c['bg'] ?>; --icon-color: <?= $c['text'] ?>; --icon-shadow: <?= $c['shadow'] ?>;">
  <a href="<?= base_url() ?>" class="back-link">
    <svg class="back-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Kembali ke Beranda
  </a>

  <div class="detail-card">
    <div class="detail-header">
      <div class="detail-icon">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <?= $iconSvg ?>
        </svg>
      </div>
      <h1><?= esc($script['title']) ?></h1>
      <p class="subtitle"><?= esc($script['description']) ?></p>
    </div>

    <div class="steps">
      <!-- Step 1 -->
      <div class="step-card">
        <div class="step-header">
          <div class="step-badge">1</div>
          <h3>Buka Terminal (Administrator)</h3>
        </div>
        <div class="step-body">
          <div><?= $step1_desc ?></div>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="step-card">
        <div class="step-header">
          <div class="step-badge">2</div>
          <h3>Salin Perintah Skrip</h3>
        </div>
        <div class="step-body">
          <div style="margin-bottom: 16px;"><?= $step2_desc ?></div>

          <div class="shell-tabs">
            <button class="shell-tab active" data-shell="ps">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
              PowerShell
            </button>
            <button class="shell-tab" data-shell="cmd">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
              CMD
            </button>
            <?php if (!empty($script['enable_linux'])): ?>
            <button class="shell-tab" data-shell="bash">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
              Linux / Bash
            </button>
            <?php endif; ?>
          </div>

          <div class="terminal-mockup">
            <div class="terminal-header">
              <div class="terminal-dots">
                <span class="dot dot-red"></span>
                <span class="dot dot-yellow"></span>
                <span class="dot dot-green"></span>
              </div>
              <div id="terminalTitle" class="terminal-title">Administrator: Windows PowerShell</div>
              <button id="copyBtn" class="btn-copy"
                data-code-ps="<?= esc($script['command']) ?>"
                data-code-cmd="<?= esc($commandCmd) ?>"
                <?php if (!empty($script['enable_linux'])): ?>
                data-code-bash="<?= esc($script['command_linux']) ?>"
                <?php endif; ?>>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                <span>Salin</span>
              </button>
            </div>
            <div class="terminal-body">
              <code><span id="promptPrefix" class="prompt-prefix">PS C:\> </span><span id="cmdText" class="cmd-text"><?= esc($script['command']) ?></span></code>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="step-card">
        <div class="step-header">
          <div class="step-badge">3</div>
          <h3>Jelajah &amp; Jalankan</h3>
        </div>
        <div class="step-body">
          <div><?= $step3_desc ?></div>
        </div>
      </div>
    </div>

    <div class="info-box">
      <div class="info-icon">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
      </div>
      <p>Pastikan koneksi internet server Anda aktif dan stabil agar skrip dapat diunduh dan dieksekusi secara instan.</p>
    </div>

  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const shellTabs = document.querySelectorAll('.shell-tab');
  const terminalTitle = document.getElementById('terminalTitle');
  const promptPrefix  = document.getElementById('promptPrefix');
  const cmdText       = document.getElementById('cmdText');
  const btn           = document.getElementById('copyBtn');

  const shellConfig = {
    ps:   { title: 'Administrator: Windows PowerShell', prompt: 'PS C:\\> ', attr: 'data-code-ps' },
    cmd:  { title: 'Command Prompt',                    prompt: 'C:\\> ',    attr: 'data-code-cmd' },
    bash: { title: 'Terminal / Bash',                   prompt: 'root@server:~# ', attr: 'data-code-bash' },
  };

  let activeShell = 'ps';

  shellTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      shellTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      activeShell = tab.getAttribute('data-shell') || 'ps';
      const cfg = shellConfig[activeShell];
      terminalTitle.textContent = cfg.title;
      promptPrefix.textContent  = cfg.prompt;
      cmdText.textContent = btn.getAttribute(cfg.attr) || '';
    });
  });

  function showCopyToast(shellLabel) {
    document.querySelector('.public-toast')?.remove();
    const toast = document.createElement('div');
    toast.className = 'public-toast';
    toast.innerHTML = `
      <div class="public-toast__icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
          <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
      </div>
      <div class="public-toast__body">
        <div class="public-toast__title">Tersalin!</div>
        <div class="public-toast__msg">Perintah ${shellLabel} siap ditempel ke terminal.</div>
      </div>
      <div class="public-toast__progress"><div class="public-toast__bar"></div></div>
    `;
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('public-toast--show'));
    const timer = setTimeout(() => {
      toast.classList.add('public-toast--exit');
      toast.addEventListener('animationend', () => toast.remove(), { once: true });
    }, 3500);
    toast.addEventListener('click', () => {
      clearTimeout(timer);
      toast.classList.add('public-toast--exit');
      toast.addEventListener('animationend', () => toast.remove(), { once: true });
    });
  }

  btn?.addEventListener('click', () => {
    const attr = shellConfig[activeShell]?.attr || 'data-code-ps';
    const code = btn.getAttribute(attr) || '';
    const shellLabel = activeShell === 'ps' ? 'PowerShell' : (activeShell === 'cmd' ? 'CMD' : 'Linux/Bash');

    navigator.clipboard.writeText(code).then(() => {
      const original = btn.innerHTML;
      btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> <span>Tersalin!</span>`;
      btn.classList.add('copied');
      setTimeout(() => {
        btn.innerHTML = original;
        btn.classList.remove('copied');
      }, 1800);
      showCopyToast(shellLabel);
    });
  });
</script>
<?= $this->endSection() ?>
