<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet"
      type="text/css">
    <title>Document</title>
</head>
     <?php
       include './front/head_front.php';
     ?>
      <div class="container py-2">
      <a href="ajouter_fourniseur.php" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i>
      Ajouter Client</a>
     </div>
</body>
</html>