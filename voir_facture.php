<?php
include_once 'Database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: liste_factures.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT f.*, c.NameEntreprise, c.ICE, c.Adresse, c.Email, c.Contact, c.NumeroGSM, c.NumeroFixe, c.Activite 
        FROM factures f 
        JOIN liste_fourniseur_client c ON f.ClientID = c.ID 
        WHERE f.ID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$facture = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$facture) {
    header("Location: liste_factures.php");
    exit;
}

$sqlItems = "SELECT * FROM facture_items WHERE FactureID = ? ORDER BY ordre ASC";
$stmtItems = $pdo->prepare($sqlItems);
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

$tva_amount = $facture['Montant_Total_HT'] * ($facture['TVA'] / 100);
$total_ttc = $facture['Montant_Total_HT'] + $tva_amount;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Facture #<?= htmlspecialchars($facture['N_facture']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        margin: 20px;
    }

    .text-primary {
        color: #0d6efd;
    }

    .invoice-title {
        color: #00a0c6;
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0;
    }

    .row {
        display: table;
        width: 100%;
        margin-bottom: 20px;
    }

    .col-6 {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-right: 15px;
    }

    .col-12 {
        width: 100%;
    }

    .text-end {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    h1 {
        font-size: 1.5rem;
        margin: 0 0 10px 0;
    }

    h5 {
        color: #0d6efd;
        font-size: 1rem;
        margin: 0 0 10px 0;
    }

    p {
        margin: 5px 0;
        line-height: 1.4;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
        padding: 8px;
    }

    .table-header {
        background-color: #00a0c6;
        color: white;
        text-align: center;
    }

    .total-section {
        background-color: #00a0c6;
        color: white;
        font-weight: bold;
    }

    .justify-content-end {
        text-align: right;
    }

    .col-md-6 {
        width: 50%;
        margin-left: 50%;
    }

    .totals-table {
        width: 100%;
        margin-top: 20px;
    }

    @page {
        margin: 0;
        size: auto;
    }

    @media print {

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
            font-size: 15px;
        }

        .no-print {
            display: none !important;
        }
    }
    </style>
</head>

<body>

    <!-- Actions -->
    <div class="container mt-3 no-print">
        <div class="d-flex justify-content-between align-items-center">
            <a href="Liste_Facturation.php" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Retour
            </a>

            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-outline-primary d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path
                            d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z" />
                        <path
                            d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                    </svg>
                    Imprimer
                </button>

                <a href="telecharger_pdf.php?id=<?= $facture['ID'] ?>"
                    class="btn btn-outline-success d-flex align-items-center gap-2" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path
                            d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                        <path
                            d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                    </svg>
                    Télécharger PDF
                </a>
            </div>
        </div>
        <hr class="my-3">
    </div>

    <!-- Facture -->
    <div class="containe">
        <div class="container mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <img src="images/logo.png" alt="Logo" style="max-width: 200px; max-height: 200px;">
                </div>
                <div class="text-end">
                    <h1 class="invoice-title">
                        <?php
                        if ($facture['type'] == 'bl') {
                            echo 'Bon de Livraison';
                        } elseif ($facture['type'] == 'facture') {
                            echo 'Facture';
                        } elseif ($facture['type'] == 'devis') {
                            echo 'Devis';
                        }
                        ?>
                    </h1>
                    <p>N°: <?= htmlspecialchars($facture['N_facture']) ?></p>
                    <p>Date: <?= date('d/m/Y', strtotime($facture['Date_Creation'])) ?></p>
                </div>
            </div>
        </div>


        <!-- En-tête -->
        <div class="container mb-4">
            <div class="d-flex justify-content-evenly align-items-center">
                <div class="col-6">
                    <h1 class="text-primary">Émetteur</h1>

                    <strong>
                        <h6>ENRIQUE TECHNOLOGY</h6>
                    </strong>
                    <p>Entrepôt n° 76, Résidence Chahbae D,<br>Av. Louis Van Beethoven, Tanger - Maroc</p>
                    <p>ICE: 003574700000586</p>
                </div>

                <!-- Infos client -->
                <div class="row mb-4 text-end">
                    <div class="col-6">
                        <h1 class="text-primary">Client</h1>
                        <h6>
                            <strong><?= htmlspecialchars($facture['NameEntreprise']) ?></strong>
                        </h6>
                        <p>Email: <?= htmlspecialchars($facture['Email']) ?></p>
                        <p>Adresse: <?= htmlspecialchars($facture['Adresse']) ?></p>
                        <p>Contact: <?= htmlspecialchars($facture['Contact']) ?></p>
                        <p>ICE: <?= htmlspecialchars($facture['ICE']) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Items -->
        <div class="row ">
            <div class="col-12">
                <table class="table-bordered ms-3">
                    <thead class="table-header">
                        <tr>
                            <th>#</th>
                            <th>Désignation</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire</th>
                            <th>Total HT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $item): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($item['Designation']) ?></td>
                            <td class="text-center"><?= $item['Quantite'] ?></td>
                            <td class="text-end"><?= number_format($item['Prix_Unit'], 2) ?>DH</td>
                            <td class="text-end"><?= number_format($item['Montant_HT'], 2) ?>DH</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totaux -->
        <div class="d-flex justify-content-evenly">
            <div style="min-width: 300px;">
                <div class="col-md-12">
                    <table class="table-bordered totals-table w-100">
                        <tr class="text-end total-section">
                            <td>Base</td>
                            <td>Taux</td>
                            <td>Taxe</td>
                        </tr>
                        <tr>
                            <td class="text-end"><?= number_format($facture['Montant_Total_HT'], 2) ?> DH</td>
                            <td class="text-end"><?= $facture['TVA'] ?> %</td>
                            <td class="text-end"><?= number_format($tva_amount, 2) ?> DH</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="min-width: 300px;">
                <div class="col-md-12">
                    <table class="table-bordered totals-table w-100">
                        <tr class="text-end total-section">
                            <td>NET A PAYER</td>
                        </tr>
                        <tr>
                            <td class="text-end"><?= number_format($total_ttc, 2) ?> DH</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <p class="ms-4 text-primary">
            Conditions de paiement : <?= htmlspecialchars($facture['condition_re']) ?>
        </p>
        <p class="ms-4 text-primary">
            Conditions de paiement : <?= htmlspecialchars($facture['Conditions']) ?>
        </p>
        <p class="ms-4 text-primary">
            Date de validité : <?= htmlspecialchars($facture['Datee']) ?>
        </p>
        <p class="ms-4 text-primary">
            Délai de livraison : <?= htmlspecialchars($facture['livraison']) ?>
        </p><br>
        <p class=" ms-4">
            Nous sommes à votre disposition pour tout complément d'informations.
        </p>
        <p class=" ms-4">
            Nous vous prions d'agréer, Cher Client, nos sincères salutations.
        </p>
    </div><br>
    <footer>
        <img src="images/unnamed" alt="Footer Image">
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>