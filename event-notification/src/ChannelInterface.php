<?php
declare(strict_types=1);

namespace N1215\Notification;

interface ChannelInterface
{
    /**
     * @param MessageInterface $message
     * @param SinkInterface $sink
     */
    public function send(MessageInterface $message, SinkInterface $sink): void;
}