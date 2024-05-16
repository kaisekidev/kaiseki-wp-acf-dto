<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Concerns;

use Throwable;

use function get_called_class;
use function wp_trigger_error;

use const E_USER_WARNING;

trait WithSafeFrom
{
    public static function safeFrom(mixed ...$payloads): ?static
    {
        try {
            return static::factory()->from(...$payloads);
        } catch (Throwable $error) {
            wp_trigger_error(
                get_called_class() . '::from()',
                $error->getMessage(),
                E_USER_WARNING
            );

            return null;
        }
    }
}
