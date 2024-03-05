<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Castables\WpPostsCastable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use WP_Post;

use function is_array;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpPostsCast implements Cast
{
    public function __construct(
        /** @var list<string>|string */
        private readonly string|array $postType = '',
    ) {
    }

    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return WpPostsCastable
     */
    public function cast(DataProperty $property, mixed $value, array $context): WpPostsCastable
    {
        return self::castValue($value, $this->postType);
    }

    /**
     * @param mixed               $value
     * @param list<string>|string $postType
     */
    public static function castValue(mixed $value, string|array $postType = ''): WpPostsCastable
    {
        if (!is_array($value)) {
            return new WpPostsCastable([], $postType);
        }

        $ids = [];
        $posts = [];

        foreach ($value as $item) {
            $postId = WpPostCast::getPostId($item);
            if ($postId !== null) {
                $ids[] = $postId;
            }
            if (!($item instanceof WP_Post)) {
                continue;
            }

            $posts[] = $item;
        }

        return new WpPostsCastable($ids, $postType, $posts);
    }
}
