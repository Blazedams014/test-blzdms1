<?php
require_once __DIR__ . '/../backend/config.php';


// Récupère les gagnants récents (8 derniers par catégorie)
$sql = "SELECT e.username, e.contact_method, w.category, w.prize, w.draw_date
FROM winners w
JOIN entries e ON e.id = w.entry_id
ORDER BY w.draw_date DESC
LIMIT 16";
$stmt = $pdo->query($sql);
$winners = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Derniers gagnants</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
<h1>🏆 Derniers gagnants</h1>


<?php if (empty($winners)): ?>
<p>Aucun gagnant pour l’instant.</p>
<?php else: ?>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
<tr>
<th>Pseudo</th>
<th>Catégorie</th>
<th>Gain</th>
<th>Date</th>
</tr>
<?php foreach ($winners as $w): ?>
<tr>
<td><?= e($w['username']) ?></td>
<td><?= ucfirst(e($w['category'])) ?></td>
<td><?= e($w['prize']) ?></td>
<td><?= date('d/m/Y H:i', strtotime($w['draw_date'])) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>


<p style="margin-top:20px; text-align:center;">
<a href="index.php"><button>⬅ Retour à l'accueil</button></a>
</p>
</div>
</body>
</html>