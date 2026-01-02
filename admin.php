
<?php
session_start();
require 'db_connect.php';

// Vérifier si l'utilisateur est connecté (sécurité)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Traitement de la suppression
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id_activite = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id_activite) {
        try {
            // Suppression de l'activité
            $stmt = $pdo->prepare("DELETE FROM activity WHERE id = ?");
            $stmt->execute([$id_activite]);
            
            // OPTIONNEL: Supprimer aussi les inscriptions liées si nécessaire
             $stmt = $pdo->prepare("DELETE FROM inscription WHERE activity = (SELECT name FROM activity WHERE id = ?)");
             $stmt->execute([$id_activity]);
            
            header('Location: admin.php');
            exit;
        } catch (PDOException $e) {
            // Gérer l'erreur de suppression
        }
    }
}

// Requête pour les détails des activités (avec places restantes et pourcentage)
$stmt = $pdo->prepare("
    SELECT
        a.id,
        a.name AS activity,
        a.responsable AS responsable,
        a.max_places,
        COUNT(i.id) AS nombre_inscrits,
        (a.max_places - COUNT(i.id)) AS places_restantes,
        ( (a.max_places - COUNT(i.id)) / a.max_places ) * 100 AS pourcentage_libre
    FROM
        activity a
    LEFT JOIN
        inscription i ON a.name = i.activity
    GROUP BY
        a.id, a.name, a.responsable, a.max_places
    ORDER BY
        a.id
");
$stmt->execute();
$details_activites = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Loisirs pour les étudiants</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yatra+One&display=swap" rel="stylesheet">
    <style>
        .logout-btn {
            background-color: #ff4500;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        .add-activity-btn {
            background-color: #5cb85c;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            float: right;
        }
        .supprimer-btn {
            background-color: #d9534f;
            color: white;
            padding: 3px 6px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9em;
        }
        .table-admin {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .table-admin th, .table-admin td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
        }
        .table-admin thead th {
            background-color: lightgrey;
            color: black;
            text-align: center;
        }
        .dashboard-grid {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }
        .dashboard-item {
            border: 2px solid #000000;
            padding: 10px;
            text-align: center;
            background-color: #ffffff;
        }
        /* CSS pour le cercle de progression */
        .progress-circle {
            position: relative;
            display: inline-block;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #f3f3f3;
            overflow: hidden;
        }
        .progress-circle .circle-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 8px solid #f3f3f3;
            border-left-color: orange;
            border-bottom-color: orange;
            border-right-color: orange;
            border-top-color: orange;
            box-sizing: border-box;
            transform: rotate(45deg); /* Point de départ en haut */
            clip: rect(0, 80px, 40px, 0); /* Cache la moitié supérieure */
        }
        /* Style pour masquer/démasquer le progress-circle */
        .progress-circle::after {
            content: attr(data-progress);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            color: orange;
        }
    </style>
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
                <a href="admin.php" class="active-link">Tableau de bord</a>
            </li>
            <li class="menu-item">
                <a href="log_out.php">Déconnexion</a>
            </li>
        </ul>
    </nav>

    <main>
        <section id="admin-dashboard" class="section active">
            <h2>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
            
            <a href="add_activity.php" class="add-activity-btn">+ Ajouter une nouvelle activité</a>
            <h3>Détails des activités</h3>

            <table class="table-admin">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Activité</th>
                    <th>Responsable</th>
                    <th>Nombre de places</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($details_activites as $detail): ?>
                <tr>
                    <td><?= htmlspecialchars($detail['id']) ?></td>
                    <td><?= htmlspecialchars($detail['activity']) ?></td>
                    <td><?= htmlspecialchars($detail['responsable']) ?></td>
                    <td><?= htmlspecialchars($detail['max_places']) ?></td>
                    <td>
                        <a href="?action=delete&id=<?= $detail['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer l\'activité : <?= htmlspecialchars($detail['activity']) ?> ?');" class="supprimer-btn">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <hr style="margin: 20px 0;">

            <h3>Statistiques de places</h3>
            <div class="dashboard-grid">
                <?php foreach ($details_activites as $detail): 
                    $pourcentage_libre = max(0, min(100, round($detail['pourcentage_libre']))); // S'assurer que le % est entre 0 et 100
                    $places_restantes = max(0, $detail['places_restantes']); // S'assurer que les places restantes ne sont pas négatives
                ?>
                <div class="dashboard-item">
                    <h4><?= htmlspecialchars($detail['activity']) ?></h4>
                    <p>Places restantes : *<?= $places_restantes ?>*</p>
                    <div class="progress-circle" data-progress="<?= $pourcentage_libre ?>%">
                        <div style="
                            position: absolute; 
                            top: 0; 
                            left: 0; 
                            width: 100%; 
                            height: 100%; 
                            border-radius: 50%;
                            background: conic-gradient(orange 0% <?= $pourcentage_libre ?>%, #f3f3f3 <?= $pourcentage_libre ?>% 100%); ">
                        </div>
                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; color: #000;">
                            <?= $pourcentage_libre ?>%
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>
</body>
</html>