<?php
declare(strict_types=1);

namespace N1215\Phlower\Builder;

use N1215\Phlower\SourceInterface;

/**
 * Class GraphBuilder
 * @package N1215\Phlower\Builder
 */
class GraphBuilder
{
    /**
     * @param SourceInterface $source
     * @return SourceBluePrint
     */
    public function from(SourceInterface $source): SourceBluePrint
    {
        return new SourceBluePrint($source);
    }
}
