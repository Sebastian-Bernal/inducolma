<?php

namespace Tests\Feature\Costos;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Maquina;
use App\Models\CostosOperacion;
use App\Models\User;

class MaquinaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: index muestra listado de máquinas con paginación
     */
    public function test_index_displays_machines_list()
    {
        $this->actingAsAdmin();
        
        Maquina::factory()->count(5)->create();

        $response = $this->get(route('maquinas.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.costos.maquinas');
        $response->assertViewHas('maquinas');
    }

    /**
     * Test: index requiere autorización admin
     */
    public function test_index_requires_admin_authorization()
    {
        $this->actingAsOperario();

        $response = $this->get(route('maquinas.index'));

        $response->assertStatus(403);
    }

    /**
     * Test: store crea máquina con datos válidos
     */
    public function test_store_creates_machine_with_valid_data()
    {
        $this->actingAsAdmin();

        $data = [
            'maquina' => 'sierra circular',
            'corte' => 'longitudinal'
        ];

        $response = $this->post(route('maquinas.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Maquina creada con éxito');
        
        $this->assertDatabaseHas('maquinas', [
            'maquina' => 'SIERRA CIRCULAR',
            'corte' => 'LONGITUDINAL'
        ]);
    }

    /**
     * Test: store convierte texto a mayúsculas
     */
    public function test_store_converts_text_to_uppercase()
    {
        $this->actingAsAdmin();

        $data = [
            'maquina' => 'cepilladora',
            'corte' => 'plano'
        ];

        $this->post(route('maquinas.store'), $data);

        $this->assertDatabaseHas('maquinas', [
            'maquina' => 'CEPILLADORA',
            'corte' => 'PLANO'
        ]);
    }

    /**
     * Test: store requiere autorización admin
     */
    public function test_store_requires_admin_authorization()
    {
        $this->actingAsCliente();

        $data = [
            'maquina' => 'Torno',
            'corte' => 'Circular'
        ];

        $response = $this->post(route('maquinas.store'), $data);

        $response->assertStatus(403);
    }

    /**
     * Test: store valida campos requeridos
     */
    public function test_store_validates_required_fields()
    {
        $this->actingAsAdmin();

        $response = $this->post(route('maquinas.store'), []);

        $response->assertSessionHasErrors(['maquina', 'corte']);
    }

    /**
     * Test: edit muestra vista de edición con datos de máquina
     */
    public function test_edit_displays_edit_view_with_machine_data()
    {
        $this->actingAsAdmin();
        
        $maquina = Maquina::factory()->create();

        $response = $this->get(route('maquinas.edit', $maquina));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.costos.edit-maquinas');
        $response->assertViewHas('maquina', function ($viewMaquina) use ($maquina) {
            return $viewMaquina->id === $maquina->id;
        });
    }

    /**
     * Test: edit requiere autorización admin
     */
    public function test_edit_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $maquina = Maquina::factory()->create();

        $response = $this->get(route('maquinas.edit', $maquina));

        $response->assertStatus(403);
    }

    /**
     * Test: update actualiza máquina correctamente
     */
    public function test_update_modifies_machine_successfully()
    {
        $this->actingAsAdmin();
        
        $maquina = Maquina::factory()->create([
            'maquina' => 'SIERRA',
            'corte' => 'LONGITUDINAL'
        ]);

        $data = [
            'maquina' => 'sierra radial',
            'corte' => 'transversal'
        ];

        $response = $this->put(route('maquinas.update', $maquina), $data);

        $response->assertRedirect(route('maquinas.index'));
        $response->assertSessionHas('status');
        
        $this->assertDatabaseHas('maquinas', [
            'id' => $maquina->id,
            'maquina' => 'SIERRA RADIAL',
            'corte' => 'TRANSVERSAL'
        ]);
    }

    /**
     * Test: update requiere autorización admin
     */
    public function test_update_requires_admin_authorization()
    {
        $this->actingAsCliente();
        
        $maquina = Maquina::factory()->create();

        $response = $this->put(route('maquinas.update', $maquina), [
            'maquina' => 'Nueva',
            'corte' => 'Nuevo'
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test: destroy elimina máquina sin relaciones
     */
    public function test_destroy_deletes_machine_without_relations()
    {
        $this->actingAsAdmin();
        
        $maquina = Maquina::factory()->create();

        $response = $this->delete(route('maquinas.destroy', $maquina));

        $response->assertRedirect();
        $this->assertDatabaseMissing('maquinas', [
            'id' => $maquina->id
        ]);
    }

    /**
     * Test: destroy no elimina máquina con datos relacionados
     */
    public function test_destroy_prevents_deletion_with_related_data()
    {
        $this->actingAsAdmin();
        
        $maquina = Maquina::factory()->create();
        CostosOperacion::factory()->create(['maquina_id' => $maquina->id]);

        $response = $this->delete(route('maquinas.destroy', $maquina));

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('maquinas', [
            'id' => $maquina->id
        ]);
    }

    /**
     * Test: destroy requiere autorización admin
     */
    public function test_destroy_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $maquina = Maquina::factory()->create();

        $response = $this->delete(route('maquinas.destroy', $maquina));

        $response->assertStatus(403);
    }
}
