<?php
declare(strict_types=1);

namespace N1215\Phlower;


/**
 * Interface SinkInterface
 * @package N1215\Phlower
 */
interface SinkInterface
{
    /**
     * @param iterable<array> $stream
     */
    public function write(iterable $stream): void;
}
