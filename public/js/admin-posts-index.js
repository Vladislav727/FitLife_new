document.addEventListener('DOMContentLoaded', () => {
    const postSearch = document.getElementById('post-search');
    const rows = Array.from(document.querySelectorAll('[data-post-row]'));

    if (!postSearch || rows.length === 0) {
        return;
    }

    const applyFilters = () => {
        const search = postSearch.value.trim().toLowerCase();

        rows.forEach((row) => {
            const searchText = (row.dataset.searchText || row.textContent || '').toLowerCase();
            row.hidden = search !== '' && !searchText.includes(search);
        });
    };

    postSearch.addEventListener('input', applyFilters);
    applyFilters();
});