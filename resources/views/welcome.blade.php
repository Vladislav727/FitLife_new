<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FitLife | Train Hard. Track Smart.</title>
    <link rel="icon" href="{{ asset('favicon.PNG') }}" type="image/png">
    <style>html,body{overscroll-behavior-y:none;overscroll-behavior-x:auto}</style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800" rel="stylesheet">
    @vite(['resources/css/welcome-entry.css'])
</head>
<body class="welcome-page">
@php
    $usersCount = \App\Models\User::count();
    $goalsCount = \App\Models\Goal::count();
    $postsCount = \App\Models\Post::count();
@endphp

<header class="welcome-header" id="welcomeHeader">
    <a href="{{ route('welcome') }}" class="welcome-brand">
        <img src="{{ asset('storage/logo/fitlife-logo.png') }}" alt="FitLife" class="welcome-brand-img">
    </a>

    <nav class="welcome-nav" id="welcomeNav">
        <a href="#features" class="welcome-nav-link">Features</a>
        <a href="#system" class="welcome-nav-link">System</a>
        <a href="#results" class="welcome-nav-link">Results</a>
        <a href="#community" class="welcome-nav-link">Community</a>
    </nav>

    <div class="welcome-actions">
        @auth
            <a href="{{ route('dashboard') }}" class="welcome-btn welcome-btn--ghost">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="welcome-btn welcome-btn--ghost">Log In</a>
            <a href="{{ route('register') }}" class="welcome-btn welcome-btn--primary">Start Strong</a>
        @endauth
    </div>

    <button class="welcome-menu-toggle" id="welcomeMenuToggle" type="button" aria-label="Toggle menu">
        <span></span>
        <span></span>
    </button>
</header>

