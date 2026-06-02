<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php $fileCount = count($ps1Files); ?>

<!-- Page header -->
<div class="page-header">
  <div>
    <h2>Hosted PS1 Files</h2>
    <p class="page-subtitle"><?= $fileCount ?> file tersedia di <code>app/Scripts/</code></p>
  </div>
  <button class="btn btn-primary" id="btn-show-upload" onclick="toggleUpload()">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
    Upload File
  </button>
</div>

<!-- Stats -->
<div class="file-stats-row">
  <div class="file-stat-card">
    <div class="fsc-icon fsc-blue">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
    <div>
      <div class="fsc-value"><?= $fileCount ?></div>
      <div class="fsc-label">Total Files</div>
    </div>
  </div>
  <div class="file-stat-card">
    <div class="fsc-icon fsc-purple">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
    </div>
    <div>
      <div class="fsc-value"><?= $fileCount > 0 ? number_format(array_sum(array_column($ps1Files, 'size')) / 1024, 1) : '0' ?> KB</div>
      <div class="fsc-label">Total Ukuran</div>
    </div>
  </div>
  <div class="file-stat-card">
    <div class="fsc-icon fsc-green">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
    </div>
    <div>
      <div class="fsc-value">/scripts/*.ps1</div>
      <div class="fsc-label">URL Pattern</div>
    </div>
  </div>
</div>

<!-- Upload Panel -->
<div class="upload-panel" id="upload-panel">
  <div class="upload-panel-header">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
    Upload Script Baru
    <button class="close-upload" onclick="toggleUpload()" title="Tutup">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <form action="/admin/scripts/files/upload" method="POST" enctype="multipart/form-data" id="upload-form">
    <?= csrf_field() ?>
    <label class="drop-zone" for="script_file" id="drop-zone">
      <div class="drop-zone-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
      </div>
      <span class="drop-title" id="drop-title">Drag & drop file <strong>.ps1</strong> di sini</span>
      <span class="drop-sub">atau <u>klik untuk pilih file</u> dari komputer</span>
      <span class="drop-hint">Hanya .ps1 • Overwrite otomatis jika nama sama</span>
      <input type="file" name="script_file" id="script_file" accept=".ps1" required>
    </label>
    <div class="upload-confirm" id="upload-confirm">
      <div class="confirm-file">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <span id="confirm-filename">—</span>
        <span id="confirm-size" class="confirm-size"></span>
      </div>
      <div class="confirm-actions">
        <button type="submit" class="btn btn-primary">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          Upload Sekarang
        </button>
        <button type="button" class="btn btn-secondary" onclick="resetUpload()">Pilih Ulang</button>
      </div>
    </div>
  </form>
</div>

<!-- File List -->
<?php if ($fileCount === 0): ?>
  <div class="files-empty-state">
    <div class="empty-icon-wrap">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
    </div>
    <h3>Belum ada file</h3>
    <p>Upload file .ps1 pertama Anda menggunakan tombol di atas.</p>
  </div>
<?php else: ?>
  <div class="files-grid">
    <?php foreach ($ps1Files as $f):
      $cmdPs  = "irm {$f['url']} | iex";
      $cmdCmd = "powershell -ExecutionPolicy Bypass -Command \"irm '{$f['url']}' | iex\"";
    ?>
      <div class="file-card">
        <!-- Header: icon + nama + meta -->
        <div class="file-card-top">
          <div class="file-card-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><polyline points="10 13 8 15 10 17"/><polyline points="14 13 16 15 14 17"/></svg>
          </div>
          <div class="file-card-meta">
            <div class="file-card-name"><?= esc($f['name']) ?></div>
            <div class="file-card-info">
              <span><?= number_format($f['size'] / 1024, 1) ?> KB</span>
              <span class="meta-sep">•</span>
              <span><?= esc($f['modified']) ?></span>
            </div>
          </div>
        </div>

        <!-- URL -->
        <div class="cmd-row cmd-row-url">
          <span class="cmd-badge badge-url">URL</span>
          <code class="cmd-text"><?= esc($f['url']) ?></code>
          <button class="btn-copy-cmd" data-copy="<?= esc($f['url']) ?>" title="Salin URL">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
          </button>
        </div>

        <!-- PowerShell install command -->
        <div class="cmd-row cmd-row-ps">
          <span class="cmd-badge badge-ps">PS</span>
          <code class="cmd-text"><?= esc($cmdPs) ?></code>
          <button class="btn-copy-cmd" data-copy="<?= esc($cmdPs) ?>" title="Salin PowerShell Command">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
          </button>
        </div>

        <!-- CMD install command -->
        <div class="cmd-row cmd-row-cmd">
          <span class="cmd-badge badge-cmd">CMD</span>
          <code class="cmd-text"><?= esc($cmdCmd) ?></code>
          <button class="btn-copy-cmd" data-copy="<?= esc($cmdCmd) ?>" title="Salin CMD Command">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
          </button>
        </div>

        <!-- Actions -->
        <div class="file-card-actions">
          <button class="btn btn-secondary btn-sm btn-preview"
                  data-url="<?= esc($f['url']) ?>"
                  data-name="<?= esc($f['name']) ?>"
                  onclick="openPreview(this)">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            Preview
          </button>
          <form method="POST" action="/admin/scripts/files/<?= esc(urlencode($f['name'])) ?>/generate"
                onsubmit="return confirmGenerate(this, '<?= esc($f['name']) ?>')">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-generate btn-sm">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Generate
            </button>
          </form>
          <form method="POST" action="/admin/scripts/files/<?= esc(urlencode($f['name'])) ?>/delete"
                onsubmit="return confirmDeleteFile(this, '<?= esc($f['name']) ?>')">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-danger btn-sm">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
              Hapus
            </button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<!-- ── Preview Modal ── -->
<div class="modal-backdrop" id="preview-modal" onclick="closePreviewOnBackdrop(event)">
  <div class="modal-dialog">
    <!-- Modal Header -->
    <div class="modal-header">
      <div class="modal-title-group">
        <div class="modal-file-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><polyline points="10 13 8 15 10 17"/><polyline points="14 13 16 15 14 17"/></svg>
        </div>
        <div>
          <div class="modal-filename" id="modal-filename">—</div>
          <div class="modal-filemeta" id="modal-filemeta"></div>
        </div>
      </div>
      <div class="modal-actions">
        <button class="modal-action-btn" id="btn-modal-copy" title="Salin semua isi script" onclick="copyModalContent()">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
          Salin
        </button>
        <a class="modal-action-btn" id="btn-modal-open" href="#" target="_blank" title="Buka di tab baru">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          Buka
        </a>
        <button class="modal-close-btn" onclick="closePreview()" title="Tutup (Esc)">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </div>

    <!-- Modal Body -->
    <div class="modal-body" id="modal-body">
      <!-- Loading state -->
      <div class="modal-loading" id="modal-loading">
        <div class="loading-spinner"></div>
        <span>Memuat script...</span>
      </div>
      <!-- Error state -->
      <div class="modal-error" id="modal-error" style="display:none">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span id="modal-error-msg">Gagal memuat file.</span>
      </div>
      <!-- Code view -->
      <div class="code-view" id="code-view" style="display:none">
        <div class="code-line-numbers" id="line-numbers"></div>
        <pre class="code-content" id="code-content"></pre>
      </div>
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
      <span class="modal-line-count" id="modal-line-count"></span>
      <div class="modal-footer-right">
        <span class="modal-lang-badge">PowerShell</span>
      </div>
    </div>
  </div>
</div>

<style>
  /* ── Page Header ── */
  .page-header {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 16px;
    margin-bottom: 28px;
  }
  .page-header h2 {
    font-family: var(--font-display); font-size: 1.5rem;
    font-weight: 800; color: var(--text);
  }
  .page-subtitle { font-size: 0.85rem; color: var(--muted); margin-top: 4px; }
  .page-subtitle code {
    font-family: var(--font-mono); font-size: 0.8rem;
    background: rgba(99,102,241,0.08); color: #818cf8;
    padding: 1px 6px; border-radius: 4px;
  }

  /* ── Stats Row ── */
  .file-stats-row {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 16px; margin-bottom: 28px;
  }
  .file-stat-card {
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 14px; padding: 18px 20px;
    display: flex; align-items: center; gap: 14px;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
  }
  .file-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
  .fsc-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .fsc-blue   { background: rgba(59,130,246,0.08); color: #3b82f6; }
  .fsc-purple { background: rgba(99,102,241,0.08); color: #6366f1; }
  .fsc-green  { background: rgba(16,185,129,0.08); color: #10b981; }
  .fsc-value  { font-family: var(--font-mono); font-size: 1.1rem; font-weight: 700; color: var(--text); }
  .fsc-label  { font-size: 0.72rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.7px; margin-top: 2px; }

  /* ── Upload Panel ── */
  .upload-panel {
    display: none; background: var(--card-bg);
    border: 1px solid var(--border); border-radius: 16px;
    overflow: hidden; margin-bottom: 28px;
    animation: fadeSlide 0.25s ease;
  }
  .upload-panel.open { display: block; }
  @keyframes fadeSlide {
    from { opacity:0; transform: translateY(-10px); }
    to   { opacity:1; transform: translateY(0); }
  }
  .upload-panel-header {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 20px; border-bottom: 1px solid var(--border);
    font-weight: 700; font-size: 0.9rem; color: var(--text);
  }
  .close-upload {
    margin-left: auto; background: none; border: none;
    color: var(--muted); cursor: pointer; padding: 4px;
    border-radius: 6px; display: flex; align-items: center;
    transition: color 0.2s, background 0.2s;
  }
  .close-upload:hover { color: var(--danger); background: rgba(239,68,68,0.06); }

  .drop-zone {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    padding: 40px 24px; margin: 20px; cursor: pointer;
    border: 2px dashed var(--border-mid); border-radius: 12px;
    text-align: center; transition: all 0.2s ease;
  }
  .drop-zone.drag-over, .drop-zone:hover {
    border-color: var(--primary); background: rgba(59,130,246,0.04);
  }
  .drop-zone input[type="file"] { display: none; }
  .drop-zone-icon {
    width: 64px; height: 64px; background: rgba(99,102,241,0.08);
    border: 1px solid rgba(99,102,241,0.15); border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    color: #6366f1; margin-bottom: 8px;
    transition: transform 0.25s ease;
  }
  .drop-zone:hover .drop-zone-icon { transform: scale(1.06) translateY(-2px); }
  .drop-title { font-size: 0.95rem; font-weight: 600; color: var(--text); }
  .drop-sub   { font-size: 0.85rem; color: var(--muted); }
  .drop-hint  { font-size: 0.75rem; color: var(--muted); opacity: 0.65; }

  .upload-confirm {
    display: none; flex-direction: column; gap: 14px;
    padding: 0 20px 20px;
  }
  .upload-confirm.visible { display: flex; }
  .confirm-file {
    display: flex; align-items: center; gap: 10px;
    background: rgba(99,102,241,0.06); border: 1px solid rgba(99,102,241,0.12);
    border-radius: 10px; padding: 12px 16px;
    font-family: var(--font-mono); font-size: 0.85rem; color: var(--text);
  }
  .confirm-size { color: var(--muted); font-size: 0.78rem; margin-left: auto; }
  .confirm-actions { display: flex; gap: 10px; }

  /* ── Files Grid ── */
  .files-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
  }
  .file-card {
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 16px; padding: 20px;
    display: flex; flex-direction: column; gap: 14px;
    transition: all 0.25s ease; position: relative; overflow: hidden;
  }
  .file-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, #6366f1, #3b82f6);
    opacity: 0; transition: opacity 0.25s;
  }
  .file-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(0,0,0,0.12); border-color: rgba(99,102,241,0.2); }
  .file-card:hover::before { opacity: 1; }

  .file-card-top { display: flex; align-items: flex-start; gap: 12px; }
  .file-card-icon {
    width: 44px; height: 44px; flex-shrink: 0;
    background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.12);
    border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #6366f1;
  }
  .file-card-name {
    font-family: var(--font-mono); font-weight: 600;
    font-size: 0.9rem; color: var(--text); word-break: break-all;
  }
  .file-card-info {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.75rem; color: var(--muted); margin-top: 4px;
  }
  .meta-sep { opacity: 0.4; }

  /* ── Command Rows ── */
  .cmd-row {
    display: flex; align-items: center; gap: 8px;
    background: rgba(8,12,23,0.5); border: 1px solid var(--border);
    border-radius: 8px; padding: 7px 10px;
    transition: border-color 0.2s;
  }
  .cmd-row:hover { border-color: var(--border-mid); }

  .cmd-row-url  { border-left: 3px solid rgba(148,163,184,0.4); }
  .cmd-row-ps   { border-left: 3px solid rgba(59,130,246,0.6); }
  .cmd-row-cmd  { border-left: 3px solid rgba(251,146,60,0.6); }

  .cmd-badge {
    font-size: 0.62rem; font-weight: 800; letter-spacing: 0.8px;
    text-transform: uppercase; padding: 2px 7px; border-radius: 5px;
    flex-shrink: 0; font-family: var(--font-mono);
  }
  .badge-url  { background: rgba(148,163,184,0.1); color: #94a3b8; border: 1px solid rgba(148,163,184,0.15); }
  .badge-ps   { background: rgba(59,130,246,0.12); color: #3b82f6;  border: 1px solid rgba(59,130,246,0.2); }
  .badge-cmd  { background: rgba(251,146,60,0.12);  color: #fb923c;  border: 1px solid rgba(251,146,60,0.2); }

  .cmd-text {
    font-family: var(--font-mono); font-size: 0.73rem;
    color: #94a3b8; overflow: hidden; text-overflow: ellipsis;
    white-space: nowrap; flex: 1;
  }

  .btn-copy-cmd {
    background: none; border: none; color: var(--muted);
    cursor: pointer; flex-shrink: 0; padding: 3px 5px;
    border-radius: 5px; display: flex; align-items: center;
    transition: color 0.2s, background 0.2s;
  }
  .btn-copy-cmd:hover  { color: var(--primary); background: rgba(59,130,246,0.08); }
  .btn-copy-cmd.copied { color: var(--success); }

  .file-card-actions {
    display: flex; gap: 8px; align-items: center;
    border-top: 1px solid var(--border); padding-top: 14px;
  }
  .file-card-actions .btn { flex: 1; justify-content: center; }
  .file-card-actions form { flex: 1; margin: 0; }
  .file-card-actions form .btn { width: 100%; }

  .btn-generate {
    background: rgba(16,185,129,0.07);
    color: #10b981;
    border: 1px solid rgba(16,185,129,0.15);
  }
  .btn-generate:hover {
    background: rgba(16,185,129,0.16);
    color: #34d399;
    border-color: rgba(16,185,129,0.3);
  }
  [data-theme="light"] .btn-generate {
    background: rgba(5,150,105,0.07);
    color: #059669;
    border-color: rgba(5,150,105,0.15);
  }
  [data-theme="light"] .btn-generate:hover {
    background: rgba(5,150,105,0.13);
    color: #047857;
  }

  /* Empty state */
  .files-empty-state {
    display: flex; flex-direction: column; align-items: center;
    gap: 12px; padding: 80px 24px; text-align: center;
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 18px; max-width: 480px; margin: 0 auto;
  }
  .empty-icon-wrap {
    width: 76px; height: 76px; border-radius: 20px;
    background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.12);
    display: flex; align-items: center; justify-content: center; color: #6366f1;
  }
  .files-empty-state h3 { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: var(--text); }
  .files-empty-state p  { font-size: 0.85rem; color: var(--muted); }

  /* Light mode */
  [data-theme="light"] .cmd-row { background: #f1f5f9; }
  [data-theme="light"] .cmd-text { color: #475569; }
  [data-theme="light"] .badge-url { background: rgba(100,116,139,0.08); }
  [data-theme="light"] .badge-ps  { background: rgba(37,99,235,0.07); color: #2563eb; border-color: rgba(37,99,235,0.15); }
  [data-theme="light"] .badge-cmd { background: rgba(234,88,12,0.07);  color: #ea580c;  border-color: rgba(234,88,12,0.15); }
  [data-theme="light"] .fsc-blue   { background: rgba(37,99,235,0.07); color: #2563eb; }
  [data-theme="light"] .fsc-purple { background: rgba(79,70,229,0.07); color: #4f46e5; }
  [data-theme="light"] .fsc-green  { background: rgba(5,150,105,0.07); color: #059669; }
  [data-theme="light"] .file-card:hover { box-shadow: 0 10px 28px rgba(0,0,0,0.06); }
  [data-theme="light"] .drop-zone.drag-over,
  [data-theme="light"] .drop-zone:hover { background: rgba(37,99,235,0.03); }

  @media (max-width: 900px) {
    .file-stats-row { grid-template-columns: 1fr 1fr; }
  }
  @media (max-width: 600px) {
    .file-stats-row { grid-template-columns: 1fr; }
    .files-grid { grid-template-columns: 1fr; }
  }

  /* ── Preview Modal ── */
  .modal-backdrop {
    position: fixed; inset: 0; z-index: 1000;
    background: rgba(0,0,0,0.65);
    backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
    opacity: 0; pointer-events: none;
    transition: opacity 0.2s ease;
  }
  .modal-backdrop.open {
    opacity: 1; pointer-events: all;
  }
  .modal-dialog {
    background: var(--card-bg);
    border: 1px solid var(--border-mid);
    border-radius: 18px;
    width: 100%; max-width: 860px;
    max-height: 85vh;
    display: flex; flex-direction: column;
    box-shadow: 0 24px 60px rgba(0,0,0,0.4);
    transform: translateY(12px) scale(0.98);
    transition: transform 0.25s ease;
    overflow: hidden;
  }
  .modal-backdrop.open .modal-dialog {
    transform: translateY(0) scale(1);
  }

  .modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    flex-shrink: 0;
  }
  .modal-title-group { display: flex; align-items: center; gap: 12px; }
  .modal-file-icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.15);
    display: flex; align-items: center; justify-content: center; color: #6366f1;
    flex-shrink: 0;
  }
  .modal-filename {
    font-family: var(--font-mono); font-weight: 700;
    font-size: 0.9rem; color: var(--text);
  }
  .modal-filemeta { font-size: 0.72rem; color: var(--muted); margin-top: 2px; }

  .modal-actions { display: flex; align-items: center; gap: 6px; }
  .modal-action-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.05); border: 1px solid var(--border);
    border-radius: 8px; padding: 6px 12px;
    font-size: 0.78rem; font-weight: 600; color: var(--text-dim);
    cursor: pointer; text-decoration: none;
    transition: all 0.18s ease;
  }
  .modal-action-btn:hover { background: rgba(255,255,255,0.1); color: var(--text); border-color: var(--border-mid); }
  .modal-action-btn.success { color: var(--success); border-color: rgba(16,185,129,0.3); }

  .modal-close-btn {
    width: 32px; height: 32px; border-radius: 8px;
    background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--danger); cursor: pointer;
    transition: all 0.18s ease;
  }
  .modal-close-btn:hover { background: rgba(239,68,68,0.14); border-color: rgba(239,68,68,0.25); }

  .modal-body {
    flex: 1; overflow: hidden; position: relative;
    min-height: 200px;
  }

  /* Loading */
  .modal-loading {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 14px; color: var(--muted); font-size: 0.85rem;
  }
  .loading-spinner {
    width: 32px; height: 32px; border-radius: 50%;
    border: 3px solid var(--border);
    border-top-color: #6366f1;
    animation: spin 0.7s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* Error */
  .modal-error {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 10px; color: var(--danger); font-size: 0.85rem;
  }

  /* Code view */
  .code-view {
    display: flex; height: 100%; overflow: auto;
    font-family: var(--font-mono); font-size: 0.82rem; line-height: 1.7;
    max-height: calc(85vh - 120px);
  }
  .code-line-numbers {
    padding: 16px 12px 16px 16px;
    text-align: right; color: var(--muted);
    user-select: none; flex-shrink: 0;
    border-right: 1px solid var(--border);
    background: rgba(0,0,0,0.15);
    min-width: 52px;
    opacity: 0.5;
  }
  .code-line-numbers div { line-height: 1.7; }
  .code-content {
    flex: 1; margin: 0; padding: 16px 20px;
    overflow: visible; white-space: pre;
    color: #e2e8f0; background: transparent;
    font-family: var(--font-mono); font-size: 0.82rem; line-height: 1.7;
  }

  /* PS1 Syntax Highlight */
  .ps-keyword  { color: #c084fc; font-weight: 600; }  /* purple - keywords */
  .ps-cmdlet   { color: #60a5fa; }                     /* blue - cmdlets */
  .ps-string   { color: #86efac; }                     /* green - strings */
  .ps-comment  { color: #64748b; font-style: italic; } /* grey - comments */
  .ps-variable { color: #fcd34d; }                     /* yellow - variables */
  .ps-number   { color: #fb923c; }                     /* orange - numbers */
  .ps-param    { color: #f0abfc; }                     /* pink - parameters */
  .ps-operator { color: #94a3b8; }                     /* dim - operators */
  .ps-type     { color: #34d399; }                     /* teal - types/brackets */

  /* Modal Footer */
  .modal-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 20px; border-top: 1px solid var(--border);
    flex-shrink: 0;
  }
  .modal-line-count { font-size: 0.75rem; color: var(--muted); font-family: var(--font-mono); }
  .modal-footer-right { display: flex; align-items: center; gap: 8px; }
  .modal-lang-badge {
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
    background: rgba(99,102,241,0.1); color: #818cf8;
    border: 1px solid rgba(99,102,241,0.15); border-radius: 5px; padding: 2px 8px;
  }

  /* Light mode modal */
  [data-theme="light"] .modal-dialog { box-shadow: 0 24px 60px rgba(0,0,0,0.15); }
  [data-theme="light"] .modal-action-btn { background: rgba(0,0,0,0.03); color: #475569; }
  [data-theme="light"] .modal-action-btn:hover { background: rgba(0,0,0,0.06); color: #0f172a; }
  [data-theme="light"] .code-line-numbers { background: rgba(0,0,0,0.03); }
  [data-theme="light"] .code-content { color: #1e293b; }
  [data-theme="light"] .ps-keyword  { color: #7c3aed; }
  [data-theme="light"] .ps-cmdlet   { color: #1d4ed8; }
  [data-theme="light"] .ps-string   { color: #15803d; }
  [data-theme="light"] .ps-comment  { color: #94a3b8; }
  [data-theme="light"] .ps-variable { color: #b45309; }
  [data-theme="light"] .ps-number   { color: #c2410c; }
  [data-theme="light"] .ps-param    { color: #7e22ce; }

  /* Custom Scrollbar for Code View */
  .code-view::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }
  .code-view::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 4px;
  }
  .code-view::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
  }
  .code-view::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
  }

  /* Light mode scrollbar */
  [data-theme="light"] .code-view::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.03);
  }
  [data-theme="light"] .code-view::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.15);
  }
  [data-theme="light"] .code-view::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.25);
  }

  @media (max-width: 600px) {
    .modal-dialog { max-height: 95vh; border-radius: 14px; }
    .modal-actions .modal-action-btn span { display: none; }
  }
</style>

<script>
  function toggleUpload() {
    document.getElementById('upload-panel').classList.toggle('open');
  }

  function resetUpload() {
    document.getElementById('upload-form').reset();
    document.getElementById('upload-confirm').classList.remove('visible');
    document.getElementById('drop-title').innerHTML = 'Drag & drop file <strong>.ps1</strong> di sini';
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Flash messages
    <?php if (session()->has('file_msg')): ?>
    window.Toast && window.Toast.fire({ icon: 'success', title: '<?= addslashes(strip_tags(session('file_msg'))) ?>' });
    <?php endif; ?>
    <?php if (session()->has('file_error')): ?>
    Swal.fire({ icon: 'error', title: 'Gagal', html: '<?= addslashes(session('file_error')) ?>', confirmButtonText: 'Tutup' });
    <?php endif; ?>

    // File input
    const fileInput = document.getElementById('script_file');
    const confirm   = document.getElementById('upload-confirm');
    const fname     = document.getElementById('confirm-filename');
    const fsize     = document.getElementById('confirm-size');

    fileInput?.addEventListener('change', () => {
      if (fileInput.files.length > 0) {
        const f = fileInput.files[0];
        fname.textContent = f.name;
        fsize.textContent = (f.size / 1024).toFixed(1) + ' KB';
        confirm.classList.add('visible');
        document.getElementById('drop-title').innerHTML = 'File dipilih ✓';
        // Auto open upload panel
        document.getElementById('upload-panel').classList.add('open');
      }
    });

    // Drag & Drop
    const zone = document.getElementById('drop-zone');
    if (zone) {
      ['dragenter','dragover'].forEach(e => zone.addEventListener(e, ev => {
        ev.preventDefault(); zone.classList.add('drag-over');
      }));
      ['dragleave','drop'].forEach(e => zone.addEventListener(e, ev => {
        ev.preventDefault(); zone.classList.remove('drag-over');
      }));
      zone.addEventListener('drop', ev => {
        const files = ev.dataTransfer.files;
        if (files.length > 0) {
          fileInput.files = files;
          fileInput.dispatchEvent(new Event('change'));
        }
      });
    }

    // Copy buttons
    document.querySelectorAll('.btn-copy-cmd').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();
        navigator.clipboard.writeText(btn.dataset.copy).then(() => {
          const orig = btn.innerHTML;
          btn.classList.add('copied');
          btn.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>`;
          window.Toast && window.Toast.fire({ icon: 'success', title: 'Tersalin!' });
          setTimeout(() => { btn.classList.remove('copied'); btn.innerHTML = orig; }, 2000);
        });
      });
    });
  });

  function confirmDeleteFile(form, filename) {
    Swal.fire({
      title: 'Hapus File?',
      html: `File <code>${filename}</code> akan dihapus permanen dari server.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus',
      cancelButtonText: 'Batal',
      customClass: {
        popup: 'custom-swal',
        confirmButton: 'custom-swal-confirm',
        cancelButton: 'custom-swal-cancel'
      },
      buttonsStyling: false,
    }).then(r => { if (r.isConfirmed) form.submit(); });
    return false;
  }

  function confirmGenerate(form, filename) {
    const basename = filename.replace(/\.ps1$/i, '');
    Swal.fire({
      title: 'Generate Script?',
      html: `File <code>${filename}</code> akan diubah menjadi Script baru di daftar.<br><br>Slug yang akan digunakan: <code>${basename.toLowerCase().replace(/[_\s]+/g,'-')}</code>`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Generate!',
      cancelButtonText: 'Batal',
      customClass: {
        popup: 'custom-swal',
        confirmButton: 'custom-swal-confirm',
        cancelButton: 'custom-swal-cancel'
      },
      buttonsStyling: false,
    }).then(r => { if (r.isConfirmed) form.submit(); });
    return false;
  }

  // ── Preview Modal ──────────────────────────────────────
  let _rawContent = '';

  function openPreview(btn) {
    const url  = btn.dataset.url;
    const name = btn.dataset.name;
    const modal = document.getElementById('preview-modal');

    // Set header info
    document.getElementById('modal-filename').textContent = name;
    document.getElementById('modal-filemeta').textContent = 'Memuat...';
    document.getElementById('btn-modal-open').href = url;

    // Reset states
    document.getElementById('modal-loading').style.display = '';
    document.getElementById('modal-error').style.display   = 'none';
    document.getElementById('code-view').style.display     = 'none';
    document.getElementById('modal-line-count').textContent = '';
    _rawContent = '';

    // Open modal
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';

    // Fetch file
    fetch(url, { cache: 'no-store' })
      .then(r => {
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.text();
      })
      .then(text => {
        _rawContent = text;
        const lines = text.split('\n');

        // Line numbers
        const lineNumEl = document.getElementById('line-numbers');
        lineNumEl.innerHTML = lines.map((_, i) =>
          `<div>${i + 1}</div>`
        ).join('');

        // Syntax highlighted content
        document.getElementById('code-content').innerHTML = highlightPs1(text);

        // Footer
        document.getElementById('modal-filemeta').textContent =
          `${lines.length} baris • ${(new Blob([text]).size / 1024).toFixed(1)} KB`;
        document.getElementById('modal-line-count').textContent =
          `${lines.length} lines`;

        // Show code
        document.getElementById('modal-loading').style.display = 'none';
        document.getElementById('code-view').style.display     = 'flex';
      })
      .catch(err => {
        document.getElementById('modal-loading').style.display  = 'none';
        document.getElementById('modal-error').style.display    = 'flex';
        document.getElementById('modal-error-msg').textContent  = `Gagal memuat: ${err.message}`;
      });
  }

  function closePreview() {
    document.getElementById('preview-modal').classList.remove('open');
    document.body.style.overflow = '';
  }

  function closePreviewOnBackdrop(e) {
    if (e.target === document.getElementById('preview-modal')) closePreview();
  }

  function copyModalContent() {
    if (!_rawContent) return;
    const btn = document.getElementById('btn-modal-copy');
    navigator.clipboard.writeText(_rawContent).then(() => {
      btn.classList.add('success');
      const orig = btn.innerHTML;
      btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> Tersalin!`;
      window.Toast && window.Toast.fire({ icon: 'success', title: 'Script disalin!' });
      setTimeout(() => { btn.classList.remove('success'); btn.innerHTML = orig; }, 2000);
    });
  }

  // Escape key
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closePreview();
  });

  // ── PS1 Syntax Highlighter ──────────────────────────────
  function highlightPs1(code) {
    const keywords = [
      'function','param','if','else','elseif','foreach','for','while','do',
      'switch','return','break','continue','exit','throw','try','catch','finally',
      'begin','process','end','filter','workflow','class','enum','using',
      'in','not','and','or','is','as','new','true','false','null',
      'Write-Host','Write-Output','Write-Error','Write-Warning','Write-Verbose',
    ];
    const cmdletPattern = /\b([A-Z][a-z]+-[A-Z][a-zA-Z]+)\b/g;
    const kwPattern = new RegExp(`\\b(${keywords.join('|')})\\b`, 'gi');

    // Process line by line to handle comments correctly
    return code.split('\n').map(line => {
      // Escape HTML first
      let l = line
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');

      // Comment — rest of line after #
      const commentIdx = findCommentStart(line);
      let codePart = commentIdx >= 0 ? l.substring(0, commentIdx) : l;
      const commentPart = commentIdx >= 0
        ? `<span class="ps-comment">${l.substring(commentIdx)}</span>`
        : '';

      // Strings (single and double quoted)
      codePart = codePart.replace(/"([^"]*)"/g, (_, s) => `<span class="ps-string">"${s}"</span>`);
      codePart = codePart.replace(/'([^']*)'/g, (_, s) => `<span class="ps-string">'${s}'</span>`);

      // Variables
      codePart = codePart.replace(/(\$[a-zA-Z_][a-zA-Z0-9_]*)/g,
        (_, v) => `<span class="ps-variable">${v}</span>`);

      // Parameters (-Name)
      codePart = codePart.replace(/(?<=\s|^)(-[a-zA-Z][a-zA-Z0-9]*)/g,
        (_, p) => `<span class="ps-param">${p}</span>`);

      // Cmdlets (Verb-Noun)
      codePart = codePart.replace(cmdletPattern,
        (_, c) => `<span class="ps-cmdlet">${c}</span>`);

      // Keywords
      codePart = codePart.replace(kwPattern,
        (_, k) => `<span class="ps-keyword">${k}</span>`);

      // Numbers
      codePart = codePart.replace(/\b(\d+\.?\d*)\b/g,
        (_, n) => `<span class="ps-number">${n}</span>`);

      // Brackets / types
      codePart = codePart.replace(/(\[|\])/g,
        (_, b) => `<span class="ps-type">${b}</span>`);

      return codePart + commentPart;
    }).join('\n');
  }

  function findCommentStart(line) {
    let inSingle = false, inDouble = false;
    for (let i = 0; i < line.length; i++) {
      const c = line[i];
      if (c === "'" && !inDouble) inSingle = !inSingle;
      else if (c === '"' && !inSingle) inDouble = !inDouble;
      else if (c === '#' && !inSingle && !inDouble) return i;
    }
    return -1;
  }
</script>

<?= $this->endSection() ?>
