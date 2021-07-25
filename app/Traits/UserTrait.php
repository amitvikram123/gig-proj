<?php
namespace App\Traits;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

trait UserTrait {
    public function getAuthenticatedUser() 
    {
        return JWTAuth::parseToken()->authenticate();
    }
}