document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('comment-search');
    const rows = Array.from(document.querySelectorAll('[data-comment-row]'));
    const emptyState = document.querySelector('[data-comments-empty]');

    if (!searchInput || rows.length === 0) {
        return;
    }

    const filterRows = () => {
        const query = searchInput.value.trim().toLowerCase();
        let visibleCount = 0;

        rows.forEach((row) => {
            const text = (row.dataset.searchText || row.textContent || '').toLowerCase();
            const matches = query === '' || text.includes(query);

            row.hidden = !matches;

            if (matches) {
                visibleCount += 1;
            }
        });

        if (emptyState) {
            emptyState.hidden = visibleCount !== 0;
        }
    };

    searchInput.addEventListener('input', filterRows);
    filterRows();
});