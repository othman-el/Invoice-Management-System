<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Fournuiseur</title>
</head>
<body>
  <?php
   include 'index.php';
  
  ?>
<div class="container mt-5" style="width: 300px; height: 40px;">

<form method="post" >
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Nom de l'entreprise </label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp " >
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">ICE </label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Adresse</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Contact</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Numero GSM </label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Numero Fix </label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Activité</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
      <select name="" id="" class="form-control">
         <option value="" disabled selected> Sélectionnez un type</option>
      <option value="client" > Client</option>
       <option value="fournisseur">Fournisseur</option>
    </select>
  </div>
  

  <button type="submit" class="btn btn-primary">SAVE</button>
</form>
</div>
 
</body>
</html>