<?php

namespace Tests\Feature\Produccion;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Proceso;
use App\Models\OrdenProduccion;
use App\Repositories\RegistroProcesos;
use Mockery;

class ProcesoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: store registra proceso con autorización admin
     */
    public function test_store_registers_proceso_with_admin_authorization()
    {
        $this->actingAsAdmin();

        $procesoData = [
            'proceso' => [
                'orden_produccion_id' => 1,
                'descripcion_id' => 1,
                'cantidad' => 50,
                'user_id' => 1
            ]
        ];

        // Mock del repositorio
        $mockRepo = Mockery::mock(RegistroProcesos::class);
        $mockRepo->shouldReceive('registrar_ruta')
            ->once()
            ->with($procesoData['proceso'])
            ->andReturn(response()->json(['success' => 'Proceso registrado']));

        $this->app->instance(RegistroProcesos::class, $mockRepo);

        $response = $this->postJson(route('procesos.store'), $procesoData);

        $response->assertStatus(200);
    }

    /**
     * Test: store requiere autorización admin
     */
    public function test_store_requires_admin_authorization()
    {
        $this->actingAsOperario();

        $procesoData = [
            'proceso' => [
                'orden_produccion_id' => 1,
                'descripcion_id' => 1,
                'cantidad' => 50
            ]
        ];

        $response = $this->postJson(route('procesos.store'), $procesoData);

        $response->assertStatus(403);
    }

    /**
     * Test: store delega lógica al repositorio RegistroProcesos
     */
    public function test_store_delegates_logic_to_repository()
    {
        $this->actingAsAdmin();

        $procesoData = [
            'proceso' => [
                'orden_produccion_id' => 5,
                'descripcion_id' => 3,
                'cantidad' => 100,
                'user_id' => 1
            ]
        ];

        $mockRepo = Mockery::mock(RegistroProcesos::class);
        $mockRepo->shouldReceive('registrar_ruta')
            ->once()
            ->with($procesoData['proceso'])
            ->andReturn(response()->json([
                'success' => 'Proceso registrado correctamente',
                'proceso_id' => 123
            ]));

        $this->app->instance(RegistroProcesos::class, $mockRepo);

        $response = $this->postJson(route('procesos.store'), $procesoData);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success']);
    }

    /**
     * Test: store sin autorización devuelve 403
     */
    public function test_store_without_authorization_returns_403()
    {
        $this->actingAsCliente();

        $procesoData = [
            'proceso' => [
                'orden_produccion_id' => 1,
                'descripcion_id' => 1,
                'cantidad' => 25
            ]
        ];

        $response = $this->postJson(route('procesos.store'), $procesoData);

        $response->assertStatus(403);
    }

    /**
     * Test: store pasa datos correctos al repositorio
     */
    public function test_store_passes_correct_data_to_repository()
    {
        $this->actingAsAdmin();

        $procesoArray = [
            'orden_produccion_id' => 10,
            'descripcion_id' => 5,
            'cantidad' => 75,
            'user_id' => 2,
            'observaciones' => 'Test observaciones'
        ];

        $mockRepo = Mockery::mock(RegistroProcesos::class);
        $mockRepo->shouldReceive('registrar_ruta')
            ->once()
            ->with(Mockery::on(function ($arg) use ($procesoArray) {
                return $arg['orden_produccion_id'] === $procesoArray['orden_produccion_id'] &&
                       $arg['descripcion_id'] === $procesoArray['descripcion_id'] &&
                       $arg['cantidad'] === $procesoArray['cantidad'];
            }))
            ->andReturn(response()->json(['success' => true]));

        $this->app->instance(RegistroProcesos::class, $mockRepo);

        $response = $this->postJson(route('procesos.store'), [
            'proceso' => $procesoArray
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test: controller usa inyección de dependencias correctamente
     */
    public function test_controller_uses_dependency_injection()
    {
        $this->actingAsAdmin();

        // Verificar que el repositorio se inyecta en el constructor
        $controller = app(\App\Http\Controllers\ProcesoController::class);

        $reflection = new \ReflectionClass($controller);
        $property = $reflection->getProperty('registroProceso');
        $property->setAccessible(true);
        $repo = $property->getValue($controller);

        $this->assertInstanceOf(RegistroProcesos::class, $repo);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
