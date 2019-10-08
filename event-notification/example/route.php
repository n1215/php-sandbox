<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use N1215\Notification\EventNotifier;
use N1215\Notification\Mail\Channel\MailChannel;
use N1215\Notification\Mail\Message\MailMessage;
use N1215\Notification\Mail\Message\MailMessageTemplate;
use N1215\Notification\Mail\Sink\MailSink;
use N1215\Notification\MessageInterface;
use N1215\Notification\Sink\SinkCollection;
use N1215\Notification\SinkCollectionInterface;
use N1215\Notification\SinkInterface;
use N1215\Notification\TargetInterface;
use N1215\Notification\TargetSelectorInterface;

class User implements TargetInterface
{
    private $email;

    private $name;

    public function __construct(string $email, string $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNotificationSinks(): SinkCollectionInterface
    {
        return new SinkCollection(new MailSink($this->email));
    }
}

class UserRegistered
{
    private $user;
    private $registeredAt;

    public function __construct(User $user, DateTime $registeredAt)
    {
        $this->user = $user;
        $this->registeredAt = $registeredAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getRegisteredAt(): DateTime
    {
        return $this->registeredAt;
    }
}

class UserRegisteredTemplate extends MailMessageTemplate {
    public function supportsEvent($event): bool
    {
        return $event instanceof UserRegistered;
    }
    public function render($event, SinkInterface $sink): MessageInterface
    {
        assert($event instanceof UserRegistered);
        $user = $event->getUser();
        $body = "{$user->getName()}様" . PHP_EOL . PHP_EOL . 'ユーザー登録されました';
        return new MailMessage($body);
    }
}

class UserTargetSelector implements TargetSelectorInterface
{
    public function supportsEvent($event): bool
    {
        return $event instanceof UserRegistered;
    }
    public function select($event): TargetInterface
    {
        assert($event instanceof UserRegistered);
        return $event->getUser();
    }
}

$userRegistered = new UserRegistered(new User('test@example.com', '田中'), new DateTime);

$eventNotifier = new EventNotifier(
    UserRegistered::class,
    new UserTargetSelector(),
    new MailChannel(),
    new UserRegisteredTemplate()
);

$eventNotifier->handle($userRegistered);

//
//// routing
//
//$container = new SomePSR11Container();
//// $container->regiseter();
//$notifications = new N1215\Notification\Dispatcher\Registrar($container);
//
//$notifications
//    ->source(UserRegistered::class)
//    ->to(UserTargetSelector::class)
//    ->inFormOf(UserRegisteredTemplate::class);
//
//
//$notificationCenter = $notifications->build();
//
//// implements PSR-14's Listener Provider Interface
//$listeners = $notificationCenter->getListenersForEvent($userRegistered);
//
//// or implements PSR-14's EventDispatcher Interface
//$notificationCenter->dispatch($userRegistered);
