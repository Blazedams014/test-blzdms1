<?php


$token = $_GET['token'] ?? '';
if (!$token) die('Token manquant.');


$sql = "SELECT id FROM entries WHERE unique_token = :token AND status = 'pending'";
$stmt = $pdo->prepare($sql);
$stmt->execute(['token' => $token]);
$entry = $stmt->fetch();


if (!$entry) {
die('Participation invalide ou déjà validée.');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Regardez la publicité</title>
<link rel="stylesheet" href="../public/assets/style.css">
<style>
.ad-box {
text-align: center;
padding: 20px;
}
.countdown {
font-size: 1.5rem;
margin: 20px 0;
font-weight: bold;
}
</style>
</head>
<body>
<div class="container ad-box">
<h1>Merci de regarder cette publicité</h1>
<p>Votre inscription sera validée une fois la publicité terminée.</p>


<!-- Ici tu pourras intégrer un vrai script pub (AdSense, vidéo sponsorisée, etc.) -->
<div class="ad-placeholder" style="background:#ddd; height:200px; display:flex; align-items:center; justify-content:center;">
<p>Zone Publicité (exemple)</p>
</div>


<div class="countdown" id="countdown">30</div>


<form action="ad_complete.php" method="POST" id="adForm">
<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
<input type="hidden" name="token" value="<?= e($token) ?>">
<button type="submit" id="validateBtn" disabled>Valider ma participation</button>
</form>
</div>


<script>
let seconds = 30;
const countdownEl = document.getElementById('countdown');
const validateBtn = document.getElementById('validateBtn');


const interval = setInterval(() => {
seconds--;
countdownEl.textContent = seconds;
if (seconds <= 0) {
clearInterval(interval);
countdownEl.textContent = "Terminé !";
validateBtn.disabled = false;
}
}, 1000);
</script>
</body>
</html>