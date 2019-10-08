<?php
declare(strict_types=1);

namespace N1215\Notification;

interface TargetSelectorInterface
{
    public function supportsEvent($event): bool;

    public function select($event): TargetInterface;
}