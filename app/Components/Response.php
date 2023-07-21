<?php

namespace App\Components;

use Illuminate\Http\JsonResponse;

trait Response
{

    /**
     * @param $data
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    protected function customSuccess($data, $message = null, $code = 1)
    {
        return response()->json([
            'code' => $code,
            'data' => $data,
            'message' => $message
        ], 200);
    }

    /**
     * @param $message
     * @param int $status
     * @param int $code
     * @return JsonResponse
     */
    protected function customError($message, $status = 403, $code = 0)
    {
        return response()->json([
            'code' => $code,
            'data' => null,
            'message' => $message,
        ], $status);
    }

    /**
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function accessDenied($message = 'access denied', $code = 0)
    {
        return response()->json([
            'code' => $code,
            'data' => null,
            'message' => $message,
        ], 406);
    }

    /**
     * @param $data
     * @return array
     */
    protected function customPaginate($data)
    {
        return [
            'page' => $data->currentPage(),
            'pageSize' => $data->perPage(),
            'totalPages' => $data->lastPage(),
            'list' => $data->items(),
        ];
    }
}
