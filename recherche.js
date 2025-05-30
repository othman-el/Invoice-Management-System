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
        backdrop: `
            rgba(0,0,0,0.4)
            url("/images/trash-animation.gif")
            left top
            no-repeat
        `
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'supprimer.php?id=' + userId;
        }
    });
}
function confirmDelete(userId) {
    Swal.fire({
        title: 'Confirmation',
        text: "Voulez-vous vraiment supprimer cet utilisateur ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
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

