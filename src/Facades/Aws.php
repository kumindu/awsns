<?php

namespace kumindu\awsns\Facades;



use kumindu\awsns\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Route;

class Aws extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'aws';
    }

    /**
     * @param string $path
     * @return \Illuminate\Support\Facades\Route
     */
    public static function routes($path = '/webhook/aws')
    {
        return Route::post($path, WebhookController::class);
    }
}
