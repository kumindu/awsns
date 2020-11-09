<?php

namespace kumindu\awsns\Http\Controllers;

use kumindu\awsns\Events\SubscriptionConfirmationReceived;
use kumindu\awsns\Events\UnsubscribeConfirmationReceived;
use kumindu\awsns\Listeners\ConfirmationHandler;
use kumindu\awsns\Events\ConfirmationReceived;
use kumindu\awsns\Events\NotificationReceived;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use kumindu\awsns\Sns\Message;
use Aws\Sns\MessageValidator;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        Event::listen(ConfirmationReceived::class, ConfirmationHandler::class);
    }

    /**
     * Action
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            // Get message
            $message = Message::fromSymfonyRequest($request);

            // Validate the message
            $validator = new MessageValidator();

            if ($validator->isValid($message)) {

                switch ($message['Type']) {
                   case Message::TYPE_SUBSCRIPTION_CONFIRMATION:
                    Event::dispatch(new SubscriptionConfirmationReceived(), [], true);
                    break;

                   case Message::TYPE_UNSUBSCRIBE_CONFIRMATION:
                    Event::dispatch(new UnsubscribeConfirmationReceived(), [], true);
                    break;

                   case Message::TYPE_NOTIFICATION:
                    Event::dispatch(new NotificationReceived(), [], true);
                    break;

                   default:
                    return $this->responseCode(Response::HTTP_BAD_REQUEST);
                }

            }else{

                if(Str::contains($message['Type'],Message::TYPE_NOTIFICATION)) {
                    Event::dispatch(new NotificationReceived(), [], true);
                }else{
                    Log::debug('"Message" is required to verify the SNS Message.'. PHP_EOL);
                }
                
            }

            return $this->responseCode(Response::HTTP_OK);

        } catch (\RuntimeException $exception) {
            Log::debug($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
        } catch (\InvalidArgumentException $exception) {
            Log::debug($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
        }

        return $this->responseCode(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param int $statusCode
     * @return \Illuminate\Http\Response
     */
    protected function responseCode(int $statusCode)
    {
        if (!isset(Response::$statusTexts[$statusCode])) {
            $statusCode = Response::HTTP_BAD_REQUEST;
        }

        return response(Response::$statusTexts[$statusCode], $statusCode);
    }
}
