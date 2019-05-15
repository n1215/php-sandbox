<?php
declare(strict_types=1);

namespace N1215\Phlower\Flow;

use N1215\Phlower\FlowInterface;

/**
 * Class Identical
 * @package N1215\Phlower\Flow
 */
class Identical implements FlowInterface
{
    public function pass(iterable $stream): Iterable
    {
        return $stream;
    }
}
