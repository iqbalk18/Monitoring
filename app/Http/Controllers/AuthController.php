<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Exception;

class AuthController extends Controller
{
    public function addUser(Request $request)
    {
        try {
            $currentUser = JWTAuth::parseToken()->authenticate();

            if (!$currentUser->hasRole('ADMIN')) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Hanya ADMIN yang dapat menambahkan user baru.'
                ], 403);
            }

            $allowedRoles = config('roles.all', []);
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'roles' => 'required|array',
                'roles.*' => 'in:' . implode(',', $allowedRoles),
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $roles = array_values(array_unique($request->input('roles', [])));
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'roles' => $roles,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'New user added successfully.',
                'user' => $user
            ], 201);

        } catch (JWTException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Token invalid or not found.'
            ], 401);
        }
    }

    public function deleteUser($id)
    {
        try {
            $currentUser = JWTAuth::parseToken()->authenticate();

            if (!$currentUser->hasRole('ADMIN')) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Hanya ADMIN yang dapat menghapus user.'
                ], 403);
            }

            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User not found.'
                ], 404);
            }

            if ($user->id === $currentUser->id) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Anda tidak dapat menghapus akun Anda sendiri.'
                ], 400);
            }

            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully.'
            ]);

        } catch (JWTException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Token invalid or not found.'
            ], 401);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Password lama tidak sesuai.'
                ], 400);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Password changed successfully.'
            ]);

        } catch (JWTException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Token tidak valid atau tidak ditemukan.'
            ], 401);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('username', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid username or password.',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json([
                'status' => 'success',
                'user' => $user,
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Token tidak valid atau kadaluarsa.',
            ], 401);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully!',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Token tidak valid atau sudah kadaluarsa.',
            ], 401);
        }
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return $this->respondWithToken($newToken);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Token refresh failed. It may have expired.',
            ], 401);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user(),
        ]);
    }
}
