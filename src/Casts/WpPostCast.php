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
     * @param bool                $updatePostMetaCache
     * @param bool                $updateTermMetaCache
     */
    public function __construct(
        private readonly string|array $postType = 'any',
        private readonly bool $updatePostMetaCache = false,
        private readonly bool $updateTermMetaCache = false,
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
        return self::castValue($value, $this->postType, $this->updatePostMetaCache, $this->updateTermMetaCache);
    }

    /**
     * @param mixed               $value
     * @param list<string>|string $postType
     * @param bool                $updatePostMetaCache
     * @param bool                $updateTermMetaCache
     */
    public static function castValue(
        mixed $value,
        string|array $postType = 'any',
        bool $updatePostMetaCache = false,
        bool $updateTermMetaCache = false,
    ): ?WpPost {
        $postId = self::getPostId($value);

        return $postId !== null ? new WpPost(
            $postId,
            $postType,
            $updatePostMetaCache,
            $updateTermMetaCache,
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
