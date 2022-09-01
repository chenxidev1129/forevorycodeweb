<?php

namespace App\Http\Middleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException; 
use Illuminate\Support\Facades\Config;

use Closure;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('authorization');
        try {
            $token = str_replace('Bearer ', '', $token);
            $user = JWTAuth::parseToken()->authenticate();

            if (!empty($token)) {
                $check = \App\Models\UserDeviceToken::where('token', $token)->first();
            }
            if (!empty($check)) {
                if (!empty($user)) {
                    if ($user->status == "inactive") {

                        return response()->json(
                            [
                                'success' => false,
                                'data' => '',
                                'message' => 'Session expire.'
                            ],
                            Config::get('constants.HttpStatus.UNAUTHORIZED')
                        );

                    }
                    $request['user'] = $user;
                    return $next($request);
                } else {

                    return response()->json(
                        [
                            'success' => false,
                            'data' => '',
                            'message' => 'Session expire.'
                        ],
                        Config::get('constants.HttpStatus.UNAUTHORIZED')
                    );

                }
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'data' => '',
                        'message' => 'Session expire.'
                    ],
                    Config::get('constants.HttpStatus.UNAUTHORIZED')
                );
            }


        } catch (JWTException $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json(
                    [
                        'success' => false,
                        'data' => '',
                        'message' => 'Session expire.'
                    ],
                    Config::get('constants.HttpStatus.UNAUTHORIZED')
                );
            }else if ($e instanceof TokenExpiredException){
                return response()->json(
                    [
                        'success' => false,
                        'data' => '',
                        'message' => 'Session expire.'
                    ],
                    Config::get('constants.HttpStatus.UNAUTHORIZED')
                );
            }else{
                return response()->json(
                    [
                        'success' => false,
                        'data' => '',
                        'message' => 'Session expire.'
                    ],
                    Config::get('constants.HttpStatus.UNAUTHORIZED')
                );
            }
        }
        return $next($request);
    }
}
