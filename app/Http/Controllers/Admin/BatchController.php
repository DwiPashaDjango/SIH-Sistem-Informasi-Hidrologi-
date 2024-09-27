<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\Admin\CRHImport;
use App\Imports\Admin\KlimatologiImport;
use App\Imports\Admin\TMAImport;
use App\Models\Post;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BatchController extends Controller
{
    public function batchTMA()
    {
        $pos = Post::where('jenis_id', 2)->select('id', 'nama')->get();
        return view('admin.batch.index', compact('pos'));
    }

    public function importTMA(Request $request)
    {
        $request->validate([
            'pos_id' => 'required',
            'file' => 'required|mimes:xlsx'
        ]);

        $pos_id = $request->pos_id;

        Excel::import(new TMAImport($pos_id), $request->file('file'));

        return back()->with('message', 'Data Berhasil Di Import');
    }

    public function batchCRH()
    {
        $pos = Post::where('jenis_id', 1)->select('id', 'nama')->get();
        return view('admin.batch.crh', compact('pos'));
    }

    public function importCRH(Request $request)
    {
        $request->validate([
            'pos_id' => 'required',
            'file' => 'required|mimes:xlsx'
        ]);

        $pos_id = $request->pos_id;

        Excel::import(new CRHImport($pos_id), $request->file('file'));

        return back()->with('message', 'Data Berhasil Di Import');
    }

    public function batchKlima()
    {
        $pos = Post::where('jenis_id', 3)->select('id', 'nama')->get();
        return view('admin.batch.klimatologi', compact('pos'));
    }

    public function importKlima(Request $request)
    {
        $request->validate([
            'pos_id' => 'required',
            'file' => 'required|mimes:xlsx'
        ]);

        $pos_id = $request->pos_id;

        Excel::import(new KlimatologiImport($pos_id), $request->file('file'));

        return back()->with('message', 'Data Berhasil Di Import');
    }
}
