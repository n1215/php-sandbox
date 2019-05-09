<?php
declare(strict_types=1);

namespace App\Seeder\Sink;

use App\Seeder\Source\SeedSourceInterface;
use Generator;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Model;

class BulkInsertSink implements SeedSinkInterface
{
    private const DEFAULT_CHUNK_COUNT = 10;

    /**
     * @var ConnectionInterface
     */
    private $conn;

    /**
     * @var int
     */
    private $chunkCount;

    /**
     * @param ConnectionInterface $conn
     * @param int $chunkCount
     */
    public function __construct(ConnectionInterface $conn, int $chunkCount = self::DEFAULT_CHUNK_COUNT)
    {
        $this->conn = $conn;
        $this->chunkCount = $chunkCount;
    }

    /**
     * @inheritDoc
     */
    public function write(Model $model, SeedSourceInterface $source): void
    {
        foreach ($this->chunk($source) as $chunkedRows) {
            $this->conn
                ->table($model->getTable())
                ->insert($chunkedRows);
        }
    }

    /**
     * @param SeedSourceInterface $source
     * @return Generator
     */
    private function chunk(SeedSourceInterface $source): Generator
    {
        $i = 0;
        $rows = [];
        foreach ($source->read() as $row) {
            if ($i >= $this->chunkCount) {
                yield $rows;
                $i = 0;
                $rows = [];
            }

            $rows[] = $row;
            $i++;
        }
    }
}