<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Province;
use App\Models\SubDas;
use App\Models\WaterQuality;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PagesQualityWater extends Controller
{
    public function index()
    {
        $province = Province::select('id', 'name')->get();
        $subdas = SubDas::select('id', 'name')->get();
        return view('pages.quality.index', compact('province', 'subdas'));
    }

    public function show(Request $request, $id)
    {
        $pos = Post::find($id);
        $waterQuality = WaterQuality::where('pos_id', $id);

        $semester = $request->semester;
        $year = $request->year;

        if (!empty($semester) && !empty($year)) {
            $waterQuality = $waterQuality->where('semester', $semester)
                ->whereYear('created_at', $year);;
        } else {
            $waterQuality = $waterQuality->whereYear('created_at', Carbon::now()->year);
        }

        $waterQualitys = $waterQuality->orderBy('created_at', 'DESC')->paginate(25);

        $labels = $waterQualitys->pluck('created_at')->map(function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        });
        $total = $waterQualitys->pluck('total');

        return view('pages.quality.show', compact('pos', 'waterQualitys', 'labels', 'total', 'year', 'semester'));
    }
}
