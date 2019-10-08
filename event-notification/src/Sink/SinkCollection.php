<?php
declare(strict_types=1);

namespace N1215\Notification\Sink;

use N1215\Notification\SinkCollectionInterface;
use N1215\Notification\SinkInterface;

class SinkCollection implements SinkCollectionInterface
{
    /**
     * @var SinkInterface[]
     */
    private $sinks;

    /**
     * SinkCollection constructor.
     * @param SinkInterface[] $sinks
     */
    public function __construct(SinkInterface ...$sinks)
    {
        $this->sinks = $sinks;
    }


    public function getContents(): array
    {
        return $this->sinks;
    }
}