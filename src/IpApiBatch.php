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
    }

    public function entities(array $entities): self
    {
        throw_if(count($entities) > config('ip-api.batch.max_entities'), new \InvalidArgumentException('Maximum number of entities per request exceeded.'));
        collect($entities)->each(fn (object|string $entity) => $this->validateEntity($entity));

        $this->entities = $entities;

        return $this;
    }

    private function validateEntity(object|string $entity): void
    {

        if (is_string($entity)) {
            throw_unless(
                Validators::isValidDomain($entity) || Validators::isValidIpAddress($entity),
                new \InvalidArgumentException('Query must be a valid domain or IP address.')
            );
        } else {
            throw_unless(
                isset($entity->query),
                new \InvalidArgumentException('Query must be provided.')
            );

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
    }

    public function get(bool $asObject = false): array|object|string
    {
        $this->validateParams();

        $this->blockOverusage();

        $endpoint = 'batch/'.'?'.http_build_query(array_merge(
            [
                'key' => $this->apiKey,
                'json' => $this->entities,
            ],
            isset($this->fields) ? ['fields' => $this->fields] : [],
            isset($this->language) ? ['lang' => $this->language] : []
        ));

        $response = HttpHelper::makeRequest(
            method: HttpMethod::POST(),
            baseUrl: $this->baseUrl,
            endpoint: $endpoint,
            payload: $this->entities
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
