<?php

declare(strict_types=1);

namespace N1215\RequestExtractorExample\Context;

use N1215\RequestExtractorExample\User;
use N1215\RequestExtractor\RequestExtractorInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @implements extractor\RequestExtractor\RequestExtractorInterface<Context>
 */
class ContextExtractor implements RequestExtractorInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return Context
     */
    public function from(ServerRequestInterface $request): Context
    {
        return new Context(
            sessionId: 'test-session-id',
            nonce: 'test-nonce',
            user: new User('testId', 'tanaka'),
        );
    }
}
