<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class),MapOutputName(CamelCaseMapper::class)]
class GoogleMap extends Data
{
    public function __construct(
        public readonly ?string $address,
        public readonly ?float $lat,
        public readonly ?float $lng,
        public readonly ?int $zoom,
        public readonly ?string $placeId,
        public readonly ?string $name,
        public readonly ?string $streetNumber,
        public readonly ?string $streetName,
        public readonly ?string $city,
        public readonly ?string $state,
        public readonly ?string $postCode,
        public readonly ?string $country,
        public readonly ?string $countryShort,
    ) {
    }
}
