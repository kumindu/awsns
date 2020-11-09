<?php

namespace kumindu\awsns\Sns;


use Symfony\Component\HttpFoundation\Request;
use Aws\Sns\Message as SnsMessage;

class Message extends SnsMessage
{
    const TYPE_SUBSCRIPTION_CONFIRMATION = 'SubscriptionConfirmation';
    const TYPE_UNSUBSCRIBE_CONFIRMATION = 'UnsubscribeConfirmation';
    const TYPE_NOTIFICATION = 'Notification';

    /**
     * Creates a Message object from a PSR-7 Request or ServerRequest object.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Message
     */
    public static function fromSymfonyRequest(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE !== json_last_error() || !is_array($data)) {
            throw new \RuntimeException('Invalid POST data.');
        }

        return new Message($data);
    }

    /**
     * @return bool
     */
    public function isNotification(): bool
    {
        return isset($this['Type']) && $this['Type'] === self::TYPE_NOTIFICATION;
    }

    /**
     * @return bool
     */
    public function isConfirmation(): bool
    {
        return isset($this['Type']) && in_array($this['Type'], [
                self::TYPE_SUBSCRIPTION_CONFIRMATION,
                self::TYPE_UNSUBSCRIBE_CONFIRMATION
            ]);
    }
}
