<?php
require_once __DIR__ . '/../backend/config.php';


$category = $_GET['category'] ?? '';
if (!in_array($category, ['robux', 'vbucks'])) {
die('Catégorie invalide.');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Participer - <?= ucfirst(e($category)) ?></title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
<h1>Participation pour gagner des <?= ucfirst(e($category)) ?></h1>
<form action="../backend/submit_entry.php" method="POST" id="participationForm">
<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
<input type="hidden" name="category" value="<?= e($category) ?>">


<label>Email *</label>
<input type="email" name="email" required>


<label>Pseudo (Roblox ou Fortnite) *</label>
<input type="text" name="username" required>


<label>Méthode de contact *</label>
<select name="contact_method" required>
<option value="">-- Choisir --</option>
<option value="discord">Discord</option>
<option value="instagram">Instagram</option>
<option value="snapchat">Snapchat</option>
<option value="email">Email</option>
</select>


<label>Identifiant contact (ex: @pseudo)</label>
<input type="text" name="contact_value" required>


<button type="submit">Valider ma participation</button>
</form>
</div>


<script src="assets/script.js"></script>
</body>
</html>