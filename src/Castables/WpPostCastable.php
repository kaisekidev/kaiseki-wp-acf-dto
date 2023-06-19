<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\DataProperty;
use WP_Post;

use function count;
use function current;
use function function_exists;
use function is_array;
use function is_numeric;

class WpPostCastable implements Castable
{
    public function __construct(
        private readonly ?int $id = null,
        /** @var string|list<string> */
        private readonly string|array $postType = '',
    ) {
    }

    public function getPostId(): ?int
    {
        return $this->id;
    }

    /**
     * @return WP_Post|null
     */
    public function getPost(): ?WP_Post
    {
        if (!function_exists('acf_get_posts')) {
            return null;
        }

        $posts = acf_get_posts([
            'post__in'      => $this->id,
            'post_type'     => $this->postType,
            'no_found_rows' => true,
        ]);

        if (count($posts) > 0) {
            return current($posts);
        }

        return null;
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
             * @return WpPostCastable
             */
            public function cast(DataProperty $property, mixed $value, array $context): WpPostCastable
            {
                $postId = self::resolvePostId($value);
                return new WpPostCastable($postId);
            }

            private static function resolvePostId(mixed $value): ?int
            {
                if (is_numeric($value)) {
                    return (int)$value;
                }
                if (is_array($value) && isset($value['ID'])) {
                    return (int)$value['ID'];
                }
                return $value instanceof \WP_Post ? $value->ID : null;
            }
        };
    }
}
