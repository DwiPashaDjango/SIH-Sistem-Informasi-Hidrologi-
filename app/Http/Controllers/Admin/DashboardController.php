<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurahHujan;
use App\Models\Klimatologi;
use App\Models\Post;
use App\Models\TMA;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->hasAnyRole(['Admin', 'Pimpinan'])) {
            $users = User::count();
            $crh = Post::where('jenis_id', 1)->count();
            $tma = Post::where('jenis_id', 2)->count();
            $klimatologi = Post::where('jenis_id', 3)->count();
            return view('admin.dashboard', compact('users', 'crh', 'tma', 'klimatologi'));
        } else {
            $pos = Post::where('id', Auth::user()->pos_id)->first();
            // dd($pos);
            if ($pos->jenis_id == 1) {
                return redirect()->route('pos.crh.show', ['id' => Auth::user()->pos_id]);
            } else if ($pos->jenis_id == 2) {
                return redirect()->route('pos.tma.show', ['id' => Auth::user()->pos_id]);
            } else {
                return redirect()->route('pos.klimatologi.show', ['id' => Auth::user()->pos_id]);
            }
        }
    }
}
