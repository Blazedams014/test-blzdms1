<?php
require_once __DIR__ . '/../backend/config.php';
session_start();
if (empty($_SESSION['is_admin'])) { header('Location: index.php'); exit; }


// Marquer comme livré
if (isset($_GET['deliver']) && is_numeric($_GET['deliver'])) {
$id = (int) $_GET['deliver'];
$sql = "UPDATE winners SET delivered = 1, delivered_at = NOW() WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
header('Location: winners.php'); exit;
}


$sql = "SELECT w.id, e.username, e.email, w.category, w.prize, w.draw_date, w.delivered FROM winners w JOIN entries e ON e.id = w.entry_id ORDER BY w.draw_date DESC";
$stmt = $pdo->query($sql);
$wins = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Gagnants</title>
<link rel="stylesheet" href="../public/assets/style.css">
</head>
<body>
<div class="container">
<h1>Liste des gagnants</h1>
<?php if (empty($wins)): ?>
<p>Aucun gagnant.</p>
<?php else: ?>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
<tr><th>ID</th><th>Pseudo</th><th>Email</th><th>Catégorie</th><th>Gain</th><th>Date</th><th>Livré</th><th>Actions</th></tr>
<?php foreach ($wins as $w): ?>
<tr>
<td><?= e($w['id']) ?></td>
<td><?= e($w['username']) ?></td>
<td><?= e($w['email']) ?></td>
<td><?= e($w['category']) ?></td>
<td><?= e($w['prize']) ?></td>
<td><?= e($w['draw_date']) ?></td>
<td><?= $w['delivered'] ? 'Oui' : 'Non' ?></td>
<td>
<?php if (!$w['delivered']): ?>
<a href="winners.php?deliver=<?= e($w['id']) ?>">Marquer livré</a>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
<p><a href="index.php">⬅ Retour</a></p>
</div>
</body>
</html>