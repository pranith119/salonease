<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SalonEase — Modern Salon Management</title>
    <meta name="description" content="SalonEase is a powerful, modern salon management platform. Manage customers, services, and appointments with ease.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #a855f7;
            --bg-dark: #0f0f1a;
            --bg-card: rgba(255, 255, 255, 0.05);
            --text: #e2e8f0;
            --text-muted: #94a3b8;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
        }
        /* Gradient blob backgrounds */
        .blob-1 {
            position: fixed; top: -20%; left: -10%; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
            border-radius: 50%; filter: blur(80px); z-index: 0; animation: float 8s ease-in-out infinite;
        }
        .blob-2 {
            position: fixed; bottom: -20%; right: -10%; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(168,85,247,0.12) 0%, transparent 70%);
            border-radius: 50%; filter: blur(80px); z-index: 0; animation: float 10s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; position: relative; z-index: 1; }

        /* Navigation */
        nav {
            padding: 20px 0;
            backdrop-filter: blur(20px);
            background: rgba(15, 15, 26, 0.8);
            position: sticky; top: 0; z-index: 50;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        nav .container { display: flex; align-items: center; justify-content: space-between; }
        .logo {
            font-size: 1.5rem; font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-links { display: flex; align-items: center; gap: 12px; }
        .nav-links a {
            padding: 10px 20px; border-radius: 10px; font-weight: 500; font-size: 0.9rem;
            text-decoration: none; transition: all 0.3s ease;
        }
        .btn-ghost { color: var(--text-muted); }
        .btn-ghost:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        }
        .btn-primary:hover { box-shadow: 0 6px 25px rgba(99,102,241,0.45); transform: translateY(-1px); }

        /* Hero */
        .hero { padding: 100px 0 80px; text-align: center; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px;
            background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2);
            border-radius: 50px; font-size: 0.85rem; color: var(--primary); margin-bottom: 28px;
        }
        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; line-height: 1.1; margin-bottom: 24px;
            background: linear-gradient(135deg, #fff 30%, var(--text-muted) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .hero p { font-size: 1.15rem; color: var(--text-muted); max-width: 600px; margin: 0 auto 40px; }
        .hero-buttons { display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; }
        .btn-lg { padding: 14px 32px; font-size: 1rem; border-radius: 12px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; }
        .btn-outline {
            color: var(--text); border: 1px solid rgba(255,255,255,0.15);
            background: transparent;
        }
        .btn-outline:hover { border-color: rgba(255,255,255,0.3); background: rgba(255,255,255,0.03); }

        /* Features */
        .features { padding: 80px 0; }
        .section-label {
            text-align: center; font-size: 0.85rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 2px; color: var(--primary); margin-bottom: 12px;
        }
        .section-title {
            text-align: center; font-size: 2rem; font-weight: 700; margin-bottom: 50px;
        }
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; }
        .feature-card {
            background: var(--bg-card); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px; padding: 32px; transition: all 0.4s ease;
        }
        .feature-card:hover {
            border-color: rgba(99,102,241,0.3); transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .feature-icon {
            width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px; font-size: 1.3rem;
        }
        .icon-blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .icon-purple { background: rgba(168,85,247,0.15); color: #c084fc; }
        .icon-green { background: rgba(34,197,94,0.15); color: #4ade80; }
        .feature-card h3 { font-size: 1.15rem; font-weight: 600; margin-bottom: 10px; }
        .feature-card p { font-size: 0.95rem; color: var(--text-muted); }

        /* CTA Section */
        .cta {
            padding: 80px 0; text-align: center;
        }
        .cta-box {
            background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(168,85,247,0.1));
            border: 1px solid rgba(99,102,241,0.15); border-radius: 24px;
            padding: 60px 40px;
        }
        .cta-box h2 { font-size: 2rem; font-weight: 700; margin-bottom: 16px; }
        .cta-box p { color: var(--text-muted); margin-bottom: 32px; font-size: 1.05rem; }

        /* Footer */
        footer {
            padding: 40px 0; border-top: 1px solid rgba(255,255,255,0.05);
            text-align: center; color: var(--text-muted); font-size: 0.85rem;
        }

        @media (max-width: 640px) {
            .hero-buttons { flex-direction: column; align-items: center; }
            .feature-grid { grid-template-columns: 1fr; }
            .cta-box { padding: 40px 24px; }
        }
    </style>
</head>
<body>
    <div class="blob-1"></div>
    <div class="blob-2"></div>

    {{-- Navigation --}}
    <nav>
        <div class="container">
            <div class="logo">SalonEase</div>
            <div class="nav-links">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary nav-links" id="nav-dashboard">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost nav-links" id="nav-login">Log In</a>
                    <a href="{{ route('register') }}" class="btn-primary nav-links" id="nav-register">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero">
        <div class="container">
            <div class="hero-badge">✨ Built with Laravel 12 & Livewire</div>
            <h1>Manage Your Salon<br>With Confidence</h1>
            <p>SalonEase is a secure, modern platform to manage your salon's customers, services, and team — all in one place.</p>
            <div class="hero-buttons">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary btn-lg" id="hero-dashboard">Go to Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary btn-lg" id="hero-register">Create Free Account</a>
                    <a href="{{ route('login') }}" class="btn-outline btn-lg" id="hero-login">Sign In</a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="features">
        <div class="container">
            <div class="section-label">Features</div>
            <h2 class="section-title">Everything Your Salon Needs</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon icon-blue">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                    </div>
                    <h3>Customer Management</h3>
                    <p>Maintain a complete database of your clients with contact details and notes. Search, filter, and manage with ease.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon icon-purple">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/></svg>
                    </div>
                    <h3>Service Catalog</h3>
                    <p>Define your salon's service menu with durations and pricing. Keep everything organized and up-to-date.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon icon-green">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                    </div>
                    <h3>Role-Based Security</h3>
                    <p>Admins and staff have tailored permissions. Sensitive operations are protected with middleware-enforced role checks.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="cta">
        <div class="container">
            <div class="cta-box">
                <h2>Ready to Streamline Your Salon?</h2>
                <p>Join SalonEase today and take your salon management to the next level.</p>
                @guest
                    <a href="{{ route('register') }}" class="btn-primary btn-lg" id="cta-register">Get Started — It's Free</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn-primary btn-lg" id="cta-dashboard">Go to Dashboard</a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} SalonEase. Built with Laravel 12, Jetstream & Livewire.</p>
        </div>
    </footer>
</body>
</html>
