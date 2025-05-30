<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include_once 'Database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de la facture invalide.");
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
    die("Facture introuvable.");
}

$sqlItems = "SELECT * FROM facture_items WHERE FactureID = ? ORDER BY ordre ASC";
$stmtItems = $pdo->prepare($sqlItems);
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

$tva_amount = $facture['Montant_Total_HT'] * ($facture['TVA'] / 100);
$total_ttc = $facture['Montant_Total_HT'] + $tva_amount;

try {
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    ob_start();
    ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Facture <?= htmlspecialchars($facture['N_facture']) ?></title>
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
    </style>
</head>

<body>
    <!-- En-tête -->
    <div class="row">
        <div class="col-6">
            <h1 class="text-primary">ENRIQUE TECHNOLOGY</h1>
            <p>Entrepôt n° 76, Résidence Chahbae D,<br>Av. Louis Van Beethoven, Tanger - Maroc</p>
            <p>ICE: 003574700000586</p>
        </div>
        <div class="col-6 text-end">
            <h1 class="invoice-title"><?= htmlspecialchars($facture['type']) ?></h1>
            <p>N°: <?= htmlspecialchars($facture['N_facture']) ?></p>
            <p>Date: <?= date('d/m/Y', strtotime($facture['Date_Creation'])) ?></p>
        </div>
    </div>

    <!-- Infos client -->
    <div class="row">
        <div class="col-6">
            <h5 class="text-primary">Client</h5>
            <p><strong><?= htmlspecialchars($facture['NameEntreprise']) ?></strong></p>
            <p>Email: <?= htmlspecialchars($facture['Email']) ?></p>
            <p>Adresse: <?= htmlspecialchars($facture['Adresse']) ?></p>
            <p>Contact: <?= htmlspecialchars($facture['Contact']) ?></p>
            <p>ICE: <?= htmlspecialchars($facture['ICE']) ?></p>
        </div>
        <div class="col-6">
            <h5 class="text-primary">Informations</h5>
            <p>TVA: <?= htmlspecialchars($facture['TVA']) ?>%</p>
        </div>
    </div>

    <!-- Items -->
    <div class="row">
        <div class="col-12">
            <table class="table-bordered">
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
    <div class="justify-content-end">
        <div class="col-md-6">
            <table class="table-bordered totals-table">
                <tr>
                    <th>Sous-total HT</th>
                    <td class="text-end"><?= number_format($facture['Montant_Total_HT'], 2) ?>DH</td>
                </tr>
                <tr>
                    <th>TVA (<?= $facture['TVA'] ?>%)</th>
                    <td class="text-end"><?= number_format($tva_amount, 2) ?>DH</td>
                </tr>
                <tr class="total-section">
                    <th>Total TTC</th>
                    <td class="text-end"><?= number_format($facture['Montant_Total_TTC'], 2) ?>DH</td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>
<?php
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $filename = 'Facture_' . $facture['N_facture'] . '.pdf';
    $dompdf->stream($filename, ['Attachment' => true]);

} catch (Exception $e) {
    echo "Erreur lors de la génération du PDF: " . $e->getMessage();
}