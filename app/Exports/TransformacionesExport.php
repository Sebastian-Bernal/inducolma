<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class TransformacionesExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $transformaciones;

    public function __construct($transformaciones)
    {
        $this->transformaciones = $transformaciones;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('modulos.reportes.administrativos.cubicajes.xls-transformaciones',[
            'data' => $this->transformaciones,
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
