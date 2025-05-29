<?php
include_once 'Database.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$sql = "SELECT * FROM charge_fix";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$charges = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Document</title>
</head>

<body>
    <?php
       include './front/head_front.php';
     ?>
    <a href="ajouter_charge_fix.php" class="btn btn-primary mb-3">
        Ajouter Charge fix
    </a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Désignation</th>
                <th>Date Achat</th>
                <th>Mois</th>
                <th>Total Out</th>
                <th>Montant</th>
                <th>Code Réf</th>
                <th>Catégorie</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($charges as $charge): ?>
            <tr>
                <td><?php echo htmlspecialchars($charge['ID']); ?></td>
                <td><?php echo htmlspecialchars($charge['DESIGNATION']); ?></td>
                <td><?php echo htmlspecialchars($charge['Date_Achat']); ?></td>
                <td><?php echo htmlspecialchars($charge['M']); ?></td>
                <td><?php echo htmlspecialchars($charge['TOTAL_OUT']); ?></td>
                <td><?php echo htmlspecialchars($charge['Montant']); ?></td>
                <td><?php echo htmlspecialchars($charge['Code_REF']); ?></td>
                <td><?php echo htmlspecialchars($charge['Categorie']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>