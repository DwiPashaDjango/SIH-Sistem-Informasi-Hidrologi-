<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisController extends Controller
{
    public function index()
    {
        $jenis = Jenis::all();

        return ResponseFormatter::success(
            $jenis,
            'Data list jenis berhasil diambil'
        );
    }

    public function detail($id)
    {
        $jenis = DB::table('jenis')->where('id', $id)->first();

        return ResponseFormatter::success(
            $jenis,
            'Data list jenis berhasil diambil'
        );
    }
}
