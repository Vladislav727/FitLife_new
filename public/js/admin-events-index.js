document.addEventListener('DOMContentLoaded', () => {
    const eventSearch = document.getElementById('event-search');
    const typeFilter = document.getElementById('type-filter');
    const rows = Array.from(document.querySelectorAll('[data-event-row]'));

    if (!eventSearch || !typeFilter || rows.length === 0) {
        return;
    }

    const applyFilters = () => {
        const search = eventSearch.value.trim().toLowerCase();
        const type = typeFilter.value;

        rows.forEach((row) => {
            const searchText = (row.dataset.searchText || row.textContent || '').toLowerCase();
            const rowType = row.dataset.type || '';
            const matchesSearch = search === '' || searchText.includes(search);
            const matchesType = type === 'all' || type === '' || rowType === type;

            row.hidden = !(matchesSearch && matchesType);
        });
    };

    eventSearch.addEventListener('input', applyFilters);
    typeFilter.addEventListener('change', applyFilters);
    applyFilters();
});