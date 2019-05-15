<?php
declare(strict_types=1);

namespace N1215\Phlower\Flow;

use N1215\Phlower\FlowInterface;

/**
 * Class Map
 * @package N1215\Phlower\Flow
 */
class Map implements FlowInterface
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        // todo validation
        $this->callable = $callable;
    }

    /**
     * @inheritDoc
     */
    public function pass(iterable $stream): iterable
    {
        $callable = $this->callable;
        foreach ($stream as $data) {
            yield $callable($data);
        }
    }
}
