<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto;

use function function_exists;
use function in_array;

class AcfGetFields
{
    /**
     * @param array<string> $excludeFromFormatting
     *
     * @return array<string, mixed>|null
     */
    public function getFields(
        mixed $postId = false,
        array $excludeFromFormatting = [
            'link',
            'post_object',
            'page_link',
            'relationship',
            'taxonomy',
            'user',
            'time_picker',
            'date_picker',
            'date_time_picker',
        ],
    ): ?array {
        if (!function_exists('get_fields')) {
            return null;
        }
        $filter = function (
            mixed $check,
            mixed $value,
            mixed $postId,
            array $field,
        ) use ($excludeFromFormatting): mixed {
            $type = $field['type'] ?? '';
            if (in_array($type, $excludeFromFormatting, true)) {
                return $value;
            }
            return null;
        };
        add_filter('acf/pre_format_value', $filter, 10, 4);
        $values = get_fields($postId);
        remove_filter('acf/pre_format_value', $filter);
        return $values;
    }

    /**
     * @param array<string> $format
     *
     * @return array<string, mixed>|null
     */
    public function getRawFields(
        mixed $postId = false,
        array $format = [
            'repeater',
            'group',
            'clone',
            'select',
            'checkbox',
            'radio',
            'true_false',
            'button_group',
        ],
    ): ?array {
        if (!function_exists('get_fields')) {
            return null;
        }
        $filter = function (
            mixed $check,
            mixed $value,
            mixed $postId,
            array $field,
        ) use ($format): mixed {
            $type = $field['type'] ?? '';
            if (in_array($type, $format, true)) {
                return null;
            }
            return $value;
        };
        add_filter('acf/pre_format_value', $filter, 10, 4);
        $values = get_fields($postId);
        remove_filter('acf/pre_format_value', $filter);
        return $values;
    }
}
