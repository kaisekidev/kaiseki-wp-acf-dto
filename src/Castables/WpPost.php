<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\WpPostCast;
use Kaiseki\WordPress\ACF\Dto\Exceptions\InvalidAttributeType;
use Kaiseki\WordPress\ACF\Dto\Util\GetPosts;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use WP_Post;

use function count;
use function current;
use function is_array;
use function is_bool;
use function is_string;
use function trigger_error;

use const E_USER_WARNING;

class WpPost implements Castable
{
    private ?WP_Post $post = null;

    public function __construct(
        private readonly int $id,
        /** @var list<string>|string */
        private readonly string|array $postType = 'any',
        private readonly bool $updatePostMetaCache = false,
        private readonly bool $updateTermMetaCache = false,
    ) {
    }

    public function getPostId(): int
    {
        return $this->id;
    }

    /**
     * @return WP_Post|null
     */
    public function getPost(): ?WP_Post
    {
        if ($this->post !== null) {
            return $this->post;
        }

        $posts = GetPosts::getPosts([
            'post__in' => $this->id,
            'post_type' => $this->postType,
            'no_found_rows' => true,
            'update_post_meta_cache' => $this->updatePostMetaCache,
            'update_post_term_cache' => $this->updateTermMetaCache,
        ]);

        if (count($posts) > 0) {
            return $this->post = current($posts);
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
        [$postType, $updatePostMetaCache, $updateTermMetaCache] = $arguments;

        if ($postType === null) {
            trigger_error('Missing WithCastable attribute "postType" for WpPostCastable', E_USER_WARNING);
        }

        if ($postType !== null && !is_string($postType) && !is_array($postType)) {
            throw InvalidAttributeType::create('postType', 'string|array');
        }

        if (is_array($postType)) {
            foreach ($postType as $type) {
                if (!is_string($type)) {
                    throw InvalidAttributeType::create('postType', 'string|array');
                }
            }
        }

        if ($updatePostMetaCache !== null && !is_bool($updatePostMetaCache)) {
            throw InvalidAttributeType::create('updatePostMetaCache', 'bool');
        }

        if ($updateTermMetaCache !== null && !is_bool($updateTermMetaCache)) {
            throw InvalidAttributeType::create('updatePostMetaCache', 'bool');
        }

        /** @var array{0?: list<string>|string, 1?: bool, 2?: bool} $arguments */
        return new WpPostCast(...$arguments);
    }
}
