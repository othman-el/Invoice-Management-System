<?php
include_once 'Database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$limit = 10;

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalStmt = $pdo->query("SELECT COUNT(*) FROM charge_fix");
$totalRows = $totalStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

$sql = "SELECT * FROM charge_fix LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$charges = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Charges Fixes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include './front/head_front.php'; ?>
    <h1 class="text-center my-4">Liste des Charges Fixes</h1>
    <div class="d-flex justify-content-between align-items-center ms-5">
        <div class="container py-2">
            <a href="ajouter_charge_fix.php" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Ajouter Charge Fixe
            </a>

        </div>
        <div class="container py-2" style="max-width: 500px;">
            <div class="position-relative">
                <input type="text" id="searchInput"
                    placeholder="Rechercher par ID, Désignation, Date, Mois, Code Réf ou Catégorie"
                    class="form-control ps-5 rounded-pill border-0 shadow-sm text-white bg-primary" />

                <style>
                #searchInput::placeholder {
                    color: white;
                    opacity: 1;
                }
                </style>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 50 50"
                    class="position-absolute top-50 start-0 translate-middle-y ms-3">
                    <path
                        d="M 21 3 C 11.621094 3 4 10.621094 4 20 C 4 29.378906 11.621094 37 21 37 C 24.710938 37 28.140625 35.804688 30.9375 33.78125 L 44.09375 46.90625 L 46.90625 44.09375 L 33.90625 31.0625 C 36.460938 28.085938 38 24.222656 38 20 C 38 10.621094 30.378906 3 21 3 Z M 21 5 C 29.296875 5 36 11.703125 36 20 C 36 28.296875 29.296875 35 21 35 C 12.703125 35 6 28.296875 6 20 C 6 11.703125 12.703125 5 21 5 Z">
                    </path>
                </svg>
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-striped table-bordered">
            <thead class="bg-primary text-center">
                <tr>
                    <th class="text-white">ID</th>
                    <th class="text-white">Désignation</th>
                    <th class="text-white">Date Achat</th>
                    <th class="text-white">Mois</th>
                    <th class="text-white">Total Out</th>
                    <th class="text-white">Montant</th>
                    <th class="text-white">Code Réf</th>
                    <th class="text-white">Catégorie</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($charges) > 0): ?>
                <?php foreach ($charges as $charge): ?>
                <tr>
                    <td class="text-center"><?= htmlspecialchars($charge['ID']) ?></td>
                    <td><?= htmlspecialchars($charge['DESIGNATION']) ?></td>
                    <td><?= htmlspecialchars($charge['Date_Achat']) ?></td>
                    <td><?= htmlspecialchars($charge['M']) ?></td>
                    <td><?= htmlspecialchars($charge['TOTAL_OUT']) ?></td>
                    <td><?= htmlspecialchars($charge['Montant']) ?></td>
                    <td><?= htmlspecialchars($charge['Code_REF']) ?></td>
                    <td><?= htmlspecialchars($charge['Categorie']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Aucune charge fixe trouvée.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Précédent">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Suivant">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <script src="recherche_charge_fix.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>