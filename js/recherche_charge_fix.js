document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => {
        if (row.cells.length >= 8) {
            const id = row.cells[0].textContent.toLowerCase();
            const designation = row.cells[1].textContent.toLowerCase();
            const dateAchat = row.cells[2].textContent.toLowerCase();
            const mois = row.cells[3].textContent.toLowerCase();
            const codeRef = row.cells[6].textContent.toLowerCase();
            const categorie = row.cells[7].textContent.toLowerCase();

            if (id.includes(filter) || 
                designation.includes(filter) || 
                dateAchat.includes(filter) || 
                mois.includes(filter) || 
                codeRef.includes(filter) || 
                categorie.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
});

function confirmDelete(userId) {
    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Cette action est irréversible!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer!',
        cancelButtonText: 'Annuler',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'supprimer.php?id=' + userId;
        }
    });
}

$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
});