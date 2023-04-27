<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Object;

use WP_User;

use function count;
use function current;
use function function_exists;

class WpUser
{
    public function __construct(
        private readonly int $id,
    ) {
    }

    public function getUserId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?WP_User
    {
        if (!function_exists('acf_get_users')) {
            return null;
        }

        $users = acf_get_users([
            'include' => [$this->id],
        ]);

        if (count($users) > 0) {
            return current($users);
        }

        return null;
    }

    /**
     * @return array{
     *     ID: int,
     *     user_firstname: string,
     *     user_lastname: string,
     *     nickname: string,
     *     user_nicename: string,
     *     display_name: string,
     *     user_email: string,
     *     user_url: string,
     *     user_registered: string,
     *     user_description: string,
     *     user_avatar: false|mixed|null,
     * }|null
     */
    public function getUserArray(): ?array
    {
        $user = $this->getUser();
        if ($user === null) {
            return null;
        }
        return [
            'ID'               => $user->ID,
            // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
            'user_firstname'   => $user->user_firstname,
            // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
            'user_lastname'    => $user->user_lastname,
            // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
            'nickname'         => $user->nickname,
            // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
            'user_nicename'    => $user->user_nicename,
            // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
            'display_name'     => $user->display_name,
            // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
            'user_email'       => $user->user_email,
            // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
            'user_url'         => $user->user_url,
            // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
            'user_registered'  => $user->user_registered,
            // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
            'user_description' => $user->user_description,
            'user_avatar'      => get_avatar($user->ID),
        ];
    }
}
