<?php
require_once __DIR__ . '/../backend/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Accueil - Cagnotte Robux & V-Bucks</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
<h1>🎮 Bienvenue sur la Cagnotte Robux & V-Bucks</h1>
<p>Participez gratuitement pour tenter de gagner des cartes <strong>Robux</strong> ou <strong>V-Bucks</strong> !<br>
Le tirage a lieu <strong>tous les samedis à 10h</strong> (heure française).</p>


<div class="choice-box">
<h2>Choisissez votre récompense :</h2>
<div class="buttons">
<a href="participate.php?category=robux"><button>🎁 Participer pour Robux</button></a>
<a href="participate.php?category=vbucks"><button>🎁 Participer pour V-Bucks</button></a>
</div>
</div>


<div class="links" style="margin-top:30px;">
<a href="winners.php">🏆 Voir les derniers gagnants</a> |
<a href="legal/mentions-legales.php">📑 Mentions légales</a> |
<a href="legal/politique-confidentialite.php">🔒 Politique de confidentialité</a> |
<a href="legal/cookies.php">🍪 Cookies</a>
</div>
</div>
</body>
</html>