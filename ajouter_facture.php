<?php
include_once 'Database.php';

if (isset($_GET['id'])) {
    $clientID = $_GET['id'];

    $sql = "SELECT * FROM liste_fourniseur_client WHERE ID = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$clientID]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        die("Client introuvable.");
    }
} else {
    die("ID client manquant.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientID = $_POST['ClientID'];
    $n_devis = $_POST['N_Devis'];
    $n_bl = $_POST['N_BL'];
    $n_facture = $_POST['N_Facture'];
    $montantHT = (float)$_POST['Montant_HT'];
    $tva = 20 ;

    $montantTTC = $montantHT + ($montantHT * $tva / 100);

    $documentPath = null;
    if (isset($_FILES['Document']) && $_FILES['Document']['error'] == UPLOAD_ERR_OK) {
        $filename = basename($_FILES['Document']['name']);
        $destination = 'uploads/' . time() . '_' . $filename;
        move_uploaded_file($_FILES['Document']['tmp_name'], $destination);
        $documentPath = $destination;
    }

    $sql = "INSERT INTO liste_facturation (ClientID, N_Devis, N_BL, N_Facture, Montant_HT, TVA, Montant_TTC, Document) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $clientID, $n_devis, $n_bl, $n_facture,
        $montantHT, $tva, $montantTTC, $documentPath
    ]);

    echo "<div class='alert alert-success text-center'>Facture enregistrée avec succès</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include './front/head_front.php'; ?>
    <div class="container py-5">
        <h2 class="text-center mb-4">Ajouter une facture pour le <?= htmlspecialchars($client['Role']) ?> :
            <?= htmlspecialchars($client['NameEntreprise']) ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="ClientID" value="<?= htmlspecialchars($client['ID']) ?>">

            <div class="mb-4 row align-items-center">
                <label for="N_Devis" class="col-sm-4 col-form-label text-end">N° Devis :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                        id="N_Devis" name="N_Devis">
                </div>
            </div>

            <div class="mb-4 row align-items-center">
                <label for="N_BL" class="col-sm-4 col-form-label text-end">N° BL :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" id="N_BL"
                        name="N_BL">
                </div>
            </div>

            <div class="mb-4 row align-items-center">
                <label for="N_Facture" class="col-sm-4 col-form-label text-end">N° Facture :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                        id="N_Facture" name="N_Facture">
                </div>
            </div>

            <div class="mb-4 row align-items-center">
                <label for="Montant_HT" class="col-sm-4 col-form-label text-end">Montant HT :</label>
                <div class="col-sm-8">
                    <input type="number" step="0.01"
                        class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" id="Montant_HT"
                        name="Montant_HT">
                </div>
            </div>

            <div class="mb-4 row align-items-center">
                <label for="Document" class="col-sm-4 col-form-label text-end">Document (PDF) :</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                        id="Document" name="Document" accept="application/pdf">
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <button type="submit" class="btn rounded-pill px-5"
                        style="background-color: #4f57c7; color: white;">
                        Enregistrer la facture
                    </button>
                </div>
            </div>
        </form>

    </div>

</body>

</html>