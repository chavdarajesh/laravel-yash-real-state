<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JwtAPIMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public $successStatus = 200;
    public $notFoundStatus = 404;
    public $internalServerStatus = 500;
    public $errorStatus = 400;
    public $unauthorizedStatus = 401;

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {

            $response = [
                'status' => $this->unauthorizedStatus,
                'message' => 'Employee Unauthorized',
            ];
            return response()->json($response, $this->successStatus);
        }

        try {

            $secretKey = env('JWT_SECRET', 'c984c69e-db4f-4c40-a512-643fdca41413');
            $algorithm = env('JWT_ALGORITHM', 'HS256');

            $decoded = JWT::decode($token, new Key($secretKey, $algorithm));
            $user = User::whereNotNull('api_token')->where('api_token', $token)->find($decoded->user_id);
            if (!$user) {
                $response = [
                    'status' => $this->unauthorizedStatus,
                    'message' => 'Employee Unauthorized',
                ];
                return response()->json($response, $this->successStatus);
            }

            if ($user->status != 1) {
                $response = [
                    'status' => $this->unauthorizedStatus,
                    'message' => 'Your Account is InActive',
                ];
                return response()->json($response, $this->successStatus);
            }
            if ($user->is_verified != 1) {
                $response = [
                    'status' => $this->unauthorizedStatus,
                    'message' => 'Your Account is Not Verified',
                ];
                return response()->json($response, $this->successStatus);
            }

            $request->merge(['user' => $user]);
            return $next($request);
        } catch (\Exception $e) {
            $response = [
                'status' => $this->unauthorizedStatus,
                'message' => 'Employee Unauthorized',
            ];
            return response()->json($response, $this->successStatus);
        }
    }
}
