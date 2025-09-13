<?php
require_once __DIR__ . '/config.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
die('Méthode non autorisée.');
}


if (!csrf_check(post('csrf_token'))) {
die('CSRF token invalide.');
}


$email = filter_var(post('email'), FILTER_VALIDATE_EMAIL);
$username = trim(post('username'));
$category = post('category');
$contact_method = post('contact_method');
$contact_value = trim(post('contact_value'));


if (!$email || !$username || !$contact_method || !$contact_value) {
die('Champs manquants.');
}


if (!in_array($category, ['robux', 'vbucks'])) {
die('Catégorie invalide.');
}


// Empêcher les doublons simples (email + catégorie)
$sql = "SELECT id FROM entries WHERE email = :email AND category = :category AND status != 'banned'";
$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $email, 'category' => $category]);
if ($stmt->fetch()) {
die('Vous avez déjà participé pour cette catégorie.');
}


$token = generate_unique_token();
$ip = get_user_ip();
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';


$sql = "INSERT INTO entries (email, username, platform, contact_method, category, ip, user_agent, status, unique_token)
VALUES (:email, :username, :platform, :contact_method, :category, :ip, :ua, 'pending', :token)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
'email' => $email,
'username' => $username,
'platform' => ($category === 'robux' ? 'roblox' : 'fortnite'),
'contact_method' => $contact_method . ':' . $contact_value,
'category' => $category,
'ip' => $ip,
'ua' => $userAgent,
'token' => $token
]);


$entryId = $pdo->lastInsertId();


// Crée un enregistrement ad_event lié
$sql = "INSERT INTO ad_events (entry_id) VALUES (:entry_id)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['entry_id' => $entryId]);


// Redirection vers la page publicité
header("Location: ad_gate.php?token=" . urlencode($token));
exit;