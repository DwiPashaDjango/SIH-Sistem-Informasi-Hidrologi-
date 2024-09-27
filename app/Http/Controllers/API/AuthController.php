<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CurahHujan;
use App\Models\Klimatologi;
use App\Models\TMA;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function dashboard()
    {
        $tma = Post::where('jenis_id', 2)->count();
        $curah = Post::where('jenis_id', 1)->count();
        $klimatologi = Post::where('jenis_id', 3)->count();
        $user = User::where('super_admin', '!=', 1)->count();

        return ResponseFormatter::success(
            [$tma, $curah, $klimatologi, $user],
            'Data Dashboard berhasil diambil'
        );
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('username', $request['username'])->with('pos')->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json([
                'message' => 'Hi ' . $user->name . ', welcome to home',
                'access_token' => $token,
                'user' => $user,
                'token_type' => 'Bearer',
            ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password_lama' => 'required|string|min:8',
            'password' => 'required|confirmed|string|min:8',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Ubah Password Gagal', 401);
        }
        $user = User::where('username', $request->username)->first();
        if (Hash::check(request('password_lama'), $user->password) === false) {
            return ResponseFormatter::error(['error' => $user], 'Password Lama Salah', 401);
        }
        $user->password = Hash::make($request->password);
        $user->update();


        return ResponseFormatter::success($user, 'Update Password Sukses');
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
