<?php
declare(strict_types=1);

namespace App\Seeder;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use LogicException;

abstract class MultipleModelsSeeder extends Seeder
{
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var SingleModelSeeder
     */
    protected $singleModelSeeder;

    /**
     * @inheritDoc
     */
    public function __construct(
        DatabaseManager $databaseManager,
        SingleModelSeeder $singleModelSeeder
    ) {
        $this->databaseManager = $databaseManager;
        $this->singleModelSeeder = $singleModelSeeder;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $modelClasses = collect($this->getModelClasses());
        if ($modelClasses->isEmpty()) {
            throw new LogicException('一つ以上のEloquent Modelクラスを指定してください。');
        }

        $this->truncateBeforeSeeding($modelClasses);

        $this->seedModels($modelClasses);
    }

    /**
     * @param Collection<string> $modelClasses
     */
    protected function truncateBeforeSeeding(Collection $modelClasses): void
    {
        $modelClass = $modelClasses->first();
        $conn = $this->databaseManager->connection((new $modelClass)->getConnection());

        try {
            $conn->statement('SET foreign_key_checks=0');
            $modelClasses->reverse()->each(function (string $modelClass) {
                $this->command->getOutput()->write("<info>Truncating:</info>{$modelClass} ...");
                $modelClass::truncate();
                $this->command->getOutput()->writeln(' <info>done</info>');
            });
        } finally {
            $conn->statement('SET foreign_key_checks=1');
        }
    }

    /**
     * @param Collection $modelClasses
     */
    protected function seedModels(Collection $modelClasses): void
    {
        $modelClasses->each(function (string $modelClass) {
            $this->command->getOutput()->write("<info>Seeding:</info>{$modelClass} ...");
            $model = new $modelClass;
            $this->singleModelSeeder->run($model);
            $this->command->getOutput()->writeln(' <info>done</info>');
        });
    }

    /**
     * Seed順に並べたEloquent Modelのクラス名の配列を取得
     * @return string[]
     */
    abstract protected function getModelClasses();
}
