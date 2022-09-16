<?php
namespace Tests\Unit;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Tests\TestCase;

class PDFControllerTest extends TestCase
{
    /** @test */
    public function show_returns_a_json_representation_pdf()
    {
        $pdf = Pdf::loadHtml('<h1>Test</h1>');
        /** @var Response $response */
        $response = $pdf->download('test.pdf');

        $this->get('ingreso-maderas-pdf')
            //->assertInstanceOf(Response::class, $response)
            //->assertNotEmpty($response->getContent())
            ->assertSuccessful()
            ->assertSeeText('THE_PDF_BINARY_DATA');
            //->assertEquals('application/pdf', $response->headers->get('Content-Type'))
           // ->assertEquals('attachment; filename="pruebapdf.pdf"', $response->headers->get('Content-Disposition'));
    }
}
