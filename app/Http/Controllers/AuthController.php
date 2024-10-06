<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
/**
 * @group Autenticación
 * 
 * Endpoints para manejar la autenticación de usuarios
 */
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    /**
     * Iniciar sesión
     * 
     * @bodyParam user string required El correo electrónico del usuario. Example: admin@example.com
     * @bodyParam password string required La contraseña del usuario. Example: password
     * 
     * @response {
     *  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *  "user": {
     *    "id": 1,
     *    "name": "Admin",
     *    "email": "admin@example.com",
     *    "is_admin": 1
     *  }
     * }
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('user', 'password');
            $data['usuario'] = $credentials['user'];
            $data['password'] = $credentials['password'];
           

            $throttleKey = $data['usuario'];

            // if (Cache::has($throttleKey)) {
            //     return response()->json(['error' => 'Account temporarily locked, try in 5 minuts'], 429);
            // }


            $user = DB::table('users')->select('email', 'name', 'id', 'is_admin')
                ->where('email', $data['usuario'])
                ->first();

            if (!$token = JWTAuth::attempt([
                'email' => $user->email ?? null,
                'password' => $data['password']
            ])) {

                Cache::add($throttleKey, 1, now()->addMinutes(5));
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json(['token' => $token, 'user' => $user])
                ->header('Authorization', $token)
                ->withCookie(
                    'token',
                    $token,
                    config('jwt.ttl'),
                    '/'
                );
        } catch (\Throwable $th) {
            return response()->json([$th->getMessage(), $th->getFile(), $th->getLine()]);
        }
    }

  
    // public function logout()
    // {
    //     auth()->logout();
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Logged out Successfully.'
    //     ], 200);
    // }

    /**
     * Obtener información del usuario autenticado
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @response {
     *  "id": 1,
     *  "name": "Admin",
     *  "email": "admin@example.com",
     *  "is_admin": 1
     * }
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    
    // public function refresh()
    // {
    //     if ($token = JWTAuth::refresh()) {
    //         return response()->json()
    //             ->json(['status' => 'successs'], 200)
    //             ->header('Authorization', $token);
    //     }
    //     return response()->json(['error' => 'refresh_token_error'], 401);
    // }

    /**
     * Renovar el token de autenticación y obtener información del usuario
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @response {
     *  "status": "success",
     *  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *  "user": {
     *    "id": 1,
     *    "name": "Admin",
     *    "email": "admin@example.com",
     *    "is_admin": 1
     *  }
     * }
     */
    public function renew()
    {
        try {
            if (!$token = JWTAuth::refresh()) {
                return response()->json(['error' => 'refresh_token_error'], 401);
            }
            $user = auth()->user();
            $user = User::find($user->id, ['id', 'name', 'email', 'is_admin']);
            return response()
                ->json(['status' => 'successs', 'token' => $token, 'user' => $user], 200)
                ->header('Authorization', $token);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'refresh_token_error' . $th->getMessage()], 401);
        }
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 24,
        ]);
    }

    /**
     * Cambiar la contraseña del usuario autenticado
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @bodyParam newPassword string required La nueva contraseña del usuario. Example: newPassword123
     * 
     * @response {
     *  "status": "success"
     * }
     */
    public function changePassword()
    {
        if (!auth()->user()) {
            return response()->json(['error' => 'refresh_token_error'], 401);
        }

        $user = User::find(auth()->user()->id);
        $user->password = Hash::make(Request()->get('newPassword'));
        $user->save();
        return Response()->json(['status' => 'successs', 200]);
    }
}