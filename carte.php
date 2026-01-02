<?php
// carte.php — conversion HTML → PHP (aucune logique ajoutée)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Carte - Loisirs pour les étudiants</title>
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
        <a href="inscription.php">S'inscrire</a>
        <ul class="submenu">
          <li>1</li>
          <li>2</li>
          <li>3</li>
        </ul>
      </li>
      <li class="menu-item">
        <a href="carte.php" class="active-link">Localiser une activité</a>
        <ul class="submenu">
          <li>1</li>
          <li>2</li>
          <li>3</li>
        </ul>
      </li>
    </ul>
  </nav>

  <main>
    <!-- Section Carte -->
    <section id="carte" class="section active">
      <div class="map-container">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11016.326578277924!2d-72.58719635233152!3d46.34792398278404!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc7c8cb9aadbb03%3A0x36f5dd28a30395a8!2zVW5pdmVyc2l0w6kgZHUgUXXDqWJlYyDDoCBUcm9pcy1SaXZpw6hyZXM!5e0!3m2!1sfr!2sca!4v1763072832438!5m2!1sfr!2sca"
          width="400"
          height="300"
          style="border:0;"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          allowfullscreen>
        </iframe>
      </div>
    </section>
  </main>
</div>
</body>
</html>