<?php

namespace App\Imports\Admin;

use App\Models\Klimatologi;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as SharedDate;

class KlimatologiImport implements ToModel, WithHeadingRow
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

        return new Klimatologi([
            'tanggal' => $tanggal,
            'termo_max_pagi' => $row['termo_max_pagi'],
            'termo_max_siang' => $row['termo_max_siang'],
            'termo_max_sore' => $row['termo_max_sore'],
            'termo_min_pagi' => $row['termo_min_pagi'],
            'termo_min_siang' => $row['termo_min_siang'],
            'termo_min_sore' => $row['termo_min_sore'],
            'bola_kering_pagi' => $row['bola_kering_pagi'],
            'bola_kering_siang' => $row['bola_kering_siang'],
            'bola_kering_sore' => $row['bola_kering_sore'],
            'bola_basah_pagi' => $row['bola_basah_pagi'],
            'bola_basah_siang' => $row['bola_basah_siang'],
            'bola_basah_sore' => $row['bola_basah_sore'],
            'rh' => $row['rh'],
            'termo_apung_max' => $row['termo_apung_max'],
            'termo_apung_min' => $row['termo_apung_min'],
            'penguapan_plus' => $row['penguapan_plus'],
            'penguapan_min' => $row['penguapan_min'],
            'sinar_matahari' => $row['sinar_matahari'],
            'anemometer_spedometer' => $row['anemometer_spedometer'],
            'hujan_otomatis' => $row['hujan_otomatis'],
            'hujan_biasa' => $row['hujan_biasa'],
            'keterangan' => $row['keterangan'],
            'pos_id' => $this->pos_id
        ]);
    }
}
