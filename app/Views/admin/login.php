<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin | ScriptHub</title>
  <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet" />

  <script>
    (function () {
      const saved = localStorage.getItem('theme');
      const preferred = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', saved || preferred);
    })();
  </script>

  <style>
    /* Baseline */
    :root,
    [data-theme="dark"] {
      --bg: #0b0f19;
      --text: #f1f5f9;
    }
    [data-theme="light"] {
      --bg: #f8fafc;
      --text: #0f172a;
    }
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: var(--bg);
      color: var(--text);
    }
    * { box-sizing: border-box; }

    .login-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 20px;
      position: relative;
      overflow: hidden;
    }

    .grid-overlay {
      position: absolute;
      inset: 0;
      background-image: 
        linear-gradient(rgba(255, 255, 255, 0.015) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
      background-size: 40px 40px;
      background-position: center center;
      pointer-events: none;
      z-index: 0;
    }

    .login-glow-blue {
      position: absolute;
      width: 450px;
      height: 450px;
      background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
      top: 15%;
      left: 20%;
      z-index: 0;
      pointer-events: none;
      filter: blur(40px);
    }

    .login-glow-orange {
      position: absolute;
      width: 450px;
      height: 450px;
      background: radial-gradient(circle, rgba(249, 115, 22, 0.08) 0%, transparent 70%);
      bottom: 15%;
      right: 20%;
      z-index: 0;
      pointer-events: none;
      filter: blur(40px);
    }

    .login-card-glow {
      position: relative;
      width: 100%;
      max-width: 440px;
      border-radius: 24px;
      padding: 1px;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.03) 50%, rgba(59, 130, 246, 0.15) 100%);
      z-index: 1;
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), 0 0 100px rgba(59, 130, 246, 0.05);
    }

    .login-card {
      background: rgba(13, 18, 30, 0.85);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border-radius: 23px;
      padding: 52px 40px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .login-logo-container {
      display: inline-flex;
      padding: 4px;
      border-radius: 20px;
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.3) 0%, rgba(249, 115, 22, 0.15) 100%);
      margin-bottom: 24px;
    }

    .login-logo {
      width: 52px;
      height: 52px;
      background: #0b0f19;
      color: #60a5fa;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid rgba(255, 255, 255, 0.05);
      box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.05);
    }

    .login-title {
      font-family: 'Outfit', sans-serif;
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 8px;
      letter-spacing: -0.02em;
      background: linear-gradient(to right, #ffffff, #e2e8f0);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .subtitle {
      color: #94a3b8;
      font-size: 0.9rem;
      margin-bottom: 36px;
      font-family: 'Poppins', sans-serif;
    }

    .error-box {
      background: rgba(239, 68, 68, 0.08);
      border: 1px solid rgba(239, 68, 68, 0.25);
      color: #fca5a5;
      border-radius: 12px;
      padding: 12px 16px;
      font-size: 0.88rem;
      margin-bottom: 24px;
      text-align: left;
      display: flex;
      align-items: center;
      gap: 10px;
      animation: shake 0.4s ease;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-6px); }
      75% { transform: translateX(6px); }
    }

    .login-form {
      text-align: left;
    }

    .field {
      margin-bottom: 24px;
    }

    .field label {
      display: block;
      font-size: 0.8rem;
      font-weight: 600;
      color: #94a3b8;
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-family: 'Poppins', sans-serif;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 14px;
      color: #475569;
      transition: color 0.2s ease;
      pointer-events: none;
    }

    .field input {
      width: 100%;
      background: rgba(11, 15, 25, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      padding: 12px 16px 12px 42px;
      color: #f8fafc;
      font-size: 0.95rem;
      font-family: 'Poppins', sans-serif;
      outline: none;
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .field input.has-right-icon { padding-right: 42px; }

    .field input:focus {
      border-color: rgba(59, 130, 246, 0.8);
      background: rgba(11, 15, 25, 0.9);
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15), 0 4px 20px rgba(59, 130, 246, 0.1);
    }

    .input-wrapper:focus-within .input-icon { color: #60a5fa; }

    .password-toggle-btn {
      position: absolute;
      right: 14px;
      background: none;
      border: none;
      color: #475569;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 4px;
      transition: color 0.2s ease;
    }
    .password-toggle-btn:hover { color: #94a3b8; }
    .icon-hidden { display: none; }

    .btn-login {
      width: 100%;
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: white;
      border: none;
      border-radius: 12px;
      padding: 14px 20px;
      font-size: 0.95rem;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
      margin-top: 12px;
      position: relative;
      overflow: hidden;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: 0.5s;
    }
    .btn-login:hover::before { left: 100%; }
    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(59, 130, 246, 0.35);
      background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    }
    .btn-login:active { transform: translateY(1px); }
    .btn-login:hover .arrow-icon { transform: translateX(3px); }
    .arrow-icon { transition: transform 0.2s ease; }

    .back-home {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 32px;
      color: #64748b;
      font-size: 0.85rem;
      text-decoration: none;
      font-family: 'Poppins', sans-serif;
      transition: color 0.2s ease, transform 0.2s ease;
    }
    .back-home:hover {
      color: #f8fafc;
      transform: translateX(-2px);
    }

    @media (max-width: 480px) {
      .login-card { padding: 40px 24px; }
    }

    /* Light mode overrides */
    [data-theme="light"] .login-wrapper { background: transparent; }
    [data-theme="light"] .grid-overlay {
      background-image:
        linear-gradient(rgba(0, 0, 0, 0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 0, 0, 0.04) 1px, transparent 1px);
    }
    [data-theme="light"] .login-glow-blue { background: radial-gradient(circle, rgba(37, 99, 235, 0.10) 0%, transparent 70%); }
    [data-theme="light"] .login-glow-orange { background: radial-gradient(circle, rgba(249, 115, 22, 0.06) 0%, transparent 70%); }
    [data-theme="light"] .login-card-glow {
      background: linear-gradient(135deg, rgba(0,0,0,0.08) 0%, rgba(0,0,0,0.02) 50%, rgba(37,99,235,0.12) 100%);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.10), 0 0 60px rgba(37, 99, 235, 0.05);
    }
    [data-theme="light"] .login-card {
      background: rgba(255, 255, 255, 0.90);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
    }
    [data-theme="light"] .login-logo {
      background: #f0f4f8;
      border-color: rgba(0, 0, 0, 0.08);
      box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.04);
    }
    [data-theme="light"] .login-title {
      background: linear-gradient(to right, #0f172a, #334155);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    [data-theme="light"] .subtitle,
    [data-theme="light"] .field label,
    [data-theme="light"] .back-home { color: #475569; }
    [data-theme="light"] .back-home:hover { color: #0f172a; }
    [data-theme="light"] .field input {
      background: rgba(241, 245, 249, 0.80);
      border-color: rgba(0, 0, 0, 0.10);
      color: #0f172a;
    }
    [data-theme="light"] .field input::placeholder { color: #94a3b8; }
    [data-theme="light"] .field input:focus {
      background: #ffffff;
      border-color: rgba(37, 99, 235, 0.7);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12), 0 4px 16px rgba(37, 99, 235, 0.08);
    }
    [data-theme="light"] .input-icon,
    [data-theme="light"] .password-toggle-btn { color: #94a3b8; }
    [data-theme="light"] .input-wrapper:focus-within .input-icon { color: #2563eb; }
    [data-theme="light"] .error-box {
      background: rgba(220, 38, 38, 0.06);
      border-color: rgba(220, 38, 38, 0.20);
      color: #dc2626;
    }
  </style>
</head>
<body>

  <div class="login-wrapper fade-up">
    <!-- Grid pattern background for login page -->
    <div class="grid-overlay"></div>
    <!-- Decorative background glow -->
    <div class="login-glow-orange"></div>
    <div class="login-glow-blue"></div>

    <div class="login-card-glow">
      <div class="login-card">
        <div class="login-logo-container">
          <div class="login-logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="16 18 22 12 16 6"/>
              <polyline points="8 6 2 12 8 18"/>
            </svg>
          </div>
        </div>
        
        <h1 class="login-title">Admin Portal</h1>
        <p class="subtitle">Masuk untuk mengelola repositori ScriptHub</p>



        <form method="POST" class="login-form" action="/admin/login">
          <div class="field">
            <label for="username">Username</label>
            <div class="input-wrapper">
              <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              <input id="username" name="username" type="text" placeholder="admin" required autocomplete="username" class="has-left-icon" value="<?= esc(old('username')) ?>" />
            </div>
          </div>
          <div class="field">
            <label for="password">Password</label>
            <div class="input-wrapper">
              <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
              <input id="password" name="password" type="password" placeholder="••••••••" required autocomplete="current-password" class="has-left-icon has-right-icon" />
              <button type="button" id="togglePassword" class="password-toggle-btn" aria-label="Tampilkan password">
                <svg id="eyeOpenIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                <svg id="eyeClosedIcon" class="icon-hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                  <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
              </button>
            </div>
          </div>
          <button type="submit" class="btn-login">
            <span>Masuk Sekarang</span>
            <svg class="arrow-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <line x1="5" y1="12" x2="19" y2="12"/>
              <polyline points="12 5 19 12 12 19"/>
            </svg>
          </button>
        </form>

        <a href="/" class="back-home">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
          </svg>
          Kembali ke Beranda
        </a>
      </div>
    </div>
  </div>

  <script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpenIcon');
    const eyeClosed = document.getElementById('eyeClosedIcon');

    toggleBtn?.addEventListener('click', () => {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen?.classList.add('icon-hidden');
        eyeClosed?.classList.remove('icon-hidden');
      } else {
        passwordInput.type = 'password';
        eyeOpen?.classList.remove('icon-hidden');
        eyeClosed?.classList.add('icon-hidden');
      }
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?= $this->include('components/swal_init') ?>
</body>
</html>
