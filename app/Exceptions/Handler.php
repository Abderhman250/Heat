<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {

            if ($exception instanceof AuthenticationException)
                return ApiResponse::error('Unauthorized', Response::HTTP_UNAUTHORIZED);
            if ($exception instanceof ThrottleRequestsException)
                return ApiResponse::error('Too Many Requests', Response::HTTP_UNAUTHORIZED);
            if ($exception instanceof UnauthorizedHttpException)
                return ApiResponse::error('Unauthorized', Response::HTTP_UNAUTHORIZED);
            if ($exception instanceof RouteNotFoundException)
                return ApiResponse::error('Not Found', Response::HTTP_NOT_FOUND);
            if ($exception instanceof MethodNotAllowedHttpException)
                return ApiResponse::error('Method Not Allowed', Response::HTTP_METHOD_NOT_ALLOWED);
            if ($exception instanceof NotFoundHttpException)
                return ApiResponse::error('Not Found', Response::HTTP_NOT_FOUND);
            if ($exception instanceof PostTooLargeException)
                return response()->json(
                    ApiResponse::error('Uploaded file is too large.', Response::HTTP_REQUEST_ENTITY_TOO_LARGE),
                    Response::HTTP_REQUEST_ENTITY_TOO_LARGE
                );

            // if ($exception instanceof ModelNotFoundException) {
            //     $resp = str_replace('App\\Models\\', '', $exception->getModel());
            //     return respondModelNotFound('Model {$resp} Not found');
            // }
        }
        return parent::render($request, $exception);
    }
}
