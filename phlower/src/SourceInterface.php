<?php
declare(strict_types=1);

namespace N1215\Phlower;


/**
 * Interface SourceInterface
 * @package N1215\Phlower
 */
interface SourceInterface
{
    /**
     * @return Iterable<array> $stream
     */
    public function read(): Iterable;
}
