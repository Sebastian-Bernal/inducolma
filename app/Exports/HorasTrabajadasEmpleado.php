<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HorasTrabajadasEmpleado implements FromView, WithStyles, ShouldAutoSize
{
    protected $horasTrabajadasEmpleado;

    public function __construct($horasTrabajadasEmpleado)
    {
        $this->horasTrabajadasEmpleado = $horasTrabajadasEmpleado;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('modulos.reportes.administrativos.personal.xls-horas-trabajadas',[
            'data' => $this->horasTrabajadasEmpleado,
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
