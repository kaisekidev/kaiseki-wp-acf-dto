<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class),MapOutputName(CamelCaseMapper::class)]
class LabelValue extends Data
{
    public function __construct(
        public readonly ?string $label,
        public readonly ?string $value,
    ) {
    }
}
