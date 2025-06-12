<?php
include_once 'Database.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$user_id = $_SESSION['user']['id'];

$facturesParPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$debut = ($page - 1) * $facturesParPage;

$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM factures WHERE user_id = :user_id");
$totalStmt->execute([':user_id' => $user_id]);
$totalFactures = $totalStmt->fetchColumn();

$totalPages = ($totalFactures > 0) ? ceil($totalFactures / $facturesParPage) : 1;

$sql = "SELECT f.*, c.NameEntreprise, c.ICE, c.Adresse, c.Email, c.Contact, c.NumeroGSM, c.NumeroFixe, c.Activite
        FROM factures f 
        JOIN liste_fourniseur_client c ON f.ClientID = c.ID
        WHERE f.user_id = :user_id
        ORDER BY f.Date_Creation DESC
        LIMIT :limite OFFSET :debut";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':limite', $facturesParPage, PDO::PARAM_INT);
$stmt->bindValue(':debut', $debut, PDO::PARAM_INT);
$stmt->execute();

$factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Factures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
    <style>
    table th,
    table td {
        white-space: nowrap;
    }
    </style>

</head>

<body>
    <?php include './front/head_front.php'; ?>
    <h2 class="text-center mb-4 my-4">Liste des factures</h2>


    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="ajouter_facture.php" class="btne  btn-sm d-flex align-items-center gap-2 ms-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM8.5 7v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 1 0z" />
            </svg>
            <span>Ajouter une facture</span>
        </a>
        <div style="max-width: 400px;">
            <div class="position-relative">
                <input type="text" id="searchInput" placeholder="Rechercher par Client Nº ou Nom d'entreprise"
                    class="form-control ps-5 rounded-pill border-0 shadow-sm text-white" />
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 50 50"
                    class="position-absolute top-50 start-0 translate-middle-y ms-3">
                    <path
                        d="M 21 3 C 11.6 3 4 10.6 4 20s7.6 17 17 17c3.7 0 7.1-1.2 9.9-3.2L44 47l2.9-2.9-14-14c2.6-2.9 4.1-6.7 4.1-10.8C38 10.6 30.4 3 21 3zM21 5c8.3 0 15 6.7 15 15s-6.7 15-15 15S6 28.3 6 20 12.7 5 21 5z" />
                </svg>
            </div>
        </div>


    </div>

    <table class="table table-striped table-bordered table-responsive" id="dataTable">
        <thead class="text-center">
            <tr>
                <th class="text-white">Client Nº</th>
                <th class="text-white">Nom de l'entreprise</th>
                <th class="text-white">ICE</th>
                <th class="text-white">Adresse</th>
                <th class="text-white">Email</th>
                <th class="text-white ">Contact</th>
                <th class="text-white ">GSM</th>
                <th class="text-white ">Fixe</th>
                <th class="text-white ">Activité</th>
                <th class="text-white ">Type</th>
                <th class="text-white ">Montant HT</th>
                <th class="text-white ">TVA</th>
                <th class="text-white ">Montant TTC</th>
                <th class="text-white ">Date création</th>
                <th class="text-white ">Document</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($factures as $facture): ?>
            <tr>
                <td><?= htmlspecialchars($facture['ClientID']) ?></td>
                <td><?= htmlspecialchars($facture['NameEntreprise']) ?></td>
                <td><?= htmlspecialchars($facture['ICE']) ?></td>
                <td><?= htmlspecialchars($facture['Adresse']) ?></td>
                <td><?= htmlspecialchars($facture['Email']) ?></td>
                <td><?= htmlspecialchars($facture['Contact']) ?></td>
                <td><?= htmlspecialchars($facture['NumeroGSM']) ?></td>
                <td><?= htmlspecialchars($facture['NumeroFixe']) ?></td>
                <td><?= htmlspecialchars($facture['Activite']) ?></td>
                <td><?= htmlspecialchars($facture['type']) ?></td>
                <td><?= htmlspecialchars($facture['Montant_Total_HT']) ?> DH</td>
                <td><?= htmlspecialchars($facture['TVA']) ?> %</td>
                <td><?= htmlspecialchars($facture['Montant_Total_TTC']) ?>DH</td>
                <td><?= date('Y-m-d', strtotime($facture['Date_Creation'])) ?></td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="voir_facture.php?id=<?= $facture['ID'] ?>"
                            class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1"
                            title="Voir la facture complète">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                <path
                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                            </svg>
                            <span>Voir</span>
                        </a>

                        <a href="telecharger_pdf.php?id=<?= $facture['ID'] ?>"
                            class="btn btn-outline-success btn-sm d-flex align-items-center gap-1"
                            title="Télécharger la facture PDF">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                <path
                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                            </svg>
                            <span>Télécharger</span>
                        </a>

                        <a href="supprimer_facture.php?id=<?= $facture['ID'] ?>">
                            <button onclick="confirmDelete(<?= $facture['ID'] ?>)"
                                class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1"
                                title="Supprimer la facture">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                                    <path
                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />
                                </svg>
                                <span>Supprimer</span>
                            </button>
                        </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($factures)): ?>
            <tr>
                <td colspan="15" class="text-center">Aucune facture trouvée.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($totalFactures > $facturesParPage): ?>
    <nav aria-label="Pagination">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page - 1 ?>">Précédent</a>
            </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
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
    <?php endif; ?>


    <script src="facture.js"></script>
</body>

</html>