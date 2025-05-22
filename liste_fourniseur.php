<?php
  include_once 'Database.php';
  $sql = "SELECT ID,NameEntreprise,ICE,Adresse,Email,Contact,NumeroGSM,NumeroFixe,Activite FROM liste_fourniseur_client where role='Fournisseur' ";
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
    <title>Liste Fournuiseur</title>
</head>

<?php
       include './front/head_front.php';
     ?>
<div class="container py-2">
    <a href="ajouter_fourniseur.php" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i>
        Ajouter Fournuiseur</a>
</div>
<table class="table table-striped table-bordered">
    <thead class="bg-primary text-center">
        <tr>
            <th class="text-white">F N°</th>
            <th class="text-white">Nom de l'entreprise</th>
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
                <a href="modifier_utilisateur.php?id=<?= $user['ID'] ?>" class="btn btn-sm btn-warning me-1"
                    title="Modifier">
                    <i class="fa-solid fa-pen"></i>
                </a>
                <a href="supprimer_utilisateur.php?id=<?= $user['ID'] ?>" class="btn btn-sm btn-danger"
                    title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
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
</body>

</html>