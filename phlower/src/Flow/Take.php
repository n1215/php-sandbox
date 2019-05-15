<?php
declare(strict_types=1);

namespace N1215\Phlower\Flow;

use N1215\Phlower\FlowInterface;

/**
 * Class Take
 * @package N1215\Phlower\Flow
 */
class Take implements FlowInterface
{
    /**
     * @var int
     */
    private $count;

    /**
     * @param int $count
     */
    public function __construct(int $count)
    {
        if ($count <= 0) {
            throw new \InvalidArgumentException('count must greater than 0');
        }

        $this->count = $count;
    }

    /**
     * @param iterable $stream
     * @return iterable
     */
    public function pass(iterable $stream): Iterable
    {
        $index = 0;
        foreach ($stream as $data) {
            if ($index >= $this->count) {
                break;
            }

            yield $data;
            $index++;
        }
    }
}
