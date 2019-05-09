<?php
declare(strict_types=1);

namespace App\Seeder\Source;

use Illuminate\Database\Eloquent\Model;

interface SeedSourceFactoryInterface
{
    /**
     * @param Model $model
     * @return SeedSourceInterface
     */
    public function make(Model $model): SeedSourceInterface;
}
