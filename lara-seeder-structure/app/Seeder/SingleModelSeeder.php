<?php
declare(strict_types=1);

namespace App\Seeder;

use App\Seeder\Sink\SeedSinkInterface;
use App\Seeder\Source\SeedSourceFactoryInterface;
use Illuminate\Database\Eloquent\Model;

class SingleModelSeeder
{
    /**
     * @var SeedSourceFactoryInterface
     */
    protected $sourceFactory;

    /**
     * @var SeedSinkInterface
     */
    protected $sink;

    /**
     * @param SeedSourceFactoryInterface $sourceFactory
     * @param SeedSinkInterface $sink
     */
    public function __construct(SeedSourceFactoryInterface $sourceFactory, SeedSinkInterface $sink)
    {
        $this->sourceFactory = $sourceFactory;
        $this->sink = $sink;
    }

    public function run(Model $model): void
    {
        $source = $this->sourceFactory->make($model);
        $this->sink->write($model, $source);
    }
}