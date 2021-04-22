<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'code' => 403,
                'message' => $e->getMessage(),
                'data' => null
            ], 403);
        }

        return parent::render($request, $e);
    }
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            return response()->json(["message" => "Error"], 500);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request){
            return response()->json([
                'success' => false,
                'code' => 405,
                'message' => 'This method is not supported for this route',
                'data' => null
            ], 405);
        });

        $this->renderable(function(ModelNotFoundException $e, $request){
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => $e->getMessage(),
                'data' => null
            ], 404);
        });

        $this->renderable(function (NotFoundHttpException $e, $request){
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Route is not found',
                'data' => null
            ], 404);
        });

        $this->renderable(function (AuthenticationException $exception, $request){
            return response()->json([
                'success' => false,
                'code' => 401,
                'message' => $exception->getMessage(),
                'data' => null
            ], 401);
        });
        
        $this->renderable(function (Exception $e, $request){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Internal Error Server",
                'data' => null
            ], 500);
        });

    }
}