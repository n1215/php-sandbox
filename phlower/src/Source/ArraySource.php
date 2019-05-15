<?php
declare(strict_types=1);

namespace N1215\Phlower\Source;

use N1215\Phlower\SourceInterface;

/**
 * Class ArraySource
 * @package N1215\Phlower\Source
 */
final class ArraySource implements SourceInterface
{
    /**
     * @var array
     */
    private $array;

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * @inheritDoc
     */
    public function read(): Iterable
    {
        return $this->array;
    }
}
