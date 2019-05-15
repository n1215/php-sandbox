<?php
declare(strict_types=1);

namespace N1215\Phlower\Source;

use N1215\Phlower\SourceInterface;

/**
 * Class iterableSource
 * @package N1215\Phlower\Source
 */
final class iterableSource implements SourceInterface
{
    /**
     * @var array
     */
    private $iterable;

    /**
     * @param iterable $iterable
     */
    public function __construct(iterable $iterable)
    {
        $this->iterable = $iterable;
    }

    /**
     * @inheritDoc
     */
    public function read(): iterable
    {
        return $this->iterable;
    }
}
