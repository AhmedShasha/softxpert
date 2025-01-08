<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param  array  $metaData
     * @return mixed
     */
    public function apiResponse(JsonResource|array $data = [], $status = 200, $message = 'success', $additional = [])
    {
        $body = [
            'status_code' => $status,
            'message'     => $message,
            'additional'  => $additional,
        ];

        if ($data instanceof JsonResource) {
            return $data->additional($body);
        } else {
            return response()->json(['data' => $data] + $body, $status);
        }
    }
}
