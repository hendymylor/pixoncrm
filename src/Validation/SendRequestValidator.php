<?php
declare(strict_types=1);

namespace App\Validation;

use App\Domain\Contact;
use InvalidArgumentException;

final class SendRequestValidator
{
    public function validate(array $data): Contact
    {
        $firstname = trim($data['firstname'] ?? '');
        $lastname  = trim($data['lastname'] ?? '');
        $email     = trim($data['email'] ?? '');
        $whatsapp  = trim($data['whatsapp'] ?? '');

        if ($firstname === '' || $lastname === '' || $email === '' || $whatsapp === '') {
            throw new InvalidArgumentException('Semua field wajib diisi.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email tidak valid.');
        }

        return new Contact($firstname, $lastname, $email, $whatsapp);
    }
}
