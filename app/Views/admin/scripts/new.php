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
          placeholder="contoh: curl -o fix.ps1 http://... && powershell -File fix.ps1"
          style="font-family: 'Fira Code', monospace;" value="<?= esc(old('command_cmd')) ?>" />
        <small style="color:var(--muted); font-size:0.78rem; margin-top:4px; display:block;">
          Opsional. Kosongkan jika perintah CMD sama dengan PowerShell. Atau klik <strong>Auto Generate CMD</strong> untuk generate otomatis dari perintah PowerShell.
        </small>
      </div>

      <!-- Linux Command Toggle -->
      <div class="form-group" style="grid-column: 1 / -1; margin-top: 10px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
          <div style="display:flex; align-items:center; gap:12px;">
            <label style="margin-bottom:0;">Fitur Command Linux</label>
            <label class="toggle-switch" style="width: 44px; height: 24px; position:relative; display:inline-block;">
              <input type="checkbox" id="enable_linux" name="enable_linux" value="1" <?= old('enable_linux') ? 'checked' : '' ?> onchange="toggleLinuxInput()" style="opacity:0; width:0; height:0;">
              <span class="slider" style="position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:rgba(148,163,184,0.3); transition:.4s; border-radius:34px;"></span>
              <style>
                #enable_linux:checked + .slider { background-color: #10b981; }
                .slider:before { position:absolute; content:""; height:18px; width:18px; left:3px; bottom:3px; background-color:white; transition:.4s; border-radius:50%; }
                #enable_linux:checked + .slider:before { transform: translateX(20px); }
              </style>
            </label>
            <span id="linux_status" style="font-size:0.8rem; font-weight:600; color:var(--muted);">Non Aktif</span>
          </div>
        </div>
        <div id="linux_input_container" style="display: <?= old('enable_linux') ? 'block' : 'none' ?>; margin-top: 10px; padding: 15px; border-radius: 12px; background: rgba(16,185,129,0.05); border: 1px solid rgba(16,185,129,0.15);">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
            <label for="command_linux" style="color: #10b981; margin-bottom:0;">Perintah Linux (Bash / cURL)</label>
            <button type="button" id="btn-gen-linux" onclick="generateLinuxFromPs()"
              style="display:inline-flex; align-items:center; gap:5px;
                     background:rgba(16,185,129,0.08); border:1px solid rgba(16,185,129,0.2);
                     color:#10b981; border-radius:8px; padding:4px 12px;
                     font-size:0.75rem; font-weight:700; cursor:pointer; font-family:var(--font-body);
                     transition:all 0.2s ease; letter-spacing:0.02em;"
              onmouseover="this.style.background='rgba(16,185,129,0.16)';this.style.borderColor='rgba(16,185,129,0.35)'"
              onmouseout="this.style.background='rgba(16,185,129,0.08)';this.style.borderColor='rgba(16,185,129,0.2)'">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>
              </svg>
              Auto Generate Linux
            </button>
          </div>
          <input id="command_linux" name="command_linux" type="text" class="form-control"
            value="<?= esc(old('command_linux')) ?>"
            placeholder="Contoh: curl -sL https://url | bash"
            style="font-family: 'Fira Code', monospace; border-color: rgba(16,185,129,0.3);" />
          <small style="color:var(--muted); font-size:0.78rem; margin-top:4px; display:block;">
            Perintah ini akan ditampilkan di tab tab LINUX (Terminal) pada halaman publik.
          </small>
        </div>
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

    let cmdResult = psCmd;
    if (psCmd.startsWith('irm') || psCmd.startsWith('Invoke-RestMethod')) {
      cmdResult = `powershell -ExecutionPolicy Bypass -Command "${psCmd}"`;
    }

    cmdInput.value = cmdResult;

    const origHTML = btn.innerHTML;
    btn.innerHTML  = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> Berhasil!`;
    btn.style.background    = 'rgba(251,146,60,0.12)';
    btn.style.borderColor   = 'rgba(251,146,60,0.3)';
    btn.style.color         = '#fb923c';
    setTimeout(() => {
      btn.innerHTML          = origHTML;
      btn.style.background   = 'rgba(251,146,60,0.08)';
      btn.style.borderColor  = 'rgba(251,146,60,0.2)';
      btn.style.color        = '#fb923c';
    }, 2000);

    cmdInput.style.borderColor   = 'rgba(16,185,129,0.6)';
    cmdInput.style.boxShadow     = '0 0 0 3px rgba(16,185,129,0.1)';
    setTimeout(() => {
      cmdInput.style.borderColor = '';
      cmdInput.style.boxShadow   = '';
    }, 2000);
  }

  function generateLinuxFromPs() {
    const psInput  = document.getElementById('command');
    const linuxInput = document.getElementById('command_linux');
    const btn      = document.getElementById('btn-gen-linux');
    const psCmd    = psInput ? psInput.value.trim() : '';

    if (!psCmd) {
      psInput && psInput.focus();
      psInput && (psInput.style.borderColor = 'rgba(239,68,68,0.6)');
      setTimeout(() => { psInput && (psInput.style.borderColor = ''); }, 2000);
      window.Toast && window.Toast.fire({ icon: 'warning', title: 'Isi dulu perintah PowerShell!' });
      return;
    }

    // Extract URL from 'irm <url> | iex'
    const match = psCmd.match(/(?:irm|Invoke-RestMethod)\s+(['"]?)(https?:\/\/[^\s'"]+)\1/i);
    let linuxResult = '';
    if (match && match[2]) {
      let url = match[2];
      url = url.replace(/\.ps1$/i, '.sh');
      linuxResult = `curl -sL ${url} | bash`;
    } else {
      // Fallback if not matching pattern
      const urlMatch = psCmd.match(/(https?:\/\/[^\s'"]+)/i);
      if (urlMatch && urlMatch[1]) {
        let url = urlMatch[1];
        url = url.replace(/\.ps1$/i, '.sh');
        linuxResult = `curl -sL ${url} | bash`;
      } else {
        window.Toast && window.Toast.fire({ icon: 'error', title: 'URL tidak ditemukan di perintah PowerShell!' });
        return;
      }
    }

    linuxInput.value = linuxResult;

    // Feedback animasi pada tombol
    const origHTML = btn.innerHTML;
    btn.innerHTML  = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> Berhasil!`;
    btn.style.background    = 'rgba(16,185,129,0.12)';
    btn.style.borderColor   = 'rgba(16,185,129,0.3)';
    btn.style.color         = '#10b981';
    setTimeout(() => {
      btn.innerHTML          = origHTML;
      btn.style.background   = 'rgba(16,185,129,0.08)';
      btn.style.borderColor  = 'rgba(16,185,129,0.2)';
      btn.style.color        = '#10b981';
    }, 2000);

    // Flash highlight pada input Linux
    linuxInput.style.borderColor   = 'rgba(16,185,129,0.6)';
    linuxInput.style.boxShadow     = '0 0 0 3px rgba(16,185,129,0.1)';
    setTimeout(() => {
      linuxInput.style.borderColor = 'rgba(16,185,129,0.3)';
      linuxInput.style.boxShadow   = '';
    }, 2000);
  }

  function toggleLinuxInput() {
    const cb = document.getElementById('enable_linux');
    const container = document.getElementById('linux_input_container');
    const status = document.getElementById('linux_status');
    
    if (cb.checked) {
      container.style.display = 'block';
      status.textContent = 'Aktif';
      status.style.color = '#10b981';
    } else {
      container.style.display = 'none';
      status.textContent = 'Non Aktif';
      status.style.color = 'var(--muted)';
    }
  }

  // Initial state
  document.addEventListener('DOMContentLoaded', () => {
    const cb = document.getElementById('enable_linux');
    if (cb) { toggleLinuxInput(); }
  });
</script>

<?php $this->endSection() ?>
