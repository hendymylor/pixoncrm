<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/autoload.php';

use App\Repository\UserSettingsRepository;
use App\Repository\ContactSubmissionRepository;
use App\Http\CurlApiClient;
use App\Service\MessageService;
use App\Validation\SendRequestValidator;
use App\Controller\SendController;

$pdo = DbConnection::make();

// repositori & service
$settingsRepo   = new UserSettingsRepository($pdo);
$submissionRepo = new ContactSubmissionRepository($pdo); // <— tambahkan ini
$apiClient      = new CurlApiClient();
$messageService = new MessageService($apiClient);
$validator      = new SendRequestValidator();

// controller sekarang butuh 4 argumen (settings, message, validator, submissionRepo)
$controller = new SendController(
    $settingsRepo,
    $messageService,
    $validator,
    $submissionRepo
);

$currentUserId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;

$controller->handle($_POST, $currentUserId);
