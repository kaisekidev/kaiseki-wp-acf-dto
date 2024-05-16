<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\LinkCast;
use Kaiseki\WordPress\ACF\Dto\Data;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;

class Link extends Data implements Castable
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $url = null,
        public readonly ?string $target = null,
    ) {
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        return new LinkCast();
    }
}
