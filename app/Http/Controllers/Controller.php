<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Create new sucess JSON response instance.
     *
     * @param string     $message
     * @param array|null $data
     * @param int        $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($message, $data, $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }

    /**
     * Create new error JSON response instance.
     *
     * @param string     $message
     * @param array|null $data
     * @param int        $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($message, $data = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
