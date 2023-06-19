<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\DataObjects\WpTerm;
use Spatie\LaravelData\Attributes\GetsCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use WP_Term;

use function is_array;
use function is_numeric;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpTermCast implements GetsCast
{
    public function __construct(
        private readonly string $taxonomy
    ) {
    }

    public function get(): Cast
    {
        return new class ($this->taxonomy) implements Cast {
            public function __construct(
                private readonly string $taxonomy
            ) {
            }

            /**
             * @param DataProperty $property
             * @param mixed        $value
             * @param array<mixed> $context
             *
             * @return WpTerm|null
             */
            public function cast(DataProperty $property, mixed $value, array $context): ?WpTerm
            {
                $termId = WpTermCast::getTermId($value);
                return $termId !== null ? new WpTerm($termId, $this->taxonomy) : null;
            }
        };
    }

    public static function getTermId(mixed $value): ?int
    {
        if (is_numeric($value)) {
            return (int)$value;
        }
        if (is_array($value) && isset($value['ID'])) {
            return (int)$value['ID'];
        }
        // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
        return $value instanceof WP_Term ? $value->term_id : null;
    }
}
