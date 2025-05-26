<?php
include_once 'Database.php';
$sql = "SELECT ID,NameEntreprise,ICE,Adresse,Email,Contact,NumeroGSM,NumeroFixe,Activite FROM liste_fourniseur_client where role ='Client' " ;
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Liste Client</title>
</head>
<?php
       include './front/head_front.php';
     ?>
<div class="d-flex justify-content-between align-items-center">
    <div class="container py-2">
        <a href="ajouter_client.php" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i>
            Ajouter Client</a>
    </div>
    <div class="container py-2" style="max-width: 400px;">
        <div class="position-relative">
            <input type="text" id="searchInput" placeholder="Rechercher par ID ou Nom"
                class="form-control ps-5 rounded-pill border-0 shadow-sm text-white bg-primary" />

            <style>
            #searchInput::placeholder {
                color: white;
                opacity: 1;
            }
            </style>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 50 50"
                class="position-absolute top-50 start-0 translate-middle-y ms-3">
                <path
                    d="M 21 3 C 11.621094 3 4 10.621094 4 20 C 4 29.378906 11.621094 37 21 37 C 24.710938 37 28.140625 35.804688 30.9375 33.78125 L 44.09375 46.90625 L 46.90625 44.09375 L 33.90625 31.0625 C 36.460938 28.085938 38 24.222656 38 20 C 38 10.621094 30.378906 3 21 3 Z M 21 5 C 29.296875 5 36 11.703125 36 20 C 36 28.296875 29.296875 35 21 35 C 12.703125 35 6 28.296875 6 20 C 6 11.703125 12.703125 5 21 5 Z">
                </path>
            </svg>
        </div>
    </div>
</div>
<table class="table table-striped table-bordered" id="dataTable">
    <thead class="bg-primary text-center">
        <tr>
            <th class="text-white">C N°</th>
            <th class="text-white">Nom de l'entreprise </th>
            <th class="text-white">ICE</th>
            <th class="text-white">Adresse</th>
            <th class="text-white">Email</th>
            <th class="text-white">Contact</th>
            <th class="text-white">NumeroGSM</th>
            <th class="text-white">NumeroFixe</th>
            <th class="text-white">Activite</th>
            <th class="text-white">Action</th>

        </tr>
    </thead>
    <tbody>
        <?php if (count($users) > 0): ?>
        <?php foreach ($users as $user): ?>
        <tr>
            <td class=" text-center"><?= htmlspecialchars($user['ID']) ?></td>
            <td><?= htmlspecialchars($user['NameEntreprise']) ?></td>
            <td><?= htmlspecialchars($user['ICE']) ?></td>
            <td><?= htmlspecialchars($user['Adresse']) ?></td>
            <td><?= htmlspecialchars($user['Email']) ?></td>
            <td><?= htmlspecialchars($user['Contact']) ?></td>
            <td><?= htmlspecialchars($user['NumeroGSM']) ?></td>
            <td><?= htmlspecialchars($user['NumeroFixe']) ?></td>
            <td><?= htmlspecialchars($user['Activite']) ?></td>
            <td class="text-center">
                <a href="modification.php?id=<?= $user['ID'] ?>" class="btn btn-sm btn-warning me-1">
                    <i class="fa-solid fa-pen"></i>
                </a>
                <a href="supprimer.php?id=<?= $user['ID'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
                    onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="4" class="text-center">Aucun utilisateur trouvé.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<script src="recherche.js"></script>
</body>

</html>