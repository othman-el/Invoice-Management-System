<?php
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

// Calculate additional fields
$tva_amount = $item['Mt_HT'] * ($item['TVA'] / 100);
$total_ttc = $item['Mt_HT'] + $tva_amount;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture - Enrique Technology</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .logo {
        max-height: 80px;
    }

    .invoice-title {
        color: #00a0c6;
        font-size: 2.5rem;
        font-weight: bold;
    }

    .table-header {
        background-color: #00a0c6;
        color: white;
    }

    .total-section {
        background-color: #00a0c6;
        color: white;
        font-weight: bold;
    }

    .conditions {
        font-size: 0.9rem;
        color: #0066cc;
    }

    .footer-logos img {
        height: 30px;
        margin-right: 10px;
    }

    .qr-code {
        max-width: 100px;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
    </style>
</head>

<body>
    <!-- Print Button -->
    <div class="container mt-3 no-print">
        <div class="row">
            <div class="col-12">
                <a href="items.php" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <button onclick="window.print()" class="btn btn-primary me-2">
                    <i class="fas fa-print"></i> Imprimer
                </button>
                <a href="telecharger_pdf.php?id=<?php echo $item['ID']; ?>" class="btn btn-success" target="_blank">
                    <i class="fas fa-download"></i> Télécharger PDF
                </a>
            </div>
        </div>
        <hr>
    </div>

    <div class="container mt-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-6">
                <div class="d-flex align-items-center">
                    <img src="/placeholder.svg?height=80&width=80" alt="Enrique Technology Logo" class="logo me-3">
                    <div>
                        <h1 class="text-primary mb-0">ENRIQUE</h1>
                        <h2 class="text-primary">TECHNOLOGY</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 text-end">
                <h1 class="invoice-title">FACTURE</h1>
                <div class="row">
                    <div class="col-6 text-start">
                        <p>Date: <?php echo htmlspecialchars($item['c_date'] ?: date('d/m/Y')); ?></p>
                        <p>Code client: <?php echo htmlspecialchars($item['code_client']); ?></p>
                    </div>
                    <div class="col-6">
                        <p>N°: <?php echo htmlspecialchars($item['n_facture_c']); ?></p>
                        <p>N° Devis: <?php echo htmlspecialchars($item['n_devis']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sender and Client Info -->
        <div class="row mb-4">
            <div class="col-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="text-primary mb-0">Émetteur</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>ENRIQUE TECHNOLOGY S.A.R.L</strong></p>
                        <p class="mb-1">Entrepôt n° 76, Résidence Chahbae D,</p>
                        <p class="mb-1">Av. Louis Van Beethoven,</p>
                        <p class="mb-1">Tanger - Maroc</p>
                        <p class="mb-0">ICE: 003574700000586</p>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="text-primary mb-0">Client</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong><?php echo htmlspecialchars($item['client']); ?></strong></p>
                        <p class="mb-1">Code: <?php echo htmlspecialchars($item['code_client']); ?></p>
                        <p class="mb-0">&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="row mb-4">
            <div class="col-12">
                <table class="table table-bordered">
                    <thead class="table-header">
                        <tr>
                            <th>Désignation</th>
                            <th class="text-center">Qté</th>
                            <th class="text-end">Prix Unit. HT</th>
                            <th class="text-end">Montant HT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($item['Designation']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($item['quantite']); ?></td>
                            <td class="text-end"><?php echo number_format($item['Montant_uHT'], 2); ?></td>
                            <td class="text-end"><?php echo number_format($item['Mt_HT'], 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totals -->
        <div class="row mb-4">
            <div class="col-7">
                <?php if (!empty($item['Observation'])): ?>
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Observations</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?php echo htmlspecialchars($item['Observation']); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-5">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Base</strong></td>
                            <td><strong>Taux</strong></td>
                            <td><strong>Taxe</strong></td>
                        </tr>
                        <tr>
                            <td><?php echo number_format($item['Mt_HT'], 2); ?></td>
                            <td><?php echo htmlspecialchars($item['TVA']); ?>%</td>
                            <td><?php echo number_format($tva_amount, 2); ?></td>
                        </tr>
                        <tr class="total-section">
                            <td colspan="2" class="text-center"><strong>NET A PAYER</strong></td>
                            <td><strong><?php echo number_format($item['Mt_TTC'], 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Conditions -->
        <div class="row mb-4">
            <div class="col-12">
                <p class="conditions"><strong>Conditions de règlement:</strong> 30 Jours</p>
                <p class="conditions"><strong>Conditions de paiement:</strong> virement</p>
                <p class="conditions"><strong>Fournisseur:</strong> <?php echo htmlspecialchars($item['fornisseur']); ?>
                </p>
                <p>Nous sommes à votre disposition pour tout complément d'informations.</p>
                <p>Nous vous prions d'agréer nos salutations distinguées.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="row mt-5">
            <div class="col-3">
                <img src="/placeholder.svg?height=100&width=100" alt="QR Code" class="qr-code">
            </div>
            <div class="col-5">
                <div class="footer-logos">
                    <img src="/placeholder.svg?height=30&width=30" alt="HP">
                    <img src="/placeholder.svg?height=30&width=30" alt="ZKTeco">
                    <img src="/placeholder.svg?height=30&width=30" alt="Cisco">
                    <img src="/placeholder.svg?height=30&width=30" alt="Lenovo">
                    <img src="/placeholder.svg?height=30&width=30" alt="Dell">
                    <img src="/placeholder.svg?height=30&width=30" alt="APC">
                </div>
            </div>
            <div class="col-4">
                <p class="mb-1"><small><strong>ENRIQUE TECHNOLOGY S.A.R.L</strong></small></p>
                <p class="mb-1"><small>RC : 135901 | Patente : IF 50122250 | CNSS : 9789821</small></p>
                <p class="mb-1"><small>ICE: 003574700000586 | R.I.B : 011 640 0000282100000887</small></p>
                <p class="mb-1"><small>Web site: contact@et-maroc.com</small></p>
                <p class="mb-0"><small>Tél: 212 661 488 187 | 661 435 035</small></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>
</body>

</html>