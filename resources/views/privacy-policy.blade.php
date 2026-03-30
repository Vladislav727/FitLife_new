<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FitLife - Privacy Policy</title>
    <link rel="icon" href="{{ asset('favicon.PNG') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0a0a0a;
            --bg-card: #111111;
            --bg-elevated: #1a1a1a;
            --primary: #22c55e;
            --primary-glow: rgba(34, 197, 94, 0.4);
            --secondary: #06b6d4;
            --text: #ffffff;
            --text-muted: #a1a1aa;
            --border: #27272a;
            --gradient-1: linear-gradient(135deg, #22c55e 0%, #06b6d4 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: var(--bg-dark);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 4px; }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--gradient-1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
        }

        .btn-ghost {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-ghost:hover {
            background: var(--bg-elevated);
            border-color: var(--primary);
        }

        .btn-primary {
            background: var(--gradient-1);
            color: var(--bg-dark);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px var(--primary-glow);
        }

        .mobile-menu {
            display: none;
            background: none;
            border: none;
            color: var(--text);
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Hero Header */
        .page-header {
            padding: 8rem 2rem 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, var(--primary-glow) 0%, transparent 50%);
            pointer-events: none;
        }

        .page-header h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .page-header h1 .gradient {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }

        /* Content */
        .content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem 4rem;
        }

        .content-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem;
        }

        .content-card h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 2rem 0 1rem;
            color: var(--text);
        }

        .content-card h2:first-child {
            margin-top: 0;
        }

        .content-card p {
            color: var(--text-muted);
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .content-card ul {
            color: var(--text-muted);
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        .content-card li {
            margin-bottom: 0.5rem;
        }

        .content-card strong {
            color: var(--text);
        }

        .content-card a {
            color: var(--primary);
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .content-card a:hover {
            opacity: 0.8;
        }

        /* Footer */
        footer {
            padding: 4rem 2rem 2rem;
            background: var(--bg-card);
            border-top: 1px solid var(--border);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr repeat(3, 1fr);
            gap: 4rem;
        }

        .footer-brand p {
            color: var(--text-muted);
            margin-top: 1rem;
            max-width: 300px;
        }

        .footer-links h4 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 4rem auto 0;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--primary);
            color: var(--bg-dark);
            border-color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .footer-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            nav {
                padding: 1rem;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--bg-card);
                flex-direction: column;
                padding: 1rem 2rem;
                gap: 1rem;
                border-bottom: 1px solid var(--border);
            }

            .nav-links.active {
                display: flex;
            }

            .mobile-menu {
                display: block;
            }

            .content-card {
                padding: 1.5rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <nav>
        <a href="/" class="logo">
            <div class="logo-icon">F</div>
            <span class="logo-text">FitLife</span>
        </a>

        <ul class="nav-links" id="navLinks">
            <li><a href="{{ route('welcome') }}">Home</a></li>
            <li><a href="{{ route('welcome') }}#features">Features</a></li>
            <li><a href="{{ route('welcome') }}#testimonials">Reviews</a></li>
        </ul>

        <div class="nav-buttons">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost">Log In</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
            @endauth
        </div>

        <button class="mobile-menu" id="mobileMenu">☰</button>
    </nav>

    <section class="page-header">
        <h1><span class="gradient">Privacy Policy</span></h1>
        <p>Last updated: January 22, 2026</p>
    </section>

    <main class="content">
        <div class="content-card">
            <h2>1. Introduction</h2>
            <p>At FitLife, we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website and services.</p>

            <h2>2. Information We Collect</h2>
            <p>We may collect the following types of information:</p>
            <ul>
                <li><strong>Personal Information:</strong> Name, email address, age, gender, and other information you provide when registering or updating your profile.</li>
                <li><strong>Health Data:</strong> Information about your workouts, nutrition, sleep, and other fitness-related data you input.</li>
                <li><strong>Usage Data:</strong> Information about how you interact with our website, such as IP address, browser type, and pages visited.</li>
                <li><strong>Device Data:</strong> Information about the devices you use to access our services, including device type and operating system.</li>
            </ul>

            <h2>3. How We Use Your Information</h2>
            <p>We use your information to:</p>
            <ul>
                <li>Provide and improve our services, such as personalized workout and nutrition plans.</li>
                <li>Communicate with you, including sending updates and notifications.</li>
                <li>Analyze usage patterns to enhance user experience.</li>
                <li>Ensure the security of our platform.</li>
            </ul>

            <h2>4. Sharing Your Information</h2>
            <p>We do not sell your personal information. We may share your information with:</p>
            <ul>
                <li><strong>Service Providers:</strong> Third-party vendors who assist with hosting, analytics, or payment processing.</li>
                <li><strong>Legal Authorities:</strong> When required by law or to protect our rights.</li>
                <li><strong>Community Features:</strong> Information you choose to share publicly, such as posts or comments.</li>
            </ul>

            <h2>5. Data Security</h2>
            <p>We implement industry-standard security measures to protect your data, including encryption and secure servers. However, no method of transmission over the internet is 100% secure.</p>

            <h2>6. Your Choices</h2>
            <p>You can:</p>
            <ul>
                <li>Access, update, or delete your personal information via your account settings.</li>
                <li>Opt out of promotional communications.</li>
                <li>Disable cookies through your browser settings, though this may affect functionality.</li>
            </ul>

            <h2>7. Cookies and Tracking</h2>
            <p>We use cookies to enhance your experience, such as remembering your preferences. You can manage cookie settings in your browser.</p>

            <h2>8. Third-Party Links</h2>
            <p>Our website may contain links to third-party sites. We are not responsible for their privacy practices.</p>

            <h2>9. Children's Privacy</h2>
            <p>Our services are not intended for individuals under 13. We do not knowingly collect data from children.</p>

            <h2>10. Changes to This Policy</h2>
            <p>We may update this Privacy Policy from time to time. We will notify you of significant changes via email or a notice on our website.</p>

            <h2>11. Contact Us</h2>
            <p>If you have questions about this Privacy Policy, please contact us at <a href="mailto:support@fitlife.com">support@fitlife.com</a>.</p>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-brand">
                <a href="/" class="logo">
                    <div class="logo-icon">F</div>
                    <span class="logo-text">FitLife</span>
                </a>
                <p>Your all-in-one platform for tracking fitness, nutrition, and wellness goals.</p>
            </div>
            <div class="footer-links">
                <h4>Product</h4>
                <ul>
                    <li><a href="{{ route('welcome') }}#features">Features</a></li>
                    <li><a href="{{ route('welcome') }}#how-it-works">How It Works</a></li>
                    <li><a href="{{ route('welcome') }}#testimonials">Reviews</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Company</h4>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="mailto:support@fitlife.com">Contact</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Legal</h4>
                <ul>
                    <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms-of-service') }}">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} FitLife. All rights reserved.</span>
            <div class="social-links">
                <a href="#" title="Twitter">𝕏</a>
                <a href="#" title="Instagram">📷</a>
                <a href="#" title="GitHub">⌨</a>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobileMenu').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('active');
        });

        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.style.background = 'rgba(10, 10, 10, 0.95)';
            } else {
                nav.style.background = 'rgba(10, 10, 10, 0.8)';
            }
        });
    </script>
</body>
</html>
