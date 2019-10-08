<?php
declare(strict_types=1);

namespace N1215\Notification;

interface SinkInterface
{
    public function isConnectableTo(ChannelInterface $channel): bool;
}