<?php
declare(strict_types=1);

namespace N1215\Notification\Mail\Message;

use N1215\Notification\Mail\Channel\MailChannel;
use N1215\Notification\MessageInterface;
use N1215\Notification\MessageTemplateInterface;
use N1215\Notification\SinkInterface;

abstract class MailMessageTemplate implements MessageTemplateInterface
{
    abstract public function supportsEvent($event): bool;

    public function getChannel(): string
    {
        return MailChannel::class;
    }

    abstract public function render($event, SinkInterface $sink): MessageInterface;
}