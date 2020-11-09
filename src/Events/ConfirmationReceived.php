<?php

namespace kumindu\awsns\Events;

use Aws\Sns\Message;

class ConfirmationReceived
{
    /**
     * @var Message
     */
    protected $message;

    /**
     * Constructor.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getTopicArn(): string
    {
        return $this->message['TopicArn'];
    }

    /**
     * @return string
     */
    public function getSubscribeUrl(): string
    {
        return $this->message['SubscribeURL'] ?? '';
    }

    /**
     * @return bool
     */
    public function isSubscriptionConfirmation(): bool
    {
        return $this->message['Type'] === 'SubscriptionConfirmation';
    }

    /**
     * @return bool
     */
    public function isUnsubscribeConfirmation(): bool
    {
        return $this->message['Type'] === 'UnsubscribeConfirmation';
    }

    /**
     * @return Message
     */
    public function getSNSMessage(): Message
    {
        return $this->message;
    }
}
