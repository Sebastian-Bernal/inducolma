<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HorasTrabajadas implements FromView, WithStyles, ShouldAutoSize
{
    protected $horasTrabajadas;

    public function __construct($horasTrabajadas)
    {
        $this->horasTrabajadas = $horasTrabajadas;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('modulos.reportes.administrativos.personal.xls-horas-trabajadas',[
            'data' => $this->horasTrabajadas,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                        'font' => ['bold' => true],
                    ],
        ];
    }
}
