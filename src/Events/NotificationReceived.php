<?php

namespace kumindu\awsns\Events;

use Aws\Sns\Message;

class NotificationReceived
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
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message['Message'];
    }

    /**
     * @return Message
     */
    public function getSNSMessage(): Message
    {
        return $this->message;
    }
}
