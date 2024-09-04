<?php

namespace BlackSheepTech\IpApi;

use BlackSheepTech\IpApi\Enums\HttpMethod;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HttpHelper
{
    public static function makeRequest(string $baseUrl, string $endpoint, array $payload, array $headers = [], string $method = 'GET', bool $sendAsForm = false, bool $allowRedirects = true): Response
    {
        $client = Http::baseUrl($baseUrl)->withHeaders($headers)->withoutVerifying()->acceptJson();

        if ($allowRedirects) {
            $client = $client->withOptions([
                'allow_redirects' => ['strict' => true],
            ]);
        }

        if ($sendAsForm) {
            $client = $client->asForm();
        }

        if (in_array($method, HttpMethod::values())) {
            return $client->$method($endpoint, $payload);
        } else {
            throw new Exception(__('Invalid Method'));
        }
    }
}
