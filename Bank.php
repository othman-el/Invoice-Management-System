<?php
include_once 'Database.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$user_id = $_SESSION['user']['id'];

$sql = "SELECT * FROM bank WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$banks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet"
        type="text/css">
    <link rel="stylesheet" href="style/style.css">

</head>

<body>
    <?php include './front/head_front.php'; ?>

    <h2 class="text-center mb-4 my-4 ">Liste des banques</h2>

    <div class="d-flex justify-content-between align-items-center my-3 px-4">
        <div>
            <a href="ajouter_bank.php" class="btne">
                <i class="fa-solid fa-plus"></i> Ajouter un Bank
            </a>
        </div>
        <div style="max-width: 400px;">
            <div class="position-relative">
                <input type="text" id="searchInput" placeholder="Rechercher par code ou observation"
                    class="form-control ps-5 rounded-pill border-0 shadow-sm text-white" />

                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 50 50"
                    class="position-absolute top-50 start-0 translate-middle-y ms-3">
                    <path
                        d="M 21 3 C 11.621094 3 4 10.621094 4 20 C 4 29.378906 11.621094 37 21 37 C 24.710938 37 28.140625 35.804688 30.9375 33.78125 L 44.09375 46.90625 L 46.90625 44.09375 L 33.90625 31.0625 C 36.460938 28.085938 38 24.222656 38 20 C 38 10.621094 30.378906 3 21 3 Z M 21 5 C 29.296875 5 36 11.703125 36 20 C 36 28.296875 29.296875 35 21 35 C 12.703125 35 6 28.296875 6 20 C 6 11.703125 12.703125 5 21 5 Z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <div class="table-responsive ">
        <table class="table table-striped table-bordered">
            <thead class=" text-white text-center">
                <tr>
                    <th class="text-white ">ID</th>
                    <th class="text-white ">Date</th>
                    <th class="text-white ">Code Achat</th>
                    <th class="text-white ">Total In</th>
                    <th class="text-white ">Observation</th>
                    <th class="text-white ">Code Ref</th>
                    <th class="text-white ">Cheque N</th>
                    <th class="text-white ">Reste Caisse</th>
                </tr>
            </thead>
            <tbody id="bankTable">
                <?php if (count($banks) > 0): ?>
                <?php foreach ($banks as $bank): ?>
                <tr>
                    <td class="text-center"><?= htmlspecialchars($bank['ID']) ?></td>
                    <td><?= htmlspecialchars($bank['Date']) ?></td>
                    <td><?= htmlspecialchars($bank['Code_achat']) ?></td>
                    <td><?= htmlspecialchars($bank['TOTAL_IN']) ?></td>
                    <td><?= htmlspecialchars($bank['Observation']) ?></td>
                    <td><?= htmlspecialchars($bank['Code_ref']) ?></td>
                    <td><?= htmlspecialchars($bank['Cheque_N']) ?></td>
                    <td><?= htmlspecialchars($bank['Reste_Caisse']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Aucune donnée disponible.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
        var input = this.value.toLowerCase();
        var rows = document.querySelectorAll("#bankTable tr");
        rows.forEach(function(row) {
            var text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    });
    </script>
</body>

</html>