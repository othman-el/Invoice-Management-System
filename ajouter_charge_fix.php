<?php
include_once 'Database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO charge_fix (DESIGNATION, Date_Achat, M, TOTAL_OUT, Montant, Code_REF, Categorie)
            VALUES (:designation, :date_achat, :m, :total_out, :montant, :code_ref, :categorie)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':designation' => $_POST['designation'],
        ':date_achat'  => $_POST['date_achat'],
        ':m'           => $_POST['m'],
        ':total_out'   => $_POST['total_out'],
        ':montant'     => $_POST['montant'],
        ':code_ref'    => $_POST['code_ref'],
        ':categorie'   => $_POST['categorie'],
    ]);

    echo "<div class='alert alert-success'>La charge fixe a été ajoutée avec succès.</div>";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajout Charge Fixe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <?php include './front/head_front.php'; ?>

    <h2>Ajouter une charge fixe</h2>
    <form method="post">
        <label>Désignation: <input type="text" name="designation" required></label><br>
        <label>Date Achat: <input type="date" name="date_achat" required></label><br>
        <label>Mois: <input type="month" name="m" required></label><br>
        <label>Total Out: <input type="number" step="0.001" name="total_out" required></label><br>
        <label>Montant: <input type="number" step="0.001" name="montant" required></label><br>
        <label>Code Réf: <input type="text" name="code_ref" required></label><br>
        <label>Catégorie: <input type="text" name="categorie" required></label><br><br>
        <input type="submit" value="Ajouter" class="btn btn-primary">
    </form>

</body>

</html>