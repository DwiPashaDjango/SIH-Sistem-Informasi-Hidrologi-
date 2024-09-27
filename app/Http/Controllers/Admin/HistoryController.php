<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurahHujan;
use App\Models\Klimatologi;
use App\Models\Post;
use App\Models\TMA;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function tmaHistory(Request $request)
    {
        $tanggalSekarang = Carbon::now()->toDateString();

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $pos = Post::with(['tma' => function ($query) use ($tanggalSekarang) {
            $query->where('tanggal', $tanggalSekarang);
        }])
            ->where('jenis_id', 2)
            ->whereNull('deleted_at')
            ->whereNotNull('tma_banjir')
            ->get();

        $newPos = [];
        foreach ($pos as $value) {
            $data = TMA::where('pos_id', $value->id)->where('sore', '>', $value->tma_banjir);

            if (!empty($start_date) && !empty($end_date)) {
                $datas = $data->whereBetween('tanggal', [$start_date, $end_date])
                    ->orderBy('tanggal', 'DESC')
                    ->get();
            } else {
                $datas = $data->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->orderBy('tanggal', 'DESC')
                    ->get();
            }

            foreach ($datas as $item) {
                $newPos[] = $item;
            }
        }

        return view('admin.history.tmaHistory', compact('newPos', 'start_date', 'end_date'));
    }

    public function crhHistory(Request $request)
    {
        $tanggalSekarang = Carbon::now()->toDateString();

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $pos = Post::with(['tma' => function ($query) use ($tanggalSekarang) {
            $query->where('tanggal', $tanggalSekarang);
        }])
            ->where('jenis_id', 1)
            ->whereNull('deleted_at')
            ->whereNotNull('tma_banjir')
            ->get();

        $newPos = [];
        foreach ($pos as $value) {
            $data = CurahHujan::where('pos_id', $value->id)
                ->where('hujan_biasa', '>', $value->tma_banjir)
                ->where('hujan_otomatis', '>', $value->tma_banjir);

            if (!empty($start_date) && !empty($end_date)) {
                $datas = $data->whereBetween('tanggal', [$start_date, $end_date])
                    ->orderBy('tanggal', 'DESC')
                    ->get();
            } else {
                $datas = $data->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->orderBy('tanggal', 'DESC')
                    ->get();
            }

            foreach ($datas as $item) {
                $newPos[] = $item;
            }
        }

        return view('admin.history.crhHistory', compact('newPos', 'start_date', 'end_date'));
    }

    public function klimaHistory(Request $request)
    {
        $tanggalSekarang = Carbon::now()->toDateString();

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $pos = Post::with(['tma' => function ($query) use ($tanggalSekarang) {
            $query->where('tanggal', $tanggalSekarang);
        }])
            ->where('jenis_id', 3)
            ->whereNull('deleted_at')
            ->whereNotNull('tma_banjir')
            ->get();

        $newPos = [];
        foreach ($pos as $value) {
            $data = Klimatologi::where('pos_id', $value->id)
                ->where('termo_max_pagi', '>', $value->tma_banjir)
                ->where('termo_max_siang', '>', $value->tma_banjir)
                ->where('termo_max_sore', '>', $value->tma_banjir);

            if (!empty($start_date) && !empty($end_date)) {
                $datas = $data->whereBetween('tanggal', [$start_date, $end_date])
                    ->orderBy('tanggal', 'DESC')
                    ->get();
            } else {
                $datas = $data->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->orderBy('tanggal', 'DESC')
                    ->get();
            }

            foreach ($datas as $item) {
                $newPos[] = $item;
            }
        }

        return view('admin.history.klimaHistory', compact('newPos', 'start_date', 'end_date'));
    }
}
