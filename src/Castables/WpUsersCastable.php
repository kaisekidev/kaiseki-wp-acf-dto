<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Castables;

use Kaiseki\WordPress\ACF\Dto\Casts\WpUsersCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use WP_User;

use function array_map;
use function count;
use function function_exists;

class WpUsersCastable implements Castable
{
    public function __construct(
        /** @var array<int> */
        private readonly array $ids,
        /** @var list<WP_User> */
        private array $users = [],
    ) {
    }

    /**
     * @return list<int>
     */
    public function getIDs(): array
    {
        return $this->ids;
    }

    /**
     * @return list<WP_User>
     */
    public function getUsers(): array
    {
        if (count($this->users) > 0) {
            return $this->users;
        }

        if (count($this->ids) < 1 || !function_exists('acf_get_users')) {
            return [];
        }

        return $this->users = acf_get_users([
            'include' => $this->ids,
        ]);
    }

    /**
     * @return list<array{
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
     * }>
     */
    public function getUserArrays(): array
    {
        return array_map(fn(WP_User $user) => [
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
        ], $this->getUsers());
    }

    /**
     * @param mixed ...$arguments
     *
     * @return Cast
     */
    public static function dataCastUsing(...$arguments): Cast
    {
        return new WpUsersCast();
    }
}
