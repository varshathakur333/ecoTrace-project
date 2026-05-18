<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoTrace — Planet First E-Waste Platform</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Literata:ital,opsz,wght@0,7..72,200..900;1,7..72,200..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Premium Custom Vanilla CSS -->
    <style>
        :root {
            --surface: #f9f9f7;
            --primary: #004335;
            --primary-container: #0d5c4a;
            --secondary: #006b55;
            --tertiary: #333f00;
            --acid-lime: #caf208;
            --soft-lilac: #e2d2fe;
            --near-black: #1a1c1b;
            --ice-line: #bfc9c3;
            --pure-white: #ffffff;
            --font-display: 'Bricolage Grotesque', sans-serif;
            --font-editorial: 'Literata', serif;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--surface);
            color: var(--near-black);
            line-height: 1.8;
            padding-bottom: 60px;
        }

        header {
            border-bottom: 2px solid var(--near-black);
            background-color: var(--pure-white);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar {
            max-width: 1360px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
        }

        .brand {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 24px;
            color: var(--primary);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand span {
            background-color: var(--acid-lime);
            color: var(--near-black);
            padding: 2px 8px;
            border: 2px solid var(--near-black);
            font-size: 12px;
            border-radius: 9999px;
            transform: rotate(-3deg);
            font-family: var(--font-mono);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--near-black);
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--primary-container);
        }

        .nav-btn {
            background-color: var(--primary);
            color: var(--pure-white) !important;
            padding: 8px 16px;
            border-radius: 6px;
            border: 2px solid var(--near-black);
            box-shadow: 2px 2px 0px var(--near-black);
            transition: all 0.2s;
        }

        .nav-btn:hover {
            transform: translate(-1px, -1px);
            box-shadow: 3px 3px 0px var(--near-black);
        }

        .lang-switcher {
            display: flex;
            border: 2px solid var(--near-black);
            border-radius: 6px;
            overflow: hidden;
            font-family: var(--font-mono);
            font-size: 12px;
        }

        .lang-switcher a {
            padding: 4px 8px;
            text-decoration: none;
            color: var(--near-black);
            background-color: var(--pure-white);
            font-weight: bold;
        }

        .lang-switcher a.active {
            background-color: var(--acid-lime);
            border-right: 2px solid var(--near-black);
        }

        .lang-switcher a:first-child {
            border-right: 2px solid var(--near-black);
        }

        main {
            max-width: 1360px;
            margin: 40px auto;
            padding: 0 24px;
        }

        /* Hero / Swiss Zine Aesthetic details */
        .marquee {
            background-color: var(--primary);
            color: var(--acid-lime);
            overflow: hidden;
            white-space: nowrap;
            padding: 12px 0;
            border-top: 2px solid var(--near-black);
            border-bottom: 2px solid var(--near-black);
            font-family: var(--font-display);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 20px;
            letter-spacing: 0.05em;
        }

        .marquee-content {
            display: inline-block;
            animation: marquee 25s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-50%, 0, 0); }
        }

        /* Brutalist forms and layouts styling */
        .grid-layout {
            display: grid;
            grid-template-columns: 8fr 4fr;
            gap: 32px;
        }

        @media (max-width: 900px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
            .navbar {
                flex-direction: column;
                gap: 16px;
            }
        }

        .btn-action {
            background-color: var(--acid-lime);
            color: var(--near-black);
            font-weight: 800;
            text-transform: uppercase;
            padding: 12px 24px;
            border: 2px solid var(--near-black);
            border-radius: 6px;
            box-shadow: 4px 4px 0px var(--near-black);
            cursor: pointer;
            font-family: var(--font-display);
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-action:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px var(--near-black);
        }

        .badge {
            display: inline-block;
            background-color: var(--soft-lilac);
            color: var(--near-black);
            border: 2px solid var(--near-black);
            padding: 2px 10px;
            font-weight: bold;
            font-size: 12px;
            border-radius: 9999px;
            transform: rotate(2deg);
            font-family: var(--font-mono);
        }

        .badge-verified {
            background-color: var(--acid-lime);
            transform: rotate(-2deg);
        }

        .footer {
            border-top: 2px solid var(--near-black);
            padding: 40px 24px;
            background-color: var(--pure-white);
            margin-top: 80px;
        }

        .footer-content {
            max-width: 1360px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: var(--font-mono);
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .footer-content {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="navbar">
            <a href="{{ route('welcome') }}" class="brand">
                ECOTRACE <span>Circular</span>
            </a>
            
            <div class="nav-links">
                <a href="{{ route('search') }}">Find Pickups</a>
                
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none; border:none; cursor:pointer; font-weight:600; text-transform:uppercase; font-size:14px; font-family:inherit;">
                            {{ __('logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}">{{ __('login') }}</a>
                    <a href="{{ route('register') }}" class="nav-btn">{{ __('register') }}</a>
                @endauth

                <!-- Multilingual Language Switcher -->
                <div class="lang-switcher">
                    <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('lang.switch', 'hi') }}" class="{{ app()->getLocale() === 'hi' ? 'active' : '' }}">हि</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Marquee strip for urgent Brutalist vibe -->
    <div class="marquee">
        <div class="marquee-content">
            STOP SCROLLING. START RECYCLING // DIVERT E-WASTE SECURELY // EARN CARBON CREDITS TODAY // PLANET FIRST ALWAYS // &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; STOP SCROLLING. START RECYCLING // DIVERT E-WASTE SECURELY // EARN CARBON CREDITS TODAY // PLANET FIRST ALWAYS //
        </div>
    </div>

    <!-- Main Content Area -->
    <main>
        {{ $slot }}
    </main>

    <!-- Custom flash notification component -->
    <x-flash />

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div>© 2026 ECOTRACE. PLANET FIRST.</div>
            <div>[ English // Hindi supported ]</div>
        </div>
    </footer>

</body>
</html>
