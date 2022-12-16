<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsEnsamblados implements FromView, WithStyles, ShouldAutoSize
{
    protected $itemsEnsamblados;

    public function __construct($itemsEnsamblados)
    {
        $this->itemsEnsamblados = $itemsEnsamblados;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('modulos.reportes.procesos.xls-items-ensamblados',[
            'data' => $this->itemsEnsamblados,
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
