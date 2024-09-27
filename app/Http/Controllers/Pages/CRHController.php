<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\CurahHujan;
use App\Models\Post;
use App\Models\Province;
use App\Models\SubDas;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CRHController extends Controller
{
    public function index()
    {
        $province = Province::select('id', 'name')->get();
        $subdas = SubDas::select('id', 'name')->get();
        return view('pages.crh.index', compact('province', 'subdas'));
    }

    public function show(Request $request, $id)
    {
        $pos = Post::find($id);
        $crh = CurahHujan::where('pos_id', $id);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if (!empty($start_date) && !empty($end_date)) {
            $crh = $crh->whereBetween('tanggal', [$start_date, $end_date]);
        } else {
            $crh = $crh->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year);
        }

        $crhs = $crh->orderBy('id', 'DESC')->paginate(25);

        $labels = $crhs->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });
        $hujan_biasa = $crhs->pluck('hujan_biasa');
        $hujan_otomatis = $crhs->pluck('hujan_otomatis');

        return view('pages.crh.show', compact('pos', 'crhs', 'labels', 'hujan_biasa', 'hujan_otomatis', 'start_date', 'end_date'));
    }

    public function generatePDF(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => 'recaptcha',
        ]);

        $pos = Post::where('id', $request->pos_id)->first();
        $tma = CurahHujan::where('pos_id', $request->pos_id);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $crhs = $tma->whereBetween('tanggal', [$start_date, $end_date])->get();
        $pdf = Pdf::loadView('pages.pdf.pos_crh', compact('crhs', 'pos', 'start_date', 'end_date'));
        $pdf->setBasePath(public_path());

        return $pdf->download('SIH BWS Sumatera VI.pdf');
    }
}
