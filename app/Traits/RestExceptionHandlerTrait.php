<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait RestExceptionHandlerTrait
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
       
        switch(true) {
            case $this->isModelNotFoundException($e):
                $retval = $this->modelNotFound();
                break;
            case $this->isAuthException($e):
                $retval = $this->authException($e->getMessage());
                break;
            default:
                $retval = $this->badRequest($e->getMessage());
        }

        return $retval;
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
//    protected function badRequest($message='Bad request', $statusCode=400)
//    {
//        return $this->jsonResponse(['error' => $message], $statusCode);
//    }
    protected function badRequest($message='Internal Server Error', $statusCode=500)
    {
        $array = array(
            'timestamp' => time(),
            'status' => $statusCode,
            'code' => 1,
            'message' => $message,
            'data' => array()
        );
        return $this->jsonResponse($array, $statusCode);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound($message='Invalid Api Call', $statusCode=404)
    {
         $array = array(
            'timestamp' => time(),
            'status' => $statusCode,
            'code' => 1,
            'message' => $message,
            'data' => array()
        );
        return $this->jsonResponse($array, $statusCode);
    }
    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authException($message='Unauthorized user', $statusCode=401)
    {
         $array = array(
            'timestamp' => time(),
            'status' => $statusCode,
            'code' => 1,
            'message' => $message,
            'data' => array()
        );
        return $this->jsonResponse($array, $statusCode);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload=null, $statusCode=404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
    }
    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isAuthException(Exception $e)
    {
        return $e instanceof \Illuminate\Auth\AuthenticationException;
    }

}