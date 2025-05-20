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
<body>
    <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <img src="./images/logo (2).png" width="100" height="100">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
        $currentPage = $_SERVER['PHP_SELF'];
        ?>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item p-2">
                    <a class="nav-link "
                       aria-current="page" href="index.php"><i class="fa-solid fa-home"></i> Accueil</a>
                </li>
                <li class="nav-item p-2">
                    <a class="nav-link "
                       aria-current="page" href="liste_fourniseur.php"><i class="fa-solid fa-user-tie"></i>
                        Liste de Fourniseur</a>
                </li>
                <?php
     
                    ?>
                    <li class="nav-item p-2" >
                        <a class="nav-link"
                           aria-current="page" href="Liste_Client.php"><i class="fa-solid fa-user"></i> Liste de Client</a>
                    </li>
                    
                        <li class="nav-item p-2">
                            <a class="nav-link "
                            aria-current="page" href="Liste_Facturation.php"><i class="fa-solid fa-file"></i>
                                Liste de Facturation</a>
                        </li>

                    <li class="nav-item p-2">
                        <a class="nav-link "
                           aria-current="page" href="Bank.php"><i class="fa-solid fa-building-columns"></i>
                            Bank</a>
                    </li>

                        <li class="nav-item p-2">
                            <a class="nav-link "
                            aria-current="page" href="Charge_fix.php"><i class="fa-solid fa-chart-pie"></i>
                                Charge fix </a>
                        </li>
                   
                    <li class="nav-item p-2">
                        <a class="nav-link " aria-current="page" href="deconnexion.php"><i
                                    class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
                    </li>

                    <?php
            
                    ?>                  
              </ul>
        </div>
    </div>
</nav>
</body>
</html>