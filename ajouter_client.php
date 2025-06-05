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
    } elseif ($contactExists) {
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



            


            
            $message = "<p style='color:green;text-align:center;'>Utilisateur ajouté avec succès ! Code de référence: <strong>$codeReference</strong></p>";
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
    <title>Ajouter Client</title>
</head>

<body>
    <?php
   include 'index.php';
  
  ?>
    <div class="container py-5">
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
                            <input type="email" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                id="email" name="email" required>
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
                        <input type="hidden" name="role" value="Client">
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