<?php


$category = $_GET['category'] ?? '';
if (!in_array($category, ['robux', 'vbucks'])) {
die('Catégorie invalide. Utilisez ?category=robux ou ?category=vbucks');
}


// Sélectionne jusqu’à 4 gagnants
$winners = pick_random_winners($pdo, $category, 4);


if (empty($winners)) {
die('Aucun participant valide trouvé pour cette catégorie.');
}


foreach ($winners as $entryId) {
// Enregistre dans la table winners
$sql = "INSERT INTO winners (entry_id, category, prize) VALUES (:entry_id, :category, :prize)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
'entry_id' => $entryId,
'category' => $category,
'prize' => ($category === 'robux' ? 'Carte Robux' : 'Carte V-Bucks')
]);


// Log pour audit
$sql = "INSERT INTO draw_logs (category, seed, selected_entry_id) VALUES (:cat, :seed, :entry)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
'cat' => $category,
'seed' => bin2hex(random_bytes(8)), // simple seed aléatoire
'entry' => $entryId
]);
}


// Affiche les gagnants avec infos
$sql = "SELECT e.username, e.email, e.contact_method, w.prize, w.draw_date
FROM winners w
JOIN entries e ON e.id = w.entry_id
WHERE w.category = :cat
ORDER BY w.draw_date DESC
LIMIT 4";
$stmt = $pdo->prepare($sql);
$stmt->execute(['cat' => $category]);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Résultats du tirage - <?= ucfirst(e($category)) ?></title>
<link rel="stylesheet" href="../public/assets/style.css">
</head>
<body>
<div class="container">
<h1>🎉 Résultats du tirage (<?= ucfirst(e($category)) ?>)</h1>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
<tr>
<th>Pseudo</th>
<th>Email</th>
<th>Contact</th>
<th>Gain</th>
<th>Date du tirage</th>
</tr>
<?php foreach ($results as $row): ?>
<tr>
<td><?= e($row['username']) ?></td>
<td><?= e($row['email']) ?></td>
<td><?= e($row['contact_method']) ?></td>
<td><?= e($row['prize']) ?></td>
<td><?= e($row['draw_date']) ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>