<?php

declare(strict_types=1);

namespace N1215\RequestExtractorExample\Context;

use Attribute;
use N1215\Context\CommitSessionAttributeInterface;
use N1215\RequestExtractor\FromRequestAttributeInterface;

/**
 * @implements extractor\RequestExtractor\FromRequestAttributeInterface<Context>
 * @implements CommitSessionAttributeInterface<Context>
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class ContextFromRequest implements FromRequestAttributeInterface, CommitSessionAttributeInterface
{
    /**
     * @inheritDoc
     */
    public function getRequestExtractor(): string
    {
        return ContextExtractor::class;
    }

    /**
     * @inheritDoc
     */
    public function getSerializer(): string
    {
        return SessionContextSerializer::class;
    }
}
