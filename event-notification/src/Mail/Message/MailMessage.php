<?php

namespace N1215\Notification\Mail\Message;

use N1215\Notification\ChannelInterface;
use N1215\Notification\Mail\Channel\MailChannel;
use N1215\Notification\MessageInterface;

class MailMessage implements MessageInterface
{
    private $body;

    /**
     * MailMessage constructor.
     * @param $body
     */
    public function __construct(string $body)
    {
        $this->body = $body;
    }

    public function supportsChannel(ChannelInterface $channel): bool
    {
        return $channel instanceof MailChannel;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }


}