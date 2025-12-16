<?php
declare(strict_types=1);

namespace App\Http;

interface ApiClientInterface
{
    public function postMultipart(string $url, array $headers, array $fields): ApiResponse;
}
