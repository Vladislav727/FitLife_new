document.addEventListener('DOMContentLoaded', () => {
    initProfileEditMediaPreview();
    initProfileShowTabs();
    initProfileEditSidebar();
});

function initProfileEditMediaPreview() {
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.querySelector('.pe-banner-preview__avatar');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                avatarPreview.src = URL.createObjectURL(file);
            }
        });
    }

    const bannerInput = document.getElementById('banner');
    const bannerBg = document.querySelector('.pe-banner-preview__bg');

    if (bannerInput && bannerBg) {
        bannerInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (loadEvent) => {
                bannerBg.style.backgroundImage = `url(${loadEvent.target.result})`;
            };
            reader.readAsDataURL(file);
        });
    }
}

function initProfileShowTabs() {
    const tabs = document.querySelectorAll('.sp-tab');
    const panels = document.querySelectorAll('.sp-tab-content');

    if (!tabs.length || !panels.length) {
        return;
    }

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            tabs.forEach((currentTab) => currentTab.classList.remove('active'));
            panels.forEach((panel) => panel.classList.remove('active'));

            tab.classList.add('active');
            document.getElementById('sptab-' + tab.dataset.spTab)?.classList.add('active');
        });
    });
}

function initProfileEditSidebar() {
    const sidebarLinks = document.querySelectorAll('.pe-sidebar__link');
    const sections = document.querySelectorAll('.pe-card[id]');

    if (!sidebarLinks.length || !sections.length) {
        return;
    }

    sidebarLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();

            const targetId = link.getAttribute('data-section');
            const target = document.getElementById(targetId);
            if (!target) {
                return;
            }

            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            sidebarLinks.forEach((currentLink) => currentLink.classList.remove('active'));
            link.classList.add('active');
        });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            sidebarLinks.forEach((link) => link.classList.remove('active'));
            document.querySelector(`.pe-sidebar__link[data-section="${entry.target.id}"]`)?.classList.add('active');
        });
    }, { threshold: 0.3 });

    sections.forEach((section) => observer.observe(section));
}
