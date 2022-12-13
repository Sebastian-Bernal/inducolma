<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsuariosPedido implements FromView, WithStyles, ShouldAutoSize
{
    protected $usuariosPedido;

    public function __construct($usuariosPedido)
    {
        $this->usuariosPedido = $usuariosPedido;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('modulos.reportes.ventas.xls-usuarios-procesos',[
            'data' => $this->usuariosPedido,
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
