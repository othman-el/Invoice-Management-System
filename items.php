<?php 
include_once 'Database.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$sql_i = "SELECT * FROM items JOIN liste_fourniseur_client ON items.Fournisseur = liste_fourniseur_client.ID";
$stmt = $pdo->prepare($sql_i);    
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet"
        type="text/css">
    <title>SGF</title>
</head>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include './front/head_front.php'; ?>

    <div class="container mt-4">
        <h1 class="text-center mb-4">Liste des Items</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div style="max-width: 400px;">
            </div>
            <a href="Ajouter_items.php" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fa fa-plus"></i>
                <span>Ajouter un item</span>
            </a>
        </div>

        <table class="table table-striped table-bordered text-center align-middle">
            <style>
            .bg-custom {
                background-color: #8DD8FF !important;
                color: black;
            }
            </style>

            <thead class="bg-primary text-white">
                <tr>
                    <th colspan="10" class="text-center bg-custom">Fournisseur</th>
                    <th colspan="8" class="text-center bg-custom">Client</th>
                </tr>

                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Fournisseur</th>
                    <th>N Facture</th>
                    <th>Article</th>
                    <th>Designation</th>
                    <th>Qte</th>
                    <th>Montant uHT</th>
                    <th>Total Uht</th>
                    <th>TVA</th>
                    <th>TOTAL TTC</th>
                    <th>Date</th>
                    <th>N Devis</th>
                    <th>N Facture</th>
                    <th>N Client</th>
                    <th>Code client</th>
                    <th>Mt HT</th>
                    <th>Mt TTC</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['ID']) ?></td>
                    <td><?= htmlspecialchars($item['Date']) ?></td>
                    <td><?= htmlspecialchars($item['NameEntreprise']) ?></td>
                    <td><?= htmlspecialchars($item['N_Facture']) ?></td>
                    <td><?= htmlspecialchars($item['Article']) ?></td>
                    <td><?= htmlspecialchars($item['Designation']) ?></td>
                    <td><?= htmlspecialchars($item['Qte']) ?></td>
                    <td><?= htmlspecialchars($item['Montant_uHT']) ?></td>
                    <td><?= htmlspecialchars($item['Total_Uht']) ?></td>
                    <td>20</td>
                    <td><?= htmlspecialchars($item['TOTAL_TTC']) ?></td>
                    <td><?= htmlspecialchars($item['Date_c']) ?></td>
                    <td><?= htmlspecialchars($item['N_Devis']) ?></td>
                    <td><?= htmlspecialchars($item['N_Facture_C']) ?></td>
                    <td><?= htmlspecialchars($item['NameEntreprise']) ?></td>
                    <td><?= htmlspecialchars($item['Code_client']) ?></td>
                    <td><?= htmlspecialchars($item['Mt_HT']) ?></td>
                    <td><?= htmlspecialchars($item['Mt_TTC']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                <tr>
                    <td colspan="18">Aucun item trouvé.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>


</html>