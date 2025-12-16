<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Contact;
use App\Domain\UserSettings;
use App\Http\ApiResponse;

interface MessageServiceInterface
{
    public function sendWelcome(Contact $contact, UserSettings $settings): ApiResponse;
}
