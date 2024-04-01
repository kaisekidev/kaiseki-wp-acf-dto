<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\DataObjects;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class),MapOutputName(CamelCaseMapper::class)]
class GoogleMap extends Data
{
    public function __construct(
        public readonly string $address,
        public readonly float $lat,
        public readonly float $lng,
        public readonly int $zoom,
        public readonly ?string $placeId = null,
        public readonly ?string $name = null,
        public readonly string|int|null $streetNumber = null,
        public readonly ?string $streetName = null,
        public readonly ?string $streetNameShort = null,
        public readonly ?string $city = null,
        public readonly ?string $state = null,
        public readonly ?string $stateShort = null,
        public readonly string|int|null $postCode = null,
        public readonly ?string $country = null,
        public readonly ?string $countryShort = null
    ) {
    }
}
