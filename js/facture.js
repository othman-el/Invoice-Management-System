document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase().trim();
    const tableBody = document.querySelector('table tbody');
    const rows = tableBody.querySelectorAll('tr');
    
    rows.forEach(row => {
        if (row.cells.length < 2) {
            return;
        }
        
        const clientNumber = row.cells[0].textContent.toLowerCase().trim();
        const companyName = row.cells[1].textContent.toLowerCase().trim();
        
        if (clientNumber.includes(filter) || companyName.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    checkSearchResults();
});

function checkSearchResults() {
    const tableBody = document.querySelector('table tbody');
    const visibleRows = Array.from(tableBody.querySelectorAll('tr')).filter(row => 
        row.style.display !== 'none' && row.cells.length > 2
    );
    
    const noResultsRow = tableBody.querySelector('.no-results-row');
    if (noResultsRow) {
        noResultsRow.remove();
    }
    
    if (visibleRows.length === 0 && document.getElementById('searchInput').value.trim() !== '') {
        const noResultsRow = document.createElement('tr');
        noResultsRow.className = 'no-results-row';
        noResultsRow.innerHTML = '<td colspan="15" class="text-center text-muted">Aucun résultat trouvé</td>';
        tableBody.appendChild(noResultsRow);
    }
}

document.getElementById('searchInput').addEventListener('input', function() {
    if (this.value.trim() === '') {
        const tableBody = document.querySelector('table tbody');
        const rows = tableBody.querySelectorAll('tr');
        
        rows.forEach(row => {
            if (!row.classList.contains('no-results-row')) {
                row.style.display = '';
            }
        });
        
        const noResultsRow = tableBody.querySelector('.no-results-row');
        if (noResultsRow) {
            noResultsRow.remove();
        }
    }
});
