<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\WpPostsCast;
use Kaiseki\WordPress\ACF\Exceptions\InvalidAttributeType;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use WP_Post;

use function acf_get_posts;
use function count;
use function function_exists;
use function is_array;
use function is_string;
use function trigger_error;

use const E_USER_WARNING;

class WpPosts implements Castable
{
    public function __construct(
        /** @var list<int> */
        private readonly array $ids,
        /** @var list<string>|string */
        private readonly string|array $postTypes = '',
        /** @var list<WP_Post> */
        private array $posts = [],
        private readonly bool $updatePostMetaCache = false,
        private readonly bool $updateTermMetaCache = false,
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
     * @return list<WP_Post>
     */
    public function getPosts(): array
    {
        if (count($this->posts) > 0) {
            return $this->posts;
        }

        if (!function_exists('acf_get_posts')) {
            return [];
        }

        return $this->posts = acf_get_posts([
            'post__in' => $this->ids,
            'post_type' => $this->postTypes,
            'no_found_rows' => true,
            'update_post_meta_cache' => $this->updatePostMetaCache,
            'update_post_term_cache' => $this->updateTermMetaCache,
        ]);
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        if (!isset($arguments[0])) {
            trigger_error('Missing WithCastable attribute "postType" for WpPostsCastable', E_USER_WARNING);
        }
        $postType = $arguments[0] ?? '';
        if (!is_string($postType) && !is_array($postType)) {
            throw InvalidAttributeType::create('postType', 'string|array');
        }

        return new WpPostsCast($postType);
    }
}
