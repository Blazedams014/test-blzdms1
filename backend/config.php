<?php
// backend/config.php
// Connexion PDO avec les infos fictives Railway fournies + helpers

function load_env($path) {
    if (!file_exists($path)) return [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $data = [];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (!strpos($line, '=')) continue;
        list($k, $v) = explode('=', $line, 2);
        $data[trim($k)] = trim($v);
    }
    return $data;
}

$env = load_env(__DIR__ . '/../.env');

// Valeurs Railway fictives par défaut
$dbHost = $env['DB_HOST'] ?? 'yamanote.proxy.rlwy.net';
$dbPort = $env['DB_PORT'] ?? '49607';
$dbName = $env['DB_DATABASE'] ?? 'railway';
$dbUser = $env['DB_USERNAME'] ?? 'root';
$dbPass = $env['DB_PASSWORD'] ?? 'fIoKgqNShMPFxyqLjcZdcDdjwGZshmQM';
$appKey = $env['APP_KEY'] ?? 's3cr3t_k3y_l0ngue_et_al3atoir3';

try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (Exception $e) {
    if (($env['APP_DEBUG'] ?? 'false') === 'true') {
        die('DB Connection error: ' . $e->getMessage());
    }
    die('Erreur de connexion à la base de données.');
}

// Helpers (CSRF, POST, etc.)
session_start();
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_check($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token ?? '');
}
function post($key, $default = null) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
function generate_unique_token() {
    return bin2hex(random_bytes(32));
}
function log_error($msg) {
    error_log(date('[Y-m-d H:i:s] ') . $msg . "\n", 3, __DIR__ . '/../logs/error.log');
}
function get_user_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}
