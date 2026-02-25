<?php

namespace Tests\Feature\Costos;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Operacion;
use App\Models\Descripcion;
use App\Models\User;

class OperacionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: index muestra listado de operaciones
     */
    public function test_index_displays_operations_list()
    {
        $this->actingAsAdmin();
        
        Operacion::factory()->count(5)->create();

        $response = $this->get(route('operaciones.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.costos.operaciones');
        $response->assertViewHas('operaciones');
    }

    /**
     * Test: index requiere autorización admin
     */
    public function test_index_requires_admin_authorization()
    {
        $this->actingAsOperario();

        $response = $this->get(route('operaciones.index'));

        $response->assertStatus(403);
    }

    /**
     * Test: store crea operación con datos válidos
     */
    public function test_store_creates_operation_with_valid_data()
    {
        $this->actingAsAdmin();

        $data = [
            'operacion' => 'corte longitudinal'
        ];

        $response = $this->post(route('operaciones.store'), $data);

        $response->assertRedirect(route('operaciones.index'));
        $response->assertSessionHas('status', 'Operación creada con éxito');
        
        $this->assertDatabaseHas('operaciones', [
            'operacion' => 'CORTE LONGITUDINAL'
        ]);
    }

    /**
     * Test: store convierte texto a mayúsculas
     */
    public function test_store_converts_text_to_uppercase()
    {
        $this->actingAsAdmin();

        $data = [
            'operacion' => 'ensamblado'
        ];

        $this->post(route('operaciones.store'), $data);

        $this->assertDatabaseHas('operaciones', [
            'operacion' => 'ENSAMBLADO'
        ]);
    }

    /**
     * Test: store requiere autorización admin
     */
    public function test_store_requires_admin_authorization()
    {
        $this->actingAsCliente();

        $data = [
            'operacion' => 'Perforado'
        ];

        $response = $this->post(route('operaciones.store'), $data);

        $response->assertStatus(403);
    }

    /**
     * Test: store valida campo requerido
     */
    public function test_store_validates_required_field()
    {
        $this->actingAsAdmin();

        $response = $this->post(route('operaciones.store'), []);

        $response->assertSessionHasErrors(['operacion']);
    }

    /**
     * Test: edit muestra vista de edición con datos de operación
     */
    public function test_edit_displays_edit_view_with_operation_data()
    {
        $this->actingAsAdmin();
        
        $operacion = Operacion::factory()->create();

        $response = $this->get(route('operaciones.edit', $operacion));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.costos.operaciones-edit');
        $response->assertViewHas('operacion', function ($viewOperacion) use ($operacion) {
            return $viewOperacion->id === $operacion->id;
        });
    }

    /**
     * Test: edit requiere autorización admin
     */
    public function test_edit_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $operacion = Operacion::factory()->create();

        $response = $this->get(route('operaciones.edit', $operacion));

        $response->assertStatus(403);
    }

    /**
     * Test: update actualiza operación correctamente
     */
    public function test_update_modifies_operation_successfully()
    {
        $this->actingAsAdmin();
        
        $operacion = Operacion::factory()->create([
            'operacion' => 'LIJADO'
        ]);

        $data = [
            'operacion' => 'pulido'
        ];

        $response = $this->put(route('operaciones.update', $operacion), $data);

        $response->assertRedirect(route('operaciones.index'));
        $response->assertSessionHas('status');
        
        $this->assertDatabaseHas('operaciones', [
            'id' => $operacion->id,
            'operacion' => 'PULIDO'
        ]);
    }

    /**
     * Test: update requiere autorización admin
     */
    public function test_update_requires_admin_authorization()
    {
        $this->actingAsCliente();
        
        $operacion = Operacion::factory()->create();

        $response = $this->put(route('operaciones.update', $operacion), [
            'operacion' => 'Nueva'
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test: destroy elimina operación sin relaciones
     */
    public function test_destroy_deletes_operation_without_relations()
    {
        $this->actingAsAdmin();
        
        $operacion = Operacion::factory()->create();

        $response = $this->delete(route('operaciones.destroy', $operacion));

        $response->assertRedirect(route('operaciones.index'));
        $this->assertDatabaseMissing('operaciones', [
            'id' => $operacion->id
        ]);
    }

    /**
     * Test: destroy no elimina operación con datos relacionados
     */
    public function test_destroy_prevents_deletion_with_related_data()
    {
        $this->actingAsAdmin();
        
        $operacion = Operacion::factory()->create();
        Descripcion::factory()->create(['operacion_id' => $operacion->id]);

        $response = $this->delete(route('operaciones.destroy', $operacion));

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('operaciones', [
            'id' => $operacion->id
        ]);
    }

    /**
     * Test: destroy requiere autorización admin
     */
    public function test_destroy_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $operacion = Operacion::factory()->create();

        $response = $this->delete(route('operaciones.destroy', $operacion));

        $response->assertStatus(403);
    }
}
