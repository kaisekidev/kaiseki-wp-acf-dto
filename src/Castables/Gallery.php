<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\GalleryCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use WP_Post;

use function acf_get_attachment;
use function acf_get_posts;
use function array_reduce;
use function is_array;
use function is_string;
use function wp_get_attachment_url;

class Gallery implements Castable
{
    public function __construct(
        /** @var array<int> */
        private readonly array $ids = [],
        /** @var list<array<string, mixed>> */
        private ?array $attachments = null,
        /** @var list<WP_Post> */
        private ?array $posts = null,
        /** @var list<string> */
        private ?array $urls = null,
    ) {
    }

    /**
     * @return list<WP_Post>
     */
    public function getPosts(): array
    {
        return $this->posts ??= acf_get_posts([
            'post_type' => 'attachment',
            'post__in' => $this->ids,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => false,
        ]);
    }

    /**
     * @return list<int>
     */
    public function getIDs(): array
    {
        return $this->ids;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAttachmentArrays(): array
    {
        return $this->attachments ??= array_reduce($this->ids, function (array $carry, int $postId) {
            $attachment = acf_get_attachment($postId);
            if (is_array($attachment)) {
                $carry[] = $attachment;
            }

            return $carry;
        }, []);
    }

    /**
     * @return list<string>
     */
    public function getUrls(): array
    {
        return $this->urls ??= array_reduce($this->ids, function (array $carry, int $postId) {
            $url = wp_get_attachment_url($postId);
            if (is_string($url) && $url !== '') {
                $carry[] = $url;
            }

            return $carry;
        }, []);
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        return new GalleryCast();
    }
}
