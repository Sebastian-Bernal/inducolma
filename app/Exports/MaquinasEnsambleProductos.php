<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MaquinasEnsambleProductos implements FromView, WithStyles, ShouldAutoSize
{
    protected $maquinasProductos;

    public function __construct($maquinasProductos)
    {
        $this->maquinasProductos = $maquinasProductos;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('modulos.reportes.procesos.xls-productos-maquinas-ensamble',[
            'data' => $this->maquinasProductos,
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
