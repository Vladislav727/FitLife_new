document.addEventListener('DOMContentLoaded', () => {
    const eventSearch = document.getElementById('event-search');
    const typeFilter = document.getElementById('type-filter');

    const applyFilters = () => {
        const search = eventSearch?.value.toLowerCase() || '';
        const type = typeFilter?.value || '';

        document.querySelectorAll('.events-table tr').forEach((row) => {
            const user = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase();
            const typeCell = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase();
            const description = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase();
            const matchesSearch = !search || (user && description && (user.includes(search) || description.includes(search)));
            const matchesType = !type || (typeCell && typeCell === type);

            row.style.display = matchesSearch && matchesType ? '' : 'none';
        });
    };

    eventSearch?.addEventListener('input', applyFilters);
    typeFilter?.addEventListener('change', applyFilters);
});