<?php
require_once __DIR__ . '/../backend/config.php';
session_start();

// Vérifier si l'utilisateur est bien admin
if (empty($_SESSION['is_admin'])) {
    header('Location: index.php');
    exit;
}

// --- Gestion du filtre ---
$filter = $_GET['filter'] ?? '';
$sql = "SELECT * FROM entries";
if ($filter === 'pending') {
    $sql .= " WHERE status = 'pending'";
} elseif ($filter === 'validated') {
    $sql .= " WHERE status = 'validated'";
} elseif ($filter === 'banned') {
    $sql .= " WHERE status = 'banned'";
}
$sql .= " ORDER BY created_at DESC LIMIT 200";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$entries = $stmt->fetchAll();

// Token CSRF pour la sécurité
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Participations</title>
  <link rel="stylesheet" href="../public/assets/style.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 8px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background: #f2f2f2;
    }
    form {
      margin: 0;
    }
    .ban-btn {
      background: #dc3545;
      color: #fff;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      cursor: pointer;
    }
    .ban-btn:hover {
      background: #a71d2a;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Gestion des participations</h1>

    <p>Filtrer :
      <a href="entries.php">Tous</a> |
      <a href="entries.php?filter=pending">En attente</a> |
      <a href="entries.php?filter=validated">Validés</a> |
      <a href="entries.php?filter=banned">Bannis</a>
    </p>

    <?php if (empty($entries)): ?>
      <p>Aucune participation trouvée.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>ID</th>
          <th>Email</th>
          <th>Pseudo</th>
          <th>Catégorie</th>
          <th>Status</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
        <?php foreach ($entries as $e): ?>
          <tr>
            <td><?= e($e['id']) ?></td>
            <td><?= e($e['email']) ?></td>
            <td><?= e($e['username']) ?></td>
            <td><?= e($e['category']) ?></td>
            <td><?= e($e['status']) ?></td>
            <td><?= e($e['created_at']) ?></td>
            <td>
              <?php if ($e['status'] !== 'banned'): ?>
                <form action="../backend/ban.php" method="POST" onsubmit="return confirm('Confirmer le bannissement ?');">
                  <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                  <input type="hidden" name="entry_id" value="<?= e($e['id']) ?>">
                  <input type="text" name="reason" placeholder="Raison (optionnel)">
                  <button type="submit" class="ban-btn">Bannir</button>
                </form>
              <?php else: ?>
                <em>Déjà banni</em>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>

    <p><a href="index.php">⬅ Retour au tableau de bord</a></p>
  </div>
</body>
</html>
