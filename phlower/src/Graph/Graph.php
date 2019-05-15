<?php
declare(strict_types=1);

namespace N1215\Phlower\Graph;

use N1215\Phlower\FlowInterface;
use N1215\Phlower\GraphInterface;
use N1215\Phlower\SinkInterface;
use N1215\Phlower\SourceInterface;

/**
 * Class Graph
 * @package N1215\Phlower\Graph
 */
final class Graph implements GraphInterface
{
    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @var SinkInterface
     */
    private $sink;

    /**
     * @var FlowInterface
     */
    private $flows;

    /**
     * @var callable[]
     */
    private $eventListeners;

    /**
     * @var callable[]
     */
    private $errorHandlers;

    /**
     * @var callable[]
     */
    private $completeHandlers;

    /**
     * @param SourceInterface $source
     * @param SinkInterface $sink
     * @param FlowInterface ...$flows
     */
    public function __construct(SourceInterface $source, SinkInterface $sink, FlowInterface ...$flows)
    {
        $this->source = $source;
        $this->sink = $sink;
        $this->flows = $flows;

        // todo イベント
        $this->eventListeners = [];
        $this->errorHandlers = [];
        $this->completeHandlers = [];
    }

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        $stream = $this->source->read();
        foreach ($this->flows as $flow) {
            $stream = $flow->pass($stream);
        }

        $this->sink->write($stream);
    }
}
