<?php

namespace kumindu\awsns\Http\Middleware;

use kumindu\awsns\MessageValidator;
use Illuminate\Support\Facades\Log;
use kumindu\awsns\Sns\Message;
use Illuminate\Http\Response;
use Exception;
use Closure;

class VerifySignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $message = Message::fromSymfonyRequest($request);

            (new MessageValidator())->validate($message);

        } catch (Exception $exception) {
            Log::debug($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());

            return response(Response::$statusTexts[Response::HTTP_BAD_REQUEST], Response::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }
}