<main class="welcome-layout">
    <section class="welcome-hero" id="top">
        <div class="welcome-hero-noise" aria-hidden="true"></div>
        <div class="welcome-hero-grid">
            <div class="welcome-hero-copy">
                <div class="welcome-hero-eyebrow">
                    <span class="welcome-hero-index">01</span>
                    <span>Performance-first fitness platform</span>
                </div>

                <div class="welcome-hero-badge">
                    <span class="welcome-badge-dot"></span>
                    Built for athletes, lifters, and people who want visible progress
                </div>

                <h1 class="welcome-hero-title">
                    Train harder.
                    <span>Look sharper.</span>
                    <em>Stay locked in.</em>
                </h1>

                <p class="welcome-hero-subtitle">
                    FitLife combines training focus, nutrition tracking, recovery metrics, and community momentum into one aggressive fitness command center.
                </p>

                <div class="welcome-hero-proofline">
                    <span>Recovery signals</span>
                    <span>Nutrition pressure</span>
                    <span>Visible accountability</span>
                </div>

                <div class="welcome-hero-cta-row">
                    @auth
                        <a href="{{ route('dashboard') }}" class="welcome-btn welcome-btn--primary welcome-btn--xl">Open Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="welcome-btn welcome-btn--primary welcome-btn--xl">Join the Platform</a>
                        <a href="#features" class="welcome-btn welcome-btn--surface welcome-btn--xl">Explore the System</a>
                    @endauth
                </div>

                <div class="welcome-hero-stats">
                    <div class="welcome-hero-stat">
                        <strong>{{ number_format($usersCount) }}+</strong>
                        <span>active users</span>
                    </div>
                    <div class="welcome-hero-stat">
                        <strong>{{ number_format($goalsCount) }}+</strong>
                        <span>goals in motion</span>
                    </div>
                    <div class="welcome-hero-stat">
                        <strong>{{ number_format($postsCount) }}+</strong>
                        <span>community updates</span>
                    </div>
                </div>
            </div>

            <div class="welcome-stage">
                <div class="welcome-stage-frame" aria-hidden="true"></div>

                <div class="welcome-stage-card">
                    <div class="welcome-stage-topbar">
                        <span class="welcome-stage-chip">Performance View</span>
                        <span class="welcome-stage-caption">Live stack / 24h sync</span>
                    </div>

                    <div class="welcome-stage-main">
                        <div class="welcome-stage-copy">
                            <p>Body composition</p>
                            <h2>78<span>%</span></h2>
                            <small>weekly consistency score</small>
                            <div class="welcome-stage-bars">
                                <span><i style="width: 91%"></i></span>
                                <span><i style="width: 74%"></i></span>
                                <span><i style="width: 86%"></i></span>
                            </div>
                        </div>
                        <div class="welcome-stage-ring">
                            <div class="welcome-stage-ring-core">
                                <span>+12%</span>
                                <small>vs last week</small>
                            </div>
                        </div>
                    </div>

                    <div class="welcome-stage-grid">
                        <article class="welcome-mini-card welcome-mini-card--metric">
                            <span>Pace</span>
                            <strong>04</strong>
                            <small>sessions booked</small>
                        </article>
                        <article class="welcome-mini-card welcome-mini-card--metric">
                            <span>Focus</span>
                            <strong>92</strong>
                            <small>readiness score</small>
                        </article>
                        <article class="welcome-mini-card welcome-mini-card--accent welcome-mini-card--wide">
                            <span>Nutrition</span>
                            <strong>1,842 kcal</strong>
                            <small>tight macro balance</small>
                        </article>
                        <article class="welcome-mini-card welcome-mini-card--orange welcome-mini-card--split">
                            <span>Hydration</span>
                            <strong>2.4 L</strong>
                            <small>above target pace</small>
                        </article>
                        <article class="welcome-mini-card welcome-mini-card--soft welcome-mini-card--split">
                            <span>Sleep</span>
                            <strong>7.9 h</strong>
                            <small>recovery almost maxed</small>
                        </article>
                    </div>

                    <div class="welcome-stage-footerband">
                        <span>Macro precision</span>
                        <span>Recovery rhythm</span>
                        <span>Community signal</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="welcome-marquee" aria-label="FitLife disciplines">
        <div class="welcome-marquee-track">
            <div class="welcome-marquee-group">
                <span>Strength</span>
                <span>Conditioning</span>
                <span>Recovery</span>
                <span>Nutrition</span>
                <span>Mobility</span>
                <span>Community</span>
                <span>Progress</span>
                <span>Strength</span>
                <span>Conditioning</span>
                <span>Recovery</span>
                <span>Nutrition</span>
                <span>Mobility</span>
                <span>Community</span>
                <span>Progress</span>
            </div>
            <div class="welcome-marquee-group" aria-hidden="true">
                <span>Strength</span>
                <span>Conditioning</span>
                <span>Recovery</span>
                <span>Nutrition</span>
                <span>Mobility</span>
                <span>Community</span>
                <span>Progress</span>
                <span>Strength</span>
                <span>Conditioning</span>
                <span>Recovery</span>
                <span>Nutrition</span>
                <span>Mobility</span>
                <span>Community</span>
                <span>Progress</span>
            </div>
        </div>
    </section>

    <section class="welcome-section welcome-section--features" id="features">
        <div class="welcome-section-heading welcome-section-heading--split">
            <div>
                <p class="welcome-section-tag">Core stack</p>
                <h2>Everything important, surfaced fast.</h2>
            </div>
            <p>No dead weight. The landing experience pushes one message: train, measure, adjust, repeat.</p>
        </div>

        <div class="welcome-bento-grid">
            <article class="welcome-bento-card welcome-bento-card--xl">
                <div class="welcome-bento-copy">
                    <p>Goal tracking</p>
                    <h3>Push every target into one visible scoreboard.</h3>
                    <span>Track current value, target value, completion rate, and momentum without switching tools.</span>
                </div>
                <div class="welcome-progress-stack">
                    <div class="welcome-progress-line welcome-progress-line--primary">
                        <label>Strength cycle <b>82%</b></label>
                        <span style="width: 82%"></span>
                    </div>
                    <div class="welcome-progress-line welcome-progress-line--offset">
                        <label>Body recomposition <b>64%</b></label>
                        <span style="width: 64%"></span>
                    </div>
                    <div class="welcome-progress-line welcome-progress-line--accent">
                        <label>Recovery target <b>91%</b></label>
                        <span style="width: 91%"></span>
                    </div>
                </div>
            </article>

            <article class="welcome-bento-card welcome-bento-card--tall">
                <p>Recovery engine</p>
                <h3>Sleep and hydration stop being afterthoughts.</h3>
                <span>See the signals that actually affect training quality before your performance drops.</span>
                <div class="welcome-pill-column">
                    <span>Sleep quality</span>
                    <span>Hydration trend</span>
                    <span>Recovery rhythm</span>
                </div>
            </article>

            <article class="welcome-bento-card welcome-bento-card--nutrition">
                <p>Nutrition logging</p>
                <h3>Fast calorie capture, cleaner daily decisions.</h3>
            </article>

            <article class="welcome-bento-card welcome-bento-card--gallery">
                <p>Progress gallery</p>
                <h3>Visual proof that keeps you honest.</h3>
                <div class="welcome-gallery-stack" aria-hidden="true">
                    <span class="welcome-gallery-card welcome-gallery-card--back"></span>
                    <span class="welcome-gallery-card welcome-gallery-card--mid"></span>
                    <span class="welcome-gallery-card welcome-gallery-card--front"></span>
                </div>
            </article>

            <article class="welcome-bento-card welcome-bento-card--wide welcome-bento-card--community">
                <p>Community</p>
                <h3>Post progress, compare effort, and stay in motion with other people doing the work.</h3>
                <div class="welcome-community-meta">
                    <strong>{{ number_format($usersCount) }} active</strong>
                    <span>people pushing updates, streaks and check-ins right now</span>
                </div>
                <div class="welcome-avatar-row">
                    <span>AK</span>
                    <span>SM</span>
                    <span>JD</span>
                    <span>+{{ max(0, $usersCount - 3) }}</span>
                </div>
            </article>
        </div>
    </section>

    <section class="welcome-section welcome-section--system" id="system">
        <div class="welcome-system-grid">
            <div class="welcome-system-copy">
                <p class="welcome-section-tag">System flow</p>
                <h2>A fitness platform that behaves like a command center.</h2>
                <p class="welcome-system-text">You set the direction. FitLife keeps the pressure on with measurable checkpoints across meals, water, sleep, progress photos, events, and social accountability.</p>

                <div class="welcome-system-list">
                    <div>
                        <strong>Plan the week</strong>
                        <span>Use the calendar to shape training volume and upcoming sessions.</span>
                    </div>
                    <div>
                        <strong>Track the inputs</strong>
                        <span>Log nutrition, water, and sleep before they become blind spots.</span>
                    </div>
                    <div>
                        <strong>See the result</strong>
                        <span>Goals, photos, and stats turn effort into visible progression.</span>
                    </div>
                </div>
            </div>

            <div class="welcome-system-board">
                <div class="welcome-board-column welcome-board-column--wide">
                    <span class="welcome-board-label">Mon</span>
                    <strong>Push</strong>
                    <small>Heavy compound focus</small>
                    <em>Chest / shoulders / triceps</em>
                </div>
                <div class="welcome-board-column welcome-board-column--fuel">
                    <span class="welcome-board-label">Tue</span>
                    <strong>Fuel</strong>
                    <small>Hit calories, fix hydration</small>
                    <em>Macro reset</em>
                </div>
                <div class="welcome-board-column welcome-board-column--recover">
                    <span class="welcome-board-label">Wed</span>
                    <strong>Recover</strong>
                    <small>Sleep debt back to zero</small>
                    <em>Mobility and rest</em>
                </div>
                <div class="welcome-board-column welcome-board-column--highlight welcome-board-column--wide-end">
                    <span class="welcome-board-label">Thu</span>
                    <strong>Peak Day</strong>
                    <small>High output session</small>
                    <em>PR attempt window</em>
                </div>
            </div>
        </div>
    </section>

    <section class="welcome-section welcome-section--results" id="results">
        <div class="welcome-section-heading">
            <p class="welcome-section-tag">Why it hits</p>
            <h2>Designed to feel more like performance software than a generic wellness app.</h2>
        </div>

        <div class="welcome-results-grid">
            <article class="welcome-result-card">
                <strong>Visual hierarchy first</strong>
                <p>The page pushes momentum, metrics, and urgency instead of soft lifestyle fluff.</p>
            </article>
            <article class="welcome-result-card">
                <strong>Dark athletic mood</strong>
                <p>High-contrast surfaces, sharp highlights, and aggressive typography echo the references you shared.</p>
            </article>
            <article class="welcome-result-card">
                <strong>Mobile still works</strong>
                <p>The layout collapses cleanly without losing the premium feel on smaller screens.</p>
            </article>
        </div>
    </section>

    <section class="welcome-section welcome-section--community" id="community">
        <div class="welcome-community-panel">
            <div>
                <p class="welcome-section-tag">Community pressure</p>
                <h2>Discipline is easier when your work is visible.</h2>
                <p>Post updates, compare progress, follow others, and keep your standards high with a live fitness community built into the product.</p>
            </div>

            <div class="welcome-community-actions">
                @auth
                    <a href="{{ route('posts.index') }}" class="welcome-btn welcome-btn--primary">Open Community</a>
                @else
                    <a href="{{ route('register') }}" class="welcome-btn welcome-btn--primary">Create Account</a>
                    <a href="{{ route('login') }}" class="welcome-btn welcome-btn--surface">I Already Have Access</a>
                @endauth
            </div>
        </div>
    </section>
</main>

<footer class="welcome-footer">
    <div class="welcome-footer-top">
        <div>
            <a href="{{ route('welcome') }}" class="welcome-brand welcome-brand--footer">
                <img src="{{ asset('storage/logo/fitlife-logo.png') }}" alt="FitLife" class="welcome-brand-img">
            </a>
            <p class="welcome-footer-copy">Track hard, recover properly, and make progress visible.</p>
        </div>

        <div class="welcome-footer-links">
            <a href="#features">Features</a>
            <a href="#system">System</a>
            <a href="#results">Results</a>
            <a href="#community">Community</a>
            <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
            <a href="{{ route('terms-of-service') }}">Terms of Service</a>
        </div>
    </div>

    <div class="welcome-footer-bottom">
        <span>© {{ now()->year }} FitLife. Built for focused training.</span>
    </div>
</footer>

<script src="{{ asset('js/welcome.js') }}"></script>
</body>
</html>
