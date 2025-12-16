<?php
declare(strict_types=1);

namespace App\Http;

final class CurlApiClient implements ApiClientInterface
{
    public function postMultipart(string $url, array $headers, array $fields): ApiResponse
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => $fields,
        ]);

        $body = curl_exec($ch);
        $error = curl_error($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            return new ApiResponse(false, $status ?: 0, null, $error);
        }

        return new ApiResponse($status >= 200 && $status < 300, $status, $body, null);
    }
}
