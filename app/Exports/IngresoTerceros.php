<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IngresoTerceros implements FromView, WithStyles, ShouldAutoSize
{
    protected $ingresoTercero;

    public function __construct($ingresoTercero)
    {
        $this->ingresoTercero = $ingresoTercero;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('modulos.reportes.administrativos.personal.xls-ingreso-terceros',[
            'data' => $this->ingresoTercero,
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
