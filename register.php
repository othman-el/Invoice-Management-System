<?php
session_start();
require_once 'Database.php';

$errors = [];
$success_message = "";

$fname = $lname = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password_raw = $_POST['password'] ?? '';

    if (empty($fname)) {
        $errors[] = "Le prénom est requis.";
    } elseif (strlen($fname) > 50) {
        $errors[] = "Le prénom ne doit pas dépasser 50 caractères.";
    }

    if (empty($lname)) {
        $errors[] = "Le nom est requis.";
    } elseif (strlen($lname) > 50) {
        $errors[] = "Le nom ne doit pas dépasser 50 caractères.";
    }

    if (empty($email)) {
        $errors[] = "L'e-mail est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'e-mail n'est pas valide.";
    }

    if (empty($password_raw)) {
        $errors[] = "Le mot de passe est requis.";
    } elseif (strlen($password_raw) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $errors[] = "L'e-mail existe déjà.";
            } else {
                $password = password_hash($password_raw, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (fname, lname, email, password) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$fname, $lname, $email, $password])) {
                    $_SESSION['success_message'] = "Inscription réussie. Connectez-vous maintenant.";
                    header('Location: connexion.php');
                    exit;
                } else {
                    $errors[] = "L'enregistrement a échoué. Veuillez réessayer.";
                }
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur de base de données : " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>S'inscrire</title>
    <link rel="stylesheet" href="style/auth.css">
</head>

<body>
    <div class="form-container">
        <div class="container mb-4">
            <img src="images/logo.png" width="150" height="150" style="display: block; margin: 0 auto;">
        </div>
        <h2>Créer un compte</h2>
        <?php if ($errors): ?>
        <div class="error"></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="fname" placeholder="Nom" required autofocus><br>
            <input type="text" name="lname" placeholder="Prénom" required><br>
            <input type="email" name="email" placeholder="E-mail" required><br>
            <input type="password" name="password" placeholder="Password" required><br>

            <button type="submit">S'inscrire</button>
        </form>
        <p>Vous avez déjà un compte ? <a href="connexion.php">Connexion</a></p>


        <?php if ($errors): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if ($success_message){ ?>
        <div class="success">
            <p style="color: green;"><?php echo $success_message; ?></p>
        </div>
        <?php } ?>
    </div>
</body>

</html>