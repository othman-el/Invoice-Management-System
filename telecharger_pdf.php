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
    }

    h1,
    h2,
    h3 {
        color: #00a0c6;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th {
        background-color: #00a0c6;
        color: white;
        padding: 8px;
        text-align: left;
    }

    td {
        padding: 8px;
        border: 1px solid #ccc;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .totals td {
        font-weight: bold;
    }

    .footer {
        font-size: 10px;
        text-align: center;
        margin-top: 30px;
    }
    </style>
</head>

<body>
    <h1>ENRIQUE TECHNOLOGY</h1>
    <h2>Facture N° <?= htmlspecialchars($facture['N_facture']) ?></h2>
    <p><strong>Date :</strong> <?= date('d/m/Y', strtotime($facture['Date_Creation'])) ?></p>

    <h3>Client</h3>
    <p>
        <strong><?= htmlspecialchars($facture['NameEntreprise']) ?></strong><br>
        <?= htmlspecialchars($facture['Adresse']) ?><br>
        ICE : <?= htmlspecialchars($facture['ICE']) ?><br>
        Email : <?= htmlspecialchars($facture['Email']) ?><br>
        GSM : <?= htmlspecialchars($facture['NumeroGSM']) ?><br>
    </p>

    <h3>Détails</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Désignation</th>
                <th>Quantité</th>
                <th>Prix Unit. (HT)</th>
                <th>Total HT</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $index => $item): ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($item['Designation']) ?></td>
                <td class="text-center"><?= $item['Quantite'] ?></td>
                <td class="text-right"><?= number_format($item['Prix_Unit'], 2) ?> €</td>
                <td class="text-right"><?= number_format($item['Montant_HT'], 2) ?> €</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="totals" style="width: 50%; float: right;">
        <tr>
            <td>Sous-total HT</td>
            <td class="text-right"><?= number_format($facture['Montant_Total_HT'], 2) ?> €</td>
        </tr>
        <tr>
            <td>TVA (<?= $facture['TVA'] ?>%)</td>
            <td class="text-right"><?= number_format($tva_amount, 2) ?> €</td>
        </tr>
        <tr>
            <td>Total TTC</td>
            <td class="text-right"><?= number_format($facture['Montant_Total_TTC'], 2) ?> €</td>
        </tr>
    </table>

    <div class="footer">
        ENRIQUE TECHNOLOGY S.A.R.L<br>
        ICE: 003574700000586 | RC: 135901 | Patente: IF 50122250 | CNSS: 9789821<br>
        contact@et-maroc.com | Tél: 212 661 488 187 / 661 435 035
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