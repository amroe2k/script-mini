<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Errors.whoops') ?> — ScriptHub</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">

    <!-- Anti-flash: apply saved/preferred theme before first paint -->
    <script>
        (function () {
            var saved = localStorage.getItem('whoopsTheme');
            var sys   = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', saved || sys);
        })();
    </script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ─────────────────────────────────────────────
           DESIGN TOKENS
        ───────────────────────────────────────────── */
        :root,
        [data-theme="light"] {
            --body-bg:          #fff5f0;
            --card-bg:          rgba(255, 255, 255, 0.52);
            --card-border:      rgba(255, 255, 255, 0.78);
            --card-shadow:      rgba(200, 40, 0, 0.12);
            --card-inset:       rgba(255, 255, 255, 0.92);
            --top-glow:         rgba(239, 68, 68, 0.65);
            --title-color:      #3b0a00;
            --sub-color:        #6b7280;
            --eyebrow-color:    rgba(200, 30, 30, 0.85);
            --divider-line:     rgba(0,0,0,0.08);
            --pill-bg:          rgba(255,255,255,0.65);
            --pill-border:      rgba(0,0,0,0.08);
            --pill-text:        #9ca3af;
            --btn2-bg:          rgba(255,255,255,0.75);
            --btn2-border:      rgba(0,0,0,0.10);
            --btn2-text:        #374151;
            --btn2-hov:         rgba(255,255,255,0.97);
            --toggle-bg:        rgba(255,255,255,0.65);
            --toggle-border:    rgba(0,0,0,0.12);
            --toggle-text:      #374151;
            --blob-opacity:     0.48;
            /* Error info panel */
            --err-panel-bg:     rgba(255,255,255,0.55);
            --err-panel-border: rgba(239, 68, 68, 0.20);
            --err-row-sep:      rgba(0,0,0,0.05);
            --err-key-color:    #ef4444;
            --err-val-color:    #1f2937;
            --err-trace-bg:     rgba(0,0,0,0.03);
            --err-trace-color:  #374151;
            --copy-bg:          rgba(239, 68, 68, 0.08);
            --copy-border:      rgba(239, 68, 68, 0.25);
            --copy-text:        #ef4444;
            --copy-hov-bg:      #ef4444;
            --copy-hov-text:    #fff;
            /* blobs */
            --b1: #ef4444; --b2: #f97316; --b3: #dc2626; --b4: #fbbf24;
        }

        [data-theme="dark"] {
            --body-bg:          #120700;
            --card-bg:          rgba(28, 10, 5, 0.62);
            --card-border:      rgba(255,255,255,0.07);
            --card-shadow:      rgba(180, 20, 0, 0.35);
            --card-inset:       rgba(255,255,255,0.04);
            --top-glow:         rgba(239, 68, 68, 0.55);
            --title-color:      #ffe8e0;
            --sub-color:        #9ca3af;
            --eyebrow-color:    rgba(255, 140, 100, 0.88);
            --divider-line:     rgba(255,255,255,0.07);
            --pill-bg:          rgba(255,255,255,0.06);
            --pill-border:      rgba(255,255,255,0.10);
            --pill-text:        #6b7280;
            --btn2-bg:          rgba(255,255,255,0.07);
            --btn2-border:      rgba(255,255,255,0.12);
            --btn2-text:        #d1d5db;
            --btn2-hov:         rgba(255,255,255,0.14);
            --toggle-bg:        rgba(255,255,255,0.07);
            --toggle-border:    rgba(255,255,255,0.12);
            --toggle-text:      #d1d5db;
            --blob-opacity:     0.28;
            /* Error info panel */
            --err-panel-bg:     rgba(255,255,255,0.04);
            --err-panel-border: rgba(239, 68, 68, 0.25);
            --err-row-sep:      rgba(255,255,255,0.05);
            --err-key-color:    #f87171;
            --err-val-color:    #e5e7eb;
            --err-trace-bg:     rgba(0,0,0,0.25);
            --err-trace-color:  #9ca3af;
            --copy-bg:          rgba(239, 68, 68, 0.12);
            --copy-border:      rgba(239, 68, 68, 0.30);
            --copy-text:        #f87171;
            --copy-hov-bg:      #ef4444;
            --copy-hov-text:    #fff;
            --b1: #b91c1c; --b2: #c2410c; --b3: #991b1b; --b4: #d97706;
        }

        /* ─────────────────────────────────────────────
           BASE
        ───────────────────────────────────────────── */
        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 4rem 1rem 3rem;
            position: relative;
            overflow-x: hidden;
            transition: background 0.4s ease;
        }

        /* ─────────────────────────────────────────────
           ANIMATED FIRE BLOBS
        ───────────────────────────────────────────── */
        .blob-wrap { position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none; }
        .blob { position:absolute;border-radius:50%;filter:blur(90px);opacity:var(--blob-opacity);will-change:transform;transition:background 0.6s,opacity 0.6s; }
        .b1{width:480px;height:480px;background:var(--b1);top:-100px;left:-120px;animation:d1 13s ease-in-out infinite alternate;}
        .b2{width:380px;height:380px;background:var(--b2);top:40%;right:-80px;animation:d2 17s ease-in-out infinite alternate;}
        .b3{width:320px;height:320px;background:var(--b3);bottom:-60px;left:32%;animation:d3 15s ease-in-out infinite alternate;}
        .b4{width:260px;height:260px;background:var(--b4);top:15%;left:52%;animation:d4 11s ease-in-out infinite alternate;}
        @keyframes d1{from{transform:translate(0,0) scale(1)}to{transform:translate(80px,110px) scale(1.12)}}
        @keyframes d2{from{transform:translate(0,0) scale(1)}to{transform:translate(-90px,-70px) scale(1.18)}}
        @keyframes d3{from{transform:translate(0,0) scale(1)}to{transform:translate(60px,-90px) scale(0.92)}}
        @keyframes d4{from{transform:translate(0,0) scale(1)}to{transform:translate(-70px,65px) scale(1.08)}}

        /* ─────────────────────────────────────────────
           THEME TOGGLE
        ───────────────────────────────────────────── */
        .theme-toggle {
            position:fixed;top:20px;right:20px;z-index:10;
            display:flex;align-items:center;gap:8px;
            padding:8px 16px;border-radius:999px;
            border:1px solid var(--toggle-border);background:var(--toggle-bg);
            backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);
            color:var(--toggle-text);font-family:'Inter',sans-serif;
            font-size:0.78rem;font-weight:600;cursor:pointer;
            transition:all 0.3s ease;box-shadow:0 2px 10px rgba(0,0,0,0.08);outline:none;
        }
        .theme-toggle:hover{transform:scale(1.05);box-shadow:0 4px 18px rgba(0,0,0,0.15);}
        .theme-toggle .icon{font-size:1rem;transition:transform 0.5s;}
        .theme-toggle:hover .icon{transform:rotate(20deg);}

        /* ─────────────────────────────────────────────
           FROSTED GLASS CARD
        ───────────────────────────────────────────── */
        .card {
            position:relative;z-index:1;
            width:min(90vw, 560px);
            padding:3.5rem 3rem;
            background:var(--card-bg);
            backdrop-filter:blur(32px) saturate(180%);
            -webkit-backdrop-filter:blur(32px) saturate(180%);
            border:1px solid var(--card-border);border-radius:28px;
            box-shadow:0 8px 40px var(--card-shadow),0 2px 8px rgba(0,0,0,0.07),inset 0 1px 0 var(--card-inset);
            text-align:center;
            animation:card-in 0.65s cubic-bezier(0.22,1,0.36,1) both;
            transition:background 0.4s,border-color 0.4s,box-shadow 0.4s;
            margin-bottom: 1.5rem;
        }
        .card::before {
            content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);
            width:55%;height:1px;
            background:linear-gradient(90deg,transparent,var(--top-glow),transparent);
            border-radius:999px;transition:background 0.4s;
        }
        @keyframes card-in{from{opacity:0;transform:translateY(30px) scale(0.96);}to{opacity:1;transform:translateY(0) scale(1);}}

        /* ─────────────────────────────────────────────
           ILLUSTRATION
        ───────────────────────────────────────────── */
        .illustration{width:100px;height:100px;margin:0 auto 1.75rem;display:flex;align-items:center;justify-content:center;position:relative;}
        .illustration::before,.illustration::after{content:'';position:absolute;border-radius:50%;animation:ring-pulse 2.8s ease-out infinite;}
        .illustration::before{width:100%;height:100%;border:2px solid rgba(239,68,68,0.22);animation-delay:0s;}
        .illustration::after{width:80%;height:80%;border:2px solid rgba(249,115,22,0.18);animation-delay:0.5s;}
        @keyframes ring-pulse{0%{transform:scale(0.8);opacity:0.8;}100%{transform:scale(1.6);opacity:0;}}
        .warn-svg{animation:float 4s ease-in-out infinite;}
        @keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-6px);}}
        .warn-bang{animation:shake 3s ease-in-out infinite;transform-origin:center;}
        @keyframes shake{0%,85%,100%{transform:rotate(0deg);}88%{transform:rotate(-9deg);}91%{transform:rotate(9deg);}94%{transform:rotate(-6deg);}97%{transform:rotate(6deg);}}

        /* ─────────────────────────────────────────────
           TYPOGRAPHY
        ───────────────────────────────────────────── */
        .eyebrow{font-size:0.72rem;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--eyebrow-color);margin-bottom:0.4rem;display:block;transition:color 0.4s;}
        .whoops{font-size:3rem;font-weight:900;line-height:1;letter-spacing:-2px;background:linear-gradient(135deg,#ef4444,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:0.5rem;display:block;animation:pop-in 0.55s cubic-bezier(0.34,1.56,0.64,1) 0.15s both;}
        @keyframes pop-in{from{opacity:0;transform:scale(0.72);}to{opacity:1;transform:scale(1);}}
        .main-title{font-size:1.35rem;font-weight:700;color:var(--title-color);margin-bottom:0.65rem;letter-spacing:-0.4px;transition:color 0.4s;}
        .subtitle{font-size:0.95rem;color:var(--sub-color);line-height:1.75;margin-bottom:2rem;font-weight:400;transition:color 0.4s;}

        /* ─────────────────────────────────────────────
           DIVIDER
        ───────────────────────────────────────────── */
        .divider{display:flex;align-items:center;gap:12px;margin-bottom:1.75rem;}
        .divider-line{flex:1;height:1px;background:var(--divider-line);transition:background 0.4s;}
        .divider-pill{font-size:0.67rem;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:var(--pill-text);background:var(--pill-bg);border:1px solid var(--pill-border);border-radius:999px;padding:3px 14px;transition:all 0.4s;}

        /* ─────────────────────────────────────────────
           BUTTONS
        ───────────────────────────────────────────── */
        .actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;}
        .btn-primary{display:inline-flex;align-items:center;gap:7px;padding:0.78rem 2rem;border-radius:14px;font-size:0.92rem;font-weight:600;text-decoration:none;border:none;cursor:pointer;background:linear-gradient(135deg,#ef4444,#f97316);color:#fff;box-shadow:0 4px 18px rgba(239,68,68,0.42);transition:all 0.25s cubic-bezier(0.34,1.56,0.64,1);font-family:'Inter',sans-serif;}
        .btn-primary:hover{transform:translateY(-3px) scale(1.04);box-shadow:0 8px 30px rgba(239,68,68,0.58);}
        .btn-secondary{display:inline-flex;align-items:center;gap:7px;padding:0.78rem 1.5rem;border-radius:14px;font-size:0.92rem;font-weight:600;text-decoration:none;background:var(--btn2-bg);border:1px solid var(--btn2-border);color:var(--btn2-text);box-shadow:0 2px 8px rgba(0,0,0,0.06);transition:all 0.2s ease;}
        .btn-secondary:hover{background:var(--btn2-hov);transform:translateY(-2px);box-shadow:0 5px 18px rgba(0,0,0,0.12);}

        /* ─────────────────────────────────────────────
           ERROR INFO PANEL
        ───────────────────────────────────────────── */
        .err-panel {
            position: relative; z-index: 1;
            width: min(90vw, 720px);
            background: var(--err-panel-bg);
            backdrop-filter: blur(24px) saturate(150%);
            -webkit-backdrop-filter: blur(24px) saturate(150%);
            border: 1px solid var(--err-panel-border);
            border-radius: 20px;
            overflow: hidden;
            animation: card-in 0.75s cubic-bezier(0.22,1,0.36,1) 0.1s both;
            transition: background 0.4s, border-color 0.4s;
        }

        /* Panel header bar */
        .err-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: linear-gradient(135deg, rgba(239,68,68,0.12), rgba(249,115,22,0.08));
            border-bottom: 1px solid var(--err-panel-border);
        }

        .err-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .err-badge {
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #ef4444;
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 999px;
            padding: 3px 12px;
        }

        .err-status {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--sub-color);
            font-family: 'Fira Code', monospace;
        }

        /* Copy button */
        .copy-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            background: var(--copy-bg);
            border: 1px solid var(--copy-border);
            color: var(--copy-text);
            transition: all 0.2s ease;
            outline: none;
            white-space: nowrap;
        }
        .copy-btn:hover {
            background: var(--copy-hov-bg);
            color: var(--copy-hov-text);
            border-color: transparent;
            transform: scale(1.04);
            box-shadow: 0 4px 14px rgba(239,68,68,0.35);
        }
        .copy-btn.copied {
            background: #16a34a;
            color: #fff;
            border-color: transparent;
        }
        .copy-btn svg { flex-shrink: 0; transition: transform 0.2s; }
        .copy-btn:hover svg { transform: scale(1.1); }

        /* Row table */
        .err-rows { padding: 0; }
        .err-row {
            display: grid;
            grid-template-columns: 130px 1fr;
            align-items: baseline;
            gap: 12px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--err-row-sep);
            transition: background 0.15s;
        }
        .err-row:hover { background: rgba(239,68,68,0.03); }
        .err-row:last-child { border-bottom: none; }

        .err-key {
            font-family: 'Fira Code', monospace;
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--err-key-color);
            letter-spacing: 0.5px;
            text-transform: lowercase;
            flex-shrink: 0;
        }
        .err-val {
            font-family: 'Fira Code', monospace;
            font-size: 0.82rem;
            color: var(--err-val-color);
            word-break: break-all;
            line-height: 1.55;
            transition: color 0.4s;
        }
        .err-val .hl { color: #f97316; font-weight: 600; }

        /* Trace section */
        .trace-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            cursor: pointer;
            background: var(--err-trace-bg);
            border-top: 1px solid var(--err-row-sep);
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--err-key-color);
            letter-spacing: 1px;
            text-transform: uppercase;
            user-select: none;
            transition: background 0.2s;
        }
        .trace-toggle:hover { background: rgba(239,68,68,0.06); }
        .trace-arrow { transition: transform 0.3s; font-size: 0.8rem; }
        .trace-arrow.open { transform: rotate(180deg); }

        .trace-body {
            display: none;
            padding: 16px 20px;
            background: var(--err-trace-bg);
            border-top: 1px solid var(--err-row-sep);
            overflow-x: auto;
        }
        .trace-body.open { display: block; }

        .trace-body pre {
            font-family: 'Fira Code', monospace;
            font-size: 0.75rem;
            color: var(--err-trace-color);
            line-height: 1.7;
            white-space: pre;
        }

        .trace-frame {
            padding: 6px 0;
            border-bottom: 1px dashed var(--err-row-sep);
        }
        .trace-frame:last-child { border-bottom: none; }
        .trace-num { color: var(--err-key-color); font-weight: 700; min-width: 28px; display: inline-block; }
        .trace-loc { color: var(--sub-color); font-size: 0.68rem; }

        /* ─────────────────────────────────────────────
           RESPONSIVE
        ───────────────────────────────────────────── */
        @media (max-width: 560px) {
            body { padding: 3rem 0.75rem 2rem; }
            .card { padding: 2.5rem 1.75rem; width: 100%; }
            .err-panel { width: 100%; border-radius: 16px; }
            .whoops { font-size: 2.2rem; }
            .main-title { font-size: 1.15rem; }
            .actions { flex-direction: column; }
            .btn-primary, .btn-secondary { justify-content: center; }
            .err-row { grid-template-columns: 1fr; gap: 4px; }
            .err-key { font-size: 0.65rem; }
        }
    </style>
</head>
<body>

    <!-- Theme Toggle -->
    <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark/light mode">
        <span class="icon" id="themeIcon">🌙</span>
        <span id="themeLabel">Dark Mode</span>
    </button>

    <!-- Fire Blobs -->
    <div class="blob-wrap" aria-hidden="true">
        <div class="blob b1"></div>
        <div class="blob b2"></div>
        <div class="blob b3"></div>
        <div class="blob b4"></div>
    </div>

    <!-- ── Main Card ──────────────────────────────── -->
    <main class="card" role="main">

        <div class="illustration" aria-hidden="true">
            <svg class="warn-svg" width="64" height="64" viewBox="0 0 72 72" fill="none">
                <defs>
                    <linearGradient id="wg" x1="0" y1="0" x2="72" y2="72" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#ef4444"/><stop offset="1" stop-color="#f97316"/>
                    </linearGradient>
                </defs>
                <path d="M36 6L68 62H4L36 6Z" stroke="url(#wg)" stroke-width="4" stroke-linejoin="round" fill="none"/>
                <g class="warn-bang">
                    <rect x="33" y="24" width="6" height="20" rx="3" fill="url(#wg)"/>
                    <circle cx="36" cy="51" r="4" fill="url(#wg)"/>
                </g>
            </svg>
        </div>

        <span class="eyebrow">System Error</span>
        <span class="whoops"><?= lang('Errors.whoops') ?></span>
        <h1 class="main-title">Terjadi Kendala Sistem</h1>
        <p class="subtitle">
            <?= lang('Errors.weHitASnag') ?><br>
            Tim kami sudah diberitahu. Silakan coba lagi beberapa saat kemudian.
        </p>

        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-pill">Apa yang bisa dilakukan?</span>
            <div class="divider-line"></div>
        </div>

        <div class="actions">
            <button onclick="window.location.reload();" class="btn-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l6.17-3.55"/></svg>
                Muat Ulang
            </button>
            <a href="<?= base_url() ?>" class="btn-secondary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Beranda
            </a>
        </div>

    </main>

    <!-- ── Error Info Panel ───────────────────────── -->
    <section class="err-panel" aria-label="Error Details" id="errorPanel">

        <!-- Panel Header -->
        <div class="err-header">
            <div class="err-header-left">
                <span class="err-badge">Exception</span>
                <span class="err-status">HTTP <?= esc($code ?? 500) ?></span>
            </div>
            <button class="copy-btn" id="copyBtn" onclick="copyError()" title="Salin semua detail error ke clipboard">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                Copy Error
            </button>
        </div>

        <!-- Error Rows -->
        <div class="err-rows" id="errRows">

            <div class="err-row">
                <span class="err-key">type</span>
                <span class="err-val"><?= esc($type ?? $title ?? 'Exception') ?></span>
            </div>

            <div class="err-row">
                <span class="err-key">message</span>
                <span class="err-val"><?= esc($message ?? '(no message)') ?></span>
            </div>

            <div class="err-row">
                <span class="err-key">http_code</span>
                <span class="err-val"><span class="hl"><?= esc($code ?? 500) ?></span></span>
            </div>

            <div class="err-row">
                <span class="err-key">file</span>
                <span class="err-val"><?= esc($file ?? '—') ?></span>
            </div>

            <div class="err-row">
                <span class="err-key">line</span>
                <span class="err-val"><span class="hl"><?= esc($line ?? '—') ?></span></span>
            </div>

            <div class="err-row">
                <span class="err-key">timestamp</span>
                <span class="err-val"><?= date('Y-m-d H:i:s T') ?></span>
            </div>

        </div>

        <!-- Stack Trace Toggle -->
        <?php if (!empty($trace)) : ?>
        <div class="trace-toggle" id="traceToggle" onclick="toggleTrace()" role="button" tabindex="0" aria-expanded="false">
            <span>Stack Trace</span>
            <span class="trace-arrow" id="traceArrow">▼</span>
        </div>
        <div class="trace-body" id="traceBody">
            <pre><?php foreach ($trace as $i => $frame) : ?><div class="trace-frame"><span class="trace-num">#<?= $i ?></span> <?= esc(($frame['class'] ?? '') . ($frame['type'] ?? '') . ($frame['function'] ?? '') . '()') ?>
