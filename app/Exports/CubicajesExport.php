<?php

namespace App\Exports;

use App\Models\Cubicaje;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class CubicajesExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $datosCubicaje;

    public function __construct($datosCubicaje)
    {
        $this->datosCubicaje = $datosCubicaje;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('modulos.reportes.administrativos.cubicajes.xls-cubicajes-viaje',[
            'data' => $this->datosCubicaje,
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
