<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;
use function is_numeric;

class WpPost implements Castable
{
    public function __construct(
        #[WithCast(ID::class)]
        private readonly ?int $id = null,
    ) {
    }

    public function getPostId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \WP_Post|null
     */
    public function getPost(): ?\WP_Post
    {
        return get_post($this->id);
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
             * @return WpPost|null
             */
            public function cast(DataProperty $property, mixed $value, array $context): ?WpPost
            {
                if ($value instanceof \WP_Post) {
                    return new WpPost($value->ID);
                }
                if (is_array($value) && isset($value['ID'])) {
                    return new WpPost((int)$value['ID']);
                }
                return is_numeric($value) ? new WpPost((int)$value) : null;
            }
        };
    }
}
