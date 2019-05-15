<?php
declare(strict_types=1);

namespace N1215\Phlower\Sink;

use N1215\Phlower\SinkInterface;

/**
 * Class Ignore
 * @package N1215\Phlower\Sink
 */
final class Ignore implements SinkInterface
{
    /**
     * @inheritDoc
     */
    public function write(iterable $stream): void
    {
        // do nothing
    }
}
