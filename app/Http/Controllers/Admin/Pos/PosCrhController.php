<?php

namespace App\Http\Controllers\Admin\Pos;

use App\Exports\Admin\CrhExport;
use App\Http\Controllers\Controller;
use App\Models\CurahHujan;
use App\Models\Jenis;
use App\Models\Post;
use App\Models\Province;
use App\Models\SubDas;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PosCrhController extends Controller
{
    public function index()
    {
        $province = Province::all();
        $subdas = SubDas::all();
        if (request()->ajax()) {
            $province = request('provinces_id');
            $regencies = request('regencies_id');
            $subdas = request('subdas_id');

            $crh = Post::with('province', 'regencie', 'subdas')
                ->where('jenis_id', 1)
                ->whereNull('deleted_at');

            if ($province != null) {
                $crh = Post::with('province', 'regencie')
                    ->where('jenis_id', 1)
                    ->where('provinces_id', $province)
                    ->whereNull('deleted_at');
            }

            if ($regencies != null) {
                $crh = Post::with('province', 'regencie')
                    ->where('jenis_id', 1)
                    ->where('regencies_id', $regencies)
                    ->whereNull('deleted_at');
            }

            if ($subdas != null) {
                $crh = Post::with('province', 'regencie')
                    ->where('jenis_id', 1)
                    ->where('subdas_id', $subdas)
                    ->whereNull('deleted_at');
            }

            return DataTables::of($crh)
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
                ->addColumn('subdas', function ($row) {
                    if ($row->subdas_id == null) {
                        return '-';
                    } else {
                        return $row->subdas->name;
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('pos.crh.show', ['id' => $row->id]) . '" class="btn btn-primary btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                    if (Auth::user()->roles[0]->name === 'Admin') {
                        $btn .= '<a href="' . route('pos.edit', ['id' => $row->id, 'jenisPos' => 'crhs']) . '" class="btn btn-warning btn-sm mr-2"><i class="fas fa-pen"></i></a>';
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" id="delete" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
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
                                })
                                ->orWhereHas('subdas', function ($q) use ($search) {
                                    $q->where('name', 'like', "%{$search}%");
                                });
                        });
                    }
                })
                ->rawColumns(['image', 'cordinat', 'action'])
                ->toJson();
        }
        return view('admin.pos.crh.index', compact('province', 'subdas'));
    }

    public function show(Request $request, $id)
    {
        $pos = Post::find($id);
        $crh = CurahHujan::where('pos_id', $id);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if (!empty($start_date) && !empty($end_date)) {
            $crhs = $crh->whereBetween('tanggal', [$start_date, $end_date]);
        } else {
            $crhs = $crh->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year);
        }

        $crhs = $crh->orderBy('id', 'DESC')->paginate(25);

        $labels = $crhs->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });

        $hujan_biasa = $crhs->pluck('hujan_biasa');
        $hujan_otomatis = $crhs->pluck('hujan_otomatis');

        return view('admin.pos.crh.show', compact('pos', 'crhs', 'labels', 'hujan_biasa', 'hujan_otomatis', 'start_date', 'end_date'));
    }

    public function createCRH($id)
    {
        $pos = Post::find($id);
        return view('admin.pos.crh.create-crh', compact('pos'));
    }

    public function generatePDFCRH($start_date, $end_date, $id)
    {
        $pos = Post::find($id);
        $crh = CurahHujan::where('pos_id', $id);

        $crhs = $crh->whereBetween('tanggal', [$start_date, $end_date])->get();
        $pdf = Pdf::loadView('admin.pdf.pos_crh', compact('crhs', 'pos', 'start_date', 'end_date'));
        $pdf->setBasePath(public_path());

        return $pdf->download('SIH BWS Sumatera VI.pdf');
    }

    public function generateExcelTMA($start_date, $end_date, $id)
    {
        return Excel::download(new CrhExport($start_date, $end_date, $id), 'SIH3 BWS Sumatera VI - Curah Hujan.xlsx');
    }

    public function storeCRH(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required',
            'keterangan' => 'required'
        ]);

        $crhs = CurahHujan::where('pos_id', $request->pos_id)->where('tanggal', $request->tanggal)->first();

        if ($crhs) {

            if ($request->jenis === 'biasa') {
                $crhs->update([
                    'tanggal' => $request->tanggal,
                    'hujan_biasa' => $request->jumlah,
                    'keterangan' => $request->keterangan,
                    'pos_id' => $request->pos_id,
                ]);
            } else {
                $crhs->update([
                    'tanggal' => $request->tanggal,
                    'hujan_otomatis' => $request->jumlah,
                    'keterangan' => $request->keterangan,
                    'pos_id' => $request->pos_id,
                ]);
            }
        } else {
            if ($request->jenis === 'biasa') {
                CurahHujan::create([
                    'tanggal' => $request->tanggal,
                    'hujan_biasa' => $request->jumlah,
                    'keterangan' => $request->keterangan,
                    'pos_id' => $request->pos_id,
                ]);
            } else {
                CurahHujan::create([
                    'tanggal' => $request->tanggal,
                    'hujan_otomatis' => $request->jumlah,
                    'keterangan' => $request->keterangan,
                    'pos_id' => $request->pos_id,
                ]);
            }
        }
        return redirect()->route('admin.absensi', ['pos_id' => $request->pos_id, 'tanggal' => Carbon::now()->format('Y-m-d'), 'jenis' => 'crhs']);

        // return back()->with('message', 'Berhasil Menyimpan Data Curah Hujan.');
    }

    public function editCRH($id)
    {
        $crhs = CurahHujan::with('pos')->find($id);
        return view('admin.pos.crh.edit-crh', compact('crhs'));
    }

    public function updateCRH(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required',
            'jumlah' => 'required',
            'keterangan' => 'required'
        ]);

        $crhs = CurahHujan::find($id);

        if ($request->jenis === 'biasa') {
            $crhs->update([
                'hujan_biasa' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);
        } else {
            $crhs->update([
                'hujan_otomatis' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);
        }

        return back()->with('message', 'Berhasil Mengubah Data Curah Hujan.');
    }

    public function destroyCRH(Request $request, $id)
    {
        $crhs = CurahHujan::find($id);

        $user = User::where('id', Auth::user()->id)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $crhs->delete();
                return Response::json(['message' => 'Berhasil Menghapus Data TMA.', 'code' => 200]);
            } else {
                return Response::json(['message' => 'Password Tidak Sesuai.', 'code' => 400]);
            }
        }

        return Response::json(['message' => 'Data TMA Tidak Di Temukan.', 'code' => 404]);
    }
}
