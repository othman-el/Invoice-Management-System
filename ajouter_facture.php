<?php
include_once 'Database.php';
    $sql = "SELECT * FROM liste_fourniseur_client ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);      



if (!empty($_POST['ClientID']) && !empty($_POST['fornisseurID']) && !empty($_POST['facteur']) && !empty($_POST['Montant_HT'])) {
    $clientID = $_POST['ClientID'];
    $fornisseurID = $_POST['fornisseurID'];
    $type = $_POST['facteur'];
    $montantHT = (float)$_POST['Montant_HT'];
    $tva = 20;

    $montantTTC = $montantHT + ($montantHT * $tva / 100);

    $sql = "INSERT INTO liste_facturation (ClientID, fornisseurID, Montant_HT, TVA, Montant_TTC, type) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $clientID, $fornisseurID, $montantHT, $tva, $montantTTC, $type,
    ]);

    echo "<div class='alert alert-success text-center'>Facture enregistrée avec succès</div>";
} else {
    echo "<div class='alert alert-danger text-center'>Veuillez remplir tous les champs requis.</div>";
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
        <h2 class="text-center mb-4">Ajouter une facture :
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4 row align-items-center">
                    <label for="client" class="col-sm-2 col-form-label text-end lead">Client :</label>
                    <div class="col-sm-8">
                        <select name="ClientID" id="ClientID"
                            class="form-control rounded-pill bg-secondary bg-opacity-25 border-0">
                            <option value="" disabled selected>Choisir un client</option>
                            <?php foreach ($clients as $client): ?>
                            <?php if ($client['Role'] === 'Client'): ?>
                            <option value="<?php echo htmlspecialchars($client['ID']); ?>">
                                <?php echo htmlspecialchars($client['NameEntreprise']); ?>
                            </option>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4 row align-items-center">
                    <label for="fournisseur" class="col-sm-2 col-form-label text-end lead">Fournisseur :</label>
                    <div class="col-sm-8">
                        <select name="fornisseurID" id="fornisseurID"
                            class="form-control rounded-pill bg-secondary bg-opacity-25 border-0">
                            <option value="" disabled selected>Choisir un fournisseur</option>
                            <?php foreach ($clients as $client): ?>
                            <?php if ($client['Role'] === 'Fournisseur'): ?>
                            <option value="<?php echo htmlspecialchars($client['ID']); ?>">
                                <?php echo htmlspecialchars($client['NameEntreprise']); ?>
                            </option>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4 row align-items-center">
                    <label for="facteur" class="col-sm-2 col-form-label text-end lead">Type :</label>
                    <div class="col-sm-8">
                        <select name="facteur" id="facteur"
                            class="form-control rounded-pill bg-secondary bg-opacity-25 border-0">
                            <option value="" disabled selected>Choisir le type</option>
                            <option value="devis">Devis</option>
                            <option value="bl">BL</option>
                            <option value="facture">Facture</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4 row align-items-center">
                    <label for="tva" class="col-sm-2 col-form-label text-end lead">TVA (Fixe 20%) :</label>
                    <div class="col-sm-8">
                        <input type="number" name="tva" id="tva" value="20"
                            class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" readonly>
                    </div>
                </div>

                <div class="mb-4 row align-items-center">
                    <label for="Montant_HT" class="col-sm-2 col-form-label text-end lead">Montant HT :</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.01"
                            class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" id="Montant_HT"
                            name="Montant_HT">
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