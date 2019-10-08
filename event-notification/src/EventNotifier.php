<?php


namespace N1215\Notification;

class EventNotifier implements EventNotifierInterface
{
    /**
     * @var string
     */
    private $eventClass;

    /**
     * @var TargetSelectorInterface
     */
    private $targetSelector;

    /**
     * @var MessageTemplateInterface
     */
    private $messageTemplate;

    /**
     * @var ChannelInterface
     */
    private $channel;

    /**
     * EventNotifier constructor.
     * @param string $eventClass
     * @param TargetSelectorInterface $targetSelector
     * @param ChannelInterface $channel
     * @param MessageTemplateInterface $messageTemplate
     */
    public function __construct(
        string $eventClass,
        TargetSelectorInterface $targetSelector,
        ChannelInterface $channel,
        MessageTemplateInterface $messageTemplate
    ) {

        $supportedChannel = $messageTemplate->getChannel();
        if (!$channel instanceof $supportedChannel) {
            throw new \InvalidArgumentException(get_class($channel) . '/' . $messageTemplate->getChannel());
        }
        $this->eventClass = $eventClass;
        $this->targetSelector = $targetSelector;
        $this->messageTemplate = $messageTemplate;
        $this->channel = $channel;
    }

    public function getEventClass(): string
    {
        return $this->eventClass;
    }

    /**
     * @inheritDoc
     */
    public function handle($event): void
    {
        if (!$event instanceof $this->eventClass) {
            throw new \LogicException();
        }

        if(!$this->targetSelector->supportsEvent($event)) {
            throw new \LogicException();
        }

        if (!$this->messageTemplate->supportsEvent($event)) {
            throw new \LogicException();
        }

        $target = $this->targetSelector->select($event);

        $sinks = array_filter($target->getNotificationSinks()->getContents(), function (SinkInterface $sink) {
            return $sink->isConnectableTo($this->channel);
        });

        if (count($sinks) === null) {
            throw new \RuntimeException();
        }

        /** @var SinkInterface $sink */
        $sink = $sinks[0];

        $message = $this->messageTemplate->render($event, $sink);

        $this->channel->send($message, $sink);
    }

}