<?php

namespace App\Exports\Admin;

use App\Models\Klimatologi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KlimatologiExport implements FromView
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
        $query = Klimatologi::where('pos_id', $this->pos_id);

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('tanggal', [$this->start_date, $this->end_date]);
        }

        $klimatologis = $query->get();

        return view('admin..excel.excel_klimatologi', compact('klimatologis'));
    }
}
