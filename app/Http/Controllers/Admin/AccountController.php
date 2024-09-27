<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        return view('admin.account.index');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = User::where('id', Auth::user()->id)->first();

        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {

                $put = $request->except(['old_password', 'password_confirmation']);
                $put['password'] = Hash::make($put['password']);

                $user->update($put);

                return back()->with('success', 'Berhasil Mengubah Password.');
            } else {
                return back()->with('message', 'Password Lama Tidak Sesuai.');
            }
        }
        return back()->with('message', 'Gagal Mengubah Password.');
    }
}
