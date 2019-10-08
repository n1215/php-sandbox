<?php
declare(strict_types=1);

namespace N1215\Notification\Mail\Channel;

use N1215\Notification\ChannelInterface;
use N1215\Notification\Mail\Message\MailMessage;
use N1215\Notification\MessageInterface;
use N1215\Notification\SinkInterface;

class MailChannel implements ChannelInterface
{
    /**
     * @param MessageInterface $message
     * @param SinkInterface $sink
     */
    public function send(MessageInterface $message, SinkInterface $sink): void
    {
        if (!$message->supportsChannel($this))
        {
            throw new \RuntimeException();
        }

        assert($message instanceof MailMessage);


        $body = $message->getBody();

        echo $body;
    }
}