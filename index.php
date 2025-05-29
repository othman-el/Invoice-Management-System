<?php
include_once 'Database.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$sql = "SELECT * FROM items";
$stmt = $pdo->prepare($sql);
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
    <style>
    .table-responsive {
        overflow-x: auto;
    }

    .action-buttons {
        white-space: nowrap;
    }

    .action-buttons .btn {
        margin: 2px;
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    </style>
</head>

<body>
    <?php include './front/head_front.php'; ?>

    <div class="container-fluid mt-3">
        <a href="ajouter_items.php" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Ajouter Items
        </a>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fournisseur</th>
                        <th>Date Facture Fournisseur</th>
                        <th>N° Facture Fournisseur</th>
                        <th>Article</th>
                        <th>Désignation</th>
                        <th>Quantité</th>
                        <th>Montant Unitaire HT</th>
                        <th>Total HT</th>
                        <th>TVA</th>
                        <th>Total TTC</th>
                        <th>Date Facture Client</th>
                        <th>N° Facture Client</th>
                        <th>N° Devis</th>
                        <th>Client</th>
                        <th>Code Client</th>
                        <th>Montant HT</th>
                        <th>Montant TTC</th>
                        <th>Marge</th>
                        <th>Observation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['ID']); ?></td>
                        <td><?php echo htmlspecialchars($item['fornisseur']); ?></td>
                        <td><?php echo htmlspecialchars($item['f_date']); ?></td>
                        <td><?php echo htmlspecialchars($item['n_facture_f']); ?></td>
                        <td><?php echo htmlspecialchars($item['article']); ?></td>
                        <td><?php echo htmlspecialchars($item['Designation']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantite']); ?></td>
                        <td><?php echo htmlspecialchars($item['Montant_uHT']); ?></td>
                        <td><?php echo htmlspecialchars($item['Total_Uht']); ?></td>
                        <td><?php echo htmlspecialchars($item['TVA']); ?></td>
                        <td><?php echo htmlspecialchars($item['TOTAL_TTC']); ?></td>
                        <td><?php echo htmlspecialchars($item['c_date']); ?></td>
                        <td><?php echo htmlspecialchars($item['n_facture_c']); ?></td>
                        <td><?php echo htmlspecialchars($item['n_devis']); ?></td>
                        <td><?php echo htmlspecialchars($item['client']); ?></td>
                        <td><?php echo htmlspecialchars($item['code_client']); ?></td>
                        <td><?php echo htmlspecialchars($item['Mt_HT']); ?></td>
                        <td><?php echo htmlspecialchars($item['Mt_TTC']); ?></td>
                        <td><?php echo htmlspecialchars($item['Marge']); ?></td>
                        <td><?php echo htmlspecialchars($item['Observation']); ?></td>
                        <td class="action-buttons">
                            <a href="voir_facture.php?id=<?php echo $item['ID']; ?>" class="btn btn-info btn-sm"
                                title="Voir facture">
                                <i class="fas fa-eye"></i> Voir facture
                            </a>
                            <a href="telecharger_pdf.php?id=<?php echo $item['ID']; ?>" class="btn btn-success btn-sm"
                                title="Télécharger PDF" target="_blank">
                                <i class="fas fa-download"></i> Télécharger PDF
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>