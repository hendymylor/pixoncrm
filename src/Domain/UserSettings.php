<?php
declare(strict_types=1);

namespace App\Domain;

final class UserSettings
{
    public function __construct(
        public int $userId,
        public string $apiUrl,
        public string $apiToken,
        public string $templateName,
        public string $language,
        public string $fromPhoneNumberId,
    ) {}
}
