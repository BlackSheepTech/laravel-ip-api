<?php

namespace BlackSheepTech\IpApi;

use BlackSheepTech\IpApi\Enums\ReturnField as Fields;
use BlackSheepTech\IpApi\Enums\ReturnLanguage as Languages;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

abstract class IpApi
{
    protected string $baseUrl;

    protected string $apiKey;

    protected string $fields;

    protected string $language;

    protected bool $disableOverusageProtection = false;

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

    public function fields(string|array $fields): self
    {
        throw_unless(
            array_diff(is_array($fields) ? $fields : explode(',', Str::remove(' ', $fields)), Fields::values()) === [],
            new \InvalidArgumentException('Invalid fields provided.')
        );

        $this->fields = is_array($fields) ? implode(',', $fields) : $fields;

        return $this;
    }

    public function language(string $language): self
    {
        throw_unless(
            in_array($language, Languages::values()),
            new \InvalidArgumentException('Invalid language provided.')
        );

        $this->language = $language;

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

    public function disableOverusageProtection(): self
    {
        $this->disableOverusageProtection = true;

        return $this;
    }

    protected function blockOverusage(): void
    {
        if (config('ip-api.overusage_protection.enabled') && ! $this->disableOverusageProtection) {
            $rl = Cache::get('ip-api_usage_rl', null);
            if ($rl === 0) {
                throw new Exception('Rate limit exceeded. Resets at '.Cache::get('ip-api_usage_reset'));
            }
        }
    }
}
