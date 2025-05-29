<?php 
include_once 'Database.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "INSERT INTO items (
            fornisseur, f_date, n_facture_f, article, Designation, quantite,
            Montant_uHT, Total_Uht, TVA, TOTAL_TTC, c_date, n_facture_c,
            n_devis, client, code_client, Mt_HT, Mt_TTC, Marge, Observation
        ) VALUES (
            :fornisseur, :f_date, :n_facture_f, :article, :Designation, :quantite,
            :Montant_uHT, :Total_Uht, :TVA, :TOTAL_TTC, :c_date, :n_facture_c,
            :n_devis, :client, :code_client, :Mt_HT, :Mt_TTC, :Marge, :Observation
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':fornisseur' => $_POST['fornisseur'],
            ':f_date' => $_POST['f_date'],
            ':n_facture_f' => $_POST['n_facture_f'],
            ':article' => $_POST['article'],
            ':Designation' => $_POST['Designation'],
            ':quantite' => $_POST['quantite'],
            ':Montant_uHT' => $_POST['Montant_uHT'],
            ':Total_Uht' => $_POST['Total_Uht'],
            ':TVA' => $_POST['TVA'],
            ':TOTAL_TTC' => $_POST['TOTAL_TTC'],
            ':c_date' => $_POST['c_date'],
            ':n_facture_c' => $_POST['n_facture_c'],
            ':n_devis' => $_POST['n_devis'],
            ':client' => $_POST['client'],
            ':code_client' => $_POST['code_client'],
            ':Mt_HT' => $_POST['Mt_HT'],
            ':Mt_TTC' => $_POST['Mt_TTC'],
            ':Marge' => $_POST['Marge'],
            ':Observation' => $_POST['Observation'],
        ]);

    } catch (PDOException $e) {
        echo "❌ Erreur : " . $e->getMessage();
    }
}
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
<?php
       include './front/head_front.php';
     ?>
</body>
<h1>Formule pour ahouter items</h1>
<form method="post">
    <!-- <label>Fournisseur (ID): <input type="number" name="fornisseur"></label><br> -->
    <!-- <label>Date Fournisseur: <input type="date" name="f_date"></label><br> -->
    <!-- <label>Facture Fournisseur: <input type="text" name="n_facture_f"></label><br> -->
    <label>Article: <input type="text" name="article"></label><br>
    <label>Désignation: <input type="text" name="Designation"></label><br>
    <label>Quantité: <input type="number" name="quantite"></label><br>
    <label>Montant Unitaire HT: <input type="number" step="0.001" name="Montant_uHT"></label><br>
    <label>Total UHT: <input type="number" step="0.001" name="Total_Uht"></label><br>
    <label>TVA (%): <input type="number" name="TVA"></label><br>
    <label>Total TTC: <input type="number" step="0.001" name="TOTAL_TTC"></label><br>
    <label>Date <input type="date" name="c_date"></label><br>
    <label>Facture Client: <input type="text" name="n_facture_c"></label><br>
    <label>Devis N°: <input type="text" name="n_devis"></label><br>
    <label>Client (ID): <input type="number" name="client"></label><br>
    <label>Code Client: <input type="text" name="code_client"></label><br>
    <label>Montant HT: <input type="number" step="0.001" name="Mt_HT"></label><br>
    <label>Montant TTC: <input type="number" step="0.001" name="Mt_TTC"></label><br>
    <!-- <label>Marge: <input type="number" step="0.001" name="Marge"></label><br> -->
    <!-- <label>Observation: <textarea name="Observation"></textarea></label><br> -->

    <input type="submit" value="Ajouter">
</form>

</html>