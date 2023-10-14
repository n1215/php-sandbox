<?php

declare(strict_types=1);

namespace N1215\RequestExtractorExample\Context;

use N1215\Context\SessionContextInterface;
use N1215\RequestExtractorExample\User;

class Context implements SessionContextInterface
{
    /**
     * @param string|null $sessionId
     * @param string|null $nonce
     * @param User|null $user
     */
    public function __construct(
        private readonly ?string $sessionId,
        private readonly ?string $nonce,
        private ?User $user
    ) {
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function getNonce(): ?string
    {
        return $this->nonce;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
