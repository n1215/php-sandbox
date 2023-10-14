<?php

declare(strict_types=1);

namespace N1215\RequestExtractor;

/**
 * @template T
 */
interface FromRequestAttributeInterface
{
    /**
     * @return RequestExtractorInterface<T>|class-string<RequestExtractorInterface<T>>
     */
    public function getRequestExtractor();
}
