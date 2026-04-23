document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('chatSidebarList');

    if (!sidebar) {
        return;
    }

    const tabs = sidebar.querySelectorAll('.chats-tab');
    const panels = sidebar.querySelectorAll('.chats-panel');

    const activateTab = (tabName) => {
        tabs.forEach((tab) => {
            tab.classList.toggle('active', tab.dataset.tab === tabName);
        });

        panels.forEach((panel) => {
            panel.classList.toggle('active', panel.id === 'panel-' + tabName);
        });
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            activateTab(tab.dataset.tab);
        });
    });

    activateTab(sidebar.dataset.defaultTab || 'messages');
});