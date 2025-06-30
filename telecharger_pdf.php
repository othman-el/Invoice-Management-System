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
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #ffffff;
        color: #000000;
        line-height: 1.4;
        font-size: 12px;
        padding: 20px;
    }

    .invoice-container {
        max-width: 1000px;
        margin: 0 auto;
        background-color: #ffffff;
    }

    /* Header */
    .header {
        display: table;
        width: 100%;
        margin-bottom: 32px;
    }

    .logo-section {
        display: table-cell;
        width: 50%;
        vertical-align: top;
    }

    .logo {
        width: 155px;
        height: 159px;
        border-radius: 8px;
        display: block;
    }

    .logo img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 8px;
    }

    .invoice-info {
        display: table-cell;
        width: 50%;
        text-align: right;
        vertical-align: top;
        padding-top: 40px;
        padding-right: 152px;
    }

    .invoice-title {
        font-size: 32px;
        font-weight: 300;
        color: #00a0c6;
        margin-bottom: 8px;
    }

    .invoice-details {
        font-size: 14px;
        color: #000000;
        margin-right: -59px;
    }

    /* Sender and Client */
    .info-section {
        display: table;
        width: 100%;
        margin-bottom: 32px;
    }

    .info-left {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-right: 32px;
    }

    .info-right {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-left: 130px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 300;
        color: #00a0c6;
        margin-bottom: 16px;
    }

    .company-name {
        font-weight: 600;
        font-size: 18px;
        margin-bottom: 8px;
        color: #000000;
    }

    .company-details {
        font-size: 14px;
        line-height: 1.6;
        color: #000000;
    }

    /* Invoice Table */
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 32px;
    }

    .invoice-table th {
        background-color: #00a0c6;
        color: #ffffff;
        padding: 12px 16px;
        text-align: left;
        font-weight: 500;
        border: 1px solid #00a0c6;
    }

    .invoice-table th.center {
        text-align: center;
    }

    .invoice-table td {
        padding: 12px 16px;
        border: 1px solid #cccccc;
        background-color: #ffffff;
        color: #000000;
    }

    .invoice-table td.center {
        text-align: center;
    }

    .invoice-table td.text-end {
        text-align: right;
    }

    /* Container for Terms and Summary */
    .facture-container {
        display: table;
        width: 100%;
        margin-bottom: 32px;
    }

    .terms-section {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-right: 20px;
    }

    .term-item {
        margin-bottom: 16px;
    }

    .term-label {
        color: #00a0c6;
        font-weight: 500;
        font-size: 14px;
    }

    .term-value {
        color: #000000;
        font-size: 14px;
    }

    /* Summary Table */
    .summary-section {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        text-align: right;
    }

    .summary-table {
        width: 320px;
        border-collapse: collapse;
        margin-left: auto;
    }

    .summary-table td {
        padding: 8px 16px;
        border: 1px solid #cccccc;
    }

    .summary-table .label {
        background-color: #f5f5f5;
        font-weight: 500;
        color: #000000;
    }

    .summary-table .value {
        background-color: #ffffff;
        color: #000000;
        text-align: right;
    }

    .summary-table .highlight {
        background-color: #00a0c6;
        color: #ffffff;
        font-weight: 500;
        text-align: right;
    }

    /* Bottom Section */
    .bottom-section {
        display: table;
        width: 100%;
        margin-top: 32px;
    }

    .logos-section {
        display: table-cell;
        width: 50%;
        vertical-align: top;
    }

    .logos-grid {
        text-align: left;
    }

    .logos-grid img {
        max-width: 200px;
        height: auto;
    }

    /* Footer */
    .footer {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        text-align: center;
        font-size: 12px;
        color: #00a0c6;
        line-height: 1.6;
        padding-left: 20px;
    }

    .footer .company-name-footer {
        font-weight: 600;
        margin-bottom: 4px;
    }

    .closing-text {
        color: #000000;
        line-height: 1.6;
        font-size: 14px;
        margin-bottom: 16px;
    }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">
                    <img src="<?= 'file://' . __DIR__ . '/images/logo.png' ?>" alt="Logo">
                </div>
            </div>
            <div class="invoice-info">
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
                <div class="invoice-details">
                    <p>N°: <?= htmlspecialchars($facture['N_facture']) ?></p>
                    <p>Date: <?= date('d/m/Y', strtotime($facture['Date_Creation'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Sender and Client Information -->
        <div class="info-section">
            <div class="info-left">
                <h2 class="section-title">Émetteur</h2>
                <div class="company-name">ENRIQUE TECHNOLOGY</div>
                <div class="company-details">
                    <div>Entrepôt n° 76, Résidence Chahbae D,</div>
                    <div>Av. Louis Van Beethoven, Tanger - Maroc</div>
                    <div>ICE: 003574700000586</div>
                </div>
            </div>
            <div class="info-right">
                <h2 class="section-title">Client</h2>
                <div class="company-name"><?= htmlspecialchars($facture['NameEntreprise']) ?></div>
                <div class="company-details">
                    <div>Email: <?= htmlspecialchars($facture['Email']) ?></div>
                    <div>Adresse: <?= htmlspecialchars($facture['Adresse']) ?></div>
                    <div>Contact: <?= htmlspecialchars($facture['Contact']) ?></div>
                    <div>ICE: <?= htmlspecialchars($facture['ICE']) ?></div>
                </div>
            </div>
        </div>

        <!-- Invoice Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th class="center">Quantité</th>
                    <th class="center">Prix Unitaire</th>
                    <th class="center">Total HT</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $index => $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['Designation']) ?></td>
                    <td class="center"><?= $item['Quantite'] ?></td>
                    <td class="center"><?= number_format($item['Prix_Unit'], 2) ?> DH</td>
                    <td class="center"><?= number_format($item['Montant_HT'], 2) ?> DH</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Container for Terms and Summary -->
        <div class="facture-container">
            <!-- Terms and Conditions -->
            <div class="terms-section">
                <div class="term-item">
                    <div class="term-label">Conditions de règlement :</div>
                    <div class="term-value"><?= htmlspecialchars($facture['condition_re']) ?></div>
                </div>
                <div class="term-item">
                    <div class="term-label">Conditions de paiement :</div>
                    <div class="term-value"><?= htmlspecialchars($facture['Conditions']) ?></div>
                </div>
                <div class="term-item">
                    <div class="term-label">Date de validité :</div>
                    <div class="term-value"><?= htmlspecialchars($facture['Datee']) ?></div>
                </div>
                <div class="term-item">
                    <div class="term-label">Délai de livraison :</div>
                    <div class="term-value"><?= htmlspecialchars($facture['livraison']) ?></div>
                </div>
            </div>

            <!-- Summary Table -->
            <div class="summary-section">
                <table class="summary-table">
                    <tbody>
                        <tr>
                            <td class="label">Total HT</td>
                            <td class="value"><?= number_format($facture['Montant_Total_HT'], 2) ?> DH</td>
                        </tr>
                        <tr>
                            <td class="label">Taux TVA</td>
                            <td class="value"><?= $facture['TVA'] ?> %</td>
                        </tr>
                        <tr>
                            <td class="label">Montant TVA</td>
                            <td class="value"><?= number_format($tva_amount, 2) ?> DH</td>
                        </tr>
                        <tr>
                            <td class="highlight">NET À PAYER</td>
                            <td class="highlight"><?= number_format($total_ttc, 2) ?> DH</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <!-- Company Logos -->
            <div class="logos-section">
                <div class="logos-grid">
                    <img src="<?= 'file://' . __DIR__ . '/images/unnamed.png' ?>" alt="Company Partners">
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="company-name-footer">ENRIQUE TECHNOLOGY S.A.R.L</div>
                <div>RC:155901|Patente:50419825|IF 66122256|CNSS : 5758821</div>
                <div>ICE:003574703000056|R.I.B: 011 640 0000252100005667</div>
                <div>Web Site : enrichtech.net|Email : contact@enrichtech.net</div>
                <div>Tél : 212 661 488 101 | 661 486 595</div>
            </div>
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
?>