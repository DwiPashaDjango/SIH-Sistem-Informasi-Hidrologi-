<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Klimatologi;
use App\Models\Post;
use App\Models\Province;
use App\Models\SubDas;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PagesKlimatologiController extends Controller
{
    public function index()
    {
        $province = Province::select('id', 'name')->get();
        $subdas = SubDas::select('id', 'name')->get();
        return view('pages.klimatologi.index', compact('province', 'subdas'));
    }

    public function show(Request $request, $id)
    {
        $pos = Post::find($id);
        $klimatoligi = Klimatologi::where('pos_id', $id);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if (!empty($start_date) && !empty($end_date)) {
            $klimatoligi = $klimatoligi->whereBetween('tanggal', [$start_date, $end_date]);
        } else {
            $klimatoligi = $klimatoligi->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year);
        }

        $klimatologis = $klimatoligi->orderBy('tanggal', 'DESC')->get();

        $labelsMax = $klimatologis->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });

        $maxPagi = $klimatologis->pluck('termo_max_pagi');
        $maxSiang = $klimatologis->pluck('termo_max_siang');
        $maxSore = $klimatologis->pluck('termo_max_sore');

        $labelsMin = $klimatologis->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });

        $minPagi = $klimatologis->pluck('termo_min_pagi');
        $minSiang = $klimatologis->pluck('termo_min_siang');
        $minSore = $klimatologis->pluck('termo_min_sore');

        $labelsAnometer = $klimatologis->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });

        $spedometer = $klimatologis->pluck('anemometer_spedometer');

        $labelsPenguapan = $klimatologis->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });

        $maxPenguapan = $klimatologis->pluck('penguapan_plus');
        $minPenguapan = $klimatologis->pluck('penguapan_min');


        return view('pages.klimatologi.show', [
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

    public function generatePDF(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => 'recaptcha',
        ]);

        $pos = Post::where('id', $request->pos_id)->first();
        $klimatoligi = Klimatologi::where('pos_id', $request->pos_id);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $klimatologis = $klimatoligi->whereBetween('tanggal', [$start_date, $end_date])->get();
        $pdf = Pdf::loadView('pages.pdf.pos_klimatologi', compact('klimatologis', 'pos', 'start_date', 'end_date'));
        $pdf->setPaper([0, 0, 210 * 2.83465, 330 * 2.83465], 'landscape');
        $pdf->setBasePath(public_path());

        return $pdf->download('SIH BWS Sumatera VI.pdf');
    }
}
