
<?php
session_start(); // Démarre la session
require 'db_connect.php';

$message_erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = filter_input(INPUT_POST, 'identifiant');
    $mot_de_passe = filter_input(INPUT_POST, 'mot_de_passe');

    if ($identifiant && $mot_de_passe) {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");
        $stmt->execute([$identifiant]);
        $user = $stmt->fetch();

        // Note: Utiliser password_verify() pour vérifier le hachage en production
        if ($user && $user['password'] === $mot_de_passe) { // Simplifié pour le moment, à remplacer par password_verify
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: admin.php'); // Redirection vers la page d'administration
            exit;
        } else {
            $message_erreur = "Identifiant ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Loisirs pour les étudiants</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yatra+One&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <div>
        <img src="img/uqtr.png" alt="Logo de l'UQTR" class="logo">
    </div>
    <div>
        <h1>
            <span>Loisirs pour les étudiants!</span>
        </h1>
    </div>
</header>

<div class="layout">
    <nav class="main-nav">
        <ul class="menu">
             <li class="menu-item">
                <a href="index.php">Accueil</a>
                </li>
            <li class="menu-item">
                <a href="inscription.php">S'inscrire</a>
                 </li>
             <li class="menu-item">
                <a href="carte.php">Localiser une activité</a>
                 </li>
            <li class="menu-item">
                <a href="login.php" class="active-link">Authentification</a>
                 </li>
        </ul>
    </nav>

    <main>
        <section id="login" class="section active">
            <h2>Connexion</h2>
            <p>Veuillez renseigner vos identifiants pour vous connecter.</p>
            
            <?php if ($message_erreur): ?>
                <p style="color: red;"><?= $message_erreur ?></p>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="identifiant">Identifiant</label>
                    <input type="text" id="identifiant" name="identifiant" required>
                </div>
                <div class="form-group" style="margin-top: 10px;">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>
                <button type="submit" style="margin-top: 15px;">Connexion</button>
            </form>
        </section>
    </main>
</div>
</body>
</html>