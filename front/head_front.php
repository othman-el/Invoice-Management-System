<?php 

include 'Database.php';

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$user_id = $_SESSION['user']['id'];

?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
</head>
<style>
:root {
    --sidebar-bg: #532C97;
    --card-bg: #6363d8;
    --main-bg: #f3f3f3;
}

.header {
    background-color: #ffffff;
    color: #22a8c1;
    padding: 2rem;
}

h1,
h2 {
    color: #22a8c1;
}

.sidebar {
    background-color: #009fbc;
    min-height: 100vh;
    width: 250px;
    position: fixed;
    left: -250px;
    top: 0;
    z-index: 9999;
    transition: left 0.3s ease-in-out;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    display: block !important;
}

.sidebar.show {
    left: 0 !important;
    display: block !important;
}

.sidebar .user-profile {
    padding: 2rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    text-align: center;
}

.sidebar .nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-weight: bold;
}

.sidebar .user-avatar {
    width: 64px;
    height: 64px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    background: transparent;
}

.sidebar .user-avatar i {
    font-size: 2rem;
    color: white;
}

.sidebar .user-profile h5 {
    color: white;
    margin-bottom: 0.5rem;
}

.sidebar .user-profile p {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.875rem;
    margin-bottom: 0;
}

.sidebar .nav-link {
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 0;
    display: flex;
    align-items: center;
    transition: background-color 0.2s;
    text-decoration: none;
}

.sidebar .nav-link:hover {
    background-color: #37B7C3;
    color: white;
}

.sidebar .nav-link i {
    margin-right: 0.75rem;
    width: 20px;
    font-size: 1.1rem;
}

.sidebar .logout-section {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.sidebar .logout-section .nav-link {
    color: white;
}

.sidebar .logout-section .nav-link:hover {
    background-color: rgba(255, 107, 107, 0.1);
    color: white;
}

.burger-menu {
    position: fixed;
    top: 9rem;
    left: 1rem;
    z-index: 1001;
    background-color: #009fbc;
    border: none;
    border-radius: 8px;
    padding: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.burger-menu:hover {
    background-color: #37B7C3;
    transform: scale(1.05);
}

.burger-menu i {
    color: white;
    font-size: 1.2rem;
}

.burger-menu.sidebar-open {
    left: 270px;
}

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.sidebar-overlay.show {
    opacity: 1;
    visibility: visible;
}

@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        left: -100%;
    }

    .sidebar.show {
        left: 0;
    }

    .burger-menu.sidebar-open {
        left: 1rem;
    }
}
</style>
<header class="header">
    <div class="d-flex align-items-center justify-content-between w-100">
        <div class="logo-container">
            <img src="images/logo.png" alt="logo" width="130px" height="130px">
        </div>
        <div class="flex-grow-1 text-center">
            <h1>Gestion des facturation</h1>
        </div>
        <div style="width:130px;"></div>
    </div>
</header>
<button class="burger-menu" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="sidebar" id="sidebar">
    <div class="user-profile">
        <h5 class="text-white mb-1"><?php echo $_SESSION['user']['fname'] . ' ' . $_SESSION['user']['lname']?></h5>
        <p class="text-white-50 small mb-0"><?php echo $_SESSION['user']['email']?></p>
    </div>

    <nav class="nav flex-column">
        <a class="nav-link" href="index.php">
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
            <i class="fa-solid fa-right-from-bracket"></i>
            Déconnexion
        </a>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const burgerMenu = document.querySelector('.burger-menu');

    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
    burgerMenu.classList.toggle('sidebar-open');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const burgerMenu = document.querySelector('.burger-menu');

    sidebar.classList.remove('show');
    overlay.classList.remove('show');
    burgerMenu.classList.remove('sidebar-open');
}

document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.sidebar .nav-link');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        }
    });
});

document.querySelectorAll('.sidebar .nav-link').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            closeSidebar();
        }
    });
});
</script>