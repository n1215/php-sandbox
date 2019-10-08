<?php

namespace N1215\Notification;

interface EventNotifierInterface
{
    public function getEventClass(): string;

    public function handle($event): void;
}