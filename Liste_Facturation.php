<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
include_once 'Database.php';

$stmt = $pdo->prepare("SELECT f.ID as facture, f.*, c.* FROM liste_facturation f JOIN liste_fourniseur_client c ON f.ClientID = c.ID");
$stmt->execute();
$factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste Facturation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include './front/head_front.php'; ?>

    <div class="table-responsive" style="overflow-x: auto;">
        <h1 class="text-center">Liste des factures</h1>
        <div class="d-flex justify-content-between align-items-center mb-3 container">
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
            <div>
                <a href="ajouter_facture.php" class=" btn btn-sm btn-primary " title=" Ajouter une facture">
                    <i class="fa-solid fa-file-invoice"></i>
                    Ajouter une facture

                </a>
            </div>
        </div>
        <table class="table table-striped table-bordered" id="dataTable" style="white-space: nowrap;">
            <thead class="bg-primary text-center">
                <tr>
                    <th class="text-white">Client Nº</th>
                    <th class="text-white">Nom de l'entreprise</th>
                    <th class="text-white">ICE</th>
                    <th class="text-white">Adresse</th>
                    <th class="text-white">Email</th>
                    <th class="text-white">Contact</th>
                    <th class="text-white">Numero GSM</th>
                    <th class="text-white">Numero Fixe</th>
                    <th class="text-white">Activite</th>
                    <th class="text-white">Type</th>
                    <th class="text-white">Montant HT</th>
                    <th class="text-white">TVA</th>
                    <th class="text-white">Montant TTC</th>
                    <th class="text-white">Document</th>
                    <th class="text-white">Date de création</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($factures as $facture): ?>
                <tr>
                    <td class="text-center"><?= htmlspecialchars($facture['ClientID']); ?></td>
                    <td><?= htmlspecialchars($facture['NameEntreprise']); ?></td>
                    <td><?= htmlspecialchars($facture['ICE']); ?></td>
                    <td><?= htmlspecialchars($facture['Adresse']); ?></td>
                    <td><?= htmlspecialchars($facture['Email']); ?></td>
                    <td><?= htmlspecialchars($facture['Contact']); ?></td>
                    <td><?= htmlspecialchars($facture['NumeroGSM']); ?></td>
                    <td><?= htmlspecialchars($facture['NumeroFixe']); ?></td>
                    <td><?= htmlspecialchars($facture['Activite']); ?></td>
                    <td><?= htmlspecialchars($facture['type']); ?></td>
                    <td><?= htmlspecialchars($facture['Montant_HT']); ?></td>
                    <td><?= htmlspecialchars($facture['TVA']); ?></td>
                    <td><?= htmlspecialchars($facture['Montant_TTC']); ?></td>
                    <td class="text-center">
                        <?php
                            $docPath = $facture['Document']; 
                            if (!empty($docPath) && file_exists($docPath)) {
                                $filename = basename($docPath);
                                $encodedFile = urlencode($filename);
                               echo '<a href="download_document.php?file=' . $encodedFile . '" class="btn btn-sm btn-success me-1" title="Télécharger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download me-1" viewBox="0 0 16 16">
                                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                        </svg>
                                        Télécharger
                                    </a>';

                                echo '<a href="view_document.php?file=' . $encodedFile . '" class="btn btn-sm btn-info" title="Voir" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye me-1" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                    Voir
                                </a>';
                            } else {
                                echo 'Aucun fichier';
                            }
                            ?>

                    </td>
                    <td><?= htmlspecialchars($facture['Date_Creation']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="recherche.js"></script>
</body>

</html>