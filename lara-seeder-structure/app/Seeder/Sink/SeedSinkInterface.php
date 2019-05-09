<?php
declare(strict_types=1);

namespace App\Seeder\Sink;

use App\Seeder\Source\SeedSourceInterface;
use Illuminate\Database\Eloquent\Model;

interface SeedSinkInterface
{
    /**
     * @param Model $model
     * @param SeedSourceInterface $source
     */
    public function write(Model $model, SeedSourceInterface $source): void;
}