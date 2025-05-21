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
input[type="text"], input[type="password"] ,input[type="email"] {
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


