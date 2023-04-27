<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Cast;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;
use function is_numeric;

class WpTerm implements Castable
{
    public function __construct(
        #[WithCast(ID::class)]
        private readonly ?int $id = null,
    ) {
    }

    public function getTermId(): ?int
    {
        return $this->id;
    }

    public function getTerm(): ?\WP_Term
    {
        if ($this->id === null) {
            return null;
        }
        $term = get_term($this->id);
        return $term instanceof \WP_Term ? $term : null;
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        return new class implements Cast {
            /**
             * @param DataProperty $property
             * @param mixed        $value
             * @param array<mixed> $context
             *
             * @return WpTerm|null
             */
            public function cast(DataProperty $property, mixed $value, array $context): ?WpTerm
            {
                if ($value instanceof \WP_Term) {
                    // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
                    return new WpTerm($value->term_id);
                }
                if (is_array($value) && isset($value['ID'])) {
                    return new WpTerm((int)$value['ID']);
                }
                return is_numeric($value) ? new WpTerm((int)$value) : null;
            }
        };
    }
}
