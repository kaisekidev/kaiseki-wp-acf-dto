<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\DataObjects\WpPosts;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function array_reduce;
use function is_array;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpPostsCast implements Cast
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
     * @return WpPosts
     */
    public function cast(DataProperty $property, mixed $value, array $context): WpPosts
    {
        if (!is_array($value)) {
            return new WpPosts([], $this->postType);
        }
        $ids = array_reduce($value, function ($carry, $item) {
            $postId = WpPostCast::getPostId($item);
            if ($postId !== null) {
                $carry[] = $postId;
            }
            return $carry;
        }, []);
        return new WpPosts($ids, $this->postType);
    }
}
