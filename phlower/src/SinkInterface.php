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
     * @param Iterable<array> $stream
     */
    public function write(Iterable $stream): void;
}
