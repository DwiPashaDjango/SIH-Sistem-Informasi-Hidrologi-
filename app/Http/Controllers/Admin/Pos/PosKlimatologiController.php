<?php

namespace App\Http\Controllers\Admin\Pos;

use App\Exports\Admin\KlimatologiExport;
use App\Http\Controllers\Controller;
use App\Models\Klimatologi;
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

class PosKlimatologiController extends Controller
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
                ->where('jenis_id', 3)
                ->whereNull('deleted_at');

            if ($province != null) {
                $crh = Post::with('province', 'regencie')
                    ->where('jenis_id', 3)
                    ->where('provinces_id', $province)
                    ->whereNull('deleted_at');
            }

            if ($regencies != null) {
                $crh = Post::with('province', 'regencie')
                    ->where('jenis_id', 3)
                    ->where('regencies_id', $regencies)
                    ->whereNull('deleted_at');
            }

            if ($subdas != null) {
                $crh = Post::with('province', 'regencie')
                    ->where('jenis_id', 3)
                    ->where('subdas_id', $subdas)
                    ->whereNull('deleted_at');
            }

            return DataTables::of($crh)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if ($row->gambar === 'default.jpg' || $row->gambar == null) {
                        return '<img src="' . asset('default.jpg') . '" width="100" alt="">';
                    } else {
                        return '<img src="' . asset('storage/' .  $row->gambar) . '" width="100" alt="">';
                    }
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
                    $btn = '<a href="' . route('pos.klimatologi.show', ['id' => $row->id]) . '" class="btn btn-primary btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                    if (Auth::user()->roles[0]->name === 'Admin') {
                        $btn .= '<a href="' . route('pos.edit', ['id' => $row->id, 'jenisPos' => 'klimatologis']) . '" class="btn btn-warning btn-sm mr-2"><i class="fas fa-pen"></i></a>';
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
        return view('admin.pos.klimatologi.index', compact('province', 'subdas'));
    }

    public function show(Request $request, $id)
    {
        $pos = Post::find($id);
        $klimatologi = Klimatologi::where('pos_id', $id);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if (!empty($start_date) && !empty($end_date)) {
            $klimatologi = $klimatologi->whereBetween('tanggal', [$start_date, $end_date]);
        } else {
            $klimatologi = $klimatologi->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year);
        }

        $klimatologis = $klimatologi->orderBy('tanggal', 'DESC')->get();

        $labelsMax = $klimatologi->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });

        $maxPagi = $klimatologis->pluck('termo_max_pagi');
        $maxSiang = $klimatologis->pluck('termo_max_siang');
        $maxSore = $klimatologis->pluck('termo_max_sore');

        $labelsMin = $klimatologi->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });

        $minPagi = $klimatologis->pluck('termo_min_pagi');
        $minSiang = $klimatologis->pluck('termo_min_siang');
        $minSore = $klimatologis->pluck('termo_min_sore');

        $labelsAnometer = $klimatologi->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });

        $spedometer = $klimatologis->pluck('anemometer_spedometer');

        $labelsPenguapan = $klimatologis->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });

        $maxPenguapan = $klimatologis->pluck('penguapan_plus');
        $minPenguapan = $klimatologis->pluck('penguapan_min');


        return view('admin.pos.klimatologi.show', [
            'pos' => $pos,
            'klimatologis' => $klimatologis,
            'labelsMax' => $labelsMax,
            'labelsMin' => $labelsMin,
            'maxPagi' => $maxPagi,
            'maxSiang' => $maxSiang,
            'maxSore' => $maxSore,
            'minPagi' => $minPagi,
            'minSiang' => $minSiang,
            'minSore' => $minSore,
            'labelsAnometer' => $labelsAnometer,
            'spedometer' => $spedometer,
            'labelsPenguapan' => $labelsPenguapan,
            'maxPenguapan' => $maxPenguapan,
            'minPenguapan' => $minPenguapan,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function createKlimatologi($id)
    {
        $pos = Post::find($id);
        return view('admin.pos.klimatologi.create', compact('pos'));
    }

    public function storeKlimatologi(Request $request)
    {
        $request->validate([
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


        $klimatologi = Klimatologi::where('pos_id', $request->pos_id)
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($klimatologi) {
            if ($request->jam == "07.00") {
                if ($request->termo_max_pagi !== "0") {
                    $klimatologi->termo_max_pagi = $request->termo_max;
                }

                if ($request->termo_min_pagi !== "0") {
                    $klimatologi->termo_min_pagi = $request->termo_min;
                }

                if ($request->bola_kering_pagi !== "0") {
                    $klimatologi->bola_kering_pagi = $request->bola_kering;
                }

                if ($request->bola_basah_pagi !== "0") {
                    $klimatologi->bola_basah_pagi = $request->bola_basah;
                }
            } elseif ($request->jam == "12.00") {
                if ($request->termo_max_siang !== "0") {
                    $klimatologi->termo_max_siang = $request->termo_max;
                }

                if ($request->termo_min_siang !== "0") {
                    $klimatologi->termo_min_siang = $request->termo_min;
                }

                if ($request->bola_kering_siang !== "0") {
                    $klimatologi->bola_kering_siang = $request->bola_kering;
                }

                if ($request->bola_basah_siang !== "0") {
                    $klimatologi->bola_basah_siang = $request->bola_basah;
                }
            } elseif ($request->jam == "17.00") {
                if ($request->termo_max_sore !== "0") {
                    $klimatologi->termo_max_sore = $request->termo_max;
                }

                if ($request->termo_min_sore !== "0") {
                    $klimatologi->termo_min_sore = $request->termo_min;
                }

                if ($request->bola_kering_sore !== "0") {
                    $klimatologi->bola_kering_sore = $request->bola_kering;
                }

                if ($request->bola_basah_sore !== "0") {
                    $klimatologi->bola_basah_sore = $request->bola_basah;
                }
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
            // if ($request->jam == "17.00") {
            // } else {
            //     return back()->with('message', 'Berhasil Menambahkan Data.');
            // }
        } else {
            $data = Klimatologi::create([
                'tanggal' => $request->tanggal,
                'pos_id' => $request->pos_id,

                'termo_max_pagi' => ($request->jam == "07.00") ? $request->termo_max : null,
                'termo_max_siang' => ($request->jam == "12.00") ? $request->termo_max_siang : null,
                'termo_max_sore' => ($request->jam == "17") ? $request->termo_max_sore : null,

                'termo_min_pagi' => ($request->jam == "07.00") ? $request->termo_min : null,
                'termo_min_siang' => ($request->jam == "12.00") ? $request->termo_min : null,
                'termo_min_sore' => ($request->jam == "17.00") ? $request->termo_min : null,

                'bola_kering_pagi' => ($request->jam == "07.00") ? $request->bola_kering : null,
                'bola_kering_siang' => ($request->jam == "12.00") ? $request->bola_kering : null,
                'bola_kering_sore' => ($request->jam == "17.00") ? $request->bola_kering : null,

                'bola_basah_pagi' => ($request->jam == "07.00") ? $request->bola_basah : null,
                'bola_basah_siang' => ($request->jam == "12.00") ? $request->bola_basah : null,
                'bola_basah_sore' => ($request->jam == "17.00") ? $request->bola_basah : null,

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
            ]);

            // return back()->with('message', 'Berhasil Menambahkan Data.');
        }
        return redirect()->route('admin.absensi', ['pos_id' => $request->pos_id, 'tanggal' => Carbon::now()->format('Y-m-d'), 'jenis' => 'klimatologis']);
    }

    public function editKlima($id)
    {
        $klimatologis = Klimatologi::with('pos')->find($id);
        return view('admin.pos.klimatologi.edit', compact('klimatologis'));
    }

    public function updateKilma(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
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

        $klimatologi = Klimatologi::find($id);

        if ($request->jam == "07.00") {
            if ($request->termo_max_pagi !== "0") {
                $klimatologi->termo_max_pagi = $request->termo_max;
            }

            if ($request->termo_min_pagi !== "0") {
                $klimatologi->termo_min_pagi = $request->termo_min;
            }

            if ($request->bola_kering_pagi !== "0") {
                $klimatologi->bola_kering_pagi = $request->bola_kering;
            }

            if ($request->bola_basah_pagi !== "0") {
                $klimatologi->bola_basah_pagi = $request->bola_basah;
            }
        } elseif ($request->jam == "12.00") {
            if ($request->termo_max_siang !== "0") {
                $klimatologi->termo_max_siang = $request->termo_max;
            }

            if ($request->termo_min_siang !== "0") {
                $klimatologi->termo_min_siang = $request->termo_min;
            }

            if ($request->bola_kering_siang !== "0") {
                $klimatologi->bola_kering_siang = $request->bola_kering;
            }

            if ($request->bola_basah_siang !== "0") {
                $klimatologi->bola_basah_siang = $request->bola_basah;
            }
        } elseif ($request->jam == "17.00") {
            if ($request->termo_max_sore !== "0") {
                $klimatologi->termo_max_sore = $request->termo_max;
            }

            if ($request->termo_min_sore !== "0") {
                $klimatologi->termo_min_sore = $request->termo_min;
            }

            if ($request->bola_kering_sore !== "0") {
                $klimatologi->bola_kering_sore = $request->bola_kering;
            }

            if ($request->bola_basah_sore !== "0") {
                $klimatologi->bola_basah_sore = $request->bola_basah;
            }
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


        return back()->with('message', 'Berhasil Mengubah Data.');
    }

    public function destroyKlima(Request $request, $id)
    {
        $klimatologis = Klimatologi::find($id);

        $user = User::where('id', Auth::user()->id)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $klimatologis->delete();
                return Response::json(['message' => 'Berhasil Menghapus Data Klimatologi.', 'code' => 200]);
            } else {
                return Response::json(['message' => 'Password Tidak Sesuai.', 'code' => 400]);
            }
        }

        return Response::json(['message' => 'Data TMA Tidak Di Temukan.', 'code' => 404]);
    }

    public function generatePDFKlima($start_date, $end_date, $id)
    {
        $pos = Post::with('regencie')->find($id);
        $klimatologi = Klimatologi::where('pos_id', $id);

        $klimatologi = $klimatologi->whereBetween('tanggal', [$start_date, $end_date]);

        $klimatologis = $klimatologi->orderBy('tanggal', 'DESC')->get();

        $pdf = Pdf::loadView('admin.pdf.pos_klimatologi', compact('pos', 'klimatologis', 'start_date', 'end_date'));
        $pdf->setBasePath(public_path());
        $pdf->setPaper([0, 0, 210 * 2.83465, 330 * 2.83465], 'landscape');

        return $pdf->download('BWS Sumatera VI Klimatologi.pdf');
    }

    public function generateExcelKlima($start_date, $end_date, $id)
    {
        return Excel::download(new KlimatologiExport($start_date, $end_date, $id), 'SIH3 BWS Sumatera VI - Klimatologi.xlsx');
    }
}
