<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $exception) {
            return $this->apiError('Not Found', 404);
        });

        $this->renderable(function (ApiException $exception) {
            if ($exception->hasDetails()) {
                return response()->json($exception->getDetails(), $exception->getCode());
            }

            return $this->apiError($exception->getMessage(), $exception->getCode());
        });

        $this->renderable(function (Throwable $exception) {
            return $this->apiError('Internal Server Error', 500);
        });
    }

    protected function apiError($message, $code)
    {
        return response()->json(['message' => $message], $code);
    }
}
