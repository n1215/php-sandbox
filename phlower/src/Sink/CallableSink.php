<?php
declare(strict_types=1);

namespace N1215\Phlower\Sink;

use N1215\Phlower\SinkInterface;

/**
 * Class Ignore
 * @package N1215\Phlower\Sink
 */
final class CallableSink implements SinkInterface
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
        $this->callable = $callable;
    }

    /**
     * @inheritDoc
     */
    public function write(iterable $stream): void
    {
        $callable = $this->callable;
        foreach ($stream as $data) {
            $callable($data);
        }
    }
}
