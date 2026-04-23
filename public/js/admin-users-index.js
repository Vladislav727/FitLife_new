document.addEventListener('DOMContentLoaded', () => {
    const userSearch = document.getElementById('user-search');
    const roleFilter = document.getElementById('role-filter');

    const applyFilters = () => {
        const search = userSearch?.value.toLowerCase() || '';
        const role = roleFilter?.value || '';

        document.querySelectorAll('.users-table tr').forEach((row) => {
            const name = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase();
            const roleCell = row.querySelector('td:nth-child(4) .role-badge')?.textContent.toLowerCase();
            const matchesSearch = !search || (name && email && (name.includes(search) || email.includes(search)));
            const matchesRole = !role || (roleCell && roleCell === role);

            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        });
    };

    userSearch?.addEventListener('input', applyFilters);
    roleFilter?.addEventListener('change', applyFilters);
});