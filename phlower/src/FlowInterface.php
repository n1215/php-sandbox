<?php
declare(strict_types=1);

namespace N1215\Phlower;


/**
 * Interface FlowInterface
 * @package N1215\Phlower
 */
interface FlowInterface
{
    /**
     * @param Iterable<array> $stream
     * @return Iterable<array>
     */
    public function pass(Iterable $stream): Iterable;
}
