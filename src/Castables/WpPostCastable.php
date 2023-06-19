<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\WpPostCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use WP_Post;

use function count;
use function current;
use function function_exists;

class WpPostCastable implements Castable
{
    public function __construct(
        private readonly ?int $id = null,
        /** @var string|list<string> */
        private readonly string|array $postType = '',
    ) {
    }

    public function getPostId(): ?int
    {
        return $this->id;
    }

    /**
     * @return WP_Post|null
     */
    public function getPost(): ?WP_Post
    {
        if (!function_exists('acf_get_posts')) {
            return null;
        }

        $posts = acf_get_posts([
            'post__in'      => $this->id,
            'post_type'     => $this->postType,
            'no_found_rows' => true,
        ]);

        if (count($posts) > 0) {
            return current($posts);
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
        return new WpPostCast();
    }
}
