<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="page-header">
  <h2>Tambah Script Baru</h2>
  <a href="/admin/scripts" class="btn btn-secondary">← Kembali</a>
</div>


<div class="card">
  <form method="POST" action="/admin/scripts/new">
    <div class="form-grid">
      <div class="form-group" style="grid-column: 1 / -1;">
        <label for="title">Judul Script *</label>
        <input id="title" name="title" type="text" class="form-control" required
          placeholder="contoh: Fix Deploy Synchronizer"
          oninput="autoSlug(this.value)" value="<?= esc(old('title')) ?>" />
      </div>
      <div class="form-group" style="grid-column: 1 / -1;">
        <label for="slug">Slug (URL) *</label>
        <input id="slug" name="slug" type="text" class="form-control" required
          placeholder="contoh: fix-deploy-synchronizer" value="<?= esc(old('slug')) ?>" />
        <small style="color:var(--muted); font-size:0.78rem; margin-top:4px; display:block;">
          URL: /scripts/<span id="slugPreview"><?= esc(old('slug') ?: '...') ?></span>
        </small>
      </div>
      <div class="form-group" style="grid-column: 1 / -1;">
        <label for="description">Deskripsi *</label>
        <textarea id="description" name="description" class="form-control" required
          placeholder="Jelaskan apa yang dilakukan script ini..."><?= esc(old('description')) ?></textarea>
      </div>
      <div class="form-group">
        <label for="tag">Tag / Bahasa *</label>
        <select id="tag" name="tag" class="form-control">
          <option value="powershell" <?= old('tag') === 'powershell' ? 'selected' : '' ?>>PowerShell</option>
          <option value="bash" <?= old('tag') === 'bash' ? 'selected' : '' ?>>Bash</option>
          <option value="cmd" <?= old('tag') === 'cmd' ? 'selected' : '' ?>>CMD</option>
        </select>
      </div>
      <div class="form-group">
        <label for="sort_order">Urutan Tampil</label>
        <input id="sort_order" name="sort_order" type="number" class="form-control" value="<?= esc(old('sort_order', '0')) ?>" min="0" />
      </div>
      <div class="form-group">
        <label for="icon">Ikon</label>
        <select id="icon" name="icon" class="form-control">
          <option value="tool" <?= old('icon') === 'tool' ? 'selected' : '' ?>>🔧 Tool (Wrench)</option>
          <option value="pen" <?= old('icon') === 'pen' ? 'selected' : '' ?>>✏️ Pen</option>
          <option value="package" <?= old('icon') === 'package' ? 'selected' : '' ?>>📦 Package</option>
          <option value="refresh" <?= old('icon') === 'refresh' ? 'selected' : '' ?>>🔄 Refresh</option>
          <option value="server" <?= old('icon') === 'server' ? 'selected' : '' ?>>🖥️ Server</option>
        </select>
      </div>
      <div class="form-group">
        <label for="icon_color">Warna Ikon</label>
        <select id="icon_color" name="icon_color" class="form-control">
          <option value="blue" <?= old('icon_color') === 'blue' ? 'selected' : '' ?>>🔵 Biru</option>
          <option value="purple" <?= old('icon_color') === 'purple' ? 'selected' : '' ?>>🟣 Ungu</option>
          <option value="green" <?= old('icon_color') === 'green' ? 'selected' : '' ?>>🟢 Hijau</option>
        </select>
      </div>
      <div class="form-group" style="grid-column: 1 / -1;">
        <label for="command">Perintah PowerShell *</label>
        <input id="command" name="command" type="text" class="form-control" required
          placeholder="contoh: irm http://... | iex"
          style="font-family: 'Fira Code', monospace;" value="<?= esc(old('command')) ?>" />
      </div>
      <div class="form-group" style="grid-column: 1 / -1;">
        <label for="command_cmd">Perintah CMD</label>
        <input id="command_cmd" name="command_cmd" type="text" class="form-control"
          placeholder="contoh: curl -o fix.ps1 http://... && powershell -File fix.ps1"
          style="font-family: 'Fira Code', monospace;" value="<?= esc(old('command_cmd')) ?>" />
        <small style="color:var(--muted); font-size:0.78rem; margin-top:4px; display:block;">
          Opsional. Kosongkan jika perintah CMD sama dengan PowerShell.
        </small>
      </div>
    </div>

    <div style="display:flex; gap:12px; margin-top:8px;">
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Simpan Script
      </button>
      <a href="/admin/scripts" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>

<script>
  function toSlug(str) {
    return str.toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-');
  }
  window.autoSlug = function(val) {
    const slug = toSlug(val);
    document.getElementById('slug').value = slug;
    document.getElementById('slugPreview').textContent = slug || '...';
  };
  document.getElementById('slug')?.addEventListener('input', (e) => {
    document.getElementById('slugPreview').textContent = e.target.value || '...';
  });
</script>

<?php $this->endSection() ?>
