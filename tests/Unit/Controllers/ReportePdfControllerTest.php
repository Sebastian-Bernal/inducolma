<?php

namespace Tests\Unit\Controllers;

use Barryvdh\DomPDF\Facade\Pdf ;
use Tests\TestCase;

class ReportePdfControllerTest extends TestCase
{
    public function show_returns_a_json_representation_pdf()
    {
        Pdf::shouldReceive('loadView')
                ->once()
                ->andReturnSelf()
                ->getMock()
                ->shouldReceive('download')
                ->once()
                ->with('pruebapdf.pdf')
                ->andReturn('THE_PDF_BINARY_DATA');

        $this->get('/ingreso-maderas-pdf')
                ->assertSuccessful()
                ->assertSeeText('THE_PDF_BINARY_DATA');
    }
}
