<?php
require 'vendor/autoload.php';
use setasign\Fpdi\Fpdi;

include_once 'Database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$user_id = $_SESSION['user']['id'];

$sql_c = "SELECT * FROM liste_fourniseur_client WHERE Role = 'Client' AND user_id = :user_id";
$stmt = $pdo->prepare($sql_c);
$stmt->execute([':user_id' => $user_id]);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ClientID = $_POST['ClientID'] ?? null;
    $type = $_POST['Type'] ?? '';
    $N_facture = $_POST['N_facture'] ?? '';
    $tva = 20;
    $date_creation = date('Y-m-d H:i:s');
    $conditions = $_POST['Conditions'] ?? '';
    $date_validite = $_POST['Datee'] ?? '';
    $livraison = $_POST['livraison'] ?? '';
    $condition_re = $_POST['condition_re'] ?? '';

    $designations = $_POST['Designation'] ?? [];
    $quantities = $_POST['Quantite'] ?? [];
    $amounts = $_POST['Montant_HT'] ?? [];

    $totalHT = 0;
    $validItems = [];

    for ($i = 0; $i < count($designations); $i++) {
        $designation = trim($designations[$i]);
        $quantity = intval($quantities[$i]);
        $prix_unit = floatval($amounts[$i]);

        if ($designation !== '' && $quantity > 0 && $prix_unit > 0) {
            $montant_ht = $quantity * $prix_unit;

            $validItems[] = [
                'designation' => $designation,
                'quantite' => $quantity,
                'prix_unit' => $prix_unit,
                'montant_ht' => $montant_ht,
                'ordre' => $i + 1
            ];

            $totalHT += $montant_ht;
        }
    }

    if (empty($validItems)) {
        echo "<p style='color:red;text-align:center;'>Vous devez ajouter au moins un article valide.</p>";
        exit;
    }

    $totalTTC = $totalHT + ($totalHT * $tva / 100);

    try {
        $pdo->beginTransaction();

        $sqlFacture = "INSERT INTO factures 
            (ClientID, N_facture, type, TVA, Montant_Total_HT, Montant_Total_TTC, Date_Creation, Conditions, condition_re, Datee, livraison, user_id)  
            VALUES (:clientid, :n_facture, :type, :tva, :montant_ht, :montant_ttc, :date_creation, :conditions, :condition_re, :date_validite, :livraison, :user_id)";

        $stmtFacture = $pdo->prepare($sqlFacture);
        $stmtFacture->execute([
            ':clientid' => $ClientID,
            ':n_facture' => $N_facture,
            ':type' => $type,
            ':tva' => $tva,
            ':montant_ht' => $totalHT,
            ':montant_ttc' => $totalTTC,
            ':date_creation' => $date_creation,
            ':conditions' => $conditions,
            ':condition_re' => $condition_re,
            ':date_validite' => $date_validite,
            ':livraison' => $livraison,
            ':user_id' => $user_id
        ]);

        $factureID = $pdo->lastInsertId();

        $sqlItems = "INSERT INTO facture_items 
            (FactureID, Designation, Quantite, Prix_Unit, Montant_HT, ordre) 
            VALUES (:factureid, :designation, :quantite, :prix_unit, :montant_ht, :ordre)";

        $stmtItems = $pdo->prepare($sqlItems);

        foreach ($validItems as $item) {
            $stmtItems->execute([
                ':factureid' => $factureID,
                ':designation' => $item['designation'],
                ':quantite' => $item['quantite'],
                ':prix_unit' => $item['prix_unit'],
                ':montant_ht' => $item['montant_ht'],
                ':ordre' => $item['ordre']
            ]);
        }

        $pdo->commit();

        header("Location: Liste_Facturation.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('Erreur lors de l\'ajout de la facture : " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
}

if (isset($_GET['show_invoices'])) {
    try {
        $sql = "SELECT f.*, c.NameEntreprise as client_name 
                FROM factures f 
                LEFT JOIN liste_fourniseur_client c ON f.ClientID = c.ID 
                WHERE f.user_id = :user_id
                ORDER BY f.Date_Creation DESC LIMIT 10";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h3>Dernières 10 factures :</h3>";
        foreach ($factures as $facture) {
            echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
            echo "<h4>Numéro de facture : " . htmlspecialchars($facture['N_facture']) . "</h4>";
            echo "<p>Client : " . htmlspecialchars($facture['client_name']) . "</p>";
            echo "<p>Total TTC : " . number_format($facture['Montant_Total_TTC'], 2) . " DH</p>";

            $sqlItems = "SELECT * FROM facture_items WHERE FactureID = :factureid ORDER BY ordre";
            $stmtItems = $pdo->prepare($sqlItems);
            $stmtItems->execute([':factureid' => $facture['ID']]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            echo "<h5>Articles :</h5><ul>";
            foreach ($items as $item) {
                echo "<li>" . htmlspecialchars($item['Designation']) . " - Quantité : " . intval($item['Quantite']) .
                     " - Prix unitaire : " . number_format($item['Prix_Unit'], 2) . " DH - Total HT : " . number_format($item['Montant_HT'], 2) . " DH</li>";
            }
            echo "</ul></div>";
        }
    } catch (Exception $e) {
        echo "Erreur lors de l'affichage des factures : " . htmlspecialchars($e->getMessage());
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

    .add-row-btn:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
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

    .add-row-btn:hover:not(:disabled) {
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

    .max-rows-warning {
        color: #dc3545;
        font-size: 0.8em;
        margin-top: 5px;
        font-style: italic;
    }
    </style>
</head>

<body>
    <?php include './front/head.php'; ?>

    <div class="container py-5">
        <a href="index.php"><button class="retoure">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"
                    height="40px" width="20px" version="1.1" id="Capa_1" viewBox="0 0 206.108 206.108"
                    xml:space="preserve">
                    <path
                        d="M152.774,69.886H30.728l24.97-24.97c3.515-3.515,3.515-9.213,0-12.728c-3.516-3.516-9.213-3.515-12.729,0L2.636,72.523  c-3.515,3.515-3.515,9.213,0,12.728l40.333,40.333c1.758,1.758,4.061,2.636,6.364,2.636c2.303,0,4.606-0.879,6.364-2.636  c3.515-3.515,3.515-9.213,0-12.728l-24.97-24.97h122.046c19.483,0,35.334,15.851,35.334,35.334s-15.851,35.334-35.334,35.334H78.531  c-4.971,0-9,4.029-9,9s4.029,9,9,9h74.242c29.408,0,53.334-23.926,53.334-53.334S182.182,69.886,152.774,69.886z" />
                </svg>
                retoure
            </button></a>
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
                            <th>Prix Unit</th>
                            <th>TVA (%)</th>
                            <th>Conditions de réglement</th>
                            <th>Conditions de paiement</th>
                            <th>Date de validité</th>
                            <th>Délai de livraison</th>
                            <th>Ajouter autre Article</th>
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
                                <input type="number" name="TVA" value="20" readonly
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0">
                            </td>
                            <td>
                                <input type="text" name="condition_re"
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    placeholder="Conditions de réglement">
                            </td>
                            <td>
                                <input type="text" name="Conditions"
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    placeholder="Conditions de paiement">
                            </td>
                            <td>
                                <input type="date" name="Datee"
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0">
                            </td>
                            <td>
                                <input type="text" name="livraison"
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    placeholder="Délai de livraison">
                            </td>

                            <td>
                                <button type="button" class="add-row-btn" id="main-add-btn" onclick="addDetailRow()"
                                    title="Ajouter un article">+</button>
                                <div class="max-rows-warning" id="max-rows-warning" style="display: none;">
                                    Maximum 5 articles supplémentaires
                                </div>
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
                    style="background-color: #009fbc; color: white;">
                    Ajouter
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let detailRowCount = 0;
    const MAX_DETAIL_ROWS = 5;

    function updateAddButtonState() {
        const addButton = document.getElementById('main-add-btn');
        const warningMessage = document.getElementById('max-rows-warning');

        if (detailRowCount >= MAX_DETAIL_ROWS) {
            addButton.disabled = true;
            addButton.title = 'Maximum 5 articles supplémentaires atteint';
            warningMessage.style.display = 'block';
        } else {
            addButton.disabled = false;
            addButton.title = `Ajouter un article (${detailRowCount}/${MAX_DETAIL_ROWS})`;
            warningMessage.style.display = 'none';
        }
    }

    function addDetailRow() {
        if (detailRowCount >= MAX_DETAIL_ROWS) {
            alert(`Vous ne pouvez ajouter que ${MAX_DETAIL_ROWS} articles supplémentaires maximum.`);
            return;
        }

        detailRowCount++;
        const tbody = document.getElementById('invoice-table-body');

        const newRow = document.createElement('tr');
        newRow.className = 'detail-row';
        newRow.id = `detail-row-${detailRowCount}`;

        newRow.innerHTML = `
            <td class="empty-cell">
                <span class="detail-indicator">Article ${detailRowCount + 1}</span>
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
            <td class="empty-cell"></td>
            <td class="empty-cell"></td>
            <td class="empty-cell"></td>
            <td>
                <button type="button" class="remove-row-btn" onclick="removeDetailRow(${detailRowCount})" 
                    title="Supprimer cet article">×</button>
            </td>
        `;

        tbody.appendChild(newRow);
        updateAddButtonState();
        calculateTotal();
    }

    function removeDetailRow(rowId) {
        const row = document.getElementById(`detail-row-${rowId}`);
        if (row) {
            row.remove();
            detailRowCount--;
            updateAddButtonState();
            updateRowNumbers();
            calculateTotal();
        }
    }

    function updateRowNumbers() {
        const detailRows = document.querySelectorAll('.detail-row');
        detailRows.forEach((row, index) => {
            const indicator = row.querySelector('.detail-indicator');
            if (indicator) {
                indicator.textContent = `Article ${index + 2}`;
            }
        });
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

        updateAddButtonState();
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