function switchSettingsTab(tab, element) {
    document.querySelectorAll('.settings-tab').forEach((panel) => {
        panel.style.display = 'none';
    });

    document.getElementById('tab-' + tab).style.display = '';

    document.querySelectorAll('.sidebar-link[data-tab]').forEach((link) => {
        link.classList.remove('active');
    });

    if (element) {
        element.classList.add('active');
    }
}

function updateThemeCards(theme) {
    document.querySelectorAll('.theme-card').forEach((card) => {
        card.classList.remove('selected');
    });

    const card = document.getElementById('theme-' + theme);
    if (card) {
        card.classList.add('selected');
    }
}

function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('fitlife-theme', theme);
    updateThemeCards(theme);
}

document.addEventListener('DOMContentLoaded', () => {
    const currentTheme = localStorage.getItem('fitlife-theme') || 'dark';
    updateThemeCards(currentTheme);
});