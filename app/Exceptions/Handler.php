<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

    // public function render($request, Throwable $exception)
    // {
    //     // Handle specific exceptions for Blade apps
    //     if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
    //         return response()->view('errors.404', [], 404);
    //     }

    //     if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
    //         return redirect()->guest(route('login'));
    //     }

    //     // Handle HTTP exceptions
    //     if ($exception instanceof HttpException) {
    //         $statusCode = $exception->getStatusCode();

    //         if (view()->exists("errors.{$statusCode}")) {
    //             return response()->view("errors.{$statusCode}", [
    //                 'exception' => $exception
    //             ], $statusCode);
    //         }
    //     }

    //     return parent::render($request, $exception);
    // }
    // public function render($request, Throwable $exception)
    // {
    //     // -----------------------------------------------------------------
    //     // 1. Your existing custom handling (unchanged)
    //     // -----------------------------------------------------------------
    //     if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
    //         return $this->renderErrorView($request, 404, 'Page not found','The resource you are looking for does not exist!');
    //     }

    //     if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
    //         return redirect()->guest(route('login'));
    //     }

    //     if ($exception instanceof HttpException) {
    //         $statusCode = $exception->getStatusCode();

    //         // If a specific view exists, let it extend the layout
    //         if (view()->exists("errors.{$statusCode}")) {
    //             return response()->view("errors.{$statusCode}", [
    //                 'exception' => $exception,
    //             ], $statusCode);
    //         }

    //         // No specific view → use generic layout
    //         $title   = $statusCode . ' ' . \Symfony\Component\HttpFoundation\Response::$statusTexts[$statusCode];
    //         $message = $exception->getMessage() ?: 'An unexpected error occurred';

    //         return $this->renderErrorView($request, $statusCode, $title, $message);
    //     }

    //     // -----------------------------------------------------------------
    //     // 2. Fallback for any other exception (500, etc.)
    //     // -----------------------------------------------------------------
    //     $response = parent::render($request, $exception);

    //     // If parent returned a view with a 4xx/5xx status, replace it
    //     if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 600) {
    //         $status  = $response->getStatusCode();
    //         $title   = $status . ' ' . \Symfony\Component\HttpFoundation\Response::$statusTexts[$status];
    //         $message = $exception->getMessage() ?: 'An unexpected error occurred';

    //         return $this->renderErrorView($request, $status, $title, $message);
    //     }

    //     return $response;
    // }

    /** Helper – renders the layout with the given data */
    // private function renderErrorView($request, int $status, string $title, string $message)
    // {
    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'message' => $message ?: $title,
    //         ], $status);
    //     }

    //     return response()->view('errors.layout', [
    //         'title'   => $title,
    //         'message' => $message ?: $title,
    //     ], $status);
    // }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
