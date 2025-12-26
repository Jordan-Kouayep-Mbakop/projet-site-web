
<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activite = filter_input(INPUT_POST, 'name');
    $responsable = filter_input(INPUT_POST, 'responsable');
    $maximum = filter_input(INPUT_POST, 'max_places');

    if ($activite && $responsable && $maximum > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO activity (name, responsable, max_places) VALUES (?, ?, ?)");
            $stmt->execute([$activite, $responsable, $maximum]);
            $message = "L'activité *" . htmlspecialchars($activite) . "* a été ajoutée avec succès !";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs correctement.";
    }
}

// Récupérer la liste complète des activités pour la liste déroulante (si nécessaire, ici simple, sinon depuis la BDD)
$activites_possibles = ['Natation', 'Badminton', 'Echecs', 'Velo', 'Kayak', 'Randonnée', 'Autre...'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Activité</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>...</header>
<div class="layout">
    <nav class="main-nav">
        </nav>
    <main>
        <section class="section active">
            <h2>Ajouter une activité</h2>
            
            <?php if ($message): ?>
                <p style="color: green; font-weight: bold;"><?= $message ?></p>
            <?php endif; ?>
            
            <form method="POST" action="add_activity.php" style="max-width: 400px;">
                <div style="margin-bottom: 15px;">
                    <label for="activite">Activité</label>
                    <select id="name" name="name" required style="width: 100%; padding: 5px;">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($activites_possibles as $act): ?>
                            <option value="<?= htmlspecialchars($act) ?>"><?= htmlspecialchars($act) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label for="responsable">Responsable</label>
                    <input type="text" id="responsable" name="responsable" required style="width: 100%; padding: 5px;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label for="maximum">Maximum (Nombre de places)</label>
                    <input type="number" id="max_places" name="max_places" min="1" required style="width: 100%; padding: 5px;">
                </div>
                
                <div>
                    <button type="submit" style="background-color: black; color: white; padding: 10px 15px; border: none; cursor: pointer;">Soumettre</button>
                    <a href="admin.php" style="margin-left: 10px; text-decoration: none; color: black;">Annuler</a>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>