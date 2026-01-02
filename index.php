
<?php
require 'db_connect.php'; // Inclure la connexion à la BDD

// Requête pour récupérer toutes les activités avec le nombre d'inscrits
$stmt = $pdo->prepare("
    SELECT
        a.id,
        a.name AS activity,
        a.responsable AS responsable,
        COUNT(i.id) AS nombre_inscrits
    FROM
        activity a
    LEFT JOIN
        inscription i ON a.name = i.activity
    GROUP BY
        a.id, a.name, a.responsable
    ORDER BY
        a.id
");
$stmt->execute();
$activites = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Loisirs pour les étudiants</title>
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
                <a href="index.php" class="active-link">Accueil</a>
                <ul class="submenu">
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="inscription.php">S'inscrire</a> <ul class="submenu">
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
                <a href="login.php">Authentification</a> <ul class="submenu">
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                </ul>
            </li>
        </ul>
    </nav>

    <main>
        <section id="accueil" class="section active">
            <h2>Notre but:</h2>
            <p>
                Notre site propose aux étudiants désireux de réaliser une ou plusieurs activités de loisir
                de rejoindre les différentes activités proposées dans la liste suivante en 3 étapes :
            </p>
            <ul>
                <li>S'inscrire</li>
                <li>Choisir une ou plusieurs activités</li>
                <li>Commencer les activités en groupe</li>
            </ul>
            <p>
                Les differentes activités des groupes sont sous la responsabilité de professionnelles. Il s'agit de passionnés du domaines qui vous feront découvrir des pans inédits de vos loisirs preférés. Qu'attendez-vous...? Rejoignez-nous!
            </p>

            <h2>Liste des activités disponibles</h2>
            <table class="table-activite">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Activité</th>
                    <th>Responsable</th>
                    <th>Nombre d’inscrits</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($activites as $activite): ?>
                <tr>
                    <th><?= htmlspecialchars($activite['id']) ?></th>
                    <td><?= htmlspecialchars($activite['activity']) ?></td>
                    <td><?= htmlspecialchars($activite['responsable']) ?></td>
                    <td><?= htmlspecialchars($activite['nombre_inscrits']) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
</body>
</html>