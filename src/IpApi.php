<?php

namespace BlackSheepTech\IpApi;

use Exception;
use Illuminate\Support\Facades\Cache;

abstract class IpApi
{
    protected string $baseUrl;

    protected string $apiKey;

    public function apiKey(string $key): self
    {
        $this->apiKey = $key;

        return $this;
    }

    public function baseUrl(string $url): self
    {
        $this->baseUrl = $url;

        return $this;
    }

    public static function geolocation(): IpApiGeolocation
    {
        return new IpApiGeolocation;
    }

    public static function batch(): IpApiBatch
    {
        return new IpApiBatch;
    }

    public function trackUsage(int $rl, int $ttl): void
    {
        if (config('ip-api.overusage_protection.enabled')) {
            Cache::put('ip-api_usage_rl', $rl, $reset = now()->addSeconds($ttl));
            Cache::put('ip-api_usage_reset', $reset->toDateTimeString(), $reset);
        }
    }

    public function resetUsageTracking(): self
    {
        Cache::forget('ip-api_usage_rl');

        return $this;
    }

    protected function blockOverusage(): void
    {
        if (config('ip-api.overusage_protection.enabled')) {
            $rl = Cache::get('ip-api_usage_rl', null);
            if ($rl === 0) {
                throw new Exception('Rate limit exceeded. Resets at '.Cache::get('ip-api_usage_reset'));
            }

        }
    }
}
