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
    } elseif (strlen($fname) < 2) {
        $errors[] = "Le prénom doit contenir au moins 2 caractères.";
    } elseif (strlen($fname) > 50) {
        $errors[] = "Le prénom ne doit pas dépasser 50 caractères.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $fname)) {
        $errors[] = "Le prénom ne peut contenir que des lettres, espaces, traits d'union et apostrophes.";
    }

    if (empty($lname)) {
        $errors[] = "Le nom est requis.";
    } elseif (strlen($lname) < 2) {
        $errors[] = "Le nom doit contenir au moins 2 caractères.";
    } elseif (strlen($lname) > 50) {
        $errors[] = "Le nom ne doit pas dépasser 50 caractères.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $lname)) {
        $errors[] = "Le nom ne peut contenir que des lettres, espaces, traits d'union et apostrophes.";
    }

    if (empty($email)) {
        $errors[] = "L'e-mail est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'e-mail n'est pas valide.";
    } elseif (strlen($email) > 100) {
        $errors[] = "L'e-mail ne doit pas dépasser 100 caractères.";
    } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        $errors[] = "Format d'e-mail invalide.";
    } else {
        $domain = substr(strrchr($email, "@"), 1);
        if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
            $errors[] = "Le domaine de l'e-mail n'existe pas.";
        }
        
        $temp_domains = [
            'mailinator.com', '10minutemail.com', 'tempmail.org', 'guerrillamail.com',
            'maildrop.cc', 'temp-mail.org', 'throwaway.email', 'yopmail.com',
            'fakemailgenerator.com', 'tempail.com', 'mailtemp.info', 'getairmail.com',
            'sharklasers.com', 'guerrillamailblock.com', 'pokemail.net', 'spam4.me',
            'emailondeck.com', 'mailcatch.com', 'dispostable.com', 'trashmail.com'
        ];
        
        $domain_lower = strtolower($domain);
        if (in_array($domain_lower, $temp_domains)) {
            $errors[] = "Les adresses e-mail temporaires ne sont pas autorisées.";
        }
        
        if (strpos($email, '..') !== false || 
            strpos($email, '.-') !== false || 
            strpos($email, '-.') !== false) {
            $errors[] = "Format d'e-mail invalide.";
        }
        
        $local_part = substr($email, 0, strpos($email, '@'));
        if (substr($local_part, 0, 1) === '.' || 
            substr($local_part, -1) === '.' ||
            substr($local_part, 0, 1) === '-' || 
            substr($local_part, -1) === '-') {
            $errors[] = "Format d'e-mail invalide.";
        }
    }

    if (empty($password_raw)) {
        $errors[] = "Le mot de passe est requis.";
    } elseif (strlen($password_raw) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif (strlen($password_raw) > 255) {
        $errors[] = "Le mot de passe ne doit pas dépasser 255 caractères.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $password_raw)) {
        $errors[] = "Le mot de passe doit contenir au moins une lettre minuscule, une majuscule et un chiffre.";
    }

    if (empty($errors)) {
        try {
            if (!isset($pdo) || !$pdo) {
                $database = new Database();
                $pdo = $database->getConnection();
            }
            
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
            error_log("Database error: " . $e->getMessage());
            $errors[] = "Une erreur système s'est produite. Veuillez réessayer plus tard.";
        } catch (Exception $e) {
            error_log("General error: " . $e->getMessage());
            $errors[] = "Une erreur s'est produite. Veuillez réessayer.";
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