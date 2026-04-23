document.addEventListener('DOMContentLoaded', () => {
    const postSearch = document.getElementById('post-search');

    if (!postSearch) {
        return;
    }

    postSearch.addEventListener('input', function () {
        const search = this.value.toLowerCase();

        document.querySelectorAll('.posts-table tr').forEach((row) => {
            const user = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase();
            const content = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase();

            row.style.display = user && content && (user.includes(search) || content.includes(search)) ? '' : 'none';
        });
    });
});