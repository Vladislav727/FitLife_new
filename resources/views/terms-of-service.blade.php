<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FitLife - Terms of Service</title>
    <link rel="icon" href="{{ asset('favicon.PNG') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/legal.css') }}">
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
        <h1><span class="gradient">Terms of Service</span></h1>
        <p>Last updated: January 22, 2026</p>
    </section>

    <main class="content">
        <div class="content-card">
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing or using FitLife, you agree to be bound by these Terms of Service. If you do not agree, please do not use our services.</p>

            <h2>2. Use of Services</h2>
            <p>You agree to use FitLife only for lawful purposes and in accordance with these Terms. You must:</p>
            <ul>
                <li>Be at least 13 years old to use our services.</li>
                <li>Provide accurate and complete information when creating an account.</li>
                <li>Not use our services to engage in illegal activities or violate the rights of others.</li>
            </ul>

            <h2>3. Account Responsibilities</h2>
            <p>You are responsible for maintaining the confidentiality of your account credentials and for all activities under your account.</p>

            <h2>4. User Content</h2>
            <p>You retain ownership of content you submit, such as posts, comments, or progress photos. By submitting content, you grant FitLife a non-exclusive, royalty-free license to use, display, and distribute it in connection with our services.</p>

            <h2>5. Prohibited Conduct</h2>
            <p>You agree not to:</p>
            <ul>
                <li>Post harmful, offensive, or unlawful content.</li>
                <li>Attempt to gain unauthorized access to our systems.</li>
                <li>Use automated scripts to scrape or collect data.</li>
            </ul>

            <h2>6. Intellectual Property</h2>
            <p>All content and materials on FitLife, including logos, designs, and software, are owned by FitLife or its licensors and protected by intellectual property laws.</p>

            <h2>7. Termination</h2>
            <p>We may suspend or terminate your account if you violate these Terms or engage in conduct that harms FitLife or its users.</p>

            <h2>8. Disclaimer of Warranties</h2>
            <p>FitLife is provided "as is" without warranties of any kind. We do not guarantee that our services will be uninterrupted or error-free.</p>

            <h2>9. Limitation of Liability</h2>
            <p>FitLife is not liable for any indirect, incidental, or consequential damages arising from your use of our services.</p>

            <h2>10. Governing Law</h2>
            <p>These Terms are governed by applicable laws. Any disputes will be resolved in the appropriate jurisdiction.</p>

            <h2>11. Changes to Terms</h2>
            <p>We may update these Terms from time to time. We will notify you of significant changes via email or a notice on our website.</p>

            <h2>12. Contact Us</h2>
            <p>If you have questions about these Terms, please contact us at <a href="mailto:support@fitlife.com">support@fitlife.com</a>.</p>
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

    <script src="{{ asset('js/legal.js') }}"></script>
</body>
</html>
