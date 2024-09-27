<?php

namespace App\Exports\Admin;

use App\Models\CurahHujan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CrhExport implements FromView
{
    protected $start_date;
    protected $end_date;
    protected $pos_id;

    public function __construct($start_date, $end_date, $pos_id)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->pos_id = $pos_id;
    }

    public function view(): View
    {
        $query = CurahHujan::where('pos_id', $this->pos_id);

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('tanggal', [$this->start_date, $this->end_date]);
        }

        $crhs = $query->get();

        return view('admin..excel.excel_crh', compact('crhs'));
    }
}
