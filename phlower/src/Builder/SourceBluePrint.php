<?php
declare(strict_types=1);

namespace N1215\Phlower\Builder;

use N1215\Phlower\FlowInterface;
use N1215\Phlower\SinkInterface;
use N1215\Phlower\SourceInterface;

/**
 * Class SourceBluePrint
 * @package N1215\Phlower\Builder
 */
class SourceBluePrint
{
    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @var FlowInterface[]
     */
    private $flows;

    /**
     * @param SourceInterface $source
     * @param FlowInterface ...$flows
     */
    public function __construct(SourceInterface $source, FlowInterface ...$flows)
    {
        $this->source = $source;
        $this->flows = $flows;
    }

    /**
     * @param FlowInterface $flow
     * @return SourceBluePrint
     */
    public function via(FlowInterface $flow): SourceBluePrint
    {
        $newFlows = $this->flows;
        $newFlows[] = $flow;
        return new self($this->source, ...$newFlows);
    }

    /**
     * @param SinkInterface $sink
     * @return GraphBluePrint
     */
    public function to(SinkInterface $sink): GraphBluePrint
    {
        return new GraphBluePrint($this->source, $sink, ...$this->flows);
    }
}
