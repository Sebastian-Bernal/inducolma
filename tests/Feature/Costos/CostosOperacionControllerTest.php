<?php

namespace Tests\Feature\Costos;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CostosOperacion;
use App\Models\Maquina;
use App\Models\Descripcion;
use App\Models\Operacion;

class CostosOperacionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: index muestra listado de costos con relaciones
     */
    public function test_index_displays_costs_list_with_relationships()
    {
        $this->actingAsAdmin();
        
        CostosOperacion::factory()->count(3)->create();
        Maquina::factory()->count(2)->create();
        Descripcion::factory()->count(2)->create();

        $response = $this->get(route('costos-de-operacion.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.costos.costos-operacion');
        $response->assertViewHas('costosOperacion');
        $response->assertViewHas('maquinas');
        $response->assertViewHas('descripciones');
        $response->assertViewHas('operaciones');
    }

    /**
     * Test: index requiere autorización admin
     */
    public function test_index_requires_admin_authorization()
    {
        $this->actingAsOperario();

        $response = $this->get(route('costos-de-operacion.index'));

        $response->assertStatus(403);
    }

    /**
     * Test: store crea costo de operación con datos válidos
     */
    public function test_store_creates_operation_cost_with_valid_data()
    {
        $this->actingAsAdmin();
        
        $maquina = Maquina::factory()->create();
        $descripcion = Descripcion::factory()->create();

        $data = [
            'cantidad' => 5,
            'valorMes' => 500000,
            'valorDia' => 20000,
            'costokwh' => 450,
            'idMaquina' => $maquina->id,
            'idDescripcion' => $descripcion->id
        ];

        $response = $this->post(route('costos-de-operacion.store'), $data);

        $response->assertRedirect(route('costos-de-operacion.index'));
        $response->assertSessionHas('status', 'Costo de operación creado con éxito');
        
        $this->assertDatabaseHas('costos_operacions', [
            'cantidad' => 5,
            'valor_mes' => 500000,
            'valor_dia' => 20000,
            'costo_kwh' => 450,
            'maquina_id' => $maquina->id,
            'descripcion_id' => $descripcion->id
        ]);
    }

    /**
     * Test: store requiere autorización admin
     */
    public function test_store_requires_admin_authorization()
    {
        $this->actingAsCliente();
        
        $maquina = Maquina::factory()->create();
        $descripcion = Descripcion::factory()->create();

        $data = [
            'cantidad' => 5,
            'valorMes' => 500000,
            'valorDia' => 20000,
            'costokwh' => 450,
            'idMaquina' => $maquina->id,
            'idDescripcion' => $descripcion->id
        ];

        $response = $this->post(route('costos-de-operacion.store'), $data);

        $response->assertStatus(403);
    }

    /**
     * Test: store valida campos requeridos
     */
    public function test_store_validates_required_fields()
    {
        $this->actingAsAdmin();

        $response = $this->post(route('costos-de-operacion.store'), []);

        $response->assertSessionHasErrors([
            'cantidad', 
            'valorMes', 
            'valorDia', 
            'costokwh', 
            'idMaquina', 
            'idDescripcion'
        ]);
    }

    /**
     * Test: edit muestra vista con datos de costo y descripciones filtradas
     */
    public function test_edit_displays_edit_view_with_filtered_descriptions()
    {
        $this->actingAsAdmin();
        
        $operacion = Operacion::factory()->create();
        $descripcion = Descripcion::factory()->create(['operacion_id' => $operacion->id]);
        $maquina = Maquina::factory()->create();
        
        $costo = CostosOperacion::factory()->create([
            'maquina_id' => $maquina->id,
            'descripcion_id' => $descripcion->id
        ]);

        $response = $this->get(route('costos-de-operacion.edit', $costo));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.costos.costos-operacion-edit');
        $response->assertViewHas('costosOperacion', function ($viewCosto) use ($costo) {
            return $viewCosto->id === $costo->id;
        });
        $response->assertViewHas('maquinas');
        $response->assertViewHas('descripciones');
        $response->assertViewHas('operaciones');
    }

    /**
     * Test: edit requiere autorización admin
     */
    public function test_edit_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $costo = CostosOperacion::factory()->create();

        $response = $this->get(route('costos-de-operacion.edit', $costo));

        $response->assertStatus(403);
    }

    /**
     * Test: update actualiza costo de operación correctamente
     */
    public function test_update_modifies_operation_cost_successfully()
    {
        $this->actingAsAdmin();
        
        $maquina = Maquina::factory()->create();
        $descripcion = Descripcion::factory()->create();
        
        $costo = CostosOperacion::factory()->create([
            'cantidad' => 5,
            'valor_mes' => 500000,
            'maquina_id' => $maquina->id,
            'descripcion_id' => $descripcion->id
        ]);

        $data = [
            'cantidad' => 10,
            'valorMes' => 800000,
            'valorDia' => 30000,
            'costokwh' => 500,
            'idMaquina' => $maquina->id,
            'idDescripcion' => $descripcion->id
        ];

        $response = $this->put(route('costos-de-operacion.update', $costo), $data);

        $response->assertRedirect(route('costos-de-operacion.index'));
        $response->assertSessionHas('status');
        
        $this->assertDatabaseHas('costos_operacions', [
            'id' => $costo->id,
            'cantidad' => 10,
            'valor_mes' => 800000,
            'valor_dia' => 30000,
            'costo_kwh' => 500
        ]);
    }

    /**
     * Test: update requiere autorización admin
     */
    public function test_update_requires_admin_authorization()
    {
        $this->actingAsCliente();
        
        $costo = CostosOperacion::factory()->create();

        $response = $this->put(route('costos-de-operacion.update', $costo), [
            'cantidad' => 10,
            'valorMes' => 600000,
            'valorDia' => 25000,
            'costokwh' => 400,
            'idMaquina' => $costo->maquina_id,
            'idDescripcion' => $costo->descripcion_id
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test: destroy elimina costo de operación
     */
    public function test_destroy_deletes_operation_cost()
    {
        $this->actingAsAdmin();
        
        $costo = CostosOperacion::factory()->create();

        $response = $this->delete(route('costos-de-operacion.destroy', $costo));

        $response->assertRedirect(route('costos-de-operacion.index'));
        $response->assertSessionHas('status', 'Costo de operación eliminado con éxito');
        
        $this->assertDatabaseMissing('costos_operacions', [
            'id' => $costo->id
        ]);
    }

    /**
     * Test: destroy requiere autorización admin
     */
    public function test_destroy_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $costo = CostosOperacion::factory()->create();

        $response = $this->delete(route('costos-de-operacion.destroy', $costo));

        $response->assertStatus(403);
    }

    /**
     * Test: descripciones endpoint retorna descripciones filtradas por operación
     */
    public function test_descripciones_endpoint_returns_filtered_descriptions()
    {
        $this->actingAsAdmin();
        
        $operacion1 = Operacion::factory()->create();
        $operacion2 = Operacion::factory()->create();
        
        $desc1 = Descripcion::factory()->create([
            'operacion_id' => $operacion1->id,
            'descripcion' => 'DESC 1'
        ]);
        $desc2 = Descripcion::factory()->create([
            'operacion_id' => $operacion1->id,
            'descripcion' => 'DESC 2'
        ]);
        $desc3 = Descripcion::factory()->create([
            'operacion_id' => $operacion2->id,
            'descripcion' => 'DESC 3'
        ]);

        $response = $this->postJson(route('descripciones-por-operacion'), [
            'idOperacion' => $operacion1->id
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['descripcion' => 'DESC 1']);
        $response->assertJsonFragment(['descripcion' => 'DESC 2']);
        $response->assertJsonMissing(['descripcion' => 'DESC 3']);
    }

    /**
     * Test: descripciones requiere autorización admin
     */
    public function test_descripciones_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $operacion = Operacion::factory()->create();

        $response = $this->postJson(route('descripciones-por-operacion'), [
            'idOperacion' => $operacion->id
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test: edit filtra descripciones por operación del costo actual
     */
    public function test_edit_filters_descriptions_by_current_operation()
    {
        $this->actingAsAdmin();
        
        $operacion = Operacion::factory()->create();
        $otraOperacion = Operacion::factory()->create();
        
        $descripcion1 = Descripcion::factory()->create(['operacion_id' => $operacion->id]);
        $descripcion2 = Descripcion::factory()->create(['operacion_id' => $operacion->id]);
        $descripcion3 = Descripcion::factory()->create(['operacion_id' => $otraOperacion->id]);
        
        $costo = CostosOperacion::factory()->create([
            'descripcion_id' => $descripcion1->id
        ]);

        $response = $this->get(route('costos-de-operacion.edit', $costo));

        $response->assertViewHas('descripciones', function ($descripciones) use ($operacion) {
            return $descripciones->count() === 2 &&
                   $descripciones->every(fn($d) => $d->operacion_id === $operacion->id);
        });
    }

    /**
     * Test: update incluye nombre de máquina en mensaje de éxito
     */
    public function test_update_includes_machine_name_in_success_message()
    {
        $this->actingAsAdmin();
        
        $maquina = Maquina::factory()->create(['maquina' => 'SIERRA CIRCULAR']);
        $descripcion = Descripcion::factory()->create();
        
        $costo = CostosOperacion::factory()->create([
            'maquina_id' => $maquina->id,
            'descripcion_id' => $descripcion->id
        ]);

        $data = [
            'cantidad' => 8,
            'valorMes' => 700000,
            'valorDia' => 28000,
            'costokwh' => 480,
            'idMaquina' => $maquina->id,
            'idDescripcion' => $descripcion->id
        ];

        $response = $this->put(route('costos-de-operacion.update', $costo), $data);

        $response->assertSessionHas('status', function ($status) use ($costo) {
            return str_contains($status, 'SIERRA CIRCULAR') &&
                   str_contains($status, (string)$costo->id);
        });
    }
}
