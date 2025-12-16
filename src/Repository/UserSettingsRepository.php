<?php
declare(strict_types=1);

namespace App\Repository;

use App\Domain\UserSettings;
use PDO;

final class UserSettingsRepository implements UserSettingsRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function findByUserId(int $userId): ?UserSettings
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user_settings WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new UserSettings(
            (int) $row['user_id'],
            $row['api_url'],
            $row['api_token'],
            $row['template_name'],
            $row['language'],
            $row['from_phone_number_id']
        );
    }
}
