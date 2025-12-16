<?php
declare(strict_types=1);

namespace App\Http;

final class ApiResponse
{
    public function __construct(
        public bool $success,
        public int $statusCode,
        public ?string $body = null,
        public ?string $error = null
    ) {}
}
