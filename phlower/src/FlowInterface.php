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
     * @param iterable<array> $stream
     * @return iterable<array>
     */
    public function pass(iterable $stream): iterable;
}
