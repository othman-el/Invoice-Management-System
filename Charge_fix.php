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

    <div class="d-flex justify-content-between align-items-center">
        <div class="container py-2">
            <a href="ajouter_charge_fix.php" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Ajouter Charge Fixe
            </a>
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

</body>

</html>