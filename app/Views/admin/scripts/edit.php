<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="page-header">
  <h2>Edit Script</h2>
  <a href="/admin/scripts" class="btn btn-secondary">← Kembali</a>
</div>

<?php if (session()->has('gen_ok')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  window.SwalCustom && window.SwalCustom.fire({
    icon: 'success',
    title: 'Script Digenerate!',
    html: '<?= addslashes(session('gen_ok')) ?>',
    confirmButtonText: 'Oke, Lengkapi Sekarang'
  });
});
</script>
<?php elseif (session()->has('gen_warn')): ?>
<div style="background:rgba(251,146,60,0.08); border:1px solid rgba(251,146,60,0.2); border-radius:12px; padding:14px 18px; margin-bottom:20px; display:flex; align-items:flex-start; gap:12px;">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2" style="flex-shrink:0; margin-top:1px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
  <p style="color:#fb923c; font-size:0.87rem; line-height:1.5; margin:0;"><?= session('gen_warn') ?></p>
</div>
<?php endif; ?>


<div class="card">
  <form method="POST" id="editForm" action="/admin/scripts/<?= esc($script['id']) ?>/edit">
    <div class="form-grid">
      <div class="form-group" style="grid-column: 1 / -1;">
        <label for="title">Judul Script *</label>
        <input id="title" name="title" type="text" class="form-control" required value="<?= esc(old('title', $script['title'])) ?>" />
      </div>
      <div class="form-group" style="grid-column: 1 / -1;">
        <label for="slug">Slug (URL) *</label>
        <input id="slug" name="slug" type="text" class="form-control" required value="<?= esc(old('slug', $script['slug'])) ?>" />
        <small style="color:var(--muted); font-size:0.78rem; margin-top:4px; display:block;">
          URL Publik: <a href="/scripts/<?= esc($script['slug']) ?>" target="_blank" style="color:var(--primary);">/scripts/<?= esc($script['slug']) ?> ↗</a>
        </small>
      </div>
      <div class="form-group" style="grid-column: 1 / -1;">
        <label for="description">Deskripsi *</label>
        <textarea id="description" name="description" class="form-control" required><?= esc(old('description', $script['description'])) ?></textarea>
      </div>
      <div class="form-group">
        <label for="tag">Tag / Bahasa *</label>
        <select id="tag" name="tag" class="form-control">
          <?php foreach (['powershell','bash','cmd'] as $t): ?>
            <option value="<?= $t ?>" <?= old('tag', $script['tag']) === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="sort_order">Urutan Tampil</label>
        <input id="sort_order" name="sort_order" type="number" class="form-control" value="<?= esc(old('sort_order', $script['sort_order'])) ?>" min="0" />
      </div>
      <div class="form-group">
        <label for="icon">Ikon</label>
        <select id="icon" name="icon" class="form-control">
          <?php 
          $icons = [
            'tool' => '🔧 Tool (Wrench)',
            'pen' => '✏️ Pen',
            'package' => '📦 Package',
            'refresh' => '🔄 Refresh',
            'server' => '🖥️ Server'
          ];
          foreach ($icons as $v => $l): ?>
            <option value="<?= $v ?>" <?= old('icon', $script['icon']) === $v ? 'selected' : '' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="icon_color">Warna Ikon</label>
        <select id="icon_color" name="icon_color" class="form-control">
          <?php 
          $colors = [
            'blue' => '🔵 Biru',
            'purple' => '🟣 Ungu',
            'green' => '🟢 Hijau'
          ];
          foreach ($colors as $v => $l): ?>
            <option value="<?= $v ?>" <?= old('icon_color', $script['icon_color']) === $v ? 'selected' : '' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group" style="grid-column: 1 / -1;">
        <label for="command">Perintah PowerShell *</label>
        <input id="command" name="command" type="text" class="form-control" required
          value="<?= esc(old('command', $script['command'])) ?>"
          style="font-family: 'Fira Code', monospace;" />
      </div>
      <div class="form-group" style="grid-column: 1 / -1;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
          <label for="command_cmd" style="margin-bottom:0;">Perintah CMD</label>
          <button type="button" id="btn-gen-cmd" onclick="generateCmdFromPs()"
            style="display:inline-flex; align-items:center; gap:5px;
                   background:rgba(251,146,60,0.08); border:1px solid rgba(251,146,60,0.2);
                   color:#fb923c; border-radius:8px; padding:4px 12px;
                   font-size:0.75rem; font-weight:700; cursor:pointer; font-family:var(--font-body);
                   transition:all 0.2s ease; letter-spacing:0.02em;"
            onmouseover="this.style.background='rgba(251,146,60,0.16)';this.style.borderColor='rgba(251,146,60,0.35)'"
            onmouseout="this.style.background='rgba(251,146,60,0.08)';this.style.borderColor='rgba(251,146,60,0.2)'">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>
            </svg>
            Auto Generate CMD
          </button>
        </div>
        <input id="command_cmd" name="command_cmd" type="text" class="form-control"
          value="<?= esc(old('command_cmd', $script['command_cmd'])) ?>"
          placeholder="Kosongkan jika sama dengan perintah PowerShell"
          style="font-family: 'Fira Code', monospace;" />
        <small style="color:var(--muted); font-size:0.78rem; margin-top:4px; display:block;">
          Opsional. Kosongkan jika perintah CMD sama dengan PowerShell. Atau klik <strong>Auto Generate CMD</strong> untuk generate otomatis dari perintah PowerShell.
        </small>
      </div>
    </div>

<script>
function generateCmdFromPs() {
  const psInput  = document.getElementById('command');
  const cmdInput = document.getElementById('command_cmd');
  const btn      = document.getElementById('btn-gen-cmd');
  const psCmd    = psInput ? psInput.value.trim() : '';

  if (!psCmd) {
    psInput && psInput.focus();
    psInput && (psInput.style.borderColor = 'rgba(239,68,68,0.6)');
    setTimeout(() => { psInput && (psInput.style.borderColor = ''); }, 2000);
    window.Toast && window.Toast.fire({ icon: 'warning', title: 'Isi dulu perintah PowerShell!' });
    return;
  }

  // Wrap PS command dengan format CMD yang umum dipakai
  const cmdResult = `powershell -ExecutionPolicy Bypass -Command "${psCmd}"`;
  cmdInput.value  = cmdResult;

  // Feedback animasi pada tombol
  const origHTML = btn.innerHTML;
  btn.innerHTML  = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> Berhasil!`;
  btn.style.background    = 'rgba(16,185,129,0.12)';
  btn.style.borderColor   = 'rgba(16,185,129,0.3)';
  btn.style.color         = '#10b981';
  setTimeout(() => {
    btn.innerHTML          = origHTML;
    btn.style.background   = 'rgba(251,146,60,0.08)';
    btn.style.borderColor  = 'rgba(251,146,60,0.2)';
    btn.style.color        = '#fb923c';
  }, 2000);

  // Flash highlight pada input CMD
  cmdInput.style.borderColor   = 'rgba(16,185,129,0.6)';
  cmdInput.style.boxShadow     = '0 0 0 3px rgba(16,185,129,0.1)';
  setTimeout(() => {
    cmdInput.style.borderColor = '';
    cmdInput.style.boxShadow   = '';
  }, 2000);
}
</script>

<?php
$step1_val = old('step1_desc', $script['step1_desc'] ?? '') ?: 'Buka program <strong>PowerShell</strong> (untuk Windows) atau terminal/shell (untuk Linux/macOS) dengan hak akses penuh administrator.';
$step2_val = old('step2_desc', $script['step2_desc'] ?? '') ?: 'Pilih jenis terminal Anda, lalu salin perintah menggunakan tombol di kanan atas:';
$step3_val = old('step3_desc', $script['step3_desc'] ?? '') ?: 'Tempel perintah yang disalin ke terminal Anda menggunakan shortcut <kbd>Ctrl + V</kbd> atau klik kanan, lalu tekan tombol <kbd>ENTER</kbd>.';
?>
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
  /* ── Quill dark theme ── */
  .ql-toolbar.ql-snow {
    background: rgba(255,255,255,0.03);
    border: 1px solid var(--border);
    border-bottom: none;
    border-radius: 10px 10px 0 0;
    padding: 6px 8px;
    flex-wrap: wrap;
  }
  .ql-container.ql-snow {
    border: 1px solid var(--border);
    border-radius: 0 0 10px 10px;
    background: rgba(8,12,23,0.6);
    min-height: 80px;
    font-family: var(--font-body);
  }
  .ql-editor {
    color: var(--text);
    min-height: 72px;
    font-size: 0.88rem;
    line-height: 1.65;
    padding: 10px 14px;
  }
  .ql-editor.ql-blank::before { color: var(--muted); font-style: italic; }
  .ql-snow .ql-picker            { color: var(--text-dim, #94a3b8); }
  .ql-snow .ql-stroke            { stroke: var(--text-dim, #94a3b8); }
  .ql-snow .ql-fill              { fill:   var(--text-dim, #94a3b8); }
  .ql-snow .ql-picker-options    { background: var(--card-bg); border-color: var(--border); border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.3); }
  .ql-snow .ql-picker-item       { color: var(--text-dim, #94a3b8); padding: 4px 10px; }
  .ql-snow .ql-picker-item:hover { color: var(--primary); }
  .ql-snow .ql-picker-item.ql-selected { color: var(--primary); }
  .ql-snow button:hover,
  .ql-snow button.ql-active      { color: var(--primary); }
  .ql-snow button:hover .ql-stroke,
  .ql-snow button.ql-active .ql-stroke { stroke: var(--primary); }
  .ql-snow button:hover .ql-fill,
  .ql-snow button.ql-active .ql-fill  { fill: var(--primary); }
  .ql-snow .ql-picker-label:hover,
  .ql-snow .ql-picker-label.ql-active { color: var(--primary); }
  .ql-snow .ql-picker-label:hover .ql-stroke,
  .ql-snow .ql-picker-label.ql-active .ql-stroke { stroke: var(--primary); }

  /* ── Light mode overrides ── */
  [data-theme="light"] .ql-container.ql-snow { background: #fff; }
  [data-theme="light"] .ql-toolbar.ql-snow   { background: rgba(0,0,0,0.025); }
  [data-theme="light"] .ql-snow .ql-picker-options { box-shadow: 0 8px 24px rgba(0,0,0,0.08); }

  .step-editor-group { margin-bottom: 20px; }
  .step-editor-label {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.75rem; font-weight: 600; color: var(--text-dim, #94a3b8);
    text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;
  }
  .step-badge-num {
    background: #3b82f6; color: #fff;
    width: 20px; height: 20px; border-radius: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.72rem; font-weight: 700; flex-shrink: 0;
  }
</style>

    <!-- ── Deskripsi Step ── -->
    <div style="margin-top: 28px; padding-top: 24px; border-top: 1px solid var(--border);">
      <h3 style="font-size:0.95rem; font-weight:700; color:var(--text); margin-bottom:4px; letter-spacing:-0.2px;">
        Deskripsi Langkah
      </h3>
      <p style="font-size:0.8rem; color:var(--muted); margin-bottom:20px; line-height:1.5;">
        Kustomisasi teks panduan tiap langkah. Kosongkan untuk pakai teks default.
      </p>

      <!-- Step 1 -->
      <div class="step-editor-group">
        <div class="step-editor-label">
          <span class="step-badge-num">1</span>
          Step 1 — Buka Terminal (Administrator)
        </div>
        <input type="hidden" name="step1_desc" id="step1_desc_input">
        <div id="editor-step1"></div>
      </div>

      <!-- Step 2 -->
      <div class="step-editor-group">
        <div class="step-editor-label">
          <span class="step-badge-num">2</span>
          Step 2 — Salin Perintah Skrip
        </div>
        <input type="hidden" name="step2_desc" id="step2_desc_input">
        <div id="editor-step2"></div>
      </div>

      <!-- Step 3 -->
      <div class="step-editor-group" style="margin-bottom:0;">
        <div class="step-editor-label">
          <span class="step-badge-num">3</span>
          Step 3 — Jelajah &amp; Jalankan
        </div>
        <input type="hidden" name="step3_desc" id="step3_desc_input">
        <div id="editor-step3"></div>
      </div>
    </div>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
(function () {
  // Register custom font families
  const Font = Quill.import('formats/font');
  Font.whitelist = ['poppins', 'serif', 'monospace'];
  Quill.register(Font, true);

  const toolbarOptions = [
    [{ 'font': ['', 'serif', 'monospace'] }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'align': [] }],
    [{ 'indent': '-1' }, { 'indent': '+1' }],
    ['clean']
  ];

  function mkEditor(id, initialHtml) {
    const q = new Quill('#' + id, {
      modules: { toolbar: toolbarOptions },
      theme: 'snow'
    });
    q.root.innerHTML = initialHtml;
    return q;
  }

  const q1 = mkEditor('editor-step1', <?= json_encode($step1_val) ?>);
  const q2 = mkEditor('editor-step2', <?= json_encode($step2_val) ?>);
  const q3 = mkEditor('editor-step3', <?= json_encode($step3_val) ?>);

  function syncEditors() {
    document.getElementById('step1_desc_input').value = q1.root.innerHTML;
    document.getElementById('step2_desc_input').value = q2.root.innerHTML;
    document.getElementById('step3_desc_input').value = q3.root.innerHTML;
  }

  // Sync on form submit (covers internal submit too)
  document.getElementById('editForm').addEventListener('submit', syncEditors);
  // Also sync when external submit button (form="editForm") is clicked
  const extBtn = document.querySelector('button[form="editForm"][type="submit"]');
  if (extBtn) extBtn.addEventListener('click', syncEditors);
})();
</script>
  </form>

  <div style="display:flex; gap:12px; margin-top:20px; align-items:center;">
    <button type="submit" form="editForm" class="btn btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      Perbarui Script
    </button>
    <a href="/admin/scripts" class="btn btn-secondary">Batal</a>
    <form method="POST" action="/admin/scripts/<?= esc($script['id']) ?>/delete"
          onsubmit="return confirmDelete(this)"
          style="margin-left: auto;">
      <button type="submit" class="btn btn-danger">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
        Hapus Script
      </button>
    </form>
  </div>
</div>

<?php $this->endSection() ?>
