<?php
declare(strict_types=1);

namespace App\Domain;

final class Contact
{
    public function __construct(
        public string $firstname,
        public string $lastname,
        public string $email,
        public string $whatsapp,
        public string $country = 'Indonesia',
        public string $groups = 'hfm'
    ) {}
}
