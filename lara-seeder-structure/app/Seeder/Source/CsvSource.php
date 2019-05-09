<?php
declare(strict_types=1);

namespace App\Seeder\Source;

use Iterator;
use SplFileObject;

class CsvSource implements SeedSourceInterface
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return Iterator<array>
     */
    public function read(): Iterator
    {
        $file = new SplFileObject($this->filePath);
        $file->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::READ_AHEAD |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::DROP_NEW_LINE
        );

        // todo ヘッダーを考慮

        return $file;
    }
}
