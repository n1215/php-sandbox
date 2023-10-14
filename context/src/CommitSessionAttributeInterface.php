<?php

declare(strict_types=1);

namespace N1215\Context;

/**
 * @template T of SessionContextInterface
 */
interface CommitSessionAttributeInterface
{
    /**
     * @return SessionContextSerializerInterface<T>|class-string<SessionContextSerializerInterface<T>>
     */
    public function getSerializer();
}
