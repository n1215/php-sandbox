<?php
declare(strict_types=1);

namespace N1215\Notification\Mail\Sink;

use N1215\Notification\ChannelInterface;
use N1215\Notification\Mail\Channel\MailChannel;
use N1215\Notification\SinkInterface;

/**
 * Class MailSink
 * @package N1215\Notification\Mail\Channel
 */
class MailSink implements SinkInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * MailSink constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }


    public function isConnectableTo(ChannelInterface $channel): bool
    {
        return $channel instanceof MailChannel;
    }
}
