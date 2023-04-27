<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto;

use Kaiseki\Config\Config;
use Psr\Container\ContainerInterface;
use Spatie\LaravelData\Support\DataConfig;

final class LaravelDataConfigFactory
{
    public function __invoke(ContainerInterface $container): DataConfig
    {
        $config = Config::get($container);
        return new DataConfig($config->array('laravel/data'));
    }
}
