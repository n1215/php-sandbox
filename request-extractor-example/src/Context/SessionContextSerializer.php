<?php

declare(strict_types=1);

namespace N1215\RequestExtractorExample\Context;

use N1215\Context\SessionContextSerializerInterface;

/**
 * @template T of SessionContextSerializerInterface<Context>
 */
class SessionContextSerializer implements SessionContextSerializerInterface
{
    /**
     * @param Context $context
     * @return string
     */
    public function serialize($context): string
    {
        return json_encode(
            [
                'user_id' => $context->getUser()?->getId(),
                'nonce' => $context->getNonce(),
            ]
        );
    }
}
