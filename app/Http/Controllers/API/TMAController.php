<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\TMA;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TMAController extends Controller
{
    public function index($id)
    {
        $data = TMA::where('pos_id', $id)->paginate(25);

        return ResponseFormatter::success(
            $data,
            'Data List TMA berhasil diambil'
        );
    }

    public function semuadata($id)
    {
        if (@$_GET['tgl_awal'] != '' && @$_GET['tgl_akhir'] != '') {
            $data = DB::table('t_m_a_s')->select('t_m_a_s.*', 'posts.nama as nama_pos')
                ->join('posts', 't_m_a_s.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->where('t_m_a_s.tanggal', '>=', @$_GET['tgl_awal'])
                ->where('t_m_a_s.tanggal', '<=', @$_GET['tgl_akhir'])
                ->orderBy('t_m_a_s.id', 'DESC')
                ->paginate(25);
        } else {
            $data = DB::table('t_m_a_s')->select('t_m_a_s.*', 'posts.nama as nama_pos')
                ->join('posts', 't_m_a_s.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->orderBy('t_m_a_s.id', 'DESC')
                ->paginate(25);
        }

        return ResponseFormatter::success(
            $data,
            'Data List TMA berhasil diambil'
        );
    }

    public function semuadatafull($id)
    {
        if (@$_GET['tgl_awal'] != '' && @$_GET['tgl_akhir'] != '') {
            $data = DB::table('t_m_a_s')->select('t_m_a_s.*', 'posts.nama as nama_pos')
                ->join('posts', 't_m_a_s.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->where('t_m_a_s.tanggal', '>=', @$_GET['tgl_awal'])
                ->where('t_m_a_s.tanggal', '<=', @$_GET['tgl_akhir'])
                ->orderBy('t_m_a_s.id', 'DESC')
                ->get();
        } else {
            $data = DB::table('t_m_a_s')->select('t_m_a_s.*', 'posts.nama as nama_pos')
                ->join('posts', 't_m_a_s.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->orderBy('t_m_a_s.id', 'DESC')
                ->get();
        }

        return ResponseFormatter::success(
            $data,
            'Data List TMA berhasil diambil'
        );
    }

    public function detail($id)
    {
        $data = TMA::where('id', $id)
            ->with('pos')
            ->get();

        return ResponseFormatter::success(
            $data,
            'Data Detail TMA berhasil diambil'
        );
    }

    public function filter($id, $startDate, $endDate)
    {
        $data = TMA::where('pos_id', $id)
            ->with('pos')
            ->where('tanggal', '>=', $startDate)
            ->where('tanggal', '<=', $endDate)
            ->paginate(25);

        return ResponseFormatter::success(
            $data,
            'Data List TMA berhasil diambil'
        );
    }

    public function filterAll($id, $startDate, $endDate)
    {
        $data = TMA::where('pos_id', $id)
            ->with('pos')
            ->where('tanggal', '>=', $startDate)
            ->where('tanggal', '<=', $endDate)
            ->orderBy('tanggal', 'asc')
            ->get();

        return ResponseFormatter::success(
            $data,
            'Data List TMA berhasil diambil'
        );
    }

    public function dayNowByPosId($id)
    {
        $data = TMA::where('pos_id', $id)
            ->where('tanggal', '=', Carbon::today())
            ->first();

        return ResponseFormatter::success(
            $data,
            'Data TMA Detail berhasil diambil'
        );
    }

    public function filterDayNow()
    {
        $data = TMA::where('tanggal', '=', Carbon::today())->get();

        return ResponseFormatter::success(
            $data,
            'Data List TMA berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'pagi' => 'required|numeric',
            'siang' => 'required|numeric',
            'sore' => 'required|numeric',
            'pos_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'Tambah Tinggi Muka Air Gagal',
                401
            );
        }

        $tma = TMA::where('pos_id', $request->pos_id)
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($tma) {
            if ($request->pagi !== '0') {
                $tma->pagi = $request->pagi;
            }

            if ($request->siang !== '0') {
                $tma->siang = $request->siang;
            }

            if ($request->sore !== '0') {
                $tma->sore = $request->sore;
            }

            if ($request->keterangan) {
                $tma->keterangan = $request->keterangan;
            }

            $tma->update();

            return ResponseFormatter::success(
                $tma,
                'Tinggi Muka Air sukses dibuat'
            );
        } else {
            $data = TMA::create([
                'tanggal' => $request->tanggal,
                'pagi' => $request->pagi,
                'siang' => $request->siang,
                'sore' => $request->sore,
                'keterangan' => $request->keterangan,
                'pos_id' => $request->pos_id,
            ]);

            return ResponseFormatter::success(
                $data,
                'Tinggi Muka Air sukses dibuat'
            );
        }
    }

    public function delete($id)
    {
        $data = TMA::where('id', $id)->first();

        if (!$data) {
            return ResponseFormatter::error('Delete TMA Gagal', 401);
        }

        $data->delete();

        return [
            'message' => 'Data TMA Sukses Dihapus',
        ];
    }

    public function getYearly($id, $year, $type)
    {
        $result = TMA::select('tanggal', $type)
            ->where('pos_id', $id)
            ->whereYear('tanggal', $year)
            ->get();

        $arr = [];

        foreach ($result as $row) {
            $val = [
                'tanggal' => $row->tanggal,
                $type => round($row->$type, 2),
            ];
            array_push($arr, $val);
        }

        return ResponseFormatter::success(
            $arr,
            'Data List Tingi Muka Air berhasil diambil'
        );
    }

    public function batch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data.*.tanggal' => 'required|date',
            'data.*.pagi' => 'numeric|nullable',
            'data.*.siang' => 'numeric|nullable',
            'data.*.sore' => 'numeric|nullable',
            'data.*.pos_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'Tambah TMA Gagal',
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
            if (isset($key['pagi'])) {
                $arr['pagi'] = $key['pagi'];
            }
            if (isset($key['siang'])) {
                $arr['siang'] = $key['siang'];
            }
            if (isset($key['sore'])) {
                $arr['sore'] = $key['sore'];
            }
            if (isset($key['keterangan'])) {
                $arr['keterangan'] = $key['keterangan'];
            }
            return $arr;
        }, $dat);

        foreach ($reformat as $row) {
            $val = TMA::where('pos_id', $row['pos_id'])
                ->where('tanggal', date('y-m-d', strtotime($row['tanggal'])))
                ->first();
            if ($val) {
                unset($row['created_at']);
                TMA::where('id', $val->id)->update($row);
            } else {
                TMA::create($row);
            }
        }

        return ResponseFormatter::success(
            $reformat,
            'Tinggi Muka Air sukses dibuat'
        );
    }
}
