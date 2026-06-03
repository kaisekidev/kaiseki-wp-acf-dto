<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\GalleryCast;
use Kaiseki\WordPress\ACF\Dto\Util\GetPosts;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use WP_Post;

use function acf_get_attachment;
use function array_filter;
use function is_array;
use function is_string;
use function wp_get_attachment_url;

use const ARRAY_FILTER_USE_KEY;

class Gallery implements Castable
{
    /** @var list<array<string, mixed>> */
    private ?array $attachments = null;
    /** @var list<WP_Post> */
    private ?array $posts = null;
    /** @var list<string> */
    private ?array $urls = null;

    /**
     * @param list<int> $ids
     */
    public function __construct(
        private readonly array $ids = [],
    ) {
    }

    /**
     * @return list<WP_Post>
     */
    public function getPosts(): array
    {
        return $this->posts ??= GetPosts::getPosts([
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
        if ($this->attachments !== null) {
            return $this->attachments;
        }

        $attachments = [];
        foreach ($this->ids as $postId) {
            $attachment = acf_get_attachment($postId);
            if (is_array($attachment)) {
                $attachments[] = array_filter(
                    $attachment,
                    static fn(int|string $key): bool => is_string($key),
                    ARRAY_FILTER_USE_KEY,
                );
            }
        }

        return $this->attachments = $attachments;
    }

    /**
     * @return list<string>
     */
    public function getUrls(): array
    {
        if ($this->urls !== null) {
            return $this->urls;
        }

        $urls = [];
        foreach ($this->ids as $postId) {
            $url = wp_get_attachment_url($postId);
            if (is_string($url) && $url !== '') {
                $urls[] = $url;
            }
        }

        return $this->urls = $urls;
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
