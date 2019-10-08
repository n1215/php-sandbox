<?php
declare(strict_types=1);

namespace N1215\Notification;

interface SinkCollectionInterface
{
    /**
     * @return SinkInterface[]
     */
    public function getContents(): array;
}