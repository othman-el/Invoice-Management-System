<?php
session_start();
require_once 'Database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit;
    } else {
        $errors[] = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <style>      
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f5f5f2;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.form-container, .dashboard {
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    width: 90%;
    max-width: 400px;
    
}
h2 {
    margin-bottom: 1rem;
    color: #0d6efd;
}
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 0.5rem;
    margin: 0.5rem 0;
    border: 1px solid #ccc;
    border-radius: 0.5rem;
}
button {
    background: #0d6efd;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    color: white;
    cursor: pointer;
}
button:hover {
    background: #0d6efd;
}
.error {
    color: red;
    margin-bottom: 1rem;
}
    </style>
<div class="form-container">
    <h2>Connexion</h2>
    <?php if ($errors): ?>
        <div class="error"><?= implode('<br>', $errors) ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required autofocus ><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">Connexion</button>
    </form>
    <p>No account ? <a href="register.php">S'inscrire</a></p>
</div>
</body>
</html>