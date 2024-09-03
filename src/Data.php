<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto;

use Kaiseki\WordPress\ACF\Dto\Concerns\WithSafeFrom;
use Spatie\LaravelData\Concerns\BaseData;
use Spatie\LaravelData\Concerns\ContextableData;
use Spatie\LaravelData\Concerns\TransformableData;
use Spatie\LaravelData\Contracts\BaseData as BaseDataContract;
use Spatie\LaravelData\Contracts\TransformableData as TransformableDataContract;

class Data implements BaseDataContract, TransformableDataContract
{
    use TransformableData;
    use BaseData;
    use WithSafeFrom;
    use ContextableData;
}
