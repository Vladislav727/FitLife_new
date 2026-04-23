<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FitLife - Privacy Policy</title>
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

    <script src="{{ asset('js/legal.js') }}"></script>
</body>
</html>
