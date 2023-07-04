<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Castables\WpPostCastable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use WP_Post;

use function is_array;
use function is_numeric;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpPostCast implements Cast
{
    /**
     * @param string|list<string> $postType
     */
    public function __construct(
        private readonly string|array $postType = '',
    ) {
    }

    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return WpPostCastable|null
     */
    public function cast(DataProperty $property, mixed $value, array $context): ?WpPostCastable
    {
        return self::castValue($value, $this->postType);
    }

    /**
     * @param string|list<string> $postType
     */
    public static function castValue(mixed $value, string|array $postType = ''): ?WpPostCastable
    {
        $postId = self::getPostId($value);
        return $postId !== null ? new WpPostCastable(
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
        return $value instanceof \WP_Post ? $value->ID : null;
    }
}
