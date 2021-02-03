<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
     * @throws \Throwable
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
    public function render($request, Throwable $e)
    {
        // Exception for jwt 
        if ($e instanceof TokenInvalidException) {
            return response()->json([
                'message' => 'Token is Invalid',
                'status' => 'fail',
                'statusCode' => 401,
                'error' => 'Unauthorized.'
            ], 200);
        } elseif ($e instanceof TokenExpiredException) {
            return response()->json([
                'message' => 'Token Expired',
                'status' => 'fail',
                'statusCode' => 401,
                'error' => 'Unauthorized.'
            ], 200);
        } elseif ($e instanceof JWTException) {
            return response()->json([
                'message' => 'There is problem with your token. ie. Token not provided.',
                'status' => 'fail',
                'statusCode' => 401,
                'error' => 'Unauthorized.'
            ], 200);
        }

        // http exception
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => 'Method not allowed.',
                'error' => null,
                'status' => 'fail',
                'statusCode' => $e->getStatusCode()
            ], $e->getStatusCode());
        } elseif ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'API endpoint does not exists.',
                'error' => null,
                'error' => 'Unauthorized.',
                'status' => 'fail',
                'statusCode' => $e->getStatusCode()
            ], $e->getStatusCode());
        }

        // Database Execption ie: findorFail
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => null,
                'status' => 'fail',
                'statusCode' => 400
            ], 200);
        }

        // Database Execption ie: findorFail
        if ($e instanceof UnauthorizedException) { 
            return response()->json([
                'message' => $e->getMessage(),
                'error' => null,
                'status' => 'fail',
                'statusCode' => $e->getStatusCode()
            ], 200);
        }

        return parent::render($request, $e);
    }
}
