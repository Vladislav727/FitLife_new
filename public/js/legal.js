document.addEventListener('DOMContentLoaded', () => {
    const mobileMenu = document.getElementById('mobileMenu');
    const navLinks = document.getElementById('navLinks');
    const nav = document.querySelector('nav');

    mobileMenu?.addEventListener('click', () => {
        navLinks?.classList.toggle('active');
    });

    window.addEventListener('scroll', () => {
        if (!nav) {
            return;
        }

        nav.style.background = window.scrollY > 50 ? 'rgba(10, 10, 10, 0.95)' : 'rgba(10, 10, 10, 0.8)';
    });
});