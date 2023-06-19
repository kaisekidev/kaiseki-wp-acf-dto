<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\DataObjects\WpPost;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;
use function is_numeric;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpPostCast implements Cast
{
    public function __construct(
        /** @var string|list<string> */
        private readonly string|array $postType = '',
    ) {
    }

    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return WpPost|null
     */
    public function cast(DataProperty $property, mixed $value, array $context): ?WpPost
    {
        $postId = self::getPostId($value);
        return $postId !== null ? new WpPost($postId, $this->postType) : null;
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
