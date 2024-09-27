<?php

namespace App\Imports\Admin;

use App\Models\TMA;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as SharedDate;

class TMAImport implements ToModel, WithHeadingRow
{
    protected $pos_id;

    public function __construct($pos_id)
    {
        $this->pos_id = $pos_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (is_numeric($row['tanggal'])) {
            $tanggal = Carbon::instance(SharedDate::excelToDateTimeObject($row['tanggal']))->format('Y-m-d');
        } else {
            $tanggal = Carbon::parse($row['tanggal'])->format('Y-m-d');
        }
        // dd($row);
        return new TMA([
            'tanggal' => $tanggal,
            'pagi' => $row['pagi'],
            'siang' => $row['siang'],
            'sore' => $row['sore'],
            'keterangan' => $row['keterangan'],
            'pos_id' => $this->pos_id
        ]);
    }
}
