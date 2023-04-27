<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Cast;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\DataProperty;

use function array_map;
use function is_array;
use function is_string;

class WpPosts implements Castable
{
    public function __construct(
        /** @var list<int> */
        private readonly array $ids,
        /** @var list<WpPost> */
        private readonly array $postDataObjects,
        /** @var string|list<string> */
        private readonly string|array $postTypes = '',
    ) {
    }

    /**
     * @return list<int>
     */
    public function getIDs(): array
    {
        return $this->ids;
    }

    /**
     * @return list<\WP_Post>
     */
    public function getPosts(): array
    {
        return acf_get_posts([
            'post__in'  => $this->ids,
            'post_type' => $this->postTypes,
        ]);
    }

    /**
     * @return list<WpPost>
     */
    public function getPostDataObjects(): array
    {
        return $this->postDataObjects;
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        $postTypes = [];
        if (is_string($arguments[0])) {
            $postTypes = [$arguments[0]];
        } elseif (is_array($arguments[0])) {
            $postTypes = $arguments[0];
        }
        if ($postTypes === []) {
            $postTypes = acf_get_post_types();
        }
        return new class ($postTypes) implements Cast {
            /**
             * @param list<string> $postTypes
             */
            public function __construct(
                private readonly array $postTypes
            ) {
            }

            /**
             * @param DataProperty $property
             * @param mixed        $value
             * @param array<mixed> $context
             *
             * @return WpPosts
             */
            public function cast(DataProperty $property, mixed $value, array $context): WpPosts
            {
                $ids = IDs::castValue($value);
                $posts = array_map(fn($id) => new WpPost($id), $ids);
                return new WpPosts($ids, $posts, $this->postTypes);
            }
        };
    }
}
