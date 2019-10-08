<?php
declare(strict_types=1);

namespace N1215\Notification;

interface MessageTemplateInterface
{
    public function supportsEvent($event): bool;

    public function getChannel(): string;

    public function render($event, SinkInterface $sink): MessageInterface;
}