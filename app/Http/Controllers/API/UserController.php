<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('pos')->paginate(25);

        return ResponseFormatter::success(
            $users,
            'Data list post berhasil diambil'
        );
    }

    public function detail($id)
    {
        $user = User::where('id', $id)->with('pos')->first();

        if (!$user) {
            return ResponseFormatter::error('Detail User Tidak ada', 401);
        }

        return ResponseFormatter::success(
            $user,
            'Data detail User berhasil diambil'
        );
    }

    public function profile()
    {
        $user = User::where('id', Auth::user()->id)->with('pos')->first();

        if (!$user) {
            return ResponseFormatter::error('Detail User Tidak ada', 401);
        }

        return ResponseFormatter::success(
            $user,
            'Data detail User berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'string|email|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'pos_id' =>  $request->super_admin == 1 ? '' : 'required',
            'nohp' => 'numeric|starts_with:0|unique:users'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Tambah User Gagal', 401);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'pos_id' => $request->pos_id ? $request->pos_id : 0,
            'super_admin' => $request->super_admin,
            'user' => $request->user,
            'nohp' => $request->nohp,
            'password' => Hash::make($request->password)
        ]);

        return ResponseFormatter::success($user, 'User sukses dibuat');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'string|email|max:255',
            'password' => $request->password ? 'string|min:8' : "",
            'pos_id' => 'required',
            'nohp' => 'numeric|starts_with:0'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Update User Gagal', 401);
        }

        $user = User::where('id', $id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->pos_id = $request->pos_id ? $request->pos_id : 0;
        $user->nohp = $request->nohp;
        $user->super_admin = $request->status == "Admin" ? 1 : 0;
        $user->user = $request->status == "User" ? 1 : 0;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->update();


        return ResponseFormatter::success($user, 'User sukses diedit');
    }

    public function delete($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return ResponseFormatter::error('Delete User Gagal', 401);
        }

        $user->delete();

        return [
            'message' => 'Data User Sukses Dihapus'
        ];
    }
}
