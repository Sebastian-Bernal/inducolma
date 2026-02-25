<?php

namespace Tests\Feature\Costos;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Descripcion;
use App\Models\Operacion;
use App\Models\CostosOperacion;
use App\Models\User;

class DescripcionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: index muestra listado de descripciones y operaciones
     */
    public function test_index_displays_descriptions_and_operations_list()
    {
        $this->actingAsAdmin();
        
        Descripcion::factory()->count(3)->create();
        Operacion::factory()->count(2)->create();

        $response = $this->get(route('descripciones.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.costos.descripciones');
        $response->assertViewHas('descripciones');
        $response->assertViewHas('operaciones');
    }

    /**
     * Test: index requiere autorización admin
     */
    public function test_index_requires_admin_authorization()
    {
        $this->actingAsOperario();

        $response = $this->get(route('descripciones.index'));

        $response->assertStatus(403);
    }

    /**
     * Test: store crea descripción con datos válidos
     */
    public function test_store_creates_description_with_valid_data()
    {
        $this->actingAsAdmin();
        
        $operacion = Operacion::factory()->create();

        $data = [
            'descripcion' => 'corte a medida',
            'idOperacion' => $operacion->id
        ];

        $response = $this->post(route('descripciones.store'), $data);

        $response->assertRedirect(route('descripciones.index'));
        $response->assertSessionHas('status', 'Descripción creada con éxito');
        
        $this->assertDatabaseHas('descripciones', [
            'descripcion' => 'CORTE A MEDIDA',
            'operacion_id' => $operacion->id
        ]);
    }

    /**
     * Test: store convierte texto a mayúsculas
     */
    public function test_store_converts_text_to_uppercase()
    {
        $this->actingAsAdmin();
        
        $operacion = Operacion::factory()->create();

        $data = [
            'descripcion' => 'pulido fino',
            'idOperacion' => $operacion->id
        ];

        $this->post(route('descripciones.store'), $data);

        $this->assertDatabaseHas('descripciones', [
            'descripcion' => 'PULIDO FINO'
        ]);
    }

    /**
     * Test: store requiere autorización admin
     */
    public function test_store_requires_admin_authorization()
    {
        $this->actingAsCliente();
        
        $operacion = Operacion::factory()->create();

        $data = [
            'descripcion' => 'Lijado',
            'idOperacion' => $operacion->id
        ];

        $response = $this->post(route('descripciones.store'), $data);

        $response->assertStatus(403);
    }

    /**
     * Test: store valida campos requeridos
     */
    public function test_store_validates_required_fields()
    {
        $this->actingAsAdmin();

        $response = $this->post(route('descripciones.store'), []);

        $response->assertSessionHasErrors(['descripcion', 'idOperacion']);
    }

    /**
     * Test: edit muestra vista de edición con datos de descripción
     */
    public function test_edit_displays_edit_view_with_description_data()
    {
        $this->actingAsAdmin();
        
        $descripcion = Descripcion::factory()->create();

        $response = $this->get(route('descripciones.edit', $descripcion));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.costos.descripciones-edit');
        $response->assertViewHas('descripcion', function ($viewDescripcion) use ($descripcion) {
            return $viewDescripcion->id === $descripcion->id;
        });
        $response->assertViewHas('operaciones');
    }

    /**
     * Test: edit requiere autorización admin
     */
    public function test_edit_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $descripcion = Descripcion::factory()->create();

        $response = $this->get(route('descripciones.edit', $descripcion));

        $response->assertStatus(403);
    }

    /**
     * Test: update actualiza descripción correctamente
     */
    public function test_update_modifies_description_successfully()
    {
        $this->actingAsAdmin();
        
        $operacion1 = Operacion::factory()->create();
        $operacion2 = Operacion::factory()->create();
        
        $descripcion = Descripcion::factory()->create([
            'descripcion' => 'CORTE INICIAL',
            'operacion_id' => $operacion1->id
        ]);

        $data = [
            'descripcion' => 'corte final',
            'idOperacion' => $operacion2->id
        ];

        $response = $this->put(route('descripciones.update', $descripcion), $data);

        $response->assertRedirect(route('descripciones.index'));
        $response->assertSessionHas('status');
        
        $this->assertDatabaseHas('descripciones', [
            'id' => $descripcion->id,
            'descripcion' => 'CORTE FINAL',
            'operacion_id' => $operacion2->id
        ]);
    }

    /**
     * Test: update requiere autorización admin
     */
    public function test_update_requires_admin_authorization()
    {
        $this->actingAsCliente();
        
        $descripcion = Descripcion::factory()->create();
        $operacion = Operacion::factory()->create();

        $response = $this->put(route('descripciones.update', $descripcion), [
            'descripcion' => 'Nueva',
            'idOperacion' => $operacion->id
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test: destroy elimina descripción sin relaciones
     */
    public function test_destroy_deletes_description_without_relations()
    {
        $this->actingAsAdmin();
        
        $descripcion = Descripcion::factory()->create();

        $response = $this->delete(route('descripciones.destroy', $descripcion));

        $response->assertRedirect(route('descripciones.index'));
        $this->assertDatabaseMissing('descripciones', [
            'id' => $descripcion->id
        ]);
    }

    /**
     * Test: destroy no elimina descripción con datos relacionados
     */
    public function test_destroy_prevents_deletion_with_related_data()
    {
        $this->actingAsAdmin();
        
        $descripcion = Descripcion::factory()->create();
        CostosOperacion::factory()->create(['descripcion_id' => $descripcion->id]);

        $response = $this->delete(route('descripciones.destroy', $descripcion));

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('descripciones', [
            'id' => $descripcion->id
        ]);
    }

    /**
     * Test: destroy requiere autorización admin
     */
    public function test_destroy_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $descripcion = Descripcion::factory()->create();

        $response = $this->delete(route('descripciones.destroy', $descripcion));

        $response->assertStatus(403);
    }
}
