<?php
declare(strict_types=1);

namespace N1215\Notification;

interface TargetInterface
{
    public function getNotificationSinks(): SinkCollectionInterface;
}