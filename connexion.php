<?php
session_start();
require_once 'Database.php';

$errors = [];
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'fname' => $user['fname'],
            'lname' => $user['lname'],
            'email' => $user['email']
        ];
        $success_message = "Welcome, " . $user['fname'] . " " . $user['lname'] . "!";

        header("Location: index.php");
    } else {
        $errors[] = "Wrong email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style/auth.css">
</head>

<body>
    <div class="form-container">
        <h2>Connexion</h2>
        <?php if ($errors): ?>
        <div class="error"></div>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" placeholder="E-mail" required autofocus><br>
            <input type="password" name="password" placeholder="Mot de passe" required><br>
            <button type="submit">Connexion</button>
        </form>
        <p>No account ? <a href="register.php">S'inscrire</a></p>

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