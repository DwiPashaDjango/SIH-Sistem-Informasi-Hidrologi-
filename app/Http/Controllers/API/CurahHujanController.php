<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseFormatter;
use App\Models\CurahHujan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CurahHujanController extends Controller
{
    public function index($id)
    {
        $data = CurahHujan::where('pos_id', $id)->paginate(25);

        return ResponseFormatter::success(
            $data,
            'Data List Curah Hujan berhasil diambil'
        );
    }

    public function semuadata($id)
    {
        if (@$_GET['tgl_awal'] != '' && @$_GET['tgl_akhir'] != '') {
            $data = DB::table('curah_hujans')->select('curah_hujans.*', 'posts.nama as nama_pos')
                ->join('posts', 'curah_hujans.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->where('curah_hujans.tanggal', '>=', @$_GET['tgl_awal'])
                ->where('curah_hujans.tanggal', '<=', @$_GET['tgl_akhir'])
                ->orderBy('curah_hujans.id', 'DESC')
                ->paginate(25);
        } else {
            $data = DB::table('curah_hujans')->select('curah_hujans.*', 'posts.nama as nama_pos')
                ->join('posts', 'curah_hujans.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->orderBy('curah_hujans.id', 'DESC')
                ->paginate(25);
        }

        return ResponseFormatter::success(
            $data,
            'Data List Curah Hujan berhasil diambil'
        );
    }

    public function semuadatafull($id)
    {
        if (@$_GET['tgl_awal'] != '' && @$_GET['tgl_akhir'] != '') {
            $data = DB::table('curah_hujans')->select('curah_hujans.*', 'posts.nama as nama_pos')
                ->join('posts', 'curah_hujans.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->where('curah_hujans.tanggal', '>=', @$_GET['tgl_awal'])
                ->where('curah_hujans.tanggal', '<=', @$_GET['tgl_akhir'])
                ->orderBy('curah_hujans.id', 'DESC')
                ->get();
        } else {
            $data = DB::table('curah_hujans')->select('curah_hujans.*', 'posts.nama as nama_pos')
                ->join('posts', 'curah_hujans.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->orderBy('curah_hujans.id', 'DESC')
                ->get();
        }

        return ResponseFormatter::success(
            $data,
            'Data List Curah Hujan berhasil diambil'
        );
    }

    public function detail($id)
    {
        $data = CurahHujan::where('id', $id)
            ->with('pos')
            ->get();

        return ResponseFormatter::success(
            $data,
            'Data Detail Curah Hujan berhasil diambil'
        );
    }

    public function filter($id, $startDate, $endDate)
    {
        $data = CurahHujan::where('pos_id', $id)
            ->with('pos')
            ->where('tanggal', '>=', $startDate)
            ->where('tanggal', '<=', $endDate)
            ->paginate(25);

        return ResponseFormatter::success(
            $data,
            'Data List Curah Hujan berhasil diambil'
        );
    }

    public function filterAll($id, $startDate, $endDate)
    {
        $data = CurahHujan::where('pos_id', $id)
            ->with('pos')
            ->where('tanggal', '>=', $startDate)
            ->where('tanggal', '<=', $endDate)
            ->orderBy('tanggal', 'asc')
            ->get();

        return ResponseFormatter::success(
            $data,
            'Data List Curah Hujan berhasil diambil'
        );
    }

    public function dayNowByPosId($id)
    {
        $data = CurahHujan::where('pos_id', $id)
            ->where('tanggal', '=', Carbon::today())
            ->first();

        return ResponseFormatter::success(
            $data,
            'Data CurahHujan Detail berhasil diambil'
        );
    }

    public function filterDayNow()
    {
        $data = CurahHujan::where('tanggal', '=', Carbon::today())->get();

        return ResponseFormatter::success(
            $data,
            'Data List Curah Hujan berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'hujan_otomatis' => 'required|numeric',
            'hujan_biasa' => 'required|numeric',
            'pos_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'Tambah Curah Hujan Gagal',
                401
            );
        }

        $curah_hujan = CurahHujan::where('pos_id', $request->pos_id)
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($curah_hujan) {
            if ($request->hujan_otomatis !== '0') {
                $curah_hujan->hujan_otomatis = $request->hujan_otomatis;
            }

            if ($request->hujan_biasa !== '0') {
                $curah_hujan->hujan_biasa = $request->hujan_biasa;
            }

            if ($request->keterangan) {
                $curah_hujan->keterangan = $request->keterangan;
            }

            $curah_hujan->update();

            return ResponseFormatter::success(
                $curah_hujan,
                'Curah Hujan sukses dibuat'
            );
        } else {
            $data = CurahHujan::create([
                'tanggal' => $request->tanggal,
                'hujan_otomatis' => $request->hujan_otomatis,
                'hujan_biasa' => $request->hujan_biasa,
                'keterangan' => $request->keterangan,
                'pos_id' => $request->pos_id,
            ]);

            return ResponseFormatter::success(
                $data,
                'Curah Hujan sukses dibuat'
            );
        }
    }

    public function delete($id)
    {
        $data = CurahHujan::where('id', $id)->first();

        if (!$data) {
            return ResponseFormatter::error('Delete CurahHujan Gagal', 401);
        }

        $data->delete();

        return [
            'message' => 'Data CurahHujan Sukses Dihapus',
        ];
    }

    public function getYearly($id, $year, $type)
    {
        $result = CurahHujan::select('tanggal', $type)
            ->where('pos_id', $id)
            ->whereYear('tanggal', $year)
            ->get();

        return ResponseFormatter::success(
            $result,
            'Data List Curah Hujan berhasil diambil'
        );
    }

    public function batch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data.*.tanggal' => 'required|date',
            'data.*.hujan_otomatis' => 'numeric|nullable',
            'data.*.hujan_biasa' => 'numeric|nullable',
            'data.*.pos_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'Tambah Curah Hujan Gagal',
                401
            );
        }
        $dat = $request->input('data');

        $reformat = array_map(function ($key) {
            $now = Carbon::now()->toDateTimeString();
            $arr = [
                'tanggal' => $key['tanggal'],
                'pos_id' => $key['pos_id'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if (isset($key['hujan_otomatis'])) {
                $arr['hujan_otomatis'] = $key['hujan_otomatis'];
            }
            if (isset($key['hujan_biasa'])) {
                $arr['hujan_biasa'] = $key['hujan_biasa'];
            }
            if (isset($key['keterangan'])) {
                $arr['keterangan'] = $key['keterangan'];
            }
            return $arr;
        }, $dat);

        foreach ($reformat as $row) {
            $val = CurahHujan::where('pos_id', $row['pos_id'])
                ->where('tanggal', date('y-m-d', strtotime($row['tanggal'])))
                ->first();
            if ($val) {
                unset($row['created_at']);
                CurahHujan::where('id', $val->id)->update($row);
            } else {
                CurahHujan::create($row);
            }
        }
        return ResponseFormatter::success(
            $reformat,
            'Curah Hujan sukses dibuat'
        );
    }
}
