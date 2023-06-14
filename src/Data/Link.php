<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Data;

use Kaiseki\WordPress\ACF\Dto\Cast\Url;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class),MapOutputName(CamelCaseMapper::class)]
class Link extends Data
{
    public function __construct(
        public readonly ?string $title,
        #[WithCast(Url::class)]
        public readonly ?string $url,
        public readonly ?string $target,
    ) {
    }
}
