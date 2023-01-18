<?php

namespace App\Models\Collections;

use App\Models\Model;

interface Collection
{
    public function add(Model $model): void;

    public function all(): array;

    public static function make(array $models): Collection;
}
