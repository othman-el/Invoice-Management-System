<?php
include_once 'Database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO bank (date, Code_achat, TOTAL_IN, Observation, Code_ref, Cheque_N, Reste_Caisse)
            VALUES (:date, :Code_achat, :TOTAL_IN, :Observation, :Code_ref, :Cheque_N, :Reste_Caisse)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':date' => $_POST['date'],
        ':Code_achat' => $_POST['Code_achat'],
        ':TOTAL_IN' => $_POST['TOTAL_IN'],
        ':Observation' => $_POST['Observation'],
        ':Code_ref' => $_POST['Code_ref'],
        ':Cheque_N' => $_POST['Cheque_N'],
        ':Reste_Caisse' => $_POST['Reste_Caisse']
    ]);

    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    exit;
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
    <title>Banks</title>
</head>
<?php
       include './front/head.php';
     ?>

<body>
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
        <h1 class="text-center">Ajouter un banque : </h1> <br>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <form method="post">
                    <div class="mb-4 row align-items-center">
                        <label for="date" class="col-sm-4 col-form-label text-end">Date de la transaction :</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="date" name="date" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="Code_achat" class="col-sm-4 col-form-label text-end">Code achat :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="Code_achat" name="Code_achat" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="total_in" class="col-sm-4 col-form-label text-end">TOTAL IN :</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="total_in" name="TOTAL_IN" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="observation" class="col-sm-4 col-form-label text-end">Observation :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="observation" name="Observation" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="code_ref" class="col-sm-4 col-form-label text-end">Type de transaction :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="code_ref" name="Code_ref" required>
                        </div>
                    </div>




                    <div class="mb-4 row align-items-center">
                        <label for="cheque_n" class="col-sm-4 col-form-label text-end">Code banque :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="cheque_n" name="Cheque_N" required>
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="reste_caisse" class="col-sm-4 col-form-label text-end">Reste Caisse :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="reste_caisse" name="Reste_Caisse" required>
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