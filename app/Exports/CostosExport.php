<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CostosExport implements  FromView, WithStyles, ShouldAutoSize
{
    protected $costos;

    public function __construct($costos) {
        $this->costos = $costos;
    }


    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('modulos.reportes.costos.xls-costos',[
            'data' => $this->costos,
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


    public function applyBorder(): array
    {
        return [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
    }


    public function getStringTable($costos)  {
        $htmlContent = '';
        foreach ($costos->procesos_pedido as $pedido) {
            $htmlContent .=
            '<table>
                <thead>
                    <tr>
                        <th>Pedido No.</th>
                        <th>Fecha y hora de inicio</th>
                        <th>Fecha y hora de fin</th>
                        <th>tarjeta entrada</th>
                        <th>tarjeta salida</th>
                        <th>subpaqueta</th>
                        <th>alto</th>
                        <th>ancho</th>
                        <th>largo</th>
                        <th>sobrante</th>
                        <th>lena</th>
                        <th>cm3 procesados</th>
                        <th>consumo energia</th>
                    </tr>
                </thead>';

                foreach ($pedido->procesos as $proceso) {
                    $htmlContent .= '<tbody>
                        <tr>
                            <td rowspan="' . count($proceso->subprocesos) . '">' . $proceso->pedido_id . '</td>
                            <td rowspan="' . count($proceso->subprocesos) . '">' . $proceso->fecha_ejecucion . ' ' . $proceso->hora_inicio  . '</td>
                            <td rowspan="' . count($proceso->subprocesos) . '">' . $proceso->fecha_fin . ' ' . $proceso->hora_fin  . '</td>';
                    $loop = 1;
                    foreach ($proceso->subprocesos as $subproceso) {
                        if ($loop != 1) {
                            $htmlContent .= '<tr>';
                        }
                        $loop += 1;
                        $htmlContent .= '<td>' . $subproceso->tarjeta_entrada . '</td>
                            <td>' . $subproceso->tarjeta_salida . '</td>
                            <td>' . $subproceso->subpaqueta . '</td>
                            <td>' . $subproceso->alto . '</td>
                            <td>' . $subproceso->ancho . '</td>
                            <td>' . $subproceso->largo . '</td>
                            <td>' . $subproceso->sobrante . '</td>
                            <td>' . $subproceso->lena . '</td>
                            <td>' . $subproceso->cm3_procesados . '</td>
                            <td>'.$loop.'</td>
                        </tr>';
                    }

                    $htmlContent .= '<tr>
                            <td colspan="9"> Total del proceso</td>
                            <td>' . $proceso->total_sobrante . '</td>
                            <td>' . $proceso->total_lena . '</td>
                            <td>' . $proceso->total_cm3 . '</td>
                            <td>' . $proceso->consumo_energia . '</td>
                        </tr>
                    </tbody>';
                }

            $htmlContent .= '</table>
            <table>
                <thead>
                    <tr>
                        <th>Total cm3 en el pedido</th>
                        <th>Total sobrante en el pedido</th>
                        <th>Total leña en el pedido</th>
                        <th>Consumo de energia en el pedido</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>' . $pedido->total_cm3_pedido . '</td>
                        <td>' . $pedido->total_sobrante_pedido . '</td>
                        <td>' . $pedido->total_lena_pedido . '</td>
                        <td>' . $pedido->consumo_energia . '</td>
                    </tr>
                </tbody>
            </table>';
        }
    }
}



