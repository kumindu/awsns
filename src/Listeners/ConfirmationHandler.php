<?php

namespace kumindu\awsns\Listeners;

use kumindu\awsns\Events\ConfirmationReceived;
use GuzzleHttp\ClientInterface as HttpClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Psr7\Response;

class ConfirmationHandler implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * Constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Handle the event
     *
     * @param ConfirmationReceived $event
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(ConfirmationReceived $event)
    {
        $response = $this->httpClient->request('GET', $event->getSubscribeUrl(), []);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            // TODO: subscribe successful
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  ConfirmationReceived $event
     * @param  \Exception  $exception
     */
    public function failed(ConfirmationReceived $event, $exception)
    {
        // TODO
    }
}
