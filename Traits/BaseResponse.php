<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait BaseResponse
{
    /**
     * Building success response
     * @param $data
     * @param int $code
     * @return JsonResponse
     */
    public  function successResponse($response = null)
    {
        if ($response instanceof \App\Http\Resources\APIPaginateCollection) {
            return response()->json($response, Response::HTTP_OK);
        }
        if ($response) {
            return response()->json(['data' => $response], Response::HTTP_OK);
        }

        return response()->json(
            ['data' => [
            'success' => true
        ]], Response::HTTP_OK);
    }

    /**
     * Building success response
     * @param $data
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse($exception, $statusCode = Response::HTTP_NOT_FOUND)
    {
        return response()->json([
            'data' => [
                'success' => false,
                'message' => $exception->getMessage()
            ]
        ], $statusCode);
    }
}
