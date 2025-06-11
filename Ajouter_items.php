<?php 
include_once 'Database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$sql = "SELECT * FROM liste_fourniseur_client";
$stmt = $pdo->prepare($sql);        
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['Qte']) && !empty($_POST['Montant_uHT'])) {
        $date = $_POST['Date'] ?? '';
        $fournisseur = $_POST['Fournisseur'] ?? '';
        $n_facture = $_POST['N_Facture'] ?? '';
        $article = $_POST['Article'] ?? '';
        $designation = $_POST['Designation'] ?? '';
        $qte = $_POST['Qte'];
        $montant_uht = $_POST['Montant_uHT'];
        $date_c = $_POST['Date_c'] ?? '';
        $n_devis = $_POST['N_Devis'] ?? '';
        $n_facture_c = $_POST['N_Facture_C'] ?? '';
        $client = $_POST['Client'] ?? '';
        $code_client = $_POST['Code_client'] ?? '';
        
        $tva = 20; 
        $total_uht = $qte * $montant_uht;
        $total_ttc = $total_uht + ($total_uht * $tva / 100);
        $mt_ht = $total_uht;
        $mt_ttc = $mt_ht + ($mt_ht * $tva / 100);

        $insertSql = "INSERT INTO items 
        (Date, Fournisseur, N_Facture, Article, Designation, Qte, Montant_uHT, Total_Uht, TVA, TOTAL_TTC, Date_c, N_Devis, N_Facture_C, Client, Code_client, Mt_HT, Mt_TTC) 
        VALUES 
        (:date, :fournisseur, :n_facture, :article, :designation, :qte, :montant_uht, :total_uht, :tva, :total_ttc, :date_c, :n_devis, :n_facture_c, :client, :code_client, :mt_ht, :mt_ttc)";
        
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([
            ':date' => $date,
            ':fournisseur' => $fournisseur,
            ':n_facture' => $n_facture,
            ':article' => $article,
            ':designation' => $designation,
            ':qte' => $qte,
            ':montant_uht' => $montant_uht,
            ':total_uht' => $total_uht,
            ':tva' => $tva,
            ':total_ttc' => $total_ttc,
            ':date_c' => $date_c,
            ':n_devis' => $n_devis,
            ':n_facture_c' => $n_facture_c,
            ':client' => $client,
            ':code_client' => $code_client,
            ':mt_ht' => $mt_ht,
            ':mt_ttc' => $mt_ttc
        ]);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTION DES FACTURATION</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="text-center mb-4">Ajouter un Items</h2>

                <form method="post">
                    <!-- Date Fournisseur -->
                    <div class="mb-4 row align-items-center">
                        <label for="Date" class="col-sm-4 col-form-label text-end">Date Fournisseur :</label>
                        <div class="col-sm-8">
                            <input type="date" id="Date" name="Date"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" required>
                        </div>
                    </div>

                    <!-- Fournisseur -->
                    <div class="mb-4 row align-items-center">
                        <label for="Fournisseur" class="col-sm-4 col-form-label text-end">Fournisseur :</label>
                        <div class="col-sm-8">
                            <select name="Fournisseur" id="Fournisseur"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" required>
                                <option value="" disabled selected>-- Sélectionner un fournisseur --</option>
                                <?php foreach ($results as $res): ?>
                                <?php if ($res['Role'] === 'Fournisseur'): ?>
                                <option value="<?= htmlspecialchars($res['ID']) ?>">
                                    <?= htmlspecialchars($res['NameEntreprise']) ?>
                                </option>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- N° Facture -->
                    <div class="mb-4 row align-items-center">
                        <label for="N_Facture" class="col-sm-4 col-form-label text-end">N° Facture :</label>
                        <div class="col-sm-8">
                            <input type="text" id="N_Facture" name="N_Facture"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" required>
                        </div>
                    </div>

                    <!-- Article -->
                    <div class="mb-4 row align-items-center">
                        <label for="Article" class="col-sm-4 col-form-label text-end">Article :</label>
                        <div class="col-sm-8">
                            <input type="text" id="Article" name="Article"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" required>
                        </div>
                    </div>

                    <!-- Designation -->
                    <div class="mb-4 row align-items-center">
                        <label for="Designation" class="col-sm-4 col-form-label text-end">Désignation :</label>
                        <div class="col-sm-8">
                            <input type="text" id="Designation" name="Designation"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" required>
                        </div>
                    </div>

                    <!-- Quantité -->
                    <div class="mb-4 row align-items-center">
                        <label for="Qte" class="col-sm-4 col-form-label text-end">Quantité :</label>
                        <div class="col-sm-8">
                            <input type="number" id="Qte" name="Qte"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" min="1" required>
                        </div>
                    </div>

                    <!-- Montant uHT -->
                    <div class="mb-4 row align-items-center">
                        <label for="Montant_uHT" class="col-sm-4 col-form-label text-end">Montant unitaire HT :</label>
                        <div class="col-sm-8">
                            <input type="number" id="Montant_uHT" name="Montant_uHT"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" min="0"
                                step="0.01" required>
                        </div>
                    </div>

                    <!-- Date client -->
                    <div class="mb-4 row align-items-center">
                        <label for="Date_c" class="col-sm-4 col-form-label text-end">Date (Client) :</label>
                        <div class="col-sm-8">
                            <input type="date" id="Date_c" name="Date_c"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" required>
                        </div>
                    </div>

                    <!-- N° Devis -->
                    <div class="mb-4 row align-items-center">
                        <label for="N_Devis" class="col-sm-4 col-form-label text-end">N° Devis :</label>
                        <div class="col-sm-8">
                            <input type="text" id="N_Devis" name="N_Devis"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0">
                        </div>
                    </div>

                    <!-- N° Facture Client -->
                    <div class="mb-4 row align-items-center">
                        <label for="N_Facture_C" class="col-sm-4 col-form-label text-end">N° Facture Client :</label>
                        <div class="col-sm-8">
                            <input type="text" id="N_Facture_C" name="N_Facture_C"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0">
                        </div>
                    </div>

                    <!-- Client -->
                    <div class="mb-4 row align-items-center">
                        <label for="Client" class="col-sm-4 col-form-label text-end">Client :</label>
                        <div class="col-sm-8">
                            <select name="Client" id="Client"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" required
                                onchange="updateCodeClient()">
                                <option value="" disabled selected>-- Sélectionner un client --</option>
                                <?php foreach ($results as $res): ?>
                                <?php if ($res['Role'] === 'Client'): ?>
                                <option value="<?= htmlspecialchars($res['ID']) ?>">
                                    <?= htmlspecialchars($res['NameEntreprise']) ?>
                                </option>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Code client -->
                    <div class="mb-4 row align-items-center">
                        <label for="Code_client" class="col-sm-4 col-form-label text-end">Code Client :</label>
                        <div class="col-sm-8">
                            <input type="text" id="Code_client" name="Code_client"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" required>
                        </div>
                    </div>

                    <!-- Montant HT -->
                    <div class="mb-4 row align-items-center">
                        <label for="Mt_HT" class="col-sm-4 col-form-label text-end">Montant HT :</label>
                        <div class="col-sm-8">
                            <input type="number" id="Mt_HT" name="Mt_HT"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" min="0"
                                step="0.01" required>
                        </div>
                    </div>

                    <!-- Bouton -->
                    <div class="row mt-5">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn rounded-pill px-5"
                                style="background-color: #4f57c7; color: white;">Ajouter</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>


</html>