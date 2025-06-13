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
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $options->set('chroot', __DIR__);

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

    .container {
        width: 100%;
        margin-bottom: 20px;
    }

    .d-flex {
        display: table;
        width: 100%;
    }

    .justify-content-between {
        display: table;
        width: 100%;
    }

    .justify-content-evenly {
        display: table;
        width: 100%;
    }

    .align-items-center {
        vertical-align: middle;
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

    .logo-img {
        max-width: 150px;
        height: auto;
    }


    h1 {
        font-size: 1.5rem;
        margin: 0 0 10px 0;
    }

    h6 {
        font-size: 1.1rem;
        margin: 0 0 10px 0;
        font-weight: bold;
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

    .totals-container {
        display: table;
        width: 100%;
        margin-top: 20px;
    }

    .totals-left {
        display: table-cell;
        width: 50%;
        padding-right: 15px;
    }

    .totals-right {
        display: table-cell;
        width: 50%;
        padding-left: 15px;
    }

    .totals-table {
        width: 100%;
    }

    .mb-4 {
        margin-bottom: 20px;
    }

    .ms-3 {
        margin-left: 15px;
    }

    .ms-4 {
        margin-left: 20px;
    }

    .w-100 {
        width: 100%;
    }



    .logo-container {
        display: table-cell;
        width: 50%;
        vertical-align: top;
    }

    .invoice-header {
        display: table-cell;
        width: 50%;
        text-align: right;
        vertical-align: top;
    }
    </style>
</head>

<body>
    <div class="container mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <img src="<?= 'file://' . __DIR__ . '/images/logo.png' ?>" class="logo-img" alt="Logo">
            </div>
            <div class="invoice-header">
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

    <div class="container mb-4">
        <div class="d-flex justify-content-evenly align-items-center">
            <div class="col-6">
                <h1 class="text-primary">Émetteur</h1>
                <h6><strong>ENRIQUE TECHNOLOGY</strong></h6>
                <p>Entrepôt n° 76, Résidence Chahbae D,<br>Av. Louis Van Beethoven, Tanger - Maroc</p>
                <p>ICE: 003574700000586</p>
            </div>

            <div class="col-6 text-end">
                <h1 class="text-primary">Client</h1>
                <h6><strong><?= htmlspecialchars($facture['NameEntreprise']) ?></strong></h6>
                <p>Email: <?= htmlspecialchars($facture['Email']) ?></p>
                <p>Adresse: <?= htmlspecialchars($facture['Adresse']) ?></p>
                <p>Contact: <?= htmlspecialchars($facture['Contact']) ?></p>
                <p>ICE: <?= htmlspecialchars($facture['ICE']) ?></p>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="container">
        <div class="col-12">
            <table class="table-bordered ms-3">
                <thead class="table-header">
                    <tr>
                        <th>Désignation</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Total HT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $index => $item): ?>
                    <tr>
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

    <!-- Totals Section -->
    <div class="totals-container">
        <div class="totals-left">
            <table class="table-bordered totals-table">
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
        <div class="totals-right">
            <table class="table-bordered totals-table">
                <tr class="text-end total-section">
                    <td>NET A PAYER</td>
                </tr>
                <tr>
                    <td class="text-end"><?= number_format($total_ttc, 2) ?> DH</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="ms-4 text-primary">
        <p>Conditions de réglement : <?= htmlspecialchars($facture['condition_re']) ?></p>
        <p>Conditions de paiement : <?= htmlspecialchars($facture['Conditions']) ?></p>
        <p>Date de validité : <?= htmlspecialchars($facture['Datee']) ?></p>
        <p>Délai de livraison : <?= htmlspecialchars($facture['livraison']) ?></p>
    </div>

    <div class="ms-4">
        <p>Nous sommes à votre disposition pour tout complément d'informations.</p>
        <p>Nous vous prions d'agréer, Cher Client, nos sincères salutations.</p>
    </div>
    <footer>
        <img src="<?= 'file://' . __DIR__ . '/images/unnamed.png' ?>" alt="Footer Image">
    </footer>
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
?>