document.addEventListener('DOMContentLoaded', () => {
    const userSearch = document.getElementById('user-search');
    const roleFilter = document.getElementById('role-filter');
    const rows = Array.from(document.querySelectorAll('[data-role]'));

    if (!userSearch || !roleFilter || rows.length === 0) {
        return;
    }

    const applyFilters = () => {
        const search = userSearch.value.trim().toLowerCase();
        const role = roleFilter.value;

        rows.forEach((row) => {
            const searchText = (row.dataset.searchText || row.textContent || '').toLowerCase();
            const rowRole = row.dataset.role || '';
            const matchesSearch = search === '' || searchText.includes(search);
            const matchesRole = role === 'all' || role === '' || rowRole === role;

            row.hidden = !(matchesSearch && matchesRole);
        });
    };

    userSearch.addEventListener('input', applyFilters);
    roleFilter.addEventListener('change', applyFilters);
    applyFilters();
});