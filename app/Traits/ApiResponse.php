<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

trait ApiResponse {
    
    protected function successResponse($data, $meta = null, $message = null, $code = 200) {

        $response = [
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ];

        if($meta !== null){
            $response["meta"] = $meta;
        }

        return response()->json($response, $code);
    }

    protected function error($message, $code) {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => null,
            'meta' => null,
        ], $code);
    }


    protected function errorResponse(\Throwable $th, string $message)
    {
        if ($th instanceof ModelNotFoundException) {
            return $this->error("Data is not found!", 404);
        }

        if ($th instanceof HttpExceptionInterface) {
            return $this->error(
                $message,
                $th->getStatusCode()
            );
        }

        return $this->error(
            $message,
            500
        );
    }

}