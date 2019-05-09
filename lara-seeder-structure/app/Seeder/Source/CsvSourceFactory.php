<?php


namespace App\Seeder\Source;


use Illuminate\Database\Eloquent\Model;

class CsvSourceFactory implements SeedSourceFactoryInterface
{
    /**
     * @var string
     */
    private $rootPath;

    /**
     * @param string $rootPath
     */
    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /**
     * @inheritDoc
     */
    public function make(Model $model): SeedSourceInterface
    {
        $filePath = $this->rootPath . '/' . $model->getTable() . '.csv';
        return new CsvSource($filePath);
    }
}