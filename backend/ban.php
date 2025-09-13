<?php
require_once __DIR__ . '/config.php';


// Ce script bloque une participation (admin seulement)
// Il attend POST { csrf_token, entry_id, reason }


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
http_response_code(405);
die('Méthode non autorisée.');
}


// Vérifier session admin
session_start();
if (empty($_SESSION['is_admin'])) {
http_response_code(403);
die('Accès admin requis.');
}


if (!csrf_check(post('csrf_token'))) {
die('CSRF token invalide.');
}


$entryId = (int) post('entry_id');
$reason = trim(post('reason')) ?: 'Manuel';


// Récupérer l'entry
$sql = "SELECT id, email, ip FROM entries WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $entryId]);
$entry = $stmt->fetch();


if (!$entry) {
die('Participation introuvable.');
}


// Insérer dans bans
$sql = "INSERT INTO bans (entry_id, email, ip, reason) VALUES (:entry_id, :email, :ip, :reason)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
'entry_id' => $entryId,
'email' => $entry['email'],
'ip' => $entry['ip'],
'reason' => $reason
]);


// Mettre à jour le statut de l'entry
$sql = "UPDATE entries SET status = 'banned' WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $entryId]);


// Retour vers la page admin entries
header('Location: ../admin/entries.php?msg=ban_ok');
exit;