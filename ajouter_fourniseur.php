<?php
include_once 'Database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $ice = $_POST['ice'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $numeroGSM = $_POST['numeroGSM'] ?? null;
    $numeroFix = $_POST['numeroFix'] ?? null;
    $activite = $_POST['activite'] ?? '';
    $role = $_POST['role'] ?? '';

    $checkIce = $pdo->prepare("SELECT COUNT(*) FROM liste_fourniseur_client WHERE ice = :ice");
    $checkIce->execute([':ice' => $ice]);
    $iceExists = $checkIce->fetchColumn();

    $checkEmail = $pdo->prepare("SELECT COUNT(*) FROM liste_fourniseur_client WHERE Email = :email");
    $checkEmail->execute([':email' => $email]);
    $emailExists = $checkEmail->fetchColumn();

    $checkContact = $pdo->prepare("SELECT COUNT(*) FROM liste_fourniseur_client WHERE Contact = :contact");
    $checkContact->execute([':contact' => $contact]);
    $contactExists = $checkContact->fetchColumn();

    if ($iceExists && $emailExists && $contactExists) {
        $message = "<p style='color:orange;text-align:center;'>ICE de l'entreprise et Email existent déjà.</p>";
    } elseif ($iceExists) {
        $message = "<p style='color:orange;text-align:center;'>ICE de l'entreprise existe déjà.</p>";
    } elseif ($emailExists) {
        $message = "<p style='color:orange;text-align:center;'>Email existe déjà.</p>";
    } 
    elseif ($contactExists) {
        $message = "<p style='color:orange;text-align:center;'>Contact existe déjà.</p>";
    } else {
     $sql = "INSERT INTO liste_fourniseur_client 
        (NameEntreprise, ICE, Adresse, Email, Contact, NumeroGSM, NumeroFixe, Activite, Role)
        VALUES (:name, :ice, :adresse, :email, :contact, :numeroGSM, :numeroFix, :activite, :role)";

$stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
           ':name' => $name,
            ':ice' => $ice,
            ':adresse' => $adresse,
            ':email' => $email,
            ':contact' => $contact,
            ':numeroGSM' => $numeroGSM,
            ':numeroFix' => $numeroFix,
            ':activite' => $activite,
            ':role' => $role
            ]);
             $lastId = $pdo->lastInsertId();

            $codeReference = str_pad($lastId, 3, '0', STR_PAD_LEFT);

            $updateSql = "UPDATE liste_fourniseur_client SET Code_de_reference = :code WHERE ID = :id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                ':code' => $codeReference,
                ':id' => $lastId
            ]);
            $message = "<p style='color:green;text-align:center;'>Utilisateur ajouté avec succès !</p>";
        } catch (PDOException $e) {
            $message = "<p style='color:red;text-align:center;'>Erreur lors de l'ajout : " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Fournuiseur</title>
</head>

<body>
    <?php include './front/head.php'; ?>

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
            <h1 class="text-center">Ajouter un fournuiseur : </h1> <br>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form method="POST">
                        <div class="mb-4 row align-items-center">
                            <label for="name" class="col-sm-4 col-form-label text-end">Nom de l'entrprise :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="name" name="name" required>
                            </div>
                        </div>
                        <div class="mb-4 row align-items-center">
                            <label for="ice" class="col-sm-4 col-form-label text-end">ICE :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="ice" name="ice" required>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="adresse" class="col-sm-4 col-form-label text-end">Adresse :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="adresse" name="adresse" required>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="email" class="col-sm-4 col-form-label text-end">Email :</label>
                            <div class="col-sm-8">
                                <input type="email"
                                    class="form-control rounded-pill bg-secondary bg-opacity-25 border-0" id="email"
                                    name="email" required>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="contact" class="col-sm-4 col-form-label text-end">Contact :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="contact" name="contact" required>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="numeroGSM" class="col-sm-4 col-form-label text-end">Numero GSM :</label>
                            <div class="col-sm-8">
                                <input type="tel" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="numeroGSM" name="numeroGSM">
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="numeroFix" class="col-sm-4 col-form-label text-end">Numero Fix :</label>
                            <div class="col-sm-8">
                                <input type="tel" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="numeroFix" name="numeroFix">
                            </div>
                        </div>
                        <div class="mb-4 row align-items-center">
                            <label for="activite" class="col-sm-4 col-form-label text-end">Activité :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="activite" name="activite" required>
                            </div>
                        </div>
                        <div class="mb-4 row align-items-center">
                            <input type="hidden" name="role" value="Fournisseur">
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