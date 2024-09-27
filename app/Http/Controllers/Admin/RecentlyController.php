<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class RecentlyController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $pos = Post::with('province', 'regencie')->where('deleted_at', '!=', null);
            return DataTables::of($pos)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img src="' . asset('storage/' . $row->gambar) . '" width="100" alt="">';
                })
                ->addColumn('name', function ($row) {
                    return $row->nama;
                })
                ->addColumn('cordinat', function ($row) {
                    return 'X : ' . $row->koordinatx . '<br> Y :' . $row->koordinaty;
                })
                ->addColumn('location', function ($row) {
                    return $row->lokasi;
                })
                ->addColumn('regency', function ($row) {
                    if ($row->regencies_id == null) {
                        return '-';
                    } else {
                        return $row->regencie->name;
                    }
                })
                ->addColumn('province', function ($row) {
                    if ($row->provinces_id == null) {
                        return '-';
                    } else {
                        return $row->province->name;
                    }
                })
                ->addColumn('action', function ($row) {
                    if (Auth::user()->roles[0]->name === 'Admin') {
                        if ($row->jenis_id == 1) {
                            $btn = '<a href="' . route('pos.crh.show', ['id' => $row->id]) . '" class="btn btn-info btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                        } else if ($row->jenis_id == 2) {
                            $btn = '<a href="' . route('pos.tma.show', ['id' => $row->id]) . '" class="btn btn-info btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                        } else {
                            $btn = '<a href="' . route('pos.klimatologi.show', ['id' => $row->id]) . '" class="btn btn-info btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                        }

                        $btn .= '<a href="javascript:void(0)" class="btn btn-primary btn-sm mr-2" data-id="' . $row->id . '" id="restore"><i class="fas fa-sync"></i></a>';
                    } else {
                        $btn = '-';
                    }
                    return $btn;
                })
                ->filter(function ($query) {
                    if (request()->has('search')) {
                        $search = request('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('nama', 'like', "%{$search}%")
                                ->orWhere('lokasi', 'like', "%{$search}%")
                                ->orWhere('koordinatx', 'like', "%{$search}%")
                                ->orWhere('koordinaty', 'like', "%{$search}%")
                                ->orWhereHas('regencie', function ($q) use ($search) {
                                    $q->where('name', 'like', "%{$search}%");
                                })
                                ->orWhereHas('province', function ($q) use ($search) {
                                    $q->where('name', 'like', "%{$search}%");
                                });
                        });
                    }
                })
                ->rawColumns(['image', 'cordinat', 'action'])
                ->toJson();
        }
        return view('admin.recently.index');
    }

    public function restore($id)
    {
        $pos = Post::find($id);
        $pos->update(['deleted_at' => null]);

        return Response::json(['message' => 'Berhasil Mengembalikan Data Pos.', 'code' => 200]);
    }
}
