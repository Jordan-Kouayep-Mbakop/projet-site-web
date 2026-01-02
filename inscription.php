
<?php
require 'db_connect.php';

$message = '';

// --- 1. Traitement du formulaire (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données du formulaire
    $nom = filter_input(INPUT_POST, 'name');
    $prenom = filter_input(INPUT_POST, 'surname');
    $dateNaissance = filter_input(INPUT_POST, 'date');
    $sexe = filter_input(INPUT_POST, 'sex');
    $activite = filter_input(INPUT_POST, 'activity');
    $motivation = filter_input(INPUT_POST, 'motivation');
    

    // Simplification : nous allons juste vérifier si les champs requis sont remplis
    if ($nom && $prenom && $dateNaissance && $sexe && $activite && $motivation > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO inscription (name, surname, date, sex, activity, motivation) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $dateNaissance, $sexe, $activite, $motivation]);
            $message = "Inscription réussie !";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs obligatoires.";
    }
}


// --- 2. Récupération des activités disponibles (pas pleines) ---
$stmt = $pdo->prepare("
    SELECT
        a.name
    FROM
        activity a
    LEFT JOIN
        inscription i ON a.name = i.activity
    GROUP BY
        a.name, a.max_places
    HAVING
        a.max_places > COUNT(i.id)
    ORDER BY
        a.name
");
$stmt->execute();
$activites_disponibles = $stmt->fetchAll(PDO::FETCH_COLUMN);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Loisirs pour les étudiants</title>
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
                <ul class="submenu">
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="inscription.php" class="active-link">S'inscrire</a>
                <ul class="submenu">
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="carte.php">Localiser une activité</a>
                <ul class="submenu">
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                </ul>
            </li>
             <li class="menu-item">
                <a href="login.php">Authentification</a>
                <ul class="submenu">
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                </ul>
            </li>
        </ul>
    </nav>

    <main>
        <section id="inscription" class="section active">
            <h2>Inscrivez vous</h2>
            
            <?php if ($message): ?>
                <script>
                    // Affiche le message de réussite/erreur dans une alerte comme sur l'image 7.png
                    alert("<?= $message ?>");
                </script>
            <?php endif; ?>

            <form id="form-inscription" method="POST" action="inscription.php"> 
                <div class="form-grid">

                    <label for="prenom">Nom</label>
                    <div>
                        <input type="text" id="name" name="name" required>
                        <span class="error-msg" id="prenom-error"></span>
                    </div>

                    <label for="nom">Prénom</label>
                    <div>
                        <input type="text" id="surname" name="surname" required>
                        <span class="error-msg" id="nom-error"></span>
                    </div>

                    <label for="dateNaissance">Date de naissance</label>
                    <div>
                        <input type="date" id="date" name="date" required>
                        <span class="error-msg" id="date-error"></span>
                    </div>

                    <span>Sexe</span>
                    <div class="radio-group">
                        <label><input type="radio" name="sex" value="Homme" required> Homme</label>
                        <label><input type="radio" name="sex" value="Femme" required> Femme</label>
                        <span class="error-msg" id="sex"></span>
                    </div>

                    <label for="activity">Activité choisie</label>
                    <div>
                        <select id="activity" name="activity" required>
                            <option value="">-- Sélectionnez une activité --</option>
                            <?php foreach ($activites_disponibles as $activite): ?>
                                <option value="<?= htmlspecialchars($activite) ?>"><?= htmlspecialchars($activite) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error-msg" id="activite-error"></span>
                    </div>

                    <label for="motivation">Motivation</label>
                    <div>
                        <textarea id="motivation" name="motivation" rows="4"></textarea>
                    </div>
                </div>

                <div>
                    <button type="reset">Réinitialiser</button>
                    <button type="submit">Valider</button>
                </div>
            </form>
        </section>
    </main>
</div>

<script src="script.js"></script> 
</body>
</html>