<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <title>404 — Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* ─────────────────────────────────────────────────
           DESIGN TOKENS — Light & Dark
        ───────────────────────────────────────────────── */
        :root,
        [data-theme="light"] {
            --body-bg:          #f5f0ff;
            --card-bg:          rgba(255, 255, 255, 0.50);
            --card-border:      rgba(255, 255, 255, 0.75);
            --card-shadow:      rgba(100, 60, 200, 0.13);
            --card-inset:       rgba(255, 255, 255, 0.90);
            --top-glow:         rgba(168, 85, 247, 0.65);
            --title-color:      #1e1040;
            --subtitle-color:   #6b7280;
            --eyebrow-color:    rgba(139, 60, 230, 0.85);
            --divider-line:     rgba(0, 0, 0, 0.09);
            --pill-bg:          rgba(255, 255, 255, 0.65);
            --pill-border:      rgba(0, 0, 0, 0.08);
            --pill-text:        #9ca3af;
            --btn2-bg:          rgba(255, 255, 255, 0.75);
            --btn2-border:      rgba(0, 0, 0, 0.11);
            --btn2-text:        #374151;
            --btn2-hover-bg:    rgba(255, 255, 255, 0.97);
            --debug-bg:         rgba(15, 10, 30, 0.05);
            --debug-border:     rgba(168, 85, 247, 0.22);
            --debug-label-bg:   rgba(248, 244, 255, 0.97);
            --debug-text:       #4b5563;
            --toggle-bg:        rgba(255, 255, 255, 0.65);
            --toggle-border:    rgba(0, 0, 0, 0.12);
            --toggle-text:      #374151;
            --blob-opacity:     0.50;
            /* blob colours */
            --b1: #a855f7;
            --b2: #ec4899;
            --b3: #3b82f6;
            --b4: #f97316;
        }

        [data-theme="dark"] {
            --body-bg:          #0d0a1a;
            --card-bg:          rgba(20, 14, 40, 0.60);
            --card-border:      rgba(255, 255, 255, 0.08);
            --card-shadow:      rgba(50, 0, 130, 0.35);
            --card-inset:       rgba(255, 255, 255, 0.05);
            --top-glow:         rgba(168, 85, 247, 0.55);
            --title-color:      #e9d8ff;
            --subtitle-color:   #9ca3af;
            --eyebrow-color:    rgba(200, 140, 255, 0.85);
            --divider-line:     rgba(255, 255, 255, 0.07);
            --pill-bg:          rgba(255, 255, 255, 0.06);
            --pill-border:      rgba(255, 255, 255, 0.10);
            --pill-text:        #6b7280;
            --btn2-bg:          rgba(255, 255, 255, 0.07);
            --btn2-border:      rgba(255, 255, 255, 0.12);
            --btn2-text:        #d1d5db;
            --btn2-hover-bg:    rgba(255, 255, 255, 0.14);
            --debug-bg:         rgba(80, 0, 180, 0.08);
            --debug-border:     rgba(168, 85, 247, 0.30);
            --debug-label-bg:   #0d0a1a;
            --debug-text:       #9ca3af;
            --toggle-bg:        rgba(255, 255, 255, 0.07);
            --toggle-border:    rgba(255, 255, 255, 0.12);
            --toggle-text:      #d1d5db;
            --blob-opacity:     0.30;
            /* blob colours — slightly deeper for dark */
            --b1: #7c3aed;
            --b2: #be185d;
            --b3: #1d4ed8;
            --b4: #c2410c;
        }

        /* ─────────────────────────────────────────────────
           BASE
        ───────────────────────────────────────────────── */
        html {
            transition: background 0.4s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            padding: 1rem;
            transition: background 0.4s ease;
        }

        /* ─────────────────────────────────────────────────
           ANIMATED BLOBS
        ───────────────────────────────────────────────── */
        .blob-wrap {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: var(--blob-opacity);
            will-change: transform;
            transition: background 0.6s ease, opacity 0.6s ease;
        }

        .blob-1 { width:520px; height:520px; background:var(--b1); top:-120px; left:-120px; animation:drift1 14s ease-in-out infinite alternate; }
        .blob-2 { width:420px; height:420px; background:var(--b2); top:45%; right:-100px; animation:drift2 18s ease-in-out infinite alternate; }
        .blob-3 { width:360px; height:360px; background:var(--b3); bottom:-80px; left:28%; animation:drift3 16s ease-in-out infinite alternate; }
        .blob-4 { width:300px; height:300px; background:var(--b4); top:18%; left:55%; animation:drift4 12s ease-in-out infinite alternate; }

        @keyframes drift1 { from { transform:translate(0,0) scale(1); } to { transform:translate(90px,130px) scale(1.15); } }
        @keyframes drift2 { from { transform:translate(0,0) scale(1); } to { transform:translate(-110px,-80px) scale(1.2); } }
        @keyframes drift3 { from { transform:translate(0,0) scale(1); } to { transform:translate(70px,-100px) scale(0.9); } }
        @keyframes drift4 { from { transform:translate(0,0) scale(1); } to { transform:translate(-80px,70px) scale(1.1); } }

        /* ─────────────────────────────────────────────────
           THEME TOGGLE
        ───────────────────────────────────────────────── */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid var(--toggle-border);
            background: var(--toggle-bg);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            color: var(--toggle-text);
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            outline: none;
        }

        .theme-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 18px rgba(0,0,0,0.15);
        }

        .theme-toggle .icon { font-size: 1rem; transition: transform 0.5s ease; }
        .theme-toggle:hover .icon { transform: rotate(20deg); }

        /* ─────────────────────────────────────────────────
           FROSTED GLASS CARD
        ───────────────────────────────────────────────── */
        .card {
            position: relative;
            z-index: 1;
            width: min(90vw, 560px);
            padding: 3.5rem 3rem;
            background: var(--card-bg);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border: 1px solid var(--card-border);
            border-radius: 28px;
            box-shadow:
                0 8px 40px var(--card-shadow),
                0 2px 8px rgba(0,0,0,0.07),
                inset 0 1px 0 var(--card-inset);
            text-align: center;
            animation: card-in 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
            transition:
                background 0.4s ease,
                border-color 0.4s ease,
                box-shadow 0.4s ease;
        }

        /* Top shimmer line */
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 50%; transform: translateX(-50%);
            width: 55%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--top-glow), transparent);
            border-radius: 999px;
            transition: background 0.4s ease;
        }

        @keyframes card-in {
            from { opacity:0; transform:translateY(30px) scale(0.96); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }

        /* ─────────────────────────────────────────────────
           TYPOGRAPHY
        ───────────────────────────────────────────────── */
        .eyebrow {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--eyebrow-color);
            margin-bottom: 1.25rem;
            display: block;
            transition: color 0.4s ease;
        }

        .code {
            font-size: 6rem;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -4px;
            background: linear-gradient(135deg, #a855f7, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
            display: block;
            animation: pop-in 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s both;
        }

        @keyframes pop-in {
            from { opacity:0; transform:scale(0.72); }
            to   { opacity:1; transform:scale(1); }
        }

        .title {
            font-size: 1.55rem;
            font-weight: 700;
            color: var(--title-color);
            margin-bottom: 0.7rem;
            letter-spacing: -0.4px;
            transition: color 0.4s ease;
        }

        .subtitle {
            font-size: 0.97rem;
            color: var(--subtitle-color);
            line-height: 1.75;
            margin-bottom: 2.25rem;
            font-weight: 400;
            transition: color 0.4s ease;
        }

        /* ─────────────────────────────────────────────────
           DIVIDER
        ───────────────────────────────────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.75rem;
        }
        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--divider-line);
            transition: background 0.4s ease;
        }
        .divider-pill {
            font-size: 0.67rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--pill-text);
            background: var(--pill-bg);
            border: 1px solid var(--pill-border);
            border-radius: 999px;
            padding: 3px 14px;
            transition: all 0.4s ease;
        }

        /* ─────────────────────────────────────────────────
           BUTTONS
        ───────────────────────────────────────────────── */
        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0.78rem 2rem;
            border-radius: 14px;
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, #a855f7, #ec4899);
            color: #fff;
            box-shadow: 0 4px 18px rgba(168, 85, 247, 0.42);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 8px 30px rgba(168, 85, 247, 0.58);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0.78rem 1.5rem;
            border-radius: 14px;
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            background: var(--btn2-bg);
            border: 1px solid var(--btn2-border);
            color: var(--btn2-text);
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background: var(--btn2-hover-bg);
            transform: translateY(-2px);
            box-shadow: 0 5px 18px rgba(0,0,0,0.12);
        }

        /* ─────────────────────────────────────────────────
           DEV DEBUG PANEL
        ───────────────────────────────────────────────── */
        .debug-panel {
            margin-top: 2rem;
            background: var(--debug-bg);
            border: 1px solid var(--debug-border);
            border-radius: 14px;
            padding: 1.3rem 1.5rem;
            text-align: left;
            position: relative;
            transition: all 0.4s ease;
        }

        .debug-label {
            position: absolute;
            top: -10px;
            left: 16px;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #a855f7;
            background: var(--debug-label-bg);
            padding: 2px 10px;
            border-radius: 999px;
            border: 1px solid var(--debug-border);
            transition: background 0.4s ease;
        }

        .debug-content {
            font-family: 'Fira Code', 'Consolas', monospace;
            font-size: 0.82rem;
            color: var(--debug-text);
            line-height: 1.65;
            white-space: pre-wrap;
            word-break: break-word;
            transition: color 0.4s ease;
        }

        .debug-content .err-key { color: #a855f7; font-weight: 700; }

        /* ─────────────────────────────────────────────────
           RESPONSIVE
        ───────────────────────────────────────────────── */
        @media (max-width: 560px) {
            .card { padding: 2.5rem 1.75rem; }
            .code { font-size: 5rem; }
            .title { font-size: 1.3rem; }
            .actions { flex-direction: column; }
            .btn-primary, .btn-secondary { justify-content: center; }
        }
    </style>
</head>
<body>

    <!-- Theme Toggle -->
    <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark/light mode">
        <span class="icon" id="themeIcon">🌙</span>
        <span id="themeLabel">Dark Mode</span>
    </button>

    <!-- Animated Background Blobs -->
    <div class="blob-wrap" aria-hidden="true">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="blob blob-4"></div>
    </div>

    <!-- Frosted Glass Card -->
    <main class="card" role="main">

        <span class="eyebrow">Error &mdash; Not Found</span>
        <span class="code" aria-label="404">404</span>
        <h1 class="title">This page doesn&rsquo;t exist</h1>
        <p class="subtitle">
            The page you&rsquo;re looking for has been moved, deleted,<br>
            or simply never existed in the first place.
        </p>

        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-pill">What&rsquo;s next?</span>
            <div class="divider-line"></div>
        </div>

        <div class="actions">
            <a href="<?= rtrim(base_url(), '/') ?: '/' ?>" class="btn-primary">
                ← Back to Home
            </a>
            <a href="javascript:history.back()" class="btn-secondary">
                Go Back
            </a>
        </div>

        <?php if (ENVIRONMENT !== 'production') : ?>
            <div class="debug-panel" role="complementary" aria-label="Debug info">
                <span class="debug-label">Dev &middot; Exception Log</span>
                <div class="debug-content"><span class="err-key">message:</span> <?= nl2br(esc($message)) ?></div>
            </div>
        <?php endif; ?>

    </main>

    <script>
        (function () {
            const root    = document.documentElement;
            const btn     = document.getElementById('themeToggle');
            const icon    = document.getElementById('themeIcon');
            const label   = document.getElementById('themeLabel');
            const KEY     = 'error404Theme';

            // Apply saved or system preference
            const saved   = localStorage.getItem(KEY);
            const sysDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            let   current = saved ?? (sysDark ? 'dark' : 'light');

            function apply(theme) {
                root.setAttribute('data-theme', theme);
                if (theme === 'dark') {
                    icon.textContent  = '☀️';
                    label.textContent = 'Light Mode';
                } else {
                    icon.textContent  = '🌙';
                    label.textContent = 'Dark Mode';
                }
                localStorage.setItem(KEY, theme);
                current = theme;
            }

            apply(current);

            btn.addEventListener('click', function () {
                apply(current === 'light' ? 'dark' : 'light');
            });

            // Follow OS preference change if user hasn't manually set it
            window.matchMedia('(prefers-color-scheme: dark)')
                .addEventListener('change', function (e) {
                    if (!localStorage.getItem(KEY)) apply(e.matches ? 'dark' : 'light');
                });
        })();
    </script>

</body>
</html>
