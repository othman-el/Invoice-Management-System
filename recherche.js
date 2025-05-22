document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#dataTable tbody tr');

    rows.forEach(row => {
        const idCell = row.cells[0].textContent.toLowerCase();
        const nameCell = row.cells[1].textContent.toLowerCase();

        if (idCell.includes(filter) || nameCell.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});