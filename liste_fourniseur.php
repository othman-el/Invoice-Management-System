<?php
include_once 'Database.php';

$limite = 10;

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limite;

$countStmt = $pdo->query("SELECT COUNT(*) FROM liste_fourniseur_client WHERE role='Fournisseur'");
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limite);

$sql = "SELECT ID, NameEntreprise, ICE, Adresse, Email, Contact, NumeroGSM, NumeroFixe, Activite 
        FROM liste_fourniseur_client 
        WHERE role='Fournisseur'
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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
<h1 class="text-center">Liste de Fourniseur</h1>
<div class="d-flex justify-content-between align-items-center">
    <div class="container py-2">
        <a href="ajouter_fourniseur.php" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i>
            Ajouter Fournuiseur</a>
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
            <td>
                <div class="d-flex justify-content-center gap-2">
                    <a href="modification.php?id=<?= $user['ID'] ?>"
                        class="btn btn-sm btn-warning me-1 d-flex align-items-center" title="Modifier l'utilisateur"
                        data-bs-toggle="tooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path
                                d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                        </svg>
                        Modifier
                    </a>

                    <button onclick="confirmDelete(<?= $user['ID'] ?>)"
                        class="btn btn-sm btn-danger d-flex align-items-center" title="Supprimer l'utilisateur"
                        data-bs-toggle="tooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path
                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                            <path
                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />
                        </svg>
                        Supprimer
                    </button>
                </div>
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
<div class="d-flex justify-content-center my-3">
    <nav>
        <ul class="pagination">
            <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page - 1 ?>">Précédent</a>
            </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page + 1 ?>">Suivant</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<script src="recherche.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>