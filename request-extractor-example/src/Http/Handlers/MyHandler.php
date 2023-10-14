<?php

declare(strict_types=1);

namespace N1215\RequestExtractorExample\Http\Handlers;

use N1215\RequestExtractorExample\Context\Context;
use N1215\RequestExtractorExample\Context\ContextFromRequest;

class MyHandler
{
    /**
     * @param Context $context
     * @return string[]
     */
    public function handle(
        #[ContextFromRequest] Context $context
    ): array {
        $user = $context->getUser();
        return [
            'userId' => $user->getId(),
            'userName' => $user->getName(),
        ];
    }
}
