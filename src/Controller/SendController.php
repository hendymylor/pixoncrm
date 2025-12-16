<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\ContactSubmissionRepositoryInterface;
use App\Repository\UserSettingsRepositoryInterface;
use App\Service\MessageServiceInterface;
use App\Validation\SendRequestValidator;
use RuntimeException;
use Throwable;

final class SendController
{
    public function __construct(
        private UserSettingsRepositoryInterface $settingsRepo,
        private MessageServiceInterface $messageService,
        private SendRequestValidator $validator,
        private ContactSubmissionRepositoryInterface $submissionRepo,
    ) {}

    public function handle(array $request, ?int $userId): void
    {
        header('Content-Type: application/json');

        try {
            if ($userId === null) {
                throw new RuntimeException('User belum login.');
            }

            $settings = $this->settingsRepo->findByUserId($userId);
            if ($settings === null) {
                throw new RuntimeException('Setting API belum diisi untuk user ini.');
            }

            $contact = $this->validator->validate($request);
            
            // simpan ke database
            $this->submissionRepo->save($contact, $userId);
            
            // lalu kirim ke API eksternal
            $response = $this->messageService->sendWelcome($contact, $settings);

            echo json_encode([
                'success' => $response->success,
                'status'  => $response->statusCode,
                'body'    => $response->body,
                'error'   => $response->error,
            ]);
        } catch (\InvalidArgumentException $e) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal error: ' . $e->getMessage(),
            ]);
        }
    }
}
