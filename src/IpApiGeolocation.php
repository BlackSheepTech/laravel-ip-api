<?php

namespace BlackSheepTech\IpApi;

use BlackSheepTech\IpApi\Enums\GeolocationReturnField as Fields;
use BlackSheepTech\IpApi\Enums\GeolocationReturnFormat as Formats;
use BlackSheepTech\IpApi\Enums\HttpMethod;
use BlackSheepTech\IpApi\Enums\ReturnLanguage as Languages;
use Illuminate\Support\Str;

class IpApiGeolocation extends IpApi
{
    protected string $query;

    protected string $format;

    protected string $fields;

    protected string $language;

    public function __construct()
    {
        $this->baseUrl(config('ip-api.base_url'));
        $this->apiKey(config('ip-api.api_key'));
        $this->query = config('ip-api.default.query');
        $this->format(config('ip-api.default.format'));
        $this->fields = config('ip-api.default.fields');
        $this->language(config('ip-api.default.language'));
    }

    public function query(string $query): self
    {
        throw_unless(
            Validators::isValidDomain($query) || Validators::isValidIpAddress($query),
            new \InvalidArgumentException('Query must be a valid domain or IP address.')
        );

        $this->query = $query;

        return $this;
    }

    public function format(string $format): self
    {
        throw_unless(
            in_array($format, Formats::values()),
            new \InvalidArgumentException('Invalid return format provided.')
        );

        $this->format = $format;

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

    private function validateParams(): void
    {
        throw_if(empty($this->apiKey), new \InvalidArgumentException('API Key must be provided.'));
        throw_if(empty($this->query), new \InvalidArgumentException('Query must be provided.'));
    }

    public function get(bool $asObject = false): array|object|string
    {
        $this->validateParams();

        $this->blockOverusage();

        if ($asObject && $this->format != Formats::JSON()) {
            $this->format = Formats::JSON();
        }

        $payload = [
            'key' => $this->apiKey,
            'query' => $this->query,
            'fields' => $this->fields,
            'lang' => $this->language,
        ];

        $response = HttpHelper::makeRequest(
            method: HttpMethod::GET(),
            baseUrl: $this->baseUrl,
            endpoint: "{$this->format}/{$this->query}",
            payload: $payload,
        );

        $this->trackUsage($response->header('X-Rl'), $response->header('X-Ttl'));

        if ($asObject) {
            return (object) $response->json();
        }

        if ($this->format == Formats::JSON()) {
            return $response->json();
        }

        return $response->body();
    }

    public function getAsObject(): object
    {
        return $this->get(true);
    }
}
