<?php
declare(strict_types=1);

namespace App\Repository;

use App\Domain\UserSettings;

interface UserSettingsRepositoryInterface
{
    public function findByUserId(int $userId): ?UserSettings;
}