<span class="trace-loc">   <?= esc(($frame['file'] ?? '[internal]') . (isset($frame['line']) ? ':' . $frame['line'] : '')) ?></span></div><?php endforeach; ?></pre>
        </div>
        <?php endif; ?>

    </section>

    <!-- Hidden element for copy -->
    <textarea id="copySource" style="position:absolute;left:-9999px;opacity:0;" readonly aria-hidden="true"><?= htmlspecialchars_decode(strip_tags(
        "=== EXCEPTION REPORT ===\n" .
        "Type    : " . esc($type ?? $title ?? 'Exception') . "\n" .
        "Message : " . esc($message ?? '(no message)') . "\n" .
        "HTTP    : " . esc($code ?? 500) . "\n" .
        "File    : " . esc($file ?? '—') . "\n" .
        "Line    : " . esc($line ?? '—') . "\n" .
        "Time    : " . date('Y-m-d H:i:s T') . "\n\n" .
        "=== STACK TRACE ===\n" .
        (!empty($trace) ? implode("\n", array_map(function($f, $i) {
            return "#" . $i . " " . ($f['class'] ?? '') . ($f['type'] ?? '') . ($f['function'] ?? '') . "()\n   " . ($f['file'] ?? '[internal]') . (isset($f['line']) ? ':' . $f['line'] : '');
        }, $trace, array_keys($trace))) : '(none)')
    )) ?></textarea>

    <script>
        /* ── Theme Toggle ── */
        (function () {
            var root  = document.documentElement;
            var btn   = document.getElementById('themeToggle');
            var icon  = document.getElementById('themeIcon');
            var label = document.getElementById('themeLabel');
            var KEY   = 'whoopsTheme';
            function apply(t) {
                root.setAttribute('data-theme', t);
                icon.textContent  = t === 'dark' ? '☀️' : '🌙';
                label.textContent = t === 'dark' ? 'Light Mode' : 'Dark Mode';
                localStorage.setItem(KEY, t);
            }
            apply(root.getAttribute('data-theme') || 'light');
            btn.addEventListener('click', function () {
                apply(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
            });
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
                if (!localStorage.getItem(KEY)) apply(e.matches ? 'dark' : 'light');
            });
        })();

        /* ── Trace Toggle ── */
        function toggleTrace() {
            var body  = document.getElementById('traceBody');
            var arrow = document.getElementById('traceArrow');
            var toggle = document.getElementById('traceToggle');
            var open  = body.classList.toggle('open');
            arrow.classList.toggle('open', open);
            toggle.setAttribute('aria-expanded', open);
        }
        document.getElementById('traceToggle') && document.getElementById('traceToggle').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleTrace(); }
        });

        /* ── Copy Error ── */
        function copyError() {
            var btn  = document.getElementById('copyBtn');
            var text = document.getElementById('copySource').value;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() { showCopied(btn); });
            } else {
                /* Fallback for older browsers / non-HTTPS */
                var ta = document.getElementById('copySource');
                ta.removeAttribute('style');
                ta.select();
                document.execCommand('copy');
                ta.style = 'position:absolute;left:-9999px;opacity:0;';
                showCopied(btn);
            }
        }
        function showCopied(btn) {
            btn.classList.add('copied');
            btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Tersalin!';
            setTimeout(function() {
                btn.classList.remove('copied');
                btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> Copy Error';
            }, 2500);
        }
    </script>

</body>
</html>
