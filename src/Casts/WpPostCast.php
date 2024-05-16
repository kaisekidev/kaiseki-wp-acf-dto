<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Castables\WpPost;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use WP_Post;

use function is_array;
use function is_numeric;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpPostCast implements Cast
{
    /**
     * @param list<string>|string $postType
     */
    public function __construct(
        private readonly string|array $postType = '',
    ) {
    }

    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return WpPost|null
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?WpPost
    {
        return self::castValue($value, $this->postType);
    }

    /**
     * @param mixed               $value
     * @param list<string>|string $postType
     */
    public static function castValue(mixed $value, string|array $postType = ''): ?WpPost
    {
        $postId = self::getPostId($value);

        return $postId !== null ? new WpPost(
            $postId,
            $postType,
            $value instanceof WP_Post ? $value : null,
        ) : null;
    }

    public static function getPostId(mixed $value): ?int
    {
        if (is_numeric($value)) {
            return (int)$value;
        }
        if (is_array($value) && isset($value['ID'])) {
            return (int)$value['ID'];
        }

        return $value instanceof WP_Post ? $value->ID : null;
    }
}
