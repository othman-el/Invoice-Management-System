<?php
require_once 'vendor/dompdf/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include_once 'Database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: items.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM items WHERE ID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    header("Location: items.php");
    exit;
}

$tva_amount = $item['Mt_HT'] * ($item['TVA'] / 100);

try {
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $options->set('isRemoteEnabled', true);
    $options->set('debugPng', true);
    $options->set('debugKeepTemp', true);
    $options->set('debugCss', true);

    $dompdf = new Dompdf($options);

    $html = '<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Facture - Enrique Technology</title>
        <style>
            @page { margin: 1cm; }
            body {
                font-family: DejaVu Sans, Arial, sans-serif;
                margin: 0;
                padding: 0;
                font-size: 12px;
                line-height: 1.4;
                color: #333;
            }
            .header {
                width: 100%;
                margin-bottom: 30px;
                border-bottom: 2px solid #00a0c6;
                padding-bottom: 20px;
            }
            .header-left {
                float: left;
                width: 48%;
            }
            .header-right {
                float: right;
                width: 48%;
                text-align: right;
            }
            .company-name {
                color: #00a0c6;
                font-size: 24px;
                font-weight: bold;
                margin: 5px 0;
            }
            .invoice-title {
                color: #00a0c6;
                font-size: 36px;
                font-weight: bold;
                margin: 0;
            }
            .clear { clear: both; }
            .info-section {
                width: 100%;
                margin-bottom: 30px;
            }
            .info-box {
                float: left;
                width: 48%;
                border: 1px solid #ddd;
                margin-bottom: 20px;
            }
            .info-box.right {
                float: right;
            }
            .info-header {
                background-color: #f8f9fa;
                color: #00a0c6;
                font-weight: bold;
                padding: 10px;
                border-bottom: 1px solid #ddd;
                margin: 0;
            }
            .info-content {
                padding: 15px;
            }
            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            .items-table th {
                background-color: #00a0c6;
                color: white;
                padding: 12px 8px;
                text-align: left;
                border: 1px solid #00a0c6;
                font-weight: bold;
            }
            .items-table td {
                padding: 10px 8px;
                border: 1px solid #ddd;
            }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .totals-table {
                width: 60%;
                float: right;
                border-collapse: collapse;
                margin: 20px 0;
            }
            .totals-table td {
                padding: 8px;
                border: 1px solid #ddd;
            }
            .total-row {
                background-color: #00a0c6;
                color: white;
                font-weight: bold;
            }
            .conditions {
                font-size: 11px;
                color: #0066cc;
                margin: 30px 0;
                clear: both;
            }
            .footer {
                margin-top: 50px;
                font-size: 10px;
                text-align: center;
                border-top: 1px solid #ddd;
                padding-top: 20px;
                clear: both;
            }
            .observations {
                background-color: #f8f9fa;
                padding: 15px;
                margin: 20px 0;
                border-left: 4px solid #00a0c6;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="header-left">
                <h1 class="company-name">ENRIQUE</h1>
                <h2 class="company-name">TECHNOLOGY</h2>
            </div>
            <div class="header-right">
                <h1 class="invoice-title">FACTURE</h1>
                <p><strong>Date:</strong> ' . htmlspecialchars($item['c_date'] ?: date('d/m/Y')) . '</p>
                <p><strong>N°:</strong> ' . htmlspecialchars($item['n_facture_c']) . '</p>
                <p><strong>Code client:</strong> ' . htmlspecialchars($item['code_client']) . '</p>
                <p><strong>N° Devis:</strong> ' . htmlspecialchars($item['n_devis']) . '</p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="info-section">
            <div class="info-box">
                <div class="info-header">Émetteur</div>
                <div class="info-content">
                    <p><strong>ENRIQUE TECHNOLOGY S.A.R.L</strong></p>
                    <p>Entrepôt n° 76, Résidence Chahbae D,<br>
                    Av. Louis Van Beethoven,<br>
                    Tanger - Maroc<br>
                    ICE: 003574700000586</p>
                </div>
            </div>
            <div class="info-box right">
                <div class="info-header">Client</div>
                <div class="info-content">
                    <p><strong>' . htmlspecialchars($item['client']) . '</strong></p>
                    <p>Code: ' . htmlspecialchars($item['code_client']) . '</p>
                </div>
            </div>
            <div class="clear"></div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th class="text-center">Qté</th>
                    <th class="text-right">Prix Unit. HT</th>
                    <th class="text-right">Montant HT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>' . htmlspecialchars($item['Designation']) . '</td>
                    <td class="text-center">' . htmlspecialchars($item['quantite']) . '</td>
                    <td class="text-right">' . number_format($item['Montant_uHT'], 2) . ' DH</td>
                    <td class="text-right">' . number_format($item['Mt_HT'], 2) . ' DH</td>
                </tr>
            </tbody>
        </table>';

    if (!empty($item['Observation'])) {
        $html .= '<div class="observations">
            <h4>Observations:</h4>
            <p>' . htmlspecialchars($item['Observation']) . '</p>
        </div>';
    }

    $html .= '<table class="totals-table">
            <tbody>
                <tr>
                    <td><strong>Base</strong></td>
                    <td><strong>Taux</strong></td>
                    <td><strong>Taxe</strong></td>
                </tr>
                <tr>
                    <td>' . number_format($item['Mt_HT'], 2) . ' DH</td>
                    <td>' . htmlspecialchars($item['TVA']) . '%</td>
                    <td>' . number_format($tva_amount, 2) . ' DH</td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" class="text-center"><strong>NET A PAYER</strong></td>
                    <td class="text-right"><strong>' . number_format($item['Mt_TTC'], 2) . ' DH</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="conditions">
            <p><strong>Conditions de règlement:</strong> 30 Jours</p>
            <p><strong>Conditions de paiement:</strong> virement</p>
            <p><strong>Fournisseur:</strong> ' . htmlspecialchars($item['fornisseur']) . '</p>
            <br>
            <p>Nous sommes à votre disposition pour tout complément d\'informations.</p>
            <p>Nous vous prions d\'agréer nos salutations distinguées.</p>
        </div>

        <div class="footer">
            <p><strong>ENRIQUE TECHNOLOGY S.A.R.L</strong></p>
            <p>RC : 135901 | Patente : IF 50122250 | CNSS : 9789821</p>
            <p>ICE: 003574700000586 | R.I.B : 011 640 0000282100000887</p>
            <p>Web site: contact@et-maroc.com | Tél: 212 661 488 187 | 661 435 035</p>
        </div>
    </body>
    </html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Generate filename
    $filename = 'Facture_' . $item['code_client'] . '_' . $item['n_facture_c'] . '.pdf';

    // Output the PDF
    $dompdf->stream($filename, [
        'Attachment' => true
    ]);

} catch (Exception $e) {
    echo "error dans creation du PDF: " . $e->getMessage();
    echo "<br><a href='items.php'>Retour</a>";
}
?>