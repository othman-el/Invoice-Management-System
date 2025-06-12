<?php
include_once 'Database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO charge_fix (DESIGNATION, Date_Achat, M, TOTAL_OUT, Montant, Code_REF, Categorie,user_id)
            VALUES (:designation, :date_achat, :m, :total_out, :montant, :code_ref, :categorie,:user_id)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':designation' => $_POST['designation'],
        ':date_achat'  => $_POST['date_achat'],
        ':m'           => $_POST['m'],
        ':total_out'   => $_POST['total_out'],
        ':montant'     => $_POST['montant'],
        ':code_ref'    => $_POST['code_ref'],
        ':categorie'   => $_POST['categorie'],
        ':user_id' => $user_id
    ]);

header("Location: Charge_fix.php");
exit;
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
    <?php include './front/head.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <a href="index.php"><button class="retoure">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"
                        height="40px" width="20px" version="1.1" id="Capa_1" viewBox="0 0 206.108 206.108"
                        xml:space="preserve">
                        <path
                            d="M152.774,69.886H30.728l24.97-24.97c3.515-3.515,3.515-9.213,0-12.728c-3.516-3.516-9.213-3.515-12.729,0L2.636,72.523  c-3.515,3.515-3.515,9.213,0,12.728l40.333,40.333c1.758,1.758,4.061,2.636,6.364,2.636c2.303,0,4.606-0.879,6.364-2.636  c3.515-3.515,3.515-9.213,0-12.728l-24.97-24.97h122.046c19.483,0,35.334,15.851,35.334,35.334s-15.851,35.334-35.334,35.334H78.531  c-4.971,0-9,4.029-9,9s4.029,9,9,9h74.242c29.408,0,53.334-23.926,53.334-53.334S182.182,69.886,152.774,69.886z" />
                    </svg>
                    retoure
                </button></a>
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
                                style="background-color: #009fbc; color: white;">Ajouter</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>


</html>