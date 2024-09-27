<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Province;
use App\Models\SubDas;
use App\Models\TMA;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TMAController extends Controller
{
    public function index()
    {
        $province = Province::select('id', 'name')->get();
        $subdas = SubDas::select('id', 'name')->get();
        return view('pages.tma.index', compact('province', 'subdas'));
    }

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

        return view('pages.tma.show', compact('pos', 'tmas', 'labels', 'pagi', 'siang', 'sore', 'start_date', 'end_date'));
    }

    public function generatePDF(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => 'recaptcha',
        ]);

        $pos = Post::where('id', $request->pos_id)->first();
        $tma = TMA::where('pos_id', $request->pos_id);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $tmas = $tma->whereBetween('tanggal', [$start_date, $end_date])->get();
        $pdf = Pdf::loadView('pages.pdf.pos_tma', compact('tmas', 'pos', 'start_date', 'end_date'));
        $pdf->setBasePath(public_path());

        return $pdf->stream('SIH BWS Sumatera VI.pdf');
    }
}
