<?php 
include_once 'Database.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$sql_i = "SELECT * FROM items JOIN liste_fourniseur_client ON items.Fournisseur = liste_fourniseur_client.ID";
$stmt = $pdo->prepare($sql_i);    
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


$limit = 10; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$countStmt = $pdo->query("SELECT COUNT(*) FROM items");
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $limit);

$sql_i = "SELECT * FROM items 
          JOIN liste_fourniseur_client ON items.Fournisseur = liste_fourniseur_client.ID 
          ORDER BY items.ID DESC 
          LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql_i);    
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <?php include './front/head_front.php'; ?>

    <h2 class="text-center mb-4 my-4">Liste des Items</h2>

    <div class="d-flex justify-content-between align-items-center">
        <div class="container py-2">
            <a href="Ajouter_items.php" class="btne re">
                <i class="fa fa-plus"></i>Ajouter un item
            </a>
        </div>
        <div class="container py-2" style="max-width: 400px;">
            <div class="position-relative">
                <input type="text" id="searchInput"
                    placeholder="Rechercher par Fournisseur, N° Facture, Client ou Code Client"
                    class="form-control ps-5 rounded-pill border-0 shadow-sm text-white" />
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 50 50"
                    class="position-absolute top-50 start-0 translate-middle-y ms-3">
                    <path
                        d="M 21 3 C 11.6 3 4 10.6 4 20s7.6 17 17 17c3.7 0 7.1-1.2 9.9-3.2L44 47l2.9-2.9-14-14c2.6-2.9 4.1-6.7 4.1-10.8C38 10.6 30.4 3 21 3zM21 5c8.3 0 15 6.7 15 15s-6.7 15-15 15S6 28.3 6 20 12.7 5 21 5z" />
                </svg>
            </div>
        </div>
    </div>
    <table class="table table-striped table-bordered text-center align-middle">
        <thead class=" text-white">
            <tr>
                <th colspan="10" class="text-center text-white">Fournisseur</th>
                <th colspan="8" class="text-center text-white">Client</th>
            </tr>

            <tr>
                <th class="text-white ">ID</th>
                <th class="text-white ">Date</th>
                <th class="text-white ">Fournisseur</th>
                <th class="text-white ">N Facture</th>
                <th class="text-white ">Article</th>
                <th class="text-white ">Designation</th>
                <th class="text-white ">Qte</th>
                <th class="text-white ">Montant uHT</th>
                <th class="text-white ">Total Uht</th>
                <th class="text-white ">TVA</th>
                <th class="text-white ">TOTAL TTC</th>
                <th class="text-white ">Date</th>
                <th class="text-white ">N Devis</th>
                <th class="text-white ">N Facture</th>
                <th class="text-white ">N Client</th>
                <th class="text-white ">Code client</th>
                <th class="text-white ">Mt HT</th>
                <th class="text-white ">Mt TTC</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['ID']) ?></td>
                <td><?= htmlspecialchars($item['Date']) ?></td>
                <td><?= htmlspecialchars($item['NameEntreprise']) ?></td>
                <td><?= htmlspecialchars($item['N_Facture']) ?></td>
                <td><?= htmlspecialchars($item['Article']) ?></td>
                <td><?= htmlspecialchars($item['Designation']) ?></td>
                <td><?= htmlspecialchars($item['Qte']) ?></td>
                <td><?= htmlspecialchars($item['Montant_uHT']) ?></td>
                <td><?= htmlspecialchars($item['Total_Uht']) ?></td>
                <td>20</td>
                <td><?= htmlspecialchars($item['TOTAL_TTC']) ?></td>
                <td><?= htmlspecialchars($item['Date_c']) ?></td>
                <td><?= htmlspecialchars($item['N_Devis']) ?></td>
                <td><?= htmlspecialchars($item['N_Facture_C']) ?></td>
                <td><?= htmlspecialchars($item['NameEntreprise']) ?></td>
                <td><?= htmlspecialchars($item['Code_client']) ?></td>
                <td><?= htmlspecialchars($item['Mt_HT']) ?></td>
                <td><?= htmlspecialchars($item['Mt_TTC']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
            <tr>
                <td colspan="18">Aucun item trouvé.</td>
            </tr>
            <?php endif; ?>
        </tbody>

    </table>
    <div class="container my-4 d-flex justify-content-center">
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">Précédent</a>
                </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">Suivant</a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="items.js"></script>
</body>

</html>