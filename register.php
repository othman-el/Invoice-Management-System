<?php
session_start();
require_once 'Database.php';

$errors = [];
$success_message = "";
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "L'e-mail existe déjà";
} else {

$stmt = $pdo->prepare("INSERT INTO users (fname, lname, email, password) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$fname, $lname, $email, $password])) {
$success_message = "Inscription réussie ! Vous pouvez maintenant vous connecter";
} else {
$errors[] = "L'enregistrement a échoué. Veuillez réessayer";
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
        <h2>Créer un compte</h2>
        <?php if ($errors): ?>
        <div class="error"></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="fname" placeholder="Nom" required><br>
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