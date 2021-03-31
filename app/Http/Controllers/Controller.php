<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *    title="Your super  ApplicationAPI",
 *    version="1.0.0",
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($data=null, $message = 'Success',$code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'code' => $code,
            'data'=> $data
        ];
        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($data = null,$errorMsg = 'Failed', $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $errorMsg,
            'code' => $code,
            'data'=> $data
        ];
        return response()->json($response, $code);
    }

    public function response404()
    {
        return abort(Response::HTTP_NOT_FOUND);
    }
}