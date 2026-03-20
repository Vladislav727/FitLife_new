<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FitLife - Transform Your Body, Transform Your Life</title>
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
            --accent: #f97316;
            --text: #ffffff;
            --text-muted: #a1a1aa;
            --border: #27272a;
            --gradient-1: linear-gradient(135deg, #22c55e 0%, #06b6d4 100%);
            --gradient-2: linear-gradient(135deg, #f97316 0%, #ef4444 100%);
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

        /* NAVIGATION */
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

        /* HERO SECTION */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8rem 2rem 4rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, var(--primary-glow) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(6, 182, 212, 0.2) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            max-width: 900px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 100px;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        .hero-badge span {
            color: var(--primary);
            font-weight: 600;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        .hero h1 .gradient {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 2.5rem;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1rem;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 4rem;
            margin-top: 4rem;
            padding-top: 4rem;
            border-top: 1px solid var(--border);
        }

        .stat {
            text-align: center;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* FEATURES SECTION */
        .features {
            padding: 6rem 2rem;
            background: var(--bg-card);
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 4rem;
        }

        .section-tag {
            display: inline-block;
            padding: 0.4rem 1rem;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 100px;
            color: var(--primary);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-1);
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: rgba(34, 197, 94, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* HOW IT WORKS */
        .how-it-works {
            padding: 6rem 2rem;
        }

        .steps {
            display: flex;
            justify-content: center;
            gap: 2rem;
            max-width: 1000px;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        .step {
            flex: 1;
            min-width: 250px;
            max-width: 300px;
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--bg-dark);
            margin: 0 auto 1.5rem;
        }

        .step h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .step p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* TESTIMONIALS */
        .testimonials {
            padding: 6rem 2rem;
            background: var(--bg-card);
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .testimonial {
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
        }

        .testimonial-stars {
            color: #fbbf24;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .testimonial-text {
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .testimonial-avatar {
            width: 48px;
            height: 48px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--bg-dark);
        }

        .testimonial-info h4 {
            font-weight: 600;
            font-size: 1rem;
        }

        .testimonial-info span {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* CTA SECTION */
        .cta {
            padding: 6rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-content {
            position: relative;
            z-index: 1;
            max-width: 700px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta p {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        /* FOOTER */
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

        /* RESPONSIVE */
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

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero-stats {
                gap: 2rem;
                flex-wrap: wrap;
            }

            .stat-value {
                font-size: 2rem;
            }

            .features-grid,
            .testimonials-grid {
                grid-template-columns: 1fr;
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

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <a href="/" class="logo">
            <div class="logo-icon">F</div>
            <span class="logo-text">FitLife</span>
        </a>

        <ul class="nav-links" id="navLinks">
            <li><a href="#features">Features</a></li>
            <li><a href="#how-it-works">How It Works</a></li>
            <li><a href="#testimonials">Reviews</a></li>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge fade-in-up">
                🚀 <span>New</span> AI-powered workout recommendations
            </div>
            <h1 class="fade-in-up" style="animation-delay: 0.1s">
                Your Journey to a<br>
                <span class="gradient">Healthier You</span><br>
                Starts Here
            </h1>
            <p class="fade-in-up" style="animation-delay: 0.2s">
                Track workouts, monitor nutrition, set goals, and connect with a community 
                of fitness enthusiasts. All in one powerful platform.
            </p>
            <div class="hero-buttons fade-in-up" style="animation-delay: 0.3s">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard →</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Start Free Trial →</a>
                    <a href="#features" class="btn btn-ghost btn-lg">Learn More</a>
                @endauth
            </div>
            <div class="hero-stats fade-in-up" style="animation-delay: 0.4s">
                <div class="stat">
                    <div class="stat-value">{{ number_format(\App\Models\User::count()) }}+</div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat">
                    <div class="stat-value">{{ number_format(\App\Models\Goal::count()) }}+</div>
                    <div class="stat-label">Goals Set</div>
                </div>
                <div class="stat">
                    <div class="stat-value">{{ number_format(\App\Models\Post::count()) }}+</div>
                    <div class="stat-label">Community Posts</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header">
            <span class="section-tag">Features</span>
            <h2>Everything You Need to Succeed</h2>
            <p>Powerful tools designed to help you reach your fitness goals faster and smarter.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Goal Tracking</h3>
                <p>Set personalized fitness goals and track your progress with detailed analytics and insights.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🥗</div>
                <h3>Nutrition Logging</h3>
                <p>Log meals, track calories, and get nutritional breakdowns to fuel your body right.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💧</div>
                <h3>Water Intake</h3>
                <p>Stay hydrated with daily water tracking and smart reminders throughout the day.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">😴</div>
                <h3>Sleep Analysis</h3>
                <p>Monitor your sleep patterns and get recommendations for better recovery.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📸</div>
                <h3>Progress Photos</h3>
                <p>Document your transformation with secure photo storage and side-by-side comparisons.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Community</h3>
                <p>Connect with like-minded individuals, share achievements, and stay motivated together.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="section-header">
            <span class="section-tag">How It Works</span>
            <h2>Start in 3 Simple Steps</h2>
            <p>Getting started with FitLife is quick and easy.</p>
        </div>

        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Create Account</h3>
                <p>Sign up for free and set up your fitness profile in under 2 minutes.</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Set Your Goals</h3>
                <p>Define what you want to achieve - lose weight, build muscle, or stay active.</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Track & Grow</h3>
                <p>Log your activities, monitor progress, and watch yourself transform.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials" id="testimonials">
        <div class="section-header">
            <span class="section-tag">Testimonials</span>
            <h2>Loved by Thousands</h2>
            <p>See what our community members have to say about their journey.</p>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"FitLife completely changed my approach to fitness. The goal tracking feature keeps me accountable, and the community support is incredible!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">AK</div>
                    <div class="testimonial-info">
                        <h4>Alex Kim</h4>
                        <span>Lost 30 lbs in 4 months</span>
                    </div>
                </div>
            </div>
            <div class="testimonial">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"The nutrition tracking is so easy to use. I finally understand what I'm putting into my body and how it affects my performance."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">SM</div>
                    <div class="testimonial-info">
                        <h4>Sarah Mitchell</h4>
                        <span>Marathon Runner</span>
                    </div>
                </div>
            </div>
            <div class="testimonial">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"I've tried many fitness apps, but FitLife is the only one that stuck. The progress photos feature is my favorite - seeing the transformation is so motivating!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">JD</div>
                    <div class="testimonial-info">
                        <h4>James Davis</h4>
                        <span>Gained 15 lbs muscle</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-content">
            <h2>Ready to Transform Your Life?</h2>
            <p>Join thousands of people who have already started their fitness journey with FitLife. It's free to get started.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard →</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started for Free →</a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
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
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#testimonials">Reviews</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Company</h4>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Contact</a></li>
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
        // Mobile menu toggle
        document.getElementById('mobileMenu').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('active');
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                // Close mobile menu if open
                document.getElementById('navLinks').classList.remove('active');
            });
        });

        // Add scroll effect to nav
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.style.background = 'rgba(10, 10, 10, 0.95)';
            } else {
                nav.style.background = 'rgba(10, 10, 10, 0.8)';
            }
        });

        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .step, .testimonial').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>
