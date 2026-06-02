<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php
  $total = count($scripts);
  $psCount   = count(array_filter($scripts, fn($s) => $s['tag'] === 'powershell'));
  $bashCount = count(array_filter($scripts, fn($s) => $s['tag'] === 'bash'));
  $cmdCount  = count(array_filter($scripts, fn($s) => $s['tag'] === 'cmd'));

  $tagClass = [
    'powershell' => 'tag-badge-ps',
    'bash'       => 'tag-badge-bash',
    'cmd'        => 'tag-badge-cmd',
  ];
?>

<!-- Stats row -->
<div class="stats-row">
  <div class="stat-card">
    <div class="stat-icon stat-icon-blue">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
    </div>
    <div>
      <div class="stat-value"><?= $total ?></div>
      <div class="stat-label">Total Scripts</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-blue">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
    </div>
    <div>
      <div class="stat-value"><?= $psCount ?></div>
      <div class="stat-label">PowerShell</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-green">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
    </div>
    <div>
      <div class="stat-value"><?= $bashCount ?></div>
      <div class="stat-label">Bash</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-purple">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
    </div>
    <div>
      <div class="stat-value"><?= $cmdCount ?></div>
      <div class="stat-label">CMD</div>
    </div>
  </div>
</div>

<!-- Page header -->
<div class="page-header">
  <div>
    <h2>Daftar Scripts</h2>
    <p class="page-subtitle"><?= $total ?> script tersedia &mdash; klik Edit untuk mengubah</p>
  </div>
  <a href="/admin/scripts/new" class="btn btn-primary">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Script
  </a>
</div>

<!-- Script cards grid -->
<?php if ($total === 0): ?>
  <div class="empty-state">
    <div class="empty-icon">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
    </div>
    <h3>Belum ada script</h3>
    <p>Mulai dengan menambahkan script pertama Anda.</p>
    <a href="/admin/scripts/new" class="btn btn-primary" style="margin-top: 16px;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Tambah Script
    </a>
  </div>
