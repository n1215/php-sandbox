<?php
declare(strict_types=1);

namespace App\Seeder\Source;

use Iterator;

interface SeedSourceInterface
{
    /**
     * @return Iterator<array>
     */
    public function read(): Iterator;
}
