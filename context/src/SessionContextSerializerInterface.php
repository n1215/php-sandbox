<?php

declare(strict_types=1);

namespace N1215\Context;

/**
 * @template T of SessionContextInterface
 */
interface SessionContextSerializerInterface
{
    /**
     * @param T $context
     * @return string
     */
    public function serialize($context): string;
}
