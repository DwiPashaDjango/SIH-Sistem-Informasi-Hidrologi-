<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubDas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class SubdasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $subdas = SubDas::withCount('pos')->orderBy('id');
            return DataTables::of($subdas)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('pos_count', function ($row) {
                    return $row->pos_count;
                })
                ->addColumn('action', function ($row) {
                    if (Auth::user()->roles[0]->name === 'Admin') {
                        $btn = '<a href="' . route('subdas.edit', ['id' => $row->id]) . '" class="btn btn-warning btn-sm mr-2"><i class="fas fa-pen"></i></a>';
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" id="delete" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                        return $btn;
                    } else {
                        return '-';
                    }
                })
                ->filter(function ($query) {
                    if (request()->has('search')) {
                        $search = request('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('admin.subdas.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.subdas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $post = $request->all();

        SubDas::create($post);

        return redirect()->route('subdas')->with('message', 'Berhasil Menambahkan Data.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subdas = SubDas::find($id);
        return view('admin.subdas.edit', compact('subdas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $subdas = SubDas::find($id);

        $put = $request->all();

        $subdas->update($put);

        return redirect()->route('subdas')->with('message', 'Berhasil Mengubah Data.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $subdas = SubDas::find($id);

        $user = User::where('id', Auth::user()->id)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $subdas->delete();
                return Response::json(['message' => 'Berhasil Menghapus Data Subdas.', 'code' => 200]);
            } else {
                return Response::json(['message' => 'Password Tidak Sesuai.', 'code' => 400]);
            }
        }

        return Response::json(['message' => 'Data TMA Tidak Di Temukan.', 'code' => 404]);
    }
}
