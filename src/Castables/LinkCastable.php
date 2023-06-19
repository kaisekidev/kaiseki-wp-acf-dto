<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;

class LinkCastable implements Castable
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
        return new class implements Cast {
            /**
             * @param DataProperty $property
             * @param mixed        $value
             * @param array<mixed> $context
             *
             * @return LinkCastable|null
             */
            public function cast(DataProperty $property, mixed $value, array $context): ?LinkCastable
            {
                if (
                    !is_array($value)
                    || !isset($value['title'], $value['url'], $value['target'])
                ) {
                    return null;
                }
                return new LinkCastable(
                    $value['title'],
                    $value['url'],
                    $value['target'],
                );
            }
        };
    }
}