<?php else: ?>
  <div class="scripts-grid">
    <?php foreach ($scripts as $i => $s): ?>
      <div class="script-card">
        <div class="script-card-header">
          <div class="script-number">#<?= sprintf('%02d', $i + 1) ?></div>
          <span class="tag-badge <?= $tagClass[$s['tag']] ?? '' ?>"><?= esc($s['tag']) ?></span>
        </div>

        <h3 class="script-title"><?= esc($s['title']) ?></h3>
        <p class="script-desc"><?= esc(mb_strlen($s['description']) > 90 ? mb_substr($s['description'], 0, 90) . 'â€¦' : $s['description']) ?></p>

        <div class="script-command">
          <svg class="cmd-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          <code><?= esc($s['command']) ?></code>
          <button class="btn-copy-command" data-code="<?= esc($s['command']) ?>" title="Salin Perintah" aria-label="Salin">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
          </button>
        </div>

        <div class="script-meta">
          <div class="meta-slug">
            <a href="/scripts/<?= esc($s['slug']) ?>" target="_blank">
              /<?= esc($s['slug']) ?> â†—
            </a>
          </div>
          <div class="meta-order">urutan: <?= esc($s['sort_order']) ?></div>
        </div>

        <div class="script-actions">
          <a href="/admin/scripts/<?= esc($s['id']) ?>/edit" class="btn btn-secondary btn-sm">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit
          </a>
          <form method="POST" action="/admin/scripts/<?= esc($s['id']) ?>/delete" onsubmit="return confirmDelete(this)">
            <button type="submit" class="btn btn-danger btn-sm">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
              Hapus
            </button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<style>
  /* â”€â”€ Stats Row Redesign â”€â”€ */
  .stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 36px;
  }

  .stat-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  }

  .stat-card::after {
    content: '';
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: radial-gradient(circle, var(--glow-color, rgba(59, 130, 246, 0.12)) 0%, transparent 70%);
    top: -20px;
    right: -20px;
    filter: blur(15px);
    pointer-events: none;
    transition: all 0.3s ease;
  }

  .stat-card:hover {
    border-color: var(--border-mid);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
  }

  .stat-card:hover::after {
    transform: scale(1.2);
  }

  .stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: transform 0.3s ease;
  }

  .stat-card:hover .stat-icon {
    transform: scale(1.08) rotate(3deg);
  }

  .stat-icon-blue   { background: rgba(59,130,246,0.08); color: #3b82f6; --glow-color: rgba(59, 130, 246, 0.12); }
  .stat-icon-green  { background: rgba(16,185,129,0.08); color: #10b981; --glow-color: rgba(16, 185, 129, 0.12); }
  .stat-icon-purple { background: rgba(168,85,247,0.08); color: #8b5cf6; --glow-color: rgba(168, 85, 247, 0.12); }

  .stat-value {
    font-family: var(--font-display);
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 2px;
    color: var(--text);
  }

  .stat-label {
    font-size: 0.72rem;
    color: var(--muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }

  /* â”€â”€ Script Cards Grid Redesign â”€â”€ */
  .scripts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 22px;
  }

  .script-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
  }

  .script-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--primary), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .script-card:hover {
    border-color: rgba(59, 130, 246, 0.2);
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
  }

  .script-card:hover::before { opacity: 1; }

  .script-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .script-number {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--muted);
    font-family: var(--font-mono);
    background: rgba(255, 255, 255, 0.04);
    padding: 2px 8px;
    border-radius: 6px;
    border: 1px solid var(--border);
  }

  .script-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text);
    line-height: 1.35;
  }

  .script-desc {
    font-size: 0.85rem;
    color: var(--text-dim);
    line-height: 1.6;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  /* â”€â”€ Command Box â”€â”€ */
  .script-command {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    background: rgba(8, 12, 23, 0.6);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--muted);
    position: relative;
    overflow: hidden;
  }

  .script-command svg.cmd-icon {
    flex-shrink: 0;
    color: var(--primary);
  }

  .script-command code {
    font-family: var(--font-mono);
    font-size: 0.78rem;
    color: #cbd5e1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
    padding-right: 28px;
  }

  /* Copy button inside command box */
  .btn-copy-command {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%) scale(0.9);
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: #94a3b8;
    width: 26px;
    height: 26px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    opacity: 0;
  }

  .script-command:hover .btn-copy-command {
    opacity: 1;
    transform: translateY(-50%) scale(1);
  }

  .btn-copy-command:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
  }

  .btn-copy-command.copied {
    background: var(--success);
    color: white;
    border-color: var(--success);
  }

  .script-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.78rem;
    padding: 2px 0;
    border-bottom: 1px dashed var(--border);
    padding-bottom: 10px;
  }

  .meta-slug a {
    color: var(--primary);
    text-decoration: none;
    font-family: var(--font-mono);
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: opacity 0.2s;
  }

  .meta-slug a:hover {
    opacity: 0.8;
  }

  .meta-order {
    color: var(--muted);
    font-weight: 500;
  }

  .script-actions {
    display: flex;
    gap: 10px;
    align-items: center;
  }

  .script-actions form {
    margin: 0;
    flex: 1;
  }

  .script-actions .btn {
    justify-content: center;
    width: 100%;
  }

  /* â”€â”€ Redesign Empty State â”€â”€ */
  .empty-state {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 80px 24px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    max-width: 500px;
    margin: 40px auto;
  }

  .empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: rgba(59,130,246,0.08);
    border: 1px solid rgba(59,130,246,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    margin-bottom: 12px;
    transition: transform 0.3s ease;
  }

  .empty-state:hover .empty-icon {
    transform: scale(1.05) rotate(-3deg);
  }

  @media (max-width: 900px) {
    .stats-row { grid-template-columns: repeat(2, 1fr); }
  }

  @media (max-width: 600px) {
    .stats-row { grid-template-columns: 1fr 1fr; gap: 12px; }
    .scripts-grid { grid-template-columns: 1fr; }
  }

  /* â”€â”€ Light Mode Overrides â”€â”€ */
  [data-theme="light"] .stat-icon-blue   { background: rgba(37, 99, 235, 0.08); color: #2563eb; }
  [data-theme="light"] .stat-icon-green  { background: rgba(5, 150, 105, 0.08); color: #059669; }
  [data-theme="light"] .stat-icon-purple { background: rgba(124, 58, 237, 0.08); color: #7c3aed; }
  
  [data-theme="light"] .script-card:hover {
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    border-color: rgba(37, 99, 235, 0.2);
  }

  [data-theme="light"] .script-number {
    background: rgba(0, 0, 0, 0.02);
  }

  [data-theme="light"] .script-command {
    background: #f1f5f9;
  }

  [data-theme="light"] .script-command code {
    color: #475569;
  }

  [data-theme="light"] .btn-copy-command {
    background: rgba(0, 0, 0, 0.03);
    border-color: rgba(0, 0, 0, 0.06);
    color: #64748b;
  }

  [data-theme="light"] .empty-icon {
    background: rgba(37, 99, 235, 0.08);
    border-color: rgba(37, 99, 235, 0.15);
    color: #2563eb;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Quick command copy system with visual feedback
    document.querySelectorAll('.btn-copy-command').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        const code = btn.getAttribute('data-code') || '';
        navigator.clipboard.writeText(code).then(() => {
          const orig = btn.innerHTML;
          btn.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`;
          btn.classList.add('copied');
          
          // Trigger global SWAL Toast
          if (window.Toast) {
            window.Toast.fire({
              icon: 'success',
              title: 'Tersalin! Perintah berhasil disalin ke papan klip.'
            });
          }

          setTimeout(() => {
            btn.innerHTML = orig;
            btn.classList.remove('copied');
          }, 2000);
        });
      });
    });
  });
</script>

<?php $this->endSection() ?>

