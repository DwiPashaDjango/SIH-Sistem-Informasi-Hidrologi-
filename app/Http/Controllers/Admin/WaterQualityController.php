<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosQuality;
use App\Models\Post;
use App\Models\User;
use App\Models\WaterQuality;
use App\Models\WaterQualityDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class WaterQualityController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $crh = PosQuality::with('pos', 'pos.regencie', 'pos.province')->get();
            return DataTables::of($crh)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img src="' . asset('storage/' . $row->pos->gambar) . '" width="100" alt="">';
                })
                ->addColumn('name', function ($row) {
                    return $row->pos->nama;
                })
                ->addColumn('cordinat', function ($row) {
                    return 'X : ' . $row->pos->koordinatx . '<br> Y :' . $row->pos->koordinaty;
                })
                ->addColumn('location', function ($row) {
                    return $row->pos->lokasi;
                })
                ->addColumn('regency', function ($row) {
                    if ($row->pos->regencies_id == null) {
                        return '-';
                    } else {
                        return $row->pos->regencie->name;
                    }
                })
                ->addColumn('province', function ($row) {
                    if ($row->pos->provinces_id == null) {
                        return '-';
                    } else {
                        return $row->pos->province->name;
                    }
                })
                ->addColumn('action', function ($row) {
                    if (Auth::user()->roles[0]->name === 'Admin') {
                        // $btn = '<a href="' . route('water.quality.show', ['id' => $row->id]) . '" class="btn btn-info btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                        $btn = '<a href="' . route('water.quality.show', ['id' => $row->pos->id]) . '" class="btn btn-warning btn-sm mr-2">Uji Kualitas Air</a>';
                        $btn .= '<a href="javascript:void(0)" id="deletePosInQualityWater" data-id="' . $row->id . '" class="btn btn-danger btn-sm">Hapus</a>';
                    } else {
                        $btn = '-';
                    }
                    return $btn;
                })

                ->rawColumns(['image', 'cordinat', 'action'])
                ->toJson();
        }
        return view('admin.quality.index');
    }

    public function testQualityWater($id)
    {
        $pos = Post::find($id);
        return view('admin.quality.test', compact('pos'));
    }

    public function generateTestQualityWater(Request $request)
    {
        $checkSemester = WaterQuality::where('pos_id', $request->pos_id)
            ->where('semester', $request->semester)
            ->where('tahun', Carbon::now()->year)
            ->first();

        if ($checkSemester) {
            return back()->with('message', 'Sudah Melakukan Uji Kualitas Air Pada ' . 'Semster ' . $checkSemester->semester . ' Tahun' . $checkSemester->tahun);
        } else {
            $post = $request->except(['parameter', 'satuan', 'hasil']);
            $post['tahun'] = Carbon::now()->year;
            $insert = WaterQuality::create($post);

            foreach ($request->parameter as $key => $value) {
                WaterQualityDetail::create([
                    'water_qualitys_id' => $insert->id,
                    'parameter' => $value,
                    'satuan' => $request->satuan[$key],
                    'hasil' => $request->hasil[$key]
                ]);
            }
        }

        return redirect()->route('water.quality.resultQualityWater', ['id' => $insert->id]);
    }

    public function resultQualityWater($id)
    {
        $qualityWater = WaterQuality::with('detail')->find($id);

        return view('admin.quality.result', compact('qualityWater'));
    }

    public function generatePDFQualityWater($id)
    {
        $qualityWater = WaterQuality::with(['pos', 'detail'])->find($id);

        $pdf = Pdf::loadView('admin.pdf.pdf_quality_water', compact('qualityWater'));
        $pdf->setBasePath(public_path());

        return $pdf->download('Uji Kualitas Air.pdf');
    }

    public function historyQualityControl($id)
    {
        $pos = Post::with(['qualityWater'])->find($id);
        return view('admin.quality.history', compact('pos'));
    }

    public function show(Request $request, $id)
    {
        $pos = Post::find($id);
        $waterQuality = WaterQuality::where('pos_id', $id);

        $semester = $request->semester;
        $year = $request->year;

        if (!empty($semester) && !empty($year)) {
            $waterQuality = $waterQuality->where('semester', $semester)
                ->whereYear('created_at', $year);;
        } else {
            $waterQuality = $waterQuality->whereYear('created_at', Carbon::now()->year);
        }

        $waterQualitys = $waterQuality->orderBy('created_at', 'DESC')->paginate(25);

        $labels = $waterQualitys->pluck('created_at')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });
        $total = $waterQualitys->pluck('total');

        return view('admin.quality.show', compact('pos', 'waterQualitys', 'labels', 'total', 'year', 'semester'));
    }

    public function edit($id)
    {
        $qualityWater = WaterQuality::with('detail')->find($id);
        return view('admin.quality.edit', compact('qualityWater'));
    }

    public function update(Request $request, $id)
    {
        $post = $request->except(['parameter', 'satuan', 'hasil', 'action']);
        $insert = WaterQuality::find($id);
        $insert->update($post);

        if ($request->action === '2') {
            WaterQualityDetail::where('water_qualitys_id', $insert->id)->delete();

            foreach ($request->parameter as $key => $value) {
                WaterQualityDetail::create([
                    'water_qualitys_id' => $insert->id,
                    'parameter' => $value,
                    'satuan' => $request->satuan[$key],
                    'hasil' => $request->hasil[$key]
                ]);
            }
        }

        return back()->with('message', 'Berhasil Mengubah Data.');
    }

    public function destroy(Request $request, $id)
    {
        $qualityWater = WaterQuality::with('detail')->find($id);

        $user = User::where('id', Auth::user()->id)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $qualityWater->delete();
                $qualityWater->detail()->delete();
                return Response::json(['message' => 'Berhasil Menghapus Data Kualitas Air.', 'code' => 200]);
            } else {
                return Response::json(['message' => 'Password Tidak Sesuai.', 'code' => 400]);
            }
        }

        return Response::json(['message' => 'Data Kualitas Air Tidak Di Temukan.', 'code' => 404]);
    }

    public function deletePosInQualityWater(Request $request, $id)
    {
        $posQuality = PosQuality::find($id);

        $user = User::where('id', Auth::user()->id)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $posQuality->delete();
                return Response::json(['message' => 'Berhasil Menghapus Pos Dari Kualitas Air.', 'code' => 200]);
            } else {
                return Response::json(['message' => 'Password Tidak Sesuai.', 'code' => 400]);
            }
        }

        return Response::json(['message' => 'Data Pos Di Kualitas Air Tidak Di Temukan.', 'code' => 404]);
    }
}
