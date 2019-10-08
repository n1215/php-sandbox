<?php
declare(strict_types=1);

namespace N1215\Notification;

interface MessageInterface
{
    public function supportsChannel(ChannelInterface $channel): bool;
}