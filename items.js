document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase().trim();
    const tableBody = document.querySelector('table tbody');
    const rows = tableBody.querySelectorAll('tr');
    
    rows.forEach(row => {
        if (row.cells.length < 18) {
            return;
        }

        const fournisseur = row.cells[2].textContent.toLowerCase().trim();
        const nFacture = row.cells[3].textContent.toLowerCase().trim();
        const nFactureC = row.cells[13].textContent.toLowerCase().trim();
        const client = row.cells[14].textContent.toLowerCase().trim();
        const codeClient = row.cells[15].textContent.toLowerCase().trim();
        
        if (fournisseur.includes(filter) || 
            nFacture.includes(filter) || 
            nFactureC.includes(filter) || 
            client.includes(filter) || 
            codeClient.includes(filter)) {
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
        row.style.display !== 'none' && row.cells.length >= 18
    );
    
    const noResultsRow = tableBody.querySelector('.no-results-row');
    if (noResultsRow) {
        noResultsRow.remove();
    }
    
    if (visibleRows.length === 0 && document.getElementById('searchInput').value.trim() !== '') {
        const noResultsRow = document.createElement('tr');
        noResultsRow.className = 'no-results-row';
        noResultsRow.innerHTML = '<td colspan="18" class="text-center text-muted">Aucun résultat trouvé</td>';
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

function advancedSearch() {
    const searchType = document.getElementById('searchType') ? document.getElementById('searchType').value : 'all';
    const filter = document.getElementById('searchInput').value.toLowerCase().trim();
    const tableBody = document.querySelector('table tbody');
    const rows = tableBody.querySelectorAll('tr');
    
    rows.forEach(row => {
        if (row.cells.length < 18) return;
        
        let shouldShow = false;
        
        switch(searchType) {
            case 'fournisseur':
                shouldShow = row.cells[2].textContent.toLowerCase().includes(filter);
                break;
            case 'n_facture':
                shouldShow = row.cells[3].textContent.toLowerCase().includes(filter);
                break;
            case 'n_facture_c':
                shouldShow = row.cells[13].textContent.toLowerCase().includes(filter);
                break;
            case 'client':
                shouldShow = row.cells[14].textContent.toLowerCase().includes(filter);
                break;
            case 'code_client':
                shouldShow = row.cells[15].textContent.toLowerCase().includes(filter);
                break;
            default:
                shouldShow = row.cells[2].textContent.toLowerCase().includes(filter) ||
                             row.cells[3].textContent.toLowerCase().includes(filter) ||
                             row.cells[13].textContent.toLowerCase().includes(filter) ||
                             row.cells[14].textContent.toLowerCase().includes(filter) ||
                             row.cells[15].textContent.toLowerCase().includes(filter);
        }
        
        row.style.display = shouldShow ? '' : 'none';
    });
    
    checkSearchResults();
}
