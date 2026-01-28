<?php

namespace App\Http\Helpers;

trait ApiResponse
{
    protected function successResponse(
        $message = 'success',
        $content = 'null',
        $status = 200, 
    )
    {
        return response()->json([
            'message' => $message,
            'content' => $content,
            'status' => $status,
        ], $status);
    }

    protected function errorResponse(
        $message = 'bad request',
        $content = 'null',
        $status = 400, 
    )
    {
        return response()->json([
            'message' => $message,
            'content' => $content,
            'status' => $status,
        ], $status);
    }
}