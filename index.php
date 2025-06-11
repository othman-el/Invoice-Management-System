<?php
include 'Database.php';

$stmt = $pdo->prepare("SELECT count(*) FROM liste_fourniseur_client WHERE Role = 'Client'");
$stmt->execute();
$count = $stmt->fetchColumn();

$stmtf = $pdo->prepare("SELECT count(*) FROM liste_fourniseur_client WHERE Role = 'Fournisseur'");
$stmtf->execute();
$countf = $stmtf->fetchColumn();

$stmtfr = $pdo->prepare("SELECT count(*) FROM factures");
$stmtfr->execute();
$countfr = $stmtfr->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des facturation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style/index.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Profile -->
        <div class="user-profile">
            <h5 class="text-white mb-1">Nom et prénom</h5>
            <p class="text-white-50 small mb-0">Email</p>
        </div>

        <!-- Navigation -->
        <nav class="nav flex-column">
            <a class="nav-link d-flex align-items-center gap-2" href="index.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1V9.5z" />
                </svg>
                Accueil
            </a>

            <a class="nav-link" href="Liste_Client.php">
                <i class="bi bi-people"></i>
                Les clients
            </a>
            <a class="nav-link" href="liste_fourniseur.php">
                <i class="bi bi-truck"></i>
                Les fournisseurs
            </a>
            <a class="nav-link" href="Liste_Facturation.php">
                <i class="bi bi-file-text"></i>
                Les factures
            </a>
            <a class="nav-link" href="Bank.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"
                    style="vertical-align: middle; margin-right: 6px;">
                    <path
                        d="M12 2L2 7v2h20V7L12 2zM4 10v10h2V10H4zm4 0v10h2V10H8zm4 0v10h2V10h-2zm4 0v10h2V10h-2zm4 0v10h2V10h-2zM2 22h20v-2H2v2z" />
                </svg>
                Banks
            </a>

            <a class="nav-link" href="Charge_fix.php">
                <i class="bi bi-currency-dollar"></i>
                Charge fixe
            </a>
            <a class="nav-link" href="items.php">
                <i class="bi bi-plus-circle"></i>
                Items
            </a>
        </nav>

        <div class="logout-section">
            <a class="nav-link" href="deconnexion.php">
                <i class="bi bi-box-arrow-right"></i>
                Déconnexion
            </a>
        </div>
    </div>

    <div class="main-content">
        <header class="header">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="logo-container">
                    <img src="images/logo.png" alt="logo" width="130px" height="130px" class="ms-5">
                </div>
                <div class="flex-grow-1 text-center">
                    <h1 class="h2 mb-0 fw-light">Gestion des facturation</h1>
                </div>
                <div style="width:130px;"></div>
            </div>
        </header>



        <main class="dashboard-content">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card stats-card">
                        <div class="card-body p-0">
                            <h3>Les clients :</h3>
                            <div class="number">
                                <?php echo $count; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card stats-card">
                        <div class="card-body p-0">
                            <h3>Les fournisseurs :</h3>
                            <div class="number">
                                <?php echo $countf; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card stats-card">
                        <div class="card-body p-0">
                            <h3>Les factures :</h3>
                            <div class="number">
                                <?php echo $countfr; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <button class="btn btn-primary d-md-none position-fixed" style="top: 1rem; left: 1rem; z-index: 1001;"
        onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('show');
    }

    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('[onclick="toggleSidebar()"]');

        if (window.innerWidth <= 768 &&
            !sidebar.contains(event.target) &&
            !toggleBtn.contains(event.target) &&
            sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });
    </script>
</body>

</html>