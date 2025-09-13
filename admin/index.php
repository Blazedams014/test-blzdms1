<?php
require_once __DIR__ . '/../backend/config.php';
session_start();


$env = load_env(__DIR__ . '/../.env');
$adminUser = $env['ADMIN_USER'] ?? 'admin';
$adminPass = $env['ADMIN_PASS'] ?? 'password';


// Logout
if (isset($_GET['logout'])) {
session_destroy();
header('Location: index.php');
exit;
}


// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$user = post('username');
$pass = post('password');


if ($user === $adminUser && $pass === $adminPass) {
$_SESSION['is_admin'] = true;
header('Location: index.php');
exit;
} else {
$error = 'Identifiants incorrects.';
}
}


if (empty($_SESSION['is_admin'])) {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Connexion</title>
<link rel="stylesheet" href="../public/assets/style.css">
</head>
<body>
<div class="container">
<h1>Connexion admin</h1>
<?php if (!empty($error)): ?>
<p style="color:red;"><?= e($error) ?></p>
<?php endif; ?>
<form method="POST">
<label>Utilisateur</label>
<input name="username" required>
<label>Mot de passe</label>
<input name="password" type="password" required>
<button type="submit">Se connecter</button>
</form>
</div>
</body>
</html>
<?php
exit;
}
// Dashboard
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Tableau de bord</title>
<link rel="stylesheet" href="../public/assets/style.css">
</head>
<body>
<div class="container">
<h1>Tableau de bord admin</h1>
<ul>
<li><a href="winners.php">Voir les gagnants</a></li>
<li><a href="entries.php">Gérer les participations</a></li>
<li><a href="?logout=1">Se déconnecter</a></li>
</ul>
</div>
</body>
</html>