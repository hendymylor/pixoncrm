<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$pdo = DbConnection::make();

$userId = (int)($_POST['user_id'] ?? 0);
$apiUrl = trim($_POST['api_url'] ?? '');
$apiToken = trim($_POST['api_token'] ?? '');
$templateName = trim($_POST['template_name'] ?? '');
$language = trim($_POST['language'] ?? '');
$fromPhoneNumberId = trim($_POST['from_phone_number_id'] ?? '');

if (!$userId || !$apiUrl || !$apiToken || !$templateName || !$language || !$fromPhoneNumberId) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Semua field wajib diisi.'
    ];
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM user_settings WHERE user_id = ?');
$stmt->execute([$userId]);
$exists = $stmt->fetchColumn();

if ($exists) {
    $stmt = $pdo->prepare('UPDATE user_settings SET api_url=?, api_token=?, template_name=?, language=?, from_phone_number_id=? WHERE user_id=?');
    $stmt->execute([$apiUrl, $apiToken, $templateName, $language, $fromPhoneNumberId, $userId]);
} else {
    $stmt = $pdo->prepare('INSERT INTO user_settings (user_id, api_url, api_token, template_name, language, from_phone_number_id) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$userId, $apiUrl, $apiToken, $templateName, $language, $fromPhoneNumberId]);
}

$_SESSION['flash'] = [
    'type' => 'success',
    'message' => 'Setting API berhasil disimpan.'
];

header('Location: index.php');
exit;
