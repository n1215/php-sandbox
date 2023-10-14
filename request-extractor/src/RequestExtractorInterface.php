<?php

declare(strict_types=1);

namespace N1215\RequestExtractor;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @template T
 */
interface RequestExtractorInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return T
     */
    public function from(ServerRequestInterface $request);
}
