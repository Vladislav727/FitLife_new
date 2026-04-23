document.addEventListener('DOMContentLoaded', () => {
    const welcomeHeader = document.getElementById('welcomeHeader');
    const welcomeNav = document.getElementById('welcomeNav');
    const welcomeMenuToggle = document.getElementById('welcomeMenuToggle');
    const welcomeSectionLinks = document.querySelectorAll('a[href^="#"]');

    const stripWelcomeHash = () => {
        if (!window.location.hash) {
            return;
        }

        const cleanUrl = window.location.pathname + window.location.search;
        window.history.replaceState(null, '', cleanUrl);
    };

    welcomeMenuToggle?.addEventListener('click', () => {
        welcomeNav?.classList.toggle('is-open');
        welcomeMenuToggle.classList.toggle('is-open');
    });

    welcomeSectionLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            const targetId = link.getAttribute('href');

            if (!targetId || targetId === '#') {
                return;
            }

            const target = document.querySelector(targetId);

            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });

            if (welcomeNav?.classList.contains('is-open')) {
                welcomeNav.classList.remove('is-open');
                welcomeMenuToggle?.classList.remove('is-open');
            }

            stripWelcomeHash();
        });
    });

    window.addEventListener('load', () => {
        if (!window.location.hash) {
            return;
        }

        window.scrollTo({ top: 0, left: 0, behavior: 'auto' });
        stripWelcomeHash();
    });

    window.addEventListener('scroll', () => {
        if (!welcomeHeader) {
            return;
        }

        welcomeHeader.classList.toggle('is-scrolled', window.scrollY > 24);
    });
});