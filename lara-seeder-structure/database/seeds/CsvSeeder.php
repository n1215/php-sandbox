<?php
declare(strict_types=1);

use App\Models\Master;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemManager;

abstract class CsvSeeder extends Seeder
{
    /**
     * @var Filesystem
     */
    protected $disk;

    /**
     * @var ConnectionInterface
     */
    protected $conn;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseManager $databaseManager, FilesystemManager $filesystemManager)
    {
        $this->disk = $filesystemManager->disk($this->getDiskName());
        $this->conn = $databaseManager->connection($this->getConnectionName());
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $modelClasses = collect($this->getModelClasses());

        try {
            $this->conn->statement('SET foreign_key_checks=0');
            $modelClasses->reverse()->each(function (string $modelClass) {
                if (!is_subclass_of($modelClass, Model::class)) {
                    throw new LogicException('Eloquentモデルを指定してください class = ' . $modelClass);
                }

                $this->command->getOutput()->write("<info>Truncating:</info>{$modelClass} ...");
                $modelClass::truncate();
                $this->command->getOutput()->writeln(' <info>done</info>');
            });
        } finally {
            $this->conn->statement('SET foreign_key_checks=1');
        }

        $modelClasses->each(function (string $modelClass) {
            $this->command->getOutput()->write("<info>Seeding:</info>{$modelClass} ...");
            $this->seedModel($modelClass);
            $this->command->getOutput()->writeln(' <info>done</info>');
        });
    }

    /**
     * @param string $modelClass
     */
    private function seedModel(string $modelClass): void
    {
        if (!is_subclass_of($modelClass, Model::class)) {
            throw new LogicException('Eloquentモデルを指定してください class = ' . $modelClass);
        }

        $fileName = $this->makeFileName(new $modelClass);

        try {
            $resource = $this->disk->readStream($fileName);
        } catch (FileNotFoundException $e) {
            throw new RuntimeException("CSVファイルが見つかりません file name = {$fileName}");
        }

        $this->loadCsv($resource, $modelClass);
    }

    /**
     * CSVファイルの読み取りに使うディスク名を取得
     * @return string
     */
    protected function getDiskName(): ?string
    {
        return null;
    }

    /**
     * DB接続名を取得
     * @return string
     */
    protected function getConnectionName(): ?string
    {
        return null;
    }

    /**
     * Seed順に並べたEloquent Modelのクラス名の配列を取得
     * @return array
     */
    protected function getModelClasses(): array
    {
        return [];
    }

    /**
     * Eloquentモデルに対応するCSVのファイル名を取得
     * @param Model $model
     * @return string
     */
    protected function makeFileName(Model $model): string
    {
        return $model->getTable() . '.csv';
    }

    /**
     * @param resource $resource
     * @param string $modelClass
     * @return Model
     */
    protected function loadCsv($resource, string $modelClass): Model
    {
        while ($row = fgetcsv($resource)) {
            $eloquent = new $modelClass($row);
            if (!$eloquent->save()) {
                throw new RuntimeException('Eloquent Modelの保存に失敗しました');
            }
        }

        return new $modelClass($row);
    }
}
