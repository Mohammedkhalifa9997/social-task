<?php

namespace App\Traits;

use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Mockery\Exception\BadMethodCallException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


trait ApiExceptionsTrait
{
    use ApiResponseTrait;

    public static function apiException($e)
    {
        if ($e instanceof NotFoundHttpException) {
            return ApiResponseTrait::apiResponse([], 'Not Found', [], 404);
        }

        if ($e instanceof BindingResolutionException) {
            return ApiResponseTrait::apiResponse([], 'Server Error', [], 500);
        }

        if ($e instanceof ModelNotFoundException) {
            return ApiResponseTrait::apiResponse([], 'Not Found', [], 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return ApiResponseTrait::apiResponse([], 'Method Not Allowed', [], 405);
        }

        if ($e instanceof RouteNotFoundException) {
            return ApiResponseTrait::apiResponse([], 'Not Found Route', [], 500);
        }

        if ($e instanceof AuthenticationException) {
            return ApiResponseTrait::apiResponse([], 'Unauthenticated', [], 401);
        }

        if ($e instanceof AccessDeniedHttpException) {
            return ApiResponseTrait::apiResponse([], 'This Action Is Unauthorized', [], 403);
        }

        if ($e instanceof BadMethodCallException) {
            return ApiResponseTrait::apiResponse([], 'Not Allowed Method', [], 403);
        }

        return ApiResponseTrait::apiResponse([], 'Server Error', [], 500);
    }
}
