<?php

namespace Tests\Feature\Personal;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Turno;
use App\Models\User;
use App\Models\Maquina;

class TurnoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: index muestra listado de turnos
     */
    public function test_index_displays_turnos_list()
    {
        $this->actingAsAdmin();
        
        Turno::factory()->count(3)->create();

        $response = $this->get(route('turnos.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.turnos.index');
        $response->assertViewHas('turnos');
    }

    /**
     * Test: index requiere autorización admin
     */
    public function test_index_requires_admin_authorization()
    {
        $this->actingAsOperario();

        $response = $this->get(route('turnos.index'));

        $response->assertStatus(403);
    }

    /**
     * Test: store crea turno con datos válidos
     */
    public function test_store_creates_turno_with_valid_data()
    {
        $this->actingAsAdmin();

        $data = [
            'turno' => 'DIURNO',
            'hora_inicio' => '06:00:00',
            'hora_fin' => '14:00:00'
        ];

        $response = $this->post(route('turnos.store'), $data);

        $response->assertRedirect(route('turnos.index'));
        $response->assertSessionHas('status');
        
        $this->assertDatabaseHas('turnos', [
            'turno' => 'DIURNO',
            'hora_inicio' => '06:00:00',
            'hora_fin' => '14:00:00'
        ]);
    }

    /**
     * Test: store requiere autorización admin
     */
    public function test_store_requires_admin_authorization()
    {
        $this->actingAsCliente();

        $data = [
            'turno' => 'NOCTURNO',
            'hora_inicio' => '22:00:00',
            'hora_fin' => '06:00:00'
        ];

        $response = $this->post(route('turnos.store'), $data);

        $response->assertStatus(403);
    }

    /**
     * Test: store valida campos requeridos
     */
    public function test_store_validates_required_fields()
    {
        $this->actingAsAdmin();

        $response = $this->post(route('turnos.store'), []);

        $response->assertSessionHasErrors(['turno', 'hora_inicio', 'hora_fin']);
    }

    /**
     * Test: edit muestra vista de edición con datos de turno
     */
    public function test_edit_displays_edit_view_with_turno_data()
    {
        $this->actingAsAdmin();
        
        $turno = Turno::factory()->create();

        $response = $this->get(route('turnos.edit', $turno));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.turnos.edit');
        $response->assertViewHas('turno', function ($viewTurno) use ($turno) {
            return $viewTurno->id === $turno->id;
        });
    }

    /**
     * Test: edit requiere autorización admin
     */
    public function test_edit_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $turno = Turno::factory()->create();

        $response = $this->get(route('turnos.edit', $turno));

        $response->assertStatus(403);
    }

    /**
     * Test: update actualiza turno correctamente
     */
    public function test_update_modifies_turno_successfully()
    {
        $this->actingAsAdmin();
        
        $turno = Turno::factory()->create([
            'turno' => 'DIURNO',
            'hora_inicio' => '06:00:00',
            'hora_fin' => '14:00:00'
        ]);

        $data = [
            'turno' => 'NOCTURNO',
            'hora_inicio' => '22:00:00',
            'hora_fin' => '06:00:00'
        ];

        $response = $this->put(route('turnos.update', $turno), $data);

        $response->assertRedirect(route('turnos.index'));
        $response->assertSessionHas('status');
        
        $this->assertDatabaseHas('turnos', [
            'id' => $turno->id,
            'turno' => 'NOCTURNO',
            'hora_inicio' => '22:00:00',
            'hora_fin' => '06:00:00'
        ]);
    }

    /**
     * Test: update requiere autorización admin
     */
    public function test_update_requires_admin_authorization()
    {
        $this->actingAsCliente();
        
        $turno = Turno::factory()->create();

        $response = $this->put(route('turnos.update', $turno), [
            'turno' => 'MIXTO'
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test: destroy elimina turno sin relaciones
     */
    public function test_destroy_deletes_turno_without_relations()
    {
        $this->actingAsAdmin();
        
        $turno = Turno::factory()->create();

        $response = $this->deleteJson(route('turnos.destroy', $turno));

        $response->assertStatus(200);
        $response->assertJson(['success' => 'turno eliminado']);
        
        $this->assertDatabaseMissing('turnos', [
            'id' => $turno->id
        ]);
    }

    /**
     * Test: destroy no elimina turno con usuarios relacionados
     */
    public function test_destroy_prevents_deletion_with_related_users()
    {
        $this->actingAsAdmin();
        
        $turno = Turno::factory()->create();
        $user = User::factory()->create(['rol_id' => 2]);
        
        // Relacionar usuario con turno a través de la tabla pivot
        $turno->users()->attach($user->id);

        $response = $this->deleteJson(route('turnos.destroy', $turno));

        $response->assertStatus(409);
        $response->assertJson(['errors' => 'No se pudo eliminar el recurso porque tiene datos asociados']);
        
        $this->assertDatabaseHas('turnos', [
            'id' => $turno->id
        ]);
    }

    /**
     * Test: destroy no elimina turno con máquinas relacionadas
     */
    public function test_destroy_prevents_deletion_with_related_machines()
    {
        $this->actingAsAdmin();
        
        $turno = Turno::factory()->create();
        $maquina = Maquina::factory()->create();
        
        // Relacionar máquina con turno
        $turno->maquinas()->attach($maquina->id);

        $response = $this->deleteJson(route('turnos.destroy', $turno));

        $response->assertStatus(409);
        $response->assertJson(['errors' => 'No se pudo eliminar el recurso porque tiene datos asociados']);
    }

    /**
     * Test: destroy requiere autorización admin
     */
    public function test_destroy_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $turno = Turno::factory()->create();

        $response = $this->deleteJson(route('turnos.destroy', $turno));

        $response->assertStatus(403);
    }
}
