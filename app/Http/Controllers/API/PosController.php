<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\CurahHujan;
use App\Models\Klimatologi;
use App\Models\PosQuality;
use App\Models\Post;
use App\Models\Regency;
use App\Models\TMA;
use App\Models\WaterQuality;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PosController extends Controller
{
    public function index(Request $request, $id)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $pos = Post::where('nama', 'LIKE', "%$keyword%")
                ->orWhere('lokasi', 'LIKE', "%$keyword%")
                ->orWhere('kabupaten', 'LIKE', "%$keyword%")
                ->orWhere('provinsi', 'LIKE', "%$keyword%")
                ->paginate(100);
        } else {
            $pos = Post::where('jenis_id', $id)->paginate($perPage);
        }


        return ResponseFormatter::success(
            $pos,
            'Data list post berhasil diambil'
        );
    }

    public function titikhujan($id_pos, $tanggal)
    {
        $data = CurahHujan::where('pos_id', $id_pos)->where('tanggal', $tanggal)->get();
        $normal = 0;
        $waspada = 0;
        $siaga = 0;
        $awas = 0;
        foreach ($data as $item) {
            $pos = Post::where('id', $item->pos_id)->first();
            $hujan_otomatis = 0;
            $hujan_biasa = 0;
            $status = "Normal";

            $hujan_otomatis = $item->hujan_otomatis;
            $hujan_biasa = $item->hujan_biasa;

            if ($hujan_biasa <= $pos->normal) {
                $normal++;
            } else if ($hujan_biasa <= $pos->waspada) {
                $waspada++;
            } else if ($hujan_biasa <= $pos->siaga) {
                $siaga++;
            } else if ($hujan_biasa <= $pos->awas) {
                $awas++;
            } else {
                $awas++;
            }
        }

        $array = ['tanggal' => $tanggal, 'normal' => $normal, 'waspada' => $waspada, 'siaga' => $siaga, 'awas' => $awas];

        return ResponseFormatter::success(
            $array,
            'Data list post berhasil diambil'
        );
    }

    public function titikmuka($id_pos, $tanggal)
    {
        $jamSekarang = Carbon::now()->format('H');
        $data = TMA::where('pos_id', $id_pos)->where('tanggal', '=', $tanggal)->get();
        $normal = 0;
        $waspada = 0;
        $siaga = 0;
        $awas = 0;
        foreach ($data as $item) {
            $value = Post::where('id', $item->pos_id)->first();
            $nilai = 0;
            $status = "Normal";

            if (!empty($data)) {
                if ($jamSekarang >= 17) {
                    $nilai = $item->sore;
                } else if ($jamSekarang >= 12) {
                    $nilai = $item->siang;
                } else {
                    $nilai = $item->pagi;
                }

                if ($nilai <= $value->normal) {
                    $normal++;
                } else if ($nilai <= $value->waspada) {
                    $waspada++;
                } else if ($nilai <= $value->siaga) {
                    $siaga++;
                } else if ($nilai <= $value->awas) {
                    $awas++;
                } else {
                    $awas++;
                }
            }
        }

        $array = ['tanggal' => $tanggal, 'normal' => $normal, 'waspada' => $waspada, 'siaga' => $siaga, 'awas' => $awas];

        return ResponseFormatter::success(
            $array,
            'Data list post berhasil diambil'
        );
    }

    public function full($id)
    {
        $pos = Post::where('jenis_id', $id)->get();
        $jamSekarang = Carbon::now()->format('H');

        $newPos = array();
        foreach ($pos as $value) {
            if ($id == 2) {
                $data = TMA::where('pos_id', $value->id)->where('tanggal', '=', Carbon::today())->first();
                //  $data = TMA::where('pos_id', $value->id)->orderBy('created_at','desc')->first();
                $nilai = 0;
                $status = "Normal";

                if (!empty($data)) {
                    if ($jamSekarang >= 17) {
                        $nilai = $data->sore;
                    } else if ($jamSekarang >= 12) {
                        $nilai = $data->siang;
                    } else {
                        $nilai = $data->pagi;
                    }

                    if ($nilai <= $value->normal) {
                        $status = "Normal";
                    } else if ($nilai <= $value->waspada) {
                        $status = "Waspada";
                    } else if ($nilai <= $value->siaga) {
                        $status = "Siaga";
                    } else if ($nilai <= $value->awas) {
                        $status = "Awas";
                    } else {
                        $status = "Awas";
                    }
                }

                array_push($newPos, [
                    'id' => $value->id,
                    'nama' => $value->nama,
                    'koordinatx' => $value->koordinatx,
                    'koordinaty' => $value->koordinaty,
                    'kabupaten' => $value->kabupaten,
                    'provinsi' => $value->provinsi,
                    'lokasi' => $value->lokasi,
                    'jenis_id' => $value->jenis_id,
                    'gambar' => $value->gambar,
                    'normal' => $value->normal,
                    'waspada' => $value->waspada,
                    'siaga' => $value->siaga,
                    'awas' => $value->awas,
                    'nilai' => $nilai,
                    'status' => $status
                ]);
            } else if ($id == 1) {
                // $data = CurahHujan::where('pos_id', $value->id)->where('tanggal', '=', Carbon::today())->first();
                $data = CurahHujan::where('pos_id', $value->id)->orderBy('created_at', 'desc')->first();
                $hujan_otomatis = 0;
                $hujan_biasa = 0;
                $status = "Normal";

                if (!empty($data)) {
                    $hujan_otomatis = $data->hujan_otomatis;
                    $hujan_biasa = $data->hujan_biasa;
                }

                if ($hujan_biasa <= $value->normal) {
                    $status = "Normal";
                } else if ($hujan_biasa <= $value->waspada) {
                    $status = "Waspada";
                } else if ($hujan_biasa <= $value->siaga) {
                    $status = "Siaga";
                } else if ($hujan_biasa <= $value->awas) {
                    $status = "Awas";
                } else {
                    $status = "Awas";
                }

                array_push($newPos, [
                    'id' => $value->id,
                    'nama' => $value->nama,
                    'koordinatx' => $value->koordinatx,
                    'koordinaty' => $value->koordinaty,
                    'kabupaten' => $value->kabupaten,
                    'provinsi' => $value->provinsi,
                    'lokasi' => $value->lokasi,
                    'jenis_id' => $value->jenis_id,
                    'gambar' => $value->gambar,
                    'normal' => $value->normal,
                    'waspada' => $value->waspada,
                    'siaga' => $value->siaga,
                    'awas' => $value->awas,
                    'hujan_biasa' => $hujan_biasa,
                    'hujan_otomatis' => $hujan_otomatis,
                    'nilai' => $hujan_biasa,
                    'status' => $status
                ]);
            } else {

                $data = Klimatologi::where('pos_id', $value->id)->where('tanggal', '=', Carbon::today())->first();
                // $data = Klimatologi::where('pos_id', $value->id)->orderBy('created_at','desc')->first();
                $hujan_otomatis = 0;
                $hujan_biasa = 0;

                if (!empty($data)) {
                    $hujan_otomatis = $data->hujan_otomatis;
                    $hujan_biasa = $data->hujan_biasa;
                }

                array_push($newPos, [
                    'id' => $value->id,
                    'nama' => $value->nama,
                    'koordinatx' => $value->koordinatx,
                    'koordinaty' => $value->koordinaty,
                    'kabupaten' => $value->kabupaten,
                    'provinsi' => $value->provinsi,
                    'lokasi' => $value->lokasi,
                    'jenis_id' => $value->jenis_id,
                    'gambar' => $value->gambar,
                    'normal' => $value->normal,
                    'waspada' => $value->waspada,
                    'siaga' => $value->siaga,
                    'awas' => $value->awas,
                    'hujan_biasa' => $hujan_biasa,
                    'hujan_otomatis' => $hujan_otomatis,
                ]);
            }
        }

        return ResponseFormatter::success(
            $newPos,
            'Data list post berhasil diambil'
        );
    }

    public function semua()
    {
        $pos = Post::all();
        $jamSekarang = Carbon::now()->format('H');

        $newPos = array();
        foreach ($pos as $value) {
            if ($value->jenis_id == '2') {
                $data = TMA::where('pos_id', $value->id)->where('tanggal', '=', Carbon::today())->first();
                //  $data = TMA::where('pos_id', $value->id)->orderBy('created_at','desc')->first();
                $nilai = 0;
                $status = "Normal";

                if (!empty($data)) {
                    if ($jamSekarang >= 17) {
                        $nilai = $data->sore;
                    } else if ($jamSekarang >= 12) {
                        $nilai = $data->siang;
                    } else {
                        $nilai = $data->pagi;
                    }

                    if ($nilai <= $value->normal) {
                        $status = "Normal";
                    } else if ($nilai <= $value->waspada) {
                        $status = "Waspada";
                    } else if ($nilai <= $value->siaga) {
                        $status = "Siaga";
                    } else if ($nilai <= $value->awas) {
                        $status = "Awas";
                    } else {
                        $status = "Awas";
                    }
                }

                array_push($newPos, [
                    'id' => $value->id,
                    'nama' => $value->nama,
                    'koordinatx' => $value->koordinatx,
                    'koordinaty' => $value->koordinaty,
                    'kabupaten' => $value->kabupaten,
                    'provinsi' => $value->provinsi,
                    'lokasi' => $value->lokasi,
                    'jenis_id' => $value->jenis_id,
                    'gambar' => $value->gambar,
                    'normal' => $value->normal,
                    'waspada' => $value->waspada,
                    'siaga' => $value->siaga,
                    'awas' => $value->awas,
                    'nilai' => $nilai,
                    'status' => $status
                ]);
            } else if ($value->jenis_id == '1') {
                // $data = CurahHujan::where('pos_id', $value->id)->where('tanggal', '=', Carbon::today())->first();
                $data = CurahHujan::where('pos_id', $value->id)->orderBy('created_at', 'desc')->first();
                $hujan_otomatis = 0;
                $hujan_biasa = 0;
                $status = "Normal";

                if (!empty($data)) {
                    $hujan_otomatis = $data->hujan_otomatis;
                    $hujan_biasa = $data->hujan_biasa;
                }

                if ($hujan_biasa <= $value->normal) {
                    $status = "Normal";
                } else if ($hujan_biasa <= $value->waspada) {
                    $status = "Waspada";
                } else if ($hujan_biasa <= $value->siaga) {
                    $status = "Siaga";
                } else if ($hujan_biasa <= $value->awas) {
                    $status = "Awas";
                } else {
                    $status = "Awas";
                }

                array_push($newPos, [
                    'id' => $value->id,
                    'nama' => $value->nama,
                    'koordinatx' => $value->koordinatx,
                    'koordinaty' => $value->koordinaty,
                    'kabupaten' => $value->kabupaten,
                    'provinsi' => $value->provinsi,
                    'lokasi' => $value->lokasi,
                    'jenis_id' => $value->jenis_id,
                    'gambar' => $value->gambar,
                    'normal' => $value->normal,
                    'waspada' => $value->waspada,
                    'siaga' => $value->siaga,
                    'awas' => $value->awas,
                    'hujan_biasa' => $hujan_biasa,
                    'hujan_otomatis' => $hujan_otomatis,
                    'nilai' => $hujan_biasa,
                    'status' => $status
                ]);
            } else {

                $data = Klimatologi::where('pos_id', $value->id)->where('tanggal', '=', Carbon::today())->first();
                // $data = Klimatologi::where('pos_id', $value->id)->orderBy('created_at','desc')->first();
                $hujan_otomatis = 0;
                $hujan_biasa = 0;

                if (!empty($data)) {
                    $hujan_otomatis = $data->hujan_otomatis;
                    $hujan_biasa = $data->hujan_biasa;
                }

                array_push($newPos, [
                    'id' => $value->id,
                    'nama' => $value->nama,
                    'koordinatx' => $value->koordinatx,
                    'koordinaty' => $value->koordinaty,
                    'kabupaten' => $value->kabupaten,
                    'provinsi' => $value->provinsi,
                    'lokasi' => $value->lokasi,
                    'jenis_id' => $value->jenis_id,
                    'gambar' => $value->gambar,
                    'normal' => $value->normal,
                    'waspada' => $value->waspada,
                    'siaga' => $value->siaga,
                    'awas' => $value->awas,
                    'hujan_biasa' => $hujan_biasa,
                    'hujan_otomatis' => $hujan_otomatis,
                ]);
            }
        }

        return ResponseFormatter::success(
            $newPos,
            'Data list post berhasil diambil'
        );
    }

    public function detail($id)
    {
        $pos = Post::where('id', $id)->with('jenis')->first();

        if (!$pos) {
            return ResponseFormatter::error('Detail Pos Tidak ada', 401);
        }

        return ResponseFormatter::success(
            $pos,
            'Data detail post berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'koordinatx' => 'string|max:255',
            'koordinaty' => 'string|max:255',
            'kabupaten' => 'string|max:255',
            'provinsi' => 'string|max:255',
            'lokasi' => 'string',
            'jenis_id' => 'required|integer',
            'gambar' => 'image|max:2048',
            'normal' => 'numeric',
            'waspada' => 'numeric',
            'siaga' => 'numeric',
            'awas' => 'numeric',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Tambah Pos Gagal', 401);
        }



        if ($request->file('gambar')) {
            $file = $request->file('gambar')->store('pos', 'public');
        } else {
            $file = "default.jpg";
        }


        $post = Post::create([
            'nama' => $request->nama,
            'koordinatx' => $request->koordinatx,
            'koordinaty' => $request->koordinaty,
            'kabupaten' => $request->kabupaten,
            'provinsi' => $request->provinsi,
            'lokasi' => $request->lokasi,
            'jenis_id' => $request->jenis_id,
            'gambar' => $file,
            'normal' => $request->normal,
            'waspada' => $request->waspada,
            'siaga' => $request->siaga,
            'awas' => $request->awas,
        ]);

        return ResponseFormatter::success($post, 'Pos sukses dibuat');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'koordinatx' => 'string|max:255',
            'koordinaty' => 'string|max:255',
            'kabupaten' => 'string|max:255',
            'provinsi' => 'string|max:255',
            'lokasi' => 'string',
            'jenis_id' => 'required|integer',
            'gambar' => 'image|max:2048',
            'normal' => 'numeric',
            'waspada' => 'numeric',
            'siaga' => 'numeric',
            'awas' => 'numeric',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Update Pos Gagal', 401);
        }

        $pos = Post::where('id', $id)->first();
        $pos->nama = $request->nama;
        $pos->koordinatx = $request->koordinatx;
        $pos->koordinaty = $request->koordinaty;
        $pos->kabupaten = $request->kabupaten;
        $pos->provinsi = $request->provinsi;
        $pos->lokasi = $request->lokasi;
        $pos->jenis_id = $request->jenis_id;
        $pos->normal = $request->normal;
        $pos->waspada = $request->waspada;
        $pos->siaga = $request->siaga;
        $pos->awas = $request->awas;
        if ($request->file('gambar')) {
            $file = $request->file('gambar')->store('pos', 'public');
            $pos->gambar = $file;
        }
        $pos->update();

        return ResponseFormatter::success($pos, 'Pos sukses diupdate');
    }

    public function delete($id)
    {
        $pos = Post::where('id', $id)->first();

        if (!$pos) {
            return ResponseFormatter::error('Delete Pos Gagal', 401);
        }

        $pos->delete();

        return [
            'message' => 'Data Pos Sukses Dihapus'
        ];
    }

    public function getAllPosTMA(Request $request)
    {
        $jamSekarang = Carbon::now()->format('H');
        $tanggalSekarang = Carbon::now()->toDateString();

        $provinceId = $request->input('provinceId');
        $regencieId = $request->input('regencieId');
        $subdasId = $request->input('subdasId');

        $pos = Post::with(['province', 'regencie', 'tma' => function ($query) use ($tanggalSekarang) {
            $query->where('tanggal', $tanggalSekarang);
        }])
            ->where('jenis_id', 2)
            ->whereNull('deleted_at');

        if (!empty($provinceId)) {
            $pos->where('provinces_id', $provinceId);
        }

        if (!empty($regencieId)) {
            $pos->where('regencies_id', $regencieId);
        }

        if (!empty($subdasId)) {
            $pos->where('subdas_id', $subdasId);
        }

        $posts = $pos->get();

        $newPos = [];

        foreach ($posts as $value) {
            $data = optional($value->tma->first());

            $dataAverage = Cache::remember('tma_average_' . $value->id, 300, function () use ($value) {
                return TMA::where('pos_id', $value->id)->latest()->first();
            });

            $nilai = 0;
            $status = "Normal";

            if ($data) {
                $pagi = $data->pagi;
                $siang = $data->siang;
                $sore = $data->sore;

                if ($jamSekarang >= 17) {
                    $nilai = $data->sore;
                } elseif ($jamSekarang >= 12) {
                    $nilai = $data->siang;
                } else {
                    $nilai = $data->pagi;
                }

                if ($nilai <= $value->normal) {
                    $status = "Normal";
                } elseif ($nilai <= $value->waspada) {
                    $status = "Waspada";
                } elseif ($nilai <= $value->siaga) {
                    $status = "Siaga";
                } else {
                    $status = "Awas";
                }
            } else {
                $pagi = 0;
                $siang = 0;
                $sore = 0;
            }

            $average = ($dataAverage->pagi + $dataAverage->siang + $dataAverage->sore) / 3;

            $gambar = $value->gambar ? asset('storage/' . $value->gambar) : asset('default.jpg');

            $newPos[] = [
                'id' => $value->id,
                'nama' => $value->nama,
                'koordinatx' => $value->koordinatx,
                'koordinaty' => $value->koordinaty,
                'kabupaten' => $value->regencie ? $value->regencie->name : '-',
                'provinsi' => $value->province ? $value->province->name : '-',
                'lokasi' => $value->lokasi,
                'jenis_id' => $value->jenis_id,
                'gambar' => $gambar,
                'normal' => $value->normal ? $value->normal : '-',
                'waspada' => $value->waspada ? $value->waspada : '-',
                'siaga' => $value->siaga ? $value->siaga : '-',
                'awas' => $value->awas ? $value->awas : '-',
                'nilai' => ($nilai == 0) ? number_format($average, 2) : $nilai,
                'pagi' => ($pagi == 0) ? '-' : $pagi,
                'siang' => ($siang == 0) ? '-' : $siang,
                'sore' => ($sore == 0) ? '-' : $sore,
                'status' => $status,
                'province' => $value->province ? $value->province->name : '-',
                'regencie' => $value->regencie ? $value->regencie->name : '-',
                'subdas' => $value->subdas ? $value->subdas->name : '-',
            ];
        }

        return ResponseFormatter::success($newPos, 'Data Pos TMA');
    }

    public function getAllPosCRH(Request $request)
    {
        $tanggalSekarang = Carbon::now()->toDateString();
        $newPos = [];

        $provinceId = $request->input('provinceId');
        $regencieId = $request->input('regencieId');
        $subdasId = $request->input('subdasId');

        $pos = Post::with(['province', 'regencie', 'curah_hujan' => function ($query) use ($tanggalSekarang) {
            $query->where('tanggal', $tanggalSekarang);
        }])
            ->where('jenis_id', 1)
            ->whereNull('deleted_at');

        if (!empty($provinceId)) {
            $pos->where('provinces_id', $provinceId);
        }

        if (!empty($regencieId)) {
            $pos->where('regencies_id', $regencieId);
        }

        if (!empty($subdasId)) {
            $pos->where('subdas_id', $subdasId);
        }

        $post = $pos->get();

        $curahHujan = CurahHujan::whereIn('pos_id', $post->pluck('id'))
            ->where('tanggal', $tanggalSekarang)
            ->get()
            ->keyBy('pos_id');

        foreach ($post as $value) {
            $data = $curahHujan->get($value->id);
            $hujan_otomatis = 0;
            $hujan_biasa = 0;
            $nilai = 0;
            $status = "Normal";

            if (!empty($data)) {
                $hujan_otomatis = $data->hujan_otomatis;
                $hujan_biasa = $data->hujan_biasa;
            }

            if ($hujan_biasa == 0) {
                $status = "Hujan Ringan";
            } else if ($hujan_biasa <= $value->normal) {
                $status = "Hujan Ringan";
            } else if ($hujan_biasa <= $value->waspada) {
                $status = "Hujan Sedang";
            } else if ($hujan_biasa <= $value->siaga) {
                $status = "Hujan Lebat";
            } else if ($hujan_biasa <= $value->awas) {
                $status = "Hujan Sangat Lebat";
            } else {
                $status = "Hujan Sangat Lebat";
            }


            if ($hujan_biasa >= $hujan_otomatis) {
                $nilai = $hujan_biasa;
            } else if ($hujan_otomatis >= $hujan_biasa) {
                $nilai = $hujan_otomatis;
            } else {
                $nilai = 0;
            }

            $gambar = $value->gambar ? asset('storage/' . $value->gambar) : asset('default.jpg');

            $newPos[] = [
                'id' => $value->id,
                'nama' => $value->nama,
                'koordinatx' => $value->koordinatx,
                'koordinaty' => $value->koordinaty,
                'kabupaten' => $value->regencie ? $value->regencie->name : '-',
                'provinsi' => $value->province ? $value->province->name : '-',
                'lokasi' => $value->lokasi,
                'jenis_id' => $value->jenis_id,
                'gambar' => $gambar,
                'normal' => $value->normal,
                'waspada' => $value->waspada,
                'siaga' => $value->siaga,
                'awas' => $value->awas,
                'hujan_biasa' => $hujan_biasa,
                'hujan_otomatis' => $hujan_otomatis,
                'nilai' => ($nilai == 0) ? '-' : $nilai,
                'status' => $status,
                'province' => $value->province ? $value->province->name : '-',
                'regencie' => $value->regencie ? $value->regencie->name : '-',
            ];
        }

        return ResponseFormatter::success($newPos, 'Data Pos CRH');
    }

    public function getAllPosKlimatologi(Request $request)
    {
        $tanggalSekarang = Carbon::now()->toDateString();
        $newPos = [];

        $provinceId = $request->input('provinceId');
        $regencieId = $request->input('regencieId');
        $subdasId = $request->input('subdasId');

        $pos = Post::with(['province', 'regencie', 'klimatologi' => function ($query) use ($tanggalSekarang) {
            $query->where('tanggal', $tanggalSekarang);
        }])
            ->where('jenis_id', 3)
            ->whereNull('deleted_at');

        if (!empty($provinceId)) {
            $pos->where('provinces_id', $provinceId);
        }

        if (!empty($regencieId)) {
            $pos->where('regencies_id', $regencieId);
        }

        if (!empty($subdasId)) {
            $pos->where('subdas_id', $subdasId);
        }

        $post = $pos->get();

        $klimatologis = Klimatologi::whereIn('pos_id', $post->pluck('id'))
            ->where('tanggal', $tanggalSekarang)
            ->get()
            ->keyBy('pos_id');

        foreach ($post as $value) {
            $data = $klimatologis->get($value->id);
            $hujan_otomatis = 0;
            $hujan_biasa = 0;
            $hasil_penguapan = 0;
            $sinar_matahari = 0;
            $anemometer_spedometer = 0;

            if (!empty($data)) {
                $hujan_otomatis = $data->hujan_otomatis;
                $hujan_biasa = $data->hujan_biasa;
                $hasil_penguapan = ($data->penguapan_plus * $data->penguapan_min) / 2;
                $anemometer_spedometer = $data->anemometer_spedometer;
                $sinar_matahari = $data->sinar_matahari;
            }

            if ($hujan_biasa <= $value->normal) {
                $status = "Normal";
            } else if ($hujan_biasa <= $value->waspada) {
                $status = "Waspada";
            } else if ($hujan_biasa <= $value->siaga) {
                $status = "Siaga";
            } else if ($hujan_biasa <= $value->awas) {
                $status = "Awas";
            } else {
                $status = "Awas";
            }

            $gambar = $value->gambar ? asset('storage/' . $value->gambar) : asset('default.jpg');

            $newPos[] = [
                'id' => $value->id,
                'nama' => $value->nama,
                'koordinatx' => $value->koordinatx,
                'koordinaty' => $value->koordinaty,
                'kabupaten' => $value->regencie ? $value->regencie->name : '-',
                'provinsi' => $value->province ? $value->province->name : '-',
                'lokasi' => $value->lokasi,
                'jenis_id' => $value->jenis_id,
                'gambar' => $value->gambar,
                'gambar' => $gambar,
                'status' => $status,
                'hujan_biasa' => ($hujan_biasa == 0) ? '-' : $hujan_biasa,
                'hujan_otomatis' => ($hujan_otomatis == 0) ? '-' : $hujan_otomatis,
                'anemometer_spedometer' => ($anemometer_spedometer == 0) ? '-' : $anemometer_spedometer,
                'hasil_penguapan' => ($hasil_penguapan == 0) ? '-' : $hasil_penguapan,
                'sinar_matahari' => ($sinar_matahari == 0) ? '-' : $sinar_matahari,
            ];
        }

        return ResponseFormatter::success($newPos, 'Data Pos CRH');
    }

    public function getAllQualityWater(Request $request)
    {
        $tanggalSekarang = Carbon::now()->format('Y-m-d');
        $newPos = [];

        $provinceId = $request->input('provinceId');
        $regencieId = $request->input('regencieId');
        $subdasId = $request->input('subdasId');

        $pos = PosQuality::with(['pos', 'pos.province', 'pos.regencie', 'pos.qualityWater' => function ($query) use ($tanggalSekarang) {
            $query->whereDate('created_at', $tanggalSekarang);
        }]);


        if (!empty($provinceId)) {
            $pos->whereHas('pos', function ($posProvince) use ($provinceId) {
                $posProvince->where('provinces_id', $provinceId);
            });
        }

        if (!empty($regencieId)) {
            $pos->whereHas('pos', function ($posRegencie) use ($regencieId) {
                $posRegencie->where('regencies_id', $regencieId);
            });
        }

        if (!empty($subdasId)) {
            $pos->whereHas('pos', function ($posSubdas) use ($subdasId) {
                $posSubdas->where('subdas_id', $subdasId);
            });
        }

        $post = $pos->get();

        foreach ($post as $value) {
            $data = WaterQuality::where('pos_id', $value->pos->id)
                ->latest()
                ->first();

            $total = 0;
            $status = "";
            $semester = 'Belum Menguji Kualitas Air';

            if (!empty($data)) {
                $total = $data->total;
                $semester = $data->semester ? $data->semester : 'Belum Menguji Kualitas Air';
            }

            if ($total > 0 && $total < 1.0) {
                $status = "Kondisi Baik";
            } else if ($total >= 1.0 && $total < 5.0) {
                $status = "Cemar Ringan";
            } else if ($total >= 5.0 && $total <= 10.0) {
                $status = "Cemar Sedang";
            } else if ($total > 10.0) {
                $status = "Cemar Berat";
            } else {
                $status = "Belum Menguji";
            }

            $gambar = $value->pos->gambar ? asset('storage/' . $value->pos->gambar) : asset('default.jpg');

            $newPos[] = [
                'id' => $value->pos->id,
                'nama' => $value->pos->nama,
                'koordinatx' => $value->pos->koordinatx,
                'koordinaty' => $value->pos->koordinaty,
                'lokasi' => $value->pos->lokasi,
                'kabupaten' => $value->pos->regencie ? $value->pos->regencie->name : '-',
                'provinsi' => $value->pos->province ? $value->pos->province->name : '-',
                'gambar' => $gambar,
                'total' => $total,
                'status' => $status,
                'semester' => $semester
            ];
        }


        return ResponseFormatter::success($newPos, 'Data Pos Kualitas Air');
    }

    public function getRegencie(Request $request)
    {
        $regencie = Regency::where('province_id', $request->provinceId)->get();

        return ResponseFormatter::success($regencie, 'Data Regencie');
    }
}
