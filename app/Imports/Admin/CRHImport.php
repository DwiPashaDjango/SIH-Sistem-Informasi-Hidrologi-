<?php

namespace App\Imports\Admin;

use App\Models\CurahHujan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as SharedDate;

class CRHImport implements ToModel, WithHeadingRow
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

        return new CurahHujan([
            'tanggal' => $tanggal,
            'hujan_otomatis' => $row['hujan_otomatis'],
            'hujan_biasa' => $row['hujan_biasa'],
            'keterangan' => $row['keterangan'],
            'pos_id' => $this->pos_id
        ]);
    }
}
