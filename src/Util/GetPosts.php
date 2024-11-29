<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Util;

use WP_Post;
use WP_Query;

use function acf_get_posts;
use function function_exists;
use function update_post_thumbnail_cache;

class GetPosts
{
    /**
     * @param array<mixed> $args
     *
     * @return list<WP_Post>
     */
    public static function getPosts(array $args): array
    {
        if (!function_exists('acf_get_posts')) {
            return [];
        }

        return acf_get_posts($args);
    }

    /**
     * @param list<int> $postIds
     */
    public static function updatePostThumbnailCache(array $postIds): void
    {
        $query = new WP_Query([
            'post__in' => $postIds,
            'fields' => 'ids',
        ]);

        update_post_thumbnail_cache($query);
    }
}
