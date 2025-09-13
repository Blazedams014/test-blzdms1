<?php
require_once __DIR__ . '/config.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
die('Méthode non autorisée.');
}


if (!csrf_check(post('csrf_token'))) {
die('CSRF token invalide.');
}


$token = post('token');
if (!$token) die('Token manquant.');


// Vérifie que l'entrée existe et est en attente
$sql = "SELECT id FROM entries WHERE unique_token = :token AND status = 'pending'";
$stmt = $pdo->prepare($sql);
$stmt->execute(['token' => $token]);
$entry = $stmt->fetch();


if (!$entry) {
die('Participation invalide ou déjà validée.');
}


$entryId = $entry['id'];


// Met à jour le statut en 'validated'
$sql = "UPDATE entries SET status = 'validated', validated_at = NOW() WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $entryId]);


// Marque l'événement pub comme complété
$sql = "UPDATE ad_events SET completed_at = NOW(), rewarded = 1 WHERE entry_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $entryId]);


// Message de confirmation
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Participation validée</title>
<link rel="stylesheet" href="../public/assets/style.css">
</head>
<body>
<div class="container">
<h1>🎉 Merci pour votre participation !</h1>
<p>Votre inscription est maintenant validée. Le tirage au sort aura lieu samedi à 10h (heure française).</p>
<a href="../public/index.php"><button>Retour à l'accueil</button></a>
</div>
</body>
</html>