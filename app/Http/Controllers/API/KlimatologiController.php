<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseFormatter;
use App\Models\Klimatologi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KlimatologiController extends Controller
{
    public function index($id)
    {
        $data = Klimatologi::where('pos_id', $id)->paginate(25);

        return ResponseFormatter::success(
            $data,
            'Data List Klimatologi berhasil diambil'
        );
    }

    public function semuadata($id)
    {
        if (@$_GET['tgl_awal'] != '' && @$_GET['tgl_akhir'] != '') {
            $data = DB::table('klimatologis')->select('klimatologis.*', 'posts.nama as nama_pos')
                ->join('posts', 'klimatologis.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->where('klimatologis.tanggal', '>=', @$_GET['tgl_awal'])
                ->where('klimatologis.tanggal', '<=', @$_GET['tgl_akhir'])
                ->orderBy('klimatologis.id', 'DESC')
                ->paginate(25);
        } else {
            $data = DB::table('klimatologis')->select('klimatologis.*', 'posts.nama as nama_pos')
                ->join('posts', 'klimatologis.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->orderBy('klimatologis.id', 'DESC')
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
            $data = DB::table('klimatologis')->select('klimatologis.*', 'posts.nama as nama_pos')
                ->join('posts', 'klimatologis.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->where('klimatologis.tanggal', '>=', @$_GET['tgl_awal'])
                ->where('klimatologis.tanggal', '<=', @$_GET['tgl_akhir'])
                ->orderBy('klimatologis.id', 'DESC')
                ->get();
        } else {
            $data = DB::table('klimatologis')->select('klimatologis.*', 'posts.nama as nama_pos')
                ->join('posts', 'klimatologis.pos_id', 'posts.id')
                ->where('posts.jenis_id', $id)
                ->orderBy('klimatologis.id', 'DESC')
                ->get();
        }

        return ResponseFormatter::success(
            $data,
            'Data List TMA berhasil diambil'
        );
    }

    public function filter($id, $startDate, $endDate)
    {
        $data = Klimatologi::where('pos_id', $id)->with('pos')->where('tanggal', '>=', $startDate)->where('tanggal', '<=', $endDate)->paginate(25);

        return ResponseFormatter::success(
            $data,
            'Data List Klimatologi berhasil diambil'
        );
    }

    public function detail($id)
    {
        $data = Klimatologi::where('id', $id)->with('pos')->get();

        return ResponseFormatter::success(
            $data,
            'Data Detail Klimatologi berhasil diambil'
        );
    }

    public function filterAll($id, $startDate, $endDate)
    {
        $data = Klimatologi::where('pos_id', $id)->with('pos')->where('tanggal', '>=', $startDate)->where('tanggal', '<=', $endDate)->orderBy('tanggal', 'asc')->get();

        return ResponseFormatter::success(
            $data,
            'Data List Klimatologi berhasil diambil'
        );
    }

    public function dayNowByPosId($id)
    {
        $data = Klimatologi::where('pos_id', $id)->where('tanggal', '=', Carbon::today())->first();

        return ResponseFormatter::success(
            $data,
            'Data Klimatologi Detail berhasil diambil'
        );
    }

    public function filterDayNow()
    {
        $data = Klimatologi::where('tanggal', '=', Carbon::today())->get();

        return ResponseFormatter::success(
            $data,
            'Data List Klimatologi berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'pos_id' => 'required',
            'termo_max_pagi' => 'numeric',
            'termo_max_siang' => 'numeric',
            'termo_max_sore' => 'numeric',
            'termo_min_pagi' => 'numeric',
            'termo_min_siang' => 'numeric',
            'termo_min_sore' => 'numeric',
            'bola_kering_pagi' => 'numeric',
            'bola_kering_siang' => 'numeric',
            'bola_kering_sore' => 'numeric',
            'bola_basah_pagi' => 'numeric',
            'bola_basah_siang' => 'numeric',
            'bola_basah_sore' => 'numeric',
            'rh' => 'numeric',
            'termo_apung_max' => 'numeric',
            'termo_apung_min' => 'numeric',
            'penguapan_plus' => 'numeric',
            'penguapan_min' => 'numeric',
            'anemometer_spedometer' => 'numeric',
            'hujan_otomatis' => 'numeric',
            'hujan_biasa' => 'numeric',
            'sinar_matahari' => 'numeric',
        ]);


        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Tambah Klimatologi Gagal', 401);
        }

        $klimatologi = Klimatologi::where('pos_id', $request->pos_id)->where('tanggal', $request->tanggal)->first();

        if ($klimatologi) {

            if ($request->termo_max_pagi !== "0") {
                $klimatologi->termo_max_pagi = $request->termo_max_pagi;
            }

            if ($request->termo_max_siang !== "0") {
                $klimatologi->termo_max_siang = $request->termo_max_siang;
            }

            if ($request->termo_max_sore !== "0") {
                $klimatologi->termo_max_sore = $request->termo_max_sore;
            }

            if ($request->termo_min_pagi !== "0") {
                $klimatologi->termo_min_pagi = $request->termo_min_pagi;
            }

            if ($request->termo_min_siang !== "0") {
                $klimatologi->termo_min_siang = $request->termo_min_siang;
            }

            if ($request->termo_min_sore !== "0") {
                $klimatologi->termo_min_sore = $request->termo_min_sore;
            }

            if ($request->bola_kering_pagi !== "0") {
                $klimatologi->bola_kering_pagi = $request->bola_kering_pagi;
            }

            if ($request->bola_kering_siang !== "0") {
                $klimatologi->bola_kering_siang = $request->bola_kering_siang;
            }

            if ($request->bola_kering_sore !== "0") {
                $klimatologi->bola_kering_sore = $request->bola_kering_sore;
            }

            if ($request->bola_basah_pagi !== "0") {
                $klimatologi->bola_basah_pagi = $request->bola_basah_pagi;
            }

            if ($request->bola_basah_siang !== "0") {
                $klimatologi->bola_basah_siang = $request->bola_basah_siang;
            }

            if ($request->bola_basah_sore !== "0") {
                $klimatologi->bola_basah_sore = $request->bola_basah_sore;
            }

            if ($request->rh) {
                $klimatologi->rh = $request->rh;
            }

            if ($request->termo_apung_max) {
                $klimatologi->termo_apung_max = $request->termo_apung_max;
            }

            if ($request->termo_apung_min) {
                $klimatologi->termo_apung_min = $request->termo_apung_min;
            }

            if ($request->penguapan_plus) {
                $klimatologi->penguapan_plus = $request->penguapan_plus;
            }

            if ($request->penguapan_min) {
                $klimatologi->penguapan_min = $request->penguapan_min;
            }


            if ($request->anemometer_spedometer) {
                $klimatologi->anemometer_spedometer = $request->anemometer_spedometer;
            }

            if ($request->hujan_otomatis) {
                $klimatologi->hujan_otomatis = $request->hujan_otomatis;
            }

            if ($request->hujan_biasa) {
                $klimatologi->hujan_biasa = $request->hujan_biasa;
            }

            if ($request->sinar_matahari) {
                $klimatologi->sinar_matahari = $request->sinar_matahari;
            }

            if ($request->keterangan) {
                $klimatologi->keterangan = $request->keterangan;
            }

            $klimatologi->update();

            return ResponseFormatter::success($klimatologi, 'Klimatologi sukses dibuat');
        } else {
            $data = Klimatologi::create([
                'tanggal' => $request->tanggal,

                'termo_max_pagi' => $request->termo_max_pagi,
                'termo_max_siang' => $request->termo_max_siang,
                'termo_max_sore' => $request->termo_max_sore,

                'termo_min_pagi' => $request->termo_min_pagi,
                'termo_min_siang' => $request->termo_min_siang,
                'termo_min_sore' => $request->termo_min_sore,

                'bola_kering_pagi' => $request->bola_kering_pagi,
                'bola_kering_siang' => $request->bola_kering_siang,
                'bola_kering_sore' => $request->bola_kering_sore,

                'bola_basah_pagi' => $request->bola_basah_pagi,
                'bola_basah_siang' => $request->bola_basah_siang,
                'bola_basah_sore' => $request->bola_basah_sore,

                'rh' => $request->rh,

                'termo_apung_max' => $request->termo_apung_max,
                'termo_apung_min' => $request->termo_apung_min,

                'penguapan_plus' => $request->penguapan_plus,
                'penguapan_min' => $request->penguapan_min,
                'anemometer_spedometer' => $request->anemometer_spedometer,

                'hujan_otomatis' => $request->hujan_otomatis,
                'hujan_biasa' => $request->hujan_biasa,
                'sinar_matahari' => $request->sinar_matahari,
                'keterangan' => $request->keterangan,
                'pos_id' => $request->pos_id,
            ]);

            return ResponseFormatter::success($data, 'Klimatologi sukses dibuat');
        }
    }

    public function delete($id)
    {
        $data = Klimatologi::where('id', $id)->first();

        if (!$data) {
            return ResponseFormatter::error('Delete Klimatologi Gagal', 401);
        }

        $data->delete();

        return [
            'message' => 'Data Klimatologi Sukses Dihapus'
        ];
    }

    public function batch(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'data.*.tanggal' => 'required|date',
            'data.*.pos_id' => 'required',
            'data.*.termo_max_pagi' => 'numeric|nullable',
            'data.*.termo_max_siang' => 'numeric|nullable',
            'data.*.termo_max_sore' => 'numeric|nullable',
            'data.*.termo_min_pagi' => 'numeric|nullable',
            'data.*.termo_min_siang' => 'numeric|nullable',
            'data.*.termo_min_sore' => 'numeric|nullable',
            'data.*.bola_kering_pagi' => 'numeric|nullable',
            'data.*.bola_kering_siang' => 'numeric|nullable',
            'data.*.bola_kering_sore' => 'numeric|nullable',
            'data.*.bola_basah_pagi' => 'numeric|nullable',
            'data.*.bola_basah_siang' => 'numeric|nullable',
            'data.*.bola_basah_sore' => 'numeric|nullable',
            'data.*.rh' => 'numeric|nullable',
            'data.*.termo_apung_max' => 'numeric|nullable',
            'data.*.termo_apung_min' => 'numeric|nullable',
            'data.*.penguapan_plus' => 'numeric|nullable',
            'data.*.penguapan_min' => 'numeric|nullable',
            'data.*.anemometer_spedometer' => 'numeric|nullable',
            'data.*.hujan_otomatis' => 'numeric|nullable',
            'data.*.hujan_biasa' => 'numeric|nullable',
            'data.*.sinar_matahari' => 'numeric|nullable',
            'data.*.keterangan' => 'nullable',
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
            if (isset($key['termo_max_pagi'])) {
                $arr['termo_max_pagi'] = $key['termo_max_pagi'];
            }
            if (isset($key['termo_max_siang'])) {
                $arr['termo_max_siang'] = $key['termo_max_siang'];
            }
            if (isset($key['termo_max_sore'])) {
                $arr['termo_max_sore'] = $key['termo_max_sore'];
            }
            if (isset($key['termo_min_pagi'])) {
                $arr['termo_min_pagi'] = $key['termo_min_pagi'];
            }
            if (isset($key['termo_min_siang'])) {
                $arr['termo_min_siang'] = $key['termo_min_siang'];
            }
            if (isset($key['termo_min_sore'])) {
                $arr['termo_min_sore'] = $key['termo_min_sore'];
            }
            if (isset($key['bola_kering_pagi'])) {
                $arr['bola_kering_pagi'] = $key['bola_kering_pagi'];
            }
            if (isset($key['bola_kering_siang'])) {
                $arr['bola_kering_siang'] = $key['bola_kering_siang'];
            }
            if (isset($key['bola_kering_sore'])) {
                $arr['bola_kering_sore'] = $key['bola_kering_sore'];
            }
            if (isset($key['bola_basah_pagi'])) {
                $arr['bola_basah_pagi'] = $key['bola_basah_pagi'];
            }
            if (isset($key['bola_basah_siang'])) {
                $arr['bola_basah_siang'] = $key['bola_basah_siang'];
            }
            if (isset($key['bola_basah_sore'])) {
                $arr['bola_basah_sore'] = $key['bola_basah_sore'];
            }
            if (isset($key['rh'])) {
                $arr['rh'] = $key['rh'];
            }
            if (isset($key['termo_apung_max'])) {
                $arr['termo_apung_max'] = $key['termo_apung_max'];
            }
            if (isset($key['termo_apung_min'])) {
                $arr['termo_apung_min'] = $key['termo_apung_min'];
            }
            if (isset($key['penguapan_plus'])) {
                $arr['penguapan_plus'] = $key['penguapan_plus'];
            }
            if (isset($key['penguapan_min'])) {
                $arr['penguapan_min'] = $key['penguapan_min'];
            }
            if (isset($key['anemometer_spedometer'])) {
                $arr['anemometer_spedometer'] = $key['anemometer_spedometer'];
            }
            if (isset($key['hujan_otomatis'])) {
                $arr['hujan_otomatis'] = $key['hujan_otomatis'];
            }
            if (isset($key['hujan_biasa'])) {
                $arr['hujan_biasa'] = $key['hujan_biasa'];
            }
            if (isset($key['sinar_matahari'])) {
                $arr['sinar_matahari'] = $key['sinar_matahari'];
            }
            if (isset($key['keterangan'])) {
                $arr['keterangan'] = $key['keterangan'];
            }
            return $arr;
        }, $dat);

        foreach ($reformat as $row) {
            $val = Klimatologi::where('pos_id', $row['pos_id'])
                ->where('tanggal', date('y-m-d', strtotime($row['tanggal'])))
                ->first();
            if ($val) {
                unset($row['created_at']);
                Klimatologi::where('id', $val->id)->update($row);
            } else {
                Klimatologi::create($row);
            }
        }
        return ResponseFormatter::success(
            $reformat,
            'Curah Hujan sukses dibuat'
        );
    }
}
