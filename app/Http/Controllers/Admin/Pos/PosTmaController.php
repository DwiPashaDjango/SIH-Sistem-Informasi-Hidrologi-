<?php

namespace App\Http\Controllers\Admin\Pos;

use App\Exports\Admin\TMAExport;
use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Jenis;
use App\Models\PosQuality;
use App\Models\Post;
use App\Models\Province;
use App\Models\Regency;
use App\Models\SubDas;
use App\Models\TMA;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PosTmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $province = Province::all();
        $subdas = SubDas::all();
        if (request()->ajax()) {
            $province = request('provinces_id');
            $regencies = request('regencies_id');
            $subdas = request('subdas_id');

            $tmas = Post::with('province', 'regencie', 'subdas')
                ->where('jenis_id', 2)
                ->whereNull('deleted_at');

            if ($province != null) {
                $tmas = Post::with('province', 'regencie')
                    ->where('jenis_id', 2)
                    ->where('provinces_id', $province)
                    ->whereNull('deleted_at');
            }

            if ($regencies != null) {
                $tmas = Post::with('province', 'regencie')
                    ->where('jenis_id', 2)
                    ->where('regencies_id', $regencies)
                    ->whereNull('deleted_at');
            }

            if ($subdas != null) {
                $tmas = Post::with('province', 'regencie')
                    ->where('jenis_id', 2)
                    ->where('subdas_id', $subdas)
                    ->whereNull('deleted_at');
            }

            return DataTables::of($tmas)
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
                    if (Auth::user()->roles[0]->name === 'Admin') {
                        $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" id="qualityPos" class="btn btn-info btn-sm mr-2"><i class="fas fa-tint"></i></a>';
                        $btn .= '<a href="' . route('pos.tma.show', ['id' => $row->id]) . '" class="btn btn-primary btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                        $btn .= '<a href="' . route('pos.edit', ['id' => $row->id, 'jenisPos' => 'tmas']) . '" class="btn btn-warning btn-sm mr-2"><i class="fas fa-pen"></i></a>';
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" id="delete" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                        return $btn;
                    } else {
                        $btn = '<a href="' . route('pos.tma.show', ['id' => $row->id]) . '" class="btn btn-primary btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                        return $btn;
                    }
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
        return view('admin.pos.tma.index', compact('province', 'subdas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($jenisPos)
    {
        $jenis = Jenis::select('id', 'nama')->get();
        $province = Province::select('id', 'name')->get();
        $subdas = SubDas::select('id', 'name')->get();
        return view('admin.pos.tma.create', compact('jenis', 'province', 'subdas', 'jenisPos'));
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
            'nama' => 'required',
            'jenis_id' => 'required',
            'koordinatx' => 'required',
            'koordinaty' => 'required',
            'lokasi' => 'required',
            'regencies_id' => 'required',
            'provinces_id' => 'required',
            'gambar' => 'required|mimes:png,jpg,jpeg',
            'subdas_id' => 'required',
            'tma_banjir' => 'required',
        ]);

        $image = $request->file('gambar');
        $imageName = rand() . '.' . $image->getClientOriginalExtension();
        $path = 'pos/' . $imageName;
        $image->storeAs('public/pos/', $imageName);

        $post = $request->all();
        $post['gambar'] = $path;

        if ($request->jenis_id == 1) {
            $post['normal'] = 0.5;
            $post['waspada'] = 20;
            $post['siaga'] = 50;
            $post['awas'] = 100;
        }

        Post::create($post);

        return redirect()->back()->with('message', 'Behasil Menyimpan Data.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $pos = Post::find($id);
        $tma = TMA::where('pos_id', $id);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if (!empty($start_date) && !empty($end_date)) {
            $tma = $tma->whereBetween('tanggal', [$start_date, $end_date]);
        } else {
            $tma = $tma->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year);
        }

        $tmas = $tma->orderBy('id', 'DESC')->paginate(25);

        $labels = $tmas->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });
        $pagi = $tmas->pluck('pagi');
        $siang = $tmas->pluck('siang');
        $sore = $tmas->pluck('sore');

        return view('admin.pos.tma.show', compact('pos', 'tmas', 'labels', 'pagi', 'siang', 'sore', 'start_date', 'end_date'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $jenisPos)
    {
        $jenis = Jenis::select('id', 'nama')->get();
        $province = Province::select('id', 'name')->get();
        $pos = Post::find($id);
        $subdas = SubDas::select('id', 'name')->get();
        return view('admin.pos.tma.edit', compact('jenis', 'pos', 'province', 'subdas', 'jenisPos'));
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
            'nama' => 'required',
            'jenis_id' => 'required',
            'koordinatx' => 'required',
            'koordinaty' => 'required',
            'lokasi' => 'required',
            'regencies_id' => 'required',
            'provinces_id' => 'required',
            'gambar' => 'mimes:png,jpg,jpeg',
            'subdas_id' => 'required',
            'tma_banjir' => 'required',
        ]);


        $pos = Post::find($id);

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = rand() . '.' . $image->getClientOriginalExtension();
            $path = 'pos/' . $imageName;
            $image->storeAs('public/pos/', $imageName);

            $pos->update([
                'nama' => $request->nama,
                'jenis_id' => $request->jenis_id,
                'koordinatx' => $request->koordinatx,
                'koordinaty' => $request->koordinaty,
                'lokasi' => $request->lokasi,
                'regencies_id' => $request->regencies_id,
                'provinces_id' => $request->provinces_id,
                'normal' => $request->normal,
                'waspada' => $request->waspada,
                'siaga' => $request->siaga,
                'awas' => $request->awas,
                'subdas_id' => $request->subdas_id,
                'tma_banjir' => $request->tma_banjir,
                'gambar' => $path,
            ]);
        } else {
            $pos->update([
                'nama' => $request->nama,
                'jenis_id' => $request->jenis_id,
                'koordinatx' => $request->koordinatx,
                'koordinaty' => $request->koordinaty,
                'lokasi' => $request->lokasi,
                'regencies_id' => $request->regencies_id,
                'provinces_id' => $request->provinces_id,
                'normal' => $request->normal,
                'waspada' => $request->waspada,
                'siaga' => $request->siaga,
                'awas' => $request->awas,
                'subdas_id' => $request->subdas_id,
                'tma_banjir' => $request->tma_banjir,
            ]);
        }
        return redirect()->back()->with('message', 'Behasil Mengubah Data.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $pos = Post::find($id);

        $user = User::where('id', Auth::user()->id)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $pos->update([
                    'deleted_at' => Carbon::now()
                ]);
                return Response::json(['message' => 'Berhasil Menghapus Data Pos.', 'code' => 200]);
            } else {
                return Response::json(['message' => 'Password Tidak Sesuai.', 'code' => 400]);
            }
        }

        return Response::json(['message' => 'Data Pos Tidak Di Temukan.', 'code' => 404]);
    }

    public function getRegencie(Request $request)
    {
        $province_id = $request->provinces_id;

        $regencies = Regency::where('province_id', $province_id)->get();

        return Response::json(['data' => $regencies]);
    }

    public function generatePDFTMA($start_date, $end_date, $id)
    {
        $pos = Post::find($id);
        $tma = TMA::where('pos_id', $id);

        $tmas = $tma->whereBetween('tanggal', [$start_date, $end_date])->get();
        $pdf = Pdf::loadView('admin.pdf.pos_tma', compact('tmas', 'pos', 'start_date', 'end_date'));
        $pdf->setBasePath(public_path());

        return $pdf->download('SIH BWS Sumatera VI.pdf');
    }

    public function generateExcelTMA($start_date, $end_date, $id)
    {
        return Excel::download(new TMAExport($start_date, $end_date, $id), 'SIH3 BWS Sumatera VI - TMA.xlsx');
    }

    public function addTMA($id)
    {
        $pos = Post::find($id);
        return view('admin.pos.tma.create-tma', compact('pos'));
    }

    public function storeTMA(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'jam' => 'required',
            'tma' => 'required',
            'keterangan' => 'required'
        ]);

        $tma = TMA::where('pos_id', $request->pos_id)->where('tanggal', $request->tanggal)->first();
        if ($tma) {
            if ($request->jam === '12.00') {
                $tma->update([
                    'siang' => $request->tma,
                    'keterangan' => $request->keterangan
                ]);
            } else if ($request->jam === '17.00') {
                if ($tma->siang != null) {
                    $tma->update([
                        'sore' => $request->tma,
                        'keterangan' => $request->keterangan
                    ]);

                    return redirect()->route('admin.absensi', ['pos_id' => $request->pos_id, 'tanggal' => Carbon::now()->format('Y-m-d'), 'jenis' => 'tmas']);
                } else {
                    return back()->with(['warning' => 'Data TMA Pagi & Siang Harus Terisi Terlebih Dahulu']);
                }
            }
        } else {
            TMA::create([
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'pagi' => $request->tma,
                'keterangan' => $request->keterangan,
                'pos_id' => $request->pos_id
            ]);
        }

        return back()->with('message', 'Berhasil Menambahkan Tinggi Mata Air');
    }

    public function editTMA($id)
    {
        $tma = TMA::with('pos')->find($id);
        return view('admin.pos.tma.edit-tma', compact('tma'));
    }

    public function updateTMA(Request $request, $id)
    {
        $request->validate([
            'jam' => 'required',
            'tma' => 'required',
            'keterangan' => 'required'
        ]);

        $tma = TMA::where('id', $id)->first();

        if ($request->jam === '12.00') {
            $tma->update([
                'siang' => $request->tma,
                'keterangan' => $request->keterangan
            ]);
        } elseif ($request->jam === '17.00') {
            $tma->update([
                'sore' => $request->tma,
                'keterangan' => $request->keterangan
            ]);
        } else {
            $tma->update([
                'pagi' => $request->tma,
                'keterangan' => $request->keterangan
            ]);
        }

        return back()->with('message', 'Berhasil Mengubah Data Tinggi Muka Air.');
    }

    public function destroyTMA(Request $request, $id)
    {
        $tma = TMA::find($id);

        $user = User::where('id', Auth::user()->id)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $tma->delete();
                return Response::json(['message' => 'Berhasil Menghapus Data TMA.', 'code' => 200]);
            } else {
                return Response::json(['message' => 'Password Tidak Sesuai.', 'code' => 400]);
            }
        }

        return Response::json(['message' => 'Data TMA Tidak Di Temukan.', 'code' => 404]);
    }

    public function moveToQualityWater(Request $request)
    {

        $checkPos = PosQuality::where('posts_id', $request->posts_id)->first();

        if ($checkPos) {
            return Response::json(['message' => 'Pos sudah terdaftar di kualitas air.', 'code' => 400]);
        }

        PosQuality::create(['posts_id' => $request->posts_id]);

        return Response::json(['message' => 'Memindahkan data pos ke kualitas air.', 'code' => 200]);
    }
}
