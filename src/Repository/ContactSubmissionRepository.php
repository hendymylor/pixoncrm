<?php
declare(strict_types=1);

namespace App\Repository;

use App\Domain\Contact;
use PDO;

final class ContactSubmissionRepository implements ContactSubmissionRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function save(Contact $contact, int $userId): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO contact_submissions (user_id, firstname, lastname, email, whatsapp)
             VALUES (:user_id, :firstname, :lastname, :email, :whatsapp)'
        );

        $stmt->execute([
            ':user_id'   => $userId,
            ':firstname' => $contact->firstname,
            ':lastname'  => $contact->lastname,
            ':email'     => $contact->email,
            ':whatsapp'  => $contact->whatsapp,
        ]);
    }
}
