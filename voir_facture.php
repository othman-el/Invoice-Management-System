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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #<?= htmlspecialchars($facture['N_facture']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    }

    .invoice-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 32px;
        background-color: #ffffff;
    }

    /* Header */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 32px;
    }

    .logo-section {
        display: flex;
        align-items: center;
    }

    .logo {
        width: 155px;
        height: 159px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: bold;
        font-size: 18px;
        margin-right: 16px;
    }

    .logo img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 8px;
    }

    .invoice-info {
        text-align: right;
        margin-top: 40px;
        margin-right: 152px;

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
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        margin-bottom: 32px;
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

    /* Summary Table */
    .summary-section {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 32px;
    }

    .summary-table {
        width: 320px;
        border-collapse: collapse;
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

    /* Terms Section */
    .terms-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        margin-bottom: 32px;
        font-size: 14px;
    }

    .term-item {
        margin-bottom: 16px;
    }

    .term-label {
        color: #00a0c6;
        font-weight: 500;
    }

    .term-value {
        color: #000000;
    }

    .closing-text {
        color: #000000;
        line-height: 1.6;
    }

    /* Logos Section */
    .logos-section {
        margin-bottom: 32px;
    }

    .logos-grid {
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 16px;
        align-items: center;
        justify-items: center;
    }

    .logo-item {
        font-size: 12px;
        font-weight: bold;
        text-align: center;
    }

    .logo-hp {
        color: #0096d6;
    }

    .logo-zkt {
        color: #00a651;
    }

    .logo-zebra {
        color: #000000;
    }

    .logo-cisco {
        color: #1ba0d7;
    }

    .logo-progress {
        color: #5cb85c;
    }

    .logo-eaton {
        color: #00a651;
    }

    .logo-dell {
        color: #007db8;
    }

    .logo-lenovo {
        color: #e2231a;
    }

    .logo-asus {
        color: #0066cc;
    }

    .logo-apc {
        color: #e2231a;
    }

    .logo-oracle {
        color: #f80000;
    }

    .logo-microsoft {
        color: #00bcf2;
    }

    .logo-kaspersky {
        color: #006f3c;
    }

    .logo-ibm {
        color: #1f70c1;
    }

    .logo-epson {
        color: #003da5;
    }

    .logo-eset {
        color: #ffffff;
        background-color: #000000;
        padding: 2px 4px;
        border-radius: 2px;
    }

    .logo-intel {
        color: #0071c5;
    }

    .logo-samsung {
        color: #1428a0;
    }

    .logo-autodesk {
        color: #0696d7;
    }

    .logo-google {
        color: #4285f4;
    }

    .logo-canon {
        color: #e60012;
    }

    .logo-xerox {
        color: #da020e;
    }

    .logo-sql {
        color: #666666;
    }

    /* Footer */
    .footer {
        text-align: center;
        font-size: 12px;
        color: #00a0c6;
        line-height: 1.6;
    }

    .footer .company-name-footer {
        font-weight: 600;
        margin-bottom: 4px;
    }

    /* Print and No-print styles */
    .no-print {
        display: block;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        body {
            font-size: 12px;
        }

        .invoice-container {
            padding: 16px;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .invoice-container {
            padding: 16px;
        }

        .header {
            flex-direction: column;
            gap: 16px;
        }

        .invoice-info {
            text-align: left;
        }

        .info-section {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .terms-section {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .logos-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .invoice-table {
            font-size: 12px;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 8px;
        }
    }

    .bottom-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .logos-section {
        flex: 1;
    }

    .footer {
        flex: 2;
    }

    .client-info {
        margin-left: 130px;
    }

    .terms-section>div {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .term-item {
        display: flex;
        flex-direction: column;
        min-width: 200px;
    }

    .term-label {
        font-weight: bold;
    }

    .term-value {
        margin-top: 5px;
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

    <!-- Invoice -->
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">
                    <img src="images/logo.png" alt="Logo" style="max-width: 100%; max-height: 100%;">
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
        <div class=" info-section">
            <div>
                <h2 class="section-title">Émetteur</h2>
                <div class="company-name">ENRIQUE TECHNOLOGY</div>
                <div class="company-details">
                    <div>Entrepôt n° 76, Résidence Chahbae D,</div>
                    <div>Av. Louis Van Beethoven, Tanger - Maroc</div>
                    <div>ICE: 003574700000586</div>
                </div>
            </div>
            <div class="client-info">
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

        <!-- Container pour aligner les deux blocs côte à côte -->
        <div class="facture-container"
            style="display: flex; gap: 0px; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">



            <!-- Terms and Conditions -->
            <div class="terms-section" style="flex: 1;">
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
            <div class="summary-section" style="flex: 1;">
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


        <div class="bottom-section">
            <!-- Company Logos -->
            <div class="logos-section">
                <div class="logos-grid">
                    <img src="images/unnamed.png" alt="">
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>