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

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Ajouter une charge fixe </h2>
                <form method="post">

                    <div class="mb-4 row align-items-center">
                        <label for="designation" class="col-sm-4 col-form-label text-end">Désignation : </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="designation" name="designation" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="date_achat" class="col-sm-4 col-form-label text-end">Date Achat :</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="date_achat" name="date_achat" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="m" class="col-sm-4 col-form-label text-end">Mois :</label>
                        <div class="col-sm-8">
                            <input type="month" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="m" name="m" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="total_out" class="col-sm-4 col-form-label text-end">Total Out :</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.001"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" id="total_out"
                                name="total_out" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="montant" class="col-sm-4 col-form-label text-end">Montant :</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.001"
                                class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" id="montant"
                                name="montant" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="code_ref" class="col-sm-4 col-form-label text-end">Code Réf :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="code_ref" name="code_ref" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="categorie" class="col-sm-4 col-form-label text-end">Catégorie :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="categorie" name="categorie" required>
                        </div>
                    </div>

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