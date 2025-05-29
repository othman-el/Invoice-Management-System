<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

include_once 'Database.php';

$stmt = $pdo->prepare("SELECT f.*, c.NameEntreprise, c.ICE, c.Adresse, c.Email, c.Contact, c.NumeroGSM, c.NumeroFixe, c.Activite 
                       FROM factures f 
                       JOIN liste_fourniseur_client c ON f.ClientID = c.ID 
                       ORDER BY f.Date_Creation DESC");
$stmt->execute();
$factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Factures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include './front/head_front.php'; ?>

    <div class="table-responsive container mt-4">
        <h1 class="text-center mb-4">Liste des factures</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div style="max-width: 400px;">
                <div class="position-relative">
                    <input type="text" id="searchInput" placeholder="Rechercher par ID ou Nom"
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
                            d="M 21 3 C 11.6 3 4 10.6 4 20s7.6 17 17 17c3.7 0 7.1-1.2 9.9-3.2L44 47l2.9-2.9-14-14c2.6-2.9 4.1-6.7 4.1-10.8C38 10.6 30.4 3 21 3zM21 5c8.3 0 15 6.7 15 15s-6.7 15-15 15S6 28.3 6 20 12.7 5 21 5z" />
                    </svg>
                </div>
            </div>

            <a href="ajouter_facture.php" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-file-invoice"></i> Ajouter une facture
            </a>
        </div>

        <table class="table table-striped table-bordered text-center align-middle" style="white-space: nowrap;">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Client Nº</th>
                    <th>Nom de l'entreprise</th>
                    <th>ICE</th>
                    <th>Adresse</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>GSM</th>
                    <th>Fixe</th>
                    <th>Activité</th>
                    <th>Type</th>
                    <th>Montant HT</th>
                    <th>TVA</th>
                    <th>Montant TTC</th>
                    <th>Document</th>
                    <th>Date création</th>
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
                    <td>
                        <?php
                    $docPath = $facture['Document'];
                    if (!empty($docPath) && file_exists($docPath)) {
                        $filename = basename($docPath);
                        $encodedFile = urlencode($filename);
                        echo '<a href="download_document.php?file=' . $encodedFile . '" class="btn btn-sm btn-success me-1" title="Télécharger">Télécharger</a>';
                        echo '<a href="view_document.php?file=' . $encodedFile . '" target="_blank" class="btn btn-sm btn-info" title="Voir">Voir</a>';
                    } else {
                        echo 'Aucun fichier';
                    }
                    ?>
                    </td>
                    <td><?= htmlspecialchars($facture['Date_Creation']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    </div>

    <script src="recherche.js"></script>
</body>

</html>