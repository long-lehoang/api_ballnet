<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $this->renderable(function (NotFoundHttpException $e, $request){
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Route is not found',
                'data' => null
            ], 404);
        });
    }
}