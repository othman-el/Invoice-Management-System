<?php
include_once 'Database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$user_id = $_SESSION['user']['id'];
if (!isset($_GET['id'])) {
    die('ID du fournisseur manquant');
}

$id = $_GET['id'];

$sql = "SELECT * FROM liste_fourniseur_client WHERE ID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Fournisseur non trouvé');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE liste_fourniseur_client SET 
        NameEntreprise = ?, ICE = ?, Adresse = ?, Email = ?, Contact = ?, 
        NumeroGSM = ?, NumeroFixe = ?, Activite = ? , Role = ? 
        WHERE ID = ?";
        
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['name'],
        $_POST['ice'],
        $_POST['adresse'],
        $_POST['email'],
        $_POST['contact'],
        $_POST['numeroGSM'],
        $_POST['numeroFix'],
        $_POST['activite'],
        $_POST['role'],
        $id
    ]);

    header('Location: liste_fourniseur.php'); 
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    <title>Modification des informations</title>
</head>

<body>
    <?php
       include './front/head_front.php';
     ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST">
                    <div class="mb-4 row align-items-center">
                        <label for="name" class="col-sm-4 col-form-label text-end">Nom de l'entreprise :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="name" name="name" value="<?= htmlspecialchars($user['NameEntreprise']) ?>">
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="ice" class="col-sm-4 col-form-label text-end">ICE :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="ice" name="ice" value="<?= htmlspecialchars($user['ICE']) ?>">
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="adresse" class="col-sm-4 col-form-label text-end">Adresse :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="adresse" name="adresse" value="<?= htmlspecialchars($user['Adresse']) ?>">
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="email" class="col-sm-4 col-form-label text-end">Email :</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="email" name="email" value="<?= htmlspecialchars($user['Email']) ?>">
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="contact" class="col-sm-4 col-form-label text-end">Contact :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="contact" name="contact" value="<?= htmlspecialchars($user['Contact']) ?>">
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="numeroGSM" class="col-sm-4 col-form-label text-end">Numéro GSM:</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="numeroGSM" name="numeroGSM" value="<?= htmlspecialchars($user['NumeroGSM']) ?>">
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="numeroFix" class="col-sm-4 col-form-label text-end">Numéro Fix:</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="numeroFix" name="numeroFix" value="<?= htmlspecialchars($user['NumeroFixe']) ?>">
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="activite" class="col-sm-4 col-form-label text-end">Activité:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="activite" name="activite" value="<?= htmlspecialchars($user['Activite']) ?>">
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label for="role" class="col-sm-4 col-form-label text-end">Rôle :</label>
                        <div class="col-sm-8">
                            <select name="role" id="role" required
                                class="form-select rounded-pill bg-secondary bg-opacity-25 border-0">
                                <option value="" disabled selected>-- Sélectionner un rôle --</option>
                                <option value="Fournisseur">Fournisseur</option>
                                <option value="Client">Client</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn rounded-pill px-5"
                                style="background-color: #4f57c7; color: white;">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>