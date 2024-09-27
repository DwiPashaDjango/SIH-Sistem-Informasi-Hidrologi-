<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Jenis;
use App\Models\Post;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function generateAbsen($pos_id, $tanggal, $jenis)
    {
        $pos = Post::where('id', $pos_id)->first();
        return view('admin.absensi.generate-absen', compact('pos', 'tanggal', 'jenis'));
    }

    public function saveAbsensi(Request $request)
    {
        $filename = rand() . '-' . Auth::user()->id . '-' . '.png';

        $path = public_path('tanda_tangan/' . $filename);

        $signature = new Absen();
        $signature->users_id = Auth::user()->id;
        $signature->pos_id = $request->pos_id;
        $signature->tanggal = $request->tanggal;
        $signature->jenis = $request->jenis;
        $signature->file = $filename;
        $signature->save();

        $data_uri = $request->signature;
        $encoded_image = explode(",", $data_uri)[1];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents($path, $decoded_image);

        return response()->json(['success' => 'Berhasil Melakukan Absensi']);
    }

    public function rekapAbsen(Request $request)
    {
        $jenis = Jenis::all();

        $month = $request->month ?? Carbon::now()->month;
        $years = $request->years ?? Carbon::now()->year;
        $jenis_id = $request->jenis_id ?? null;

        $dateString = $years . '-' . $month;

        $bulan = Carbon::parse($dateString)->format('m');

        $startDate = \Carbon\Carbon::parse($dateString)->startOfMonth();
        $endDate = \Carbon\Carbon::parse($dateString)->endOfMonth();

        $posts = Post::with('absen')
            ->where('jenis_id', $request->jenis_id)
            ->whereHas('absen', function ($query) use ($month, $years) {
                $query->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $years);
            })
            ->get();

        return view('admin.absensi.rekap', compact('jenis', 'posts', 'startDate', 'endDate', 'jenis_id', 'bulan', 'years'));
    }

    public function generatePdf($jenis_id, $month, $years)
    {
        $dateString = $years . '-' . $month;

        $startDate = \Carbon\Carbon::parse($dateString)->startOfMonth();
        $endDate = \Carbon\Carbon::parse($dateString)->endOfMonth();

        $posts = Post::with('absen')
            ->where('jenis_id', $jenis_id)
            ->whereHas('absen', function ($query) use ($month, $years) {
                $query->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $years);
            })
            ->get();

        $jenis = Jenis::where('id', $jenis_id)->first();

        $pdf = Pdf::loadView('admin.pdf.absensi', compact('posts', 'jenis', 'month', 'years', 'startDate', 'endDate'));
        $pdf->setPaper('A4', 'landscape');
        $pdf->setBasePath(public_path());
        return $pdf->download('Absensi-petugas.pdf');
    }
}
