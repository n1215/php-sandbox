<?php
declare(strict_types=1);

namespace N1215\Phlower\Source;

use N1215\Phlower\SourceInterface;

/**
 * Class IterableSource
 * @package N1215\Phlower\Source
 */
final class IterableSource implements SourceInterface
{
    /**
     * @var array
     */
    private $iterable;

    /**
     * @param Iterable $iterable
     */
    public function __construct(Iterable $iterable)
    {
        $this->iterable = $iterable;
    }

    /**
     * @inheritDoc
     */
    public function read(): Iterable
    {
        return $this->iterable;
    }
}
