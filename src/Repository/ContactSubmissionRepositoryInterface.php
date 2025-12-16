<?php
declare(strict_types=1);

namespace App\Repository;

use App\Domain\Contact;

interface ContactSubmissionRepositoryInterface
{
    public function save(Contact $contact, int $userId): void;
}
