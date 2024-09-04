<?php

namespace BlackSheepTech\IpApi;

use BlackSheepTech\IpApi\Enums\HttpMethod;
use BlackSheepTech\IpApi\Enums\ReturnField as Fields;
use BlackSheepTech\IpApi\Enums\ReturnLanguage as Languages;
use Illuminate\Support\Str;

class IpApiBatch extends IpApi
{
    private array $entities;

    public function __construct()
    {
        $this->baseUrl(config('ip-api.base_url'));
        $this->apiKey(config('ip-api.api_key'));
        $this->fields = config('ip-api.default.fields');
        $this->language(config('ip-api.default.language'));
    }

    public function entities(array $entities): self
    {
        throw_if(count($entities) > config('ip-api.batch.max_entities'), new \InvalidArgumentException('Maximum number of entities per request exceeded.'));
        collect($entities)->each(fn ($entity) => $this->validateEntity((object) $entity));

        $this->entities = $entities;

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

    private function validateEntity(object $entity): void
    {
        throw_unless(
            Validators::isValidDomain($entity->query) || Validators::isValidIpAddress($entity->query),
            new \InvalidArgumentException('Query must be a valid domain or IP address.')
        );

        if (isset($entity->lang)) {
            throw_unless(
                in_array($entity->lang, Languages::values()),
                new \InvalidArgumentException('Invalid language provided.')
            );
        }

        if (isset($entity->fields)) {
            throw_unless(
                array_diff(is_array($entity->fields) ? $entity->fields : explode(',', Str::remove(' ', $entity->fields)), Fields::values()) === [],
                new \InvalidArgumentException('Invalid fields provided.')
            );
        }
    }

    public function get(bool $asObject = false): array|object|string
    {
        $this->validateParams();

        $this->blockOverusage();

        $payload = [
            'key' => $this->apiKey,
            'lang' => $this->language,
            'data' => $this->entities,
        ];

        $response = HttpHelper::makeRequest(
            method: HttpMethod::POST(),
            baseUrl: $this->baseUrl,
            endpoint: 'batch',
            payload: $payload,
        );

        if ($response->successful()) {
            $this->trackUsage($response->header('X-Rl'), $response->header('X-Ttl'));

            if ($asObject) {
                return (object) $response->json();
            }

            return $response->json();
        }

        $response->throw();
    }

    public function getAsObject(): object
    {
        return $this->get(true);
    }

    private function validateParams(): void
    {
        throw_unless(isset($this->entities), new \InvalidArgumentException('Entities must be provided.'));
        throw_if(empty($this->apiKey), new \InvalidArgumentException('API Key must be provided.'));
    }
}
