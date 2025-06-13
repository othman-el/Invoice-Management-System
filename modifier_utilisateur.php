<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>modifier utilisateur</title>
</head>

<body>
    </head>

    <?php
     include './front/head_front.php';
     require_once 'Database.php';
     session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$user_id = $_SESSION['user']['id'];
     $id = $_GET["id"];



if(isset($_POST["modifier"])){
  
$stmt = $pdo->prepare("SELECT * FROM liste_fourniseur_client WHERE id=?");
$stmt->execute([$id]);

$actors = $stmt->fetch(PDO::FETCH_ASSOC);

  $NameEntreprise = $_POST["NameEntreprise"];
  $ICE = $_POST["ICE"];
  $Adresse = $_POST["Adresse"];
  $Email = $_POST["Email"];
  $Contact = $_POST["Contact"];
  $NumeroGSM = $_POST["NumeroGSM"];
  $NumeroFixe = $_POST["NumeroFixe"];
  $Activite = $_POST["Activite"];
  $Role = $_POST["Role"];

 

     if(!empty($NameEntreprise) && !empty($ICE) && !empty($Adresse) && !empty($Email) && !empty($Contact) && !empty($NumeroGSM) && !empty($NumeroFixe) && !empty($Activite) && !empty($Role)){

          $stmt = $pdo->prepare("UPDATE liste_fourniseur_client 
                                    SET NameEntreprise=? , ICE=? , Adresse=? , Email=? , Contact=? , NumeroGSM=? , NumeroFixe=? , Activite=? , Role=?
                                    WHERE id=?");

           $stmt->execute([$NameEntreprise,$ICE,$Adresse,$Email,$Contact,$NumeroGSM,$NumeroFixe,$Activite,$Role,$id]);

     header("location:liste_fourniseur.php");

     
     }else{
        ?>
    <div class="alert alert-danger">
        <strong>Erreur!</strong> Veuillez remplir tous les champs.
    </div>
    <?php
     }
}
?>

    <body>

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <form action="" method="post">

                        <div class="mb-3">
                            <input type="hidden" class="form-control" value="<?php echo $actors['ID']?>" name="id">
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="name" class="col-sm-4 col-form-label text-end">Nom de l'entrprise :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="name" name="name" value="<?php echo $actors['NameEntreprise']?>" required>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="name" class="col-sm-4 col-form-label text-end">ICE :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="name" name="ICE" value="<?php echo $actors['ICE']?>" required>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="name" class="col-sm-4 col-form-label text-end">Adresse :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="name" name="Adresse" value="<?php echo $actors['Adresse']?>" required>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="name" class="col-sm-4 col-form-label text-end">Email :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="name" name="Email" value="<?php echo $actors['Email']?>" required>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="name" class="col-sm-4 col-form-label text-end">Contact :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="name" name="Contact" value="<?php echo $actors['Contact']?>" required>
                            </div>
                        </div>



                        <div class="mb-4 row align-items-center">
                            <label for="name" class="col-sm-4 col-form-label text-end">NumeroGSM :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="name" name="NumeroGSM" value="<?php echo $actors['NumeroGSM']?>" required>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="name" class="col-sm-4 col-form-label text-end">NumeroFixe :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="name" name="NumeroFixe" value="<?php echo $actors['NumeroFixe']?>" required>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label for="name" class="col-sm-4 col-form-label text-end">Activite :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0"
                                    id="name" name="Activite" value="<?php echo $actors['Activite']?>" required>
                            </div>
                        </div>


                        <div class="mb-4 row align-items-center">
                            <select name="role" class="form-control rounded-pill bg-secondary bg-opacity-25 border-0">
                                <option value="" disabled selected>selectionner le role</option>
                                <option value="Client">Client</option>
                                <option value="Fournisseur">Fournisseur</option>
                            </select>
                        </div>

                        <div class="row mt-5">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn rounded-pill px-5"
                                    style="background-color: #4f57c7; color: white;" name="modifier">SAVE</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </body>

</html>