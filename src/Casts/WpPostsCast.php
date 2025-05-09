<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Castables\WpPosts;
use Kaiseki\WordPress\ACF\Dto\Util\GetPosts;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use WP_Post;

use function count;
use function is_array;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpPostsCast implements Cast
{
    public function __construct(
        /** @var list<string>|string */
        private readonly string|array $postType = 'any',
        private readonly bool $updatePostMetaCache = false,
        private readonly bool $updateTermMetaCache = false,
        private readonly bool $updatePostThumbnailCache = false,
    ) {
    }

    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return WpPosts
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): WpPosts
    {
        return self::castValue(
            $value,
            $this->postType,
            $this->updatePostMetaCache,
            $this->updatePostThumbnailCache,
            $this->updateTermMetaCache,
        );
    }

    /**
     * @param mixed               $value
     * @param list<string>|string $postType
     * @param bool                $updatePostMetaCache
     * @param bool                $updateTermMetaCache
     * @param bool                $updatePostThumbnailCache
     */
    public static function castValue(
        mixed $value,
        string|array $postType = 'any',
        bool $updatePostMetaCache = false,
        bool $updateTermMetaCache = false,
        bool $updatePostThumbnailCache = false,
    ): WpPosts {
        if (!is_array($value)) {
            return new WpPosts(
                ids: [],
                postTypes: $postType,
                updatePostMetaCache: $updatePostMetaCache,
                updateTermMetaCache: $updateTermMetaCache,
                updatePostThumbnailCache: $updatePostThumbnailCache,
            );
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

        if (count($posts) > 0) {
            GetPosts::updatePostThumbnailCache($ids);
        }

        return new WpPosts(
            ids: $ids,
            postTypes: $postType,
            posts: $posts,
            updatePostMetaCache: $updatePostMetaCache,
            updateTermMetaCache: $updateTermMetaCache,
            updatePostThumbnailCache: $updatePostThumbnailCache,
        );
    }
}
