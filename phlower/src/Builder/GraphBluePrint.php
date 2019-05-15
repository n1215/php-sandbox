<?php
declare(strict_types=1);

namespace N1215\Phlower\Builder;

use N1215\Phlower\FlowInterface;
use N1215\Phlower\Graph\Graph;
use N1215\Phlower\GraphInterface;
use N1215\Phlower\SinkInterface;
use N1215\Phlower\SourceInterface;

/**
 * Class GraphBluePrint
 * @package N1215\Phlower\Builder
 */
class GraphBluePrint
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
        $this->eventListeners = [];
        $this->errorHandlers = [];
        $this->completeHandlers = [];
    }

    /**
     * @param callable $eventListener
     * @return GraphBluePrint
     */
    public function on(callable $eventListener): GraphBluePrint
    {

        return $this;
    }

    /**
     * @param callable $errorHandler
     * @return GraphBluePrint
     */
    public function catch(callable $errorHandler): GraphBluePrint
    {
        return $this;
    }

    /**
     * @param callable $finalHandler
     * @return GraphBluePrint
     */
    public function finally(callable $finalHandler): GraphBluePrint
    {
        return $this;
    }

    /**
     * @return GraphInterface
     */
    public function build(): GraphInterface
    {
        return new Graph($this->source, $this->sink, ...$this->flows);
    }
}
