<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventosPersonal implements FromView, WithStyles, ShouldAutoSize
{
    protected $eventoPersonal;

    public function __construct($eventoPersonal)
    {
        $this->eventoPersonal = $eventoPersonal;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('modulos.reportes.administrativos.personal.xls-eventos-personal',[
            'data' => $this->eventoPersonal,
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
