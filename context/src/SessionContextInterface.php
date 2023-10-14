<?php

declare(strict_types=1);

namespace N1215\Context;

interface SessionContextInterface
{
    public function getSessionId(): ?string;
}
