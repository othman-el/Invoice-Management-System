<?php
require 'vendor/autoload.php';
use setasign\Fpdi\Fpdi;

include_once 'Database.php';

$sql_c = "SELECT * FROM liste_fourniseur_client WHERE Role = 'Client'";
$stmt = $pdo->prepare($sql_c);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ClientID = $_POST['ClientID'];
    $type = $_POST['Type'];
    $N_facture = $_POST['N_facture'];
    $tva = 20;
    $date_creation = date('Y-m-d H:i:s');

    $documentPath = null;
    if (isset($_FILES['Document']) && $_FILES['Document']['error'] == UPLOAD_ERR_OK) {
        $filename = basename($_FILES['Document']['name']);
        $destination = 'uploads/' . time() . '_' . $filename;
        move_uploaded_file($_FILES['Document']['tmp_name'], $destination);
        $documentPath = $destination;
    }

    $designations = $_POST['Designation'] ?? [];
    $quantities = $_POST['Quantite'] ?? [];
    $amounts = $_POST['Montant_HT'] ?? [];
    
    $totalHT = 0;
    $validItems = [];
    
    for ($i = 0; $i < count($designations); $i++) {
        if (!empty($designations[$i]) && !empty($quantities[$i]) && !empty($amounts[$i])) {
            $quantity = intval($quantities[$i]);
            $prix_unit = floatval($amounts[$i]);
            $montant_ht = $quantity * $prix_unit;
            
            $validItems[] = [
                'designation' => trim($designations[$i]),
                'quantite' => $quantity,
                'prix_unit' => $prix_unit,
                'montant_ht' => $montant_ht,
                'ordre' => $i + 1
            ];
            
            $totalHT += $montant_ht;
        }
    }
    
    $totalTTC = $totalHT + ($totalHT * $tva / 100);

    try {
        $pdo->beginTransaction();

        $sqlFacture = "INSERT INTO factures 
            (ClientID, N_facture, type, TVA, Montant_Total_HT, Montant_Total_TTC, Document, Date_Creation)  
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmtFacture = $pdo->prepare($sqlFacture);
        $stmtFacture->execute([
            $ClientID,
            $N_facture,
            $type,
            $tva,
            $totalHT,
            $totalTTC,
            $documentPath,
            $date_creation
        ]);

        $factureID = $pdo->lastInsertId();

        $sqlItems = "INSERT INTO facture_items 
            (FactureID, Designation, Quantite, Prix_Unit, Montant_HT, ordre) 
            VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmtItems = $pdo->prepare($sqlItems);
        
        foreach ($validItems as $item) {
            $stmtItems->execute([
                $factureID,
                $item['designation'],
                $item['quantite'],
                $item['prix_unit'],
                $item['montant_ht'],
                $item['ordre']
            ]);
        }

        $pdo->commit();
        
        $savedItemsCount = count($validItems);
        echo "<script>
            alert('Facture ajoutée avec succès !\\nN° de facture : {$N_facture}\\nNombre d’articles : {$savedItemsCount}\\nTotal HT : {$totalHT}DH\\nTotal TTC : {$totalTTC}DH');
            window.location.href='ajouter_facture.php';
        </script>";
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('Erreur lors de l’ajout de la facture : " . $e->getMessage() . "');</script>";
    }
}

if (isset($_GET['show_invoices'])) {
    try {
        $sql = "SELECT f.*, c.NameEntreprise as client_name 
                FROM factures f 
                LEFT JOIN liste_fourniseur_client c ON f.ClientID = c.ID 
                ORDER BY f.Date_Creation DESC LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Dernières factures :</h3>";
        foreach ($factures as $facture) {
            echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
            echo "<h4>Facture N° : {$facture['N_facture']}</h4>";
            echo "<p>Client : {$facture['client_name']}</p>";
            echo "<p>Total TTC : {$facture['Montant_Total_TTC']}DH</p>";
            
            $sqlItems = "SELECT * FROM facture_items WHERE FactureID = ? ORDER BY ordre";
            $stmtItems = $pdo->prepare($sqlItems);
            $stmtItems->execute([$facture['ID']]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h5>Articles :</h5><ul>";
            foreach ($items as $item) {
                echo "<li>{$item['Designation']} - Quantité : {$item['Quantite']} - Prix unitaire : {$item['Prix_Unit']}DH - Total HT : {$item['Montant_HT']}DH</li>";
            }
            echo "</ul></div>";
        }
    } catch (Exception $e) {
        echo "Erreur lors de l'affichage des factures : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Ajouter Facture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .add-row-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        margin: 5px;
        transition: all 0.3s;
    }

    .remove-row-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 50%;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .add-row-btn:hover {
        background-color: #218838;
    }

    .remove-row-btn:hover {
        background-color: #c82333;
    }

    .detail-row {
        background-color: #f8f9fa;
    }

    .empty-cell {
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
        height: 45px;
    }

    .detail-indicator {
        font-size: 0.8em;
        color: #6c757d;
        font-style: italic;
    }

    .table-responsive {
        overflow-x: auto;
    }
    </style>
</head>

<body>
    <?php include './front/head_front.php'; ?>

    <div class="container py-5">
        <h2 class="text-center mb-4">Ajouter une nouvelle facture</h2>

        <form method="POST" enctype="multipart/form-data" class="mx-auto" style="max-width: 100%;">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Numéro de facture</th>
                            <th>Désignation</th>
                            <th>Quantité</th>
                            <th>Montant HT</th>
                            <th>Document (PDF)</th>
                            <th>TVA (%)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-table-body">
                        <tr class="main-row">
                            <td>
                                <select name="ClientID" required
                                    class="form-select rounded-pill bg-secondary bg-opacity-25 border-0">
                                    <option value="">Sélectionner un client</option>
                                    <?php foreach($clients as $client) :?>
                                    <option value="<?= $client['ID'] ?>">
                                        <?= htmlspecialchars($client['NameEntreprise']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name="Type" required
                                    class="form-select rounded-pill bg-secondary bg-opacity-25 border-0">
                                    <option value="facture">Facture</option>
                                    <option value="bl">Bon de livraison</option>
                                    <option value="devis">Devis</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="N_facture" required
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    placeholder="Numéro de facture">
                            </td>
                            <td>
                                <input type="text" name="Designation[]" required
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    placeholder="Description du produit/service">
                            </td>
                            <td>
                                <input type="number" name="Quantite[]" required min="1"
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    placeholder="Quantité" onchange="calculateTotal()">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="Montant_HT[]" required min="0"
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    placeholder="Montant" onchange="calculateTotal()">
                            </td>
                            <td>
                                <input type="file" name="Document" accept=".pdf"
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0">
                            </td>
                            <td>
                                <input type="number" name="TVA" value="20" readonly
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0">
                            </td>
                            <td>
                                <button type="button" class="add-row-btn" onclick="addDetailRow()"
                                    title="Ajouter un article">+</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Total HT : <span id="total-ht">0.00</span> DH</strong>
                    </div>
                    <div class="col-md-6">
                        <strong>Total TTC : <span id="total-ttc">0.00</span> DH</strong>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn px-5 py-2 rounded-pill"
                    style="background-color: #4f57c7; color: white;">
                    Créer la facture
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let detailRowCount = 0;

    function addDetailRow() {
        detailRowCount++;
        const tbody = document.getElementById('invoice-table-body');

        const newRow = document.createElement('tr');
        newRow.className = 'detail-row';
        newRow.id = `detail-row-${detailRowCount}`;

        newRow.innerHTML = `
            <td class="empty-cell">
                <span class="detail-indicator">Article supplémentaire</span>
            </td>
            <td class="empty-cell"></td>
            <td class="empty-cell"></td>
            <td>
                <input type="text" name="Designation[]" required
                    class="form-control rounded-pill bg-light border-0"
                    placeholder="Description supplémentaire">
            </td>
            <td>
                <input type="number" name="Quantite[]" required min="1"
                    class="form-control rounded-pill bg-light border-0"
                    placeholder="Quantité" onchange="calculateTotal()">
            </td>
            <td>
                <input type="number" step="0.01" name="Montant_HT[]" required min="0"
                    class="form-control rounded-pill bg-light border-0"
                    placeholder="Montant" onchange="calculateTotal()">
            </td>
            <td class="empty-cell"></td>
            <td class="empty-cell"></td>
            <td>
                <button type="button" class="remove-row-btn" onclick="removeDetailRow(${detailRowCount})" 
                    title="Supprimer cet article">×
                </button>
            </td>
        `;

        tbody.appendChild(newRow);
        calculateTotal();
    }

    function removeDetailRow(rowId) {
        const row = document.getElementById(`detail-row-${rowId}`);
        if (row) {
            row.remove();
            calculateTotal();
        }
    }

    function calculateTotal() {
        const quantityInputs = document.querySelectorAll('input[name="Quantite[]"]');
        const amountInputs = document.querySelectorAll('input[name="Montant_HT[]"]');
        let totalHT = 0;

        for (let i = 0; i < quantityInputs.length; i++) {
            const quantity = parseFloat(quantityInputs[i].value) || 0;
            const amount = parseFloat(amountInputs[i].value) || 0;
            totalHT += quantity * amount;
        }

        const totalTTC = totalHT + (totalHT * 0.20);
        document.getElementById('total-ht').textContent = totalHT.toFixed(2);
        document.getElementById('total-ttc').textContent = totalTTC.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const mainQuantity = document.querySelector('.main-row input[name="Quantite[]"]');
        const mainAmount = document.querySelector('.main-row input[name="Montant_HT[]"]');

        if (mainQuantity) mainQuantity.addEventListener('input', calculateTotal);
        if (mainAmount) mainAmount.addEventListener('input', calculateTotal);
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        const designations = document.querySelectorAll('input[name="Designation[]"]');
        const quantities = document.querySelectorAll('input[name="Quantite[]"]');
        const amounts = document.querySelectorAll('input[name="Montant_HT[]"]');

        let validItemsCount = 0;
        for (let i = 0; i < designations.length; i++) {
            if (designations[i].value.trim() !== '' &&
                quantities[i].value !== '' &&
                amounts[i].value !== '') {
                validItemsCount++;
            }
        }

        if (validItemsCount === 0) {
            e.preventDefault();
            alert('Veuillez remplir au moins un article (désignation, quantité, montant).');
            return false;
        }

        if (validItemsCount > 1) {
            const confirmation = confirm(
                `Vous allez ajouter une facture contenant ${validItemsCount} articles. Voulez-vous continuer ?`
            );
            if (!confirmation) {
                e.preventDefault();
                return false;
            }
        }
    });
    </script>
</body>

</html>