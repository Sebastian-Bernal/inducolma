<?php

namespace Tests\Feature\Reportes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Evento;
use App\Models\TipoEvento;
use App\Models\User;

class EventoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: index muestra listado de eventos incluyendo eliminados
     */
    public function test_index_displays_events_list_including_soft_deleted()
    {
        $this->actingAsAdmin();
        
        $tipoEvento = TipoEvento::factory()->create();
        
        Evento::factory()->count(3)->create(['tipo_evento_id' => $tipoEvento->id]);
        $eventoEliminado = Evento::factory()->create(['tipo_evento_id' => $tipoEvento->id]);
        $eventoEliminado->delete();

        $response = $this->get(route('eventos.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.eventos.index');
        $response->assertViewHas('eventos', function ($eventos) {
            return $eventos->count() === 4; // 3 activos + 1 eliminado
        });
        $response->assertViewHas('tipo_eventos');
    }

    /**
     * Test: store crea evento con datos válidos
     */
    public function test_store_creates_event_with_valid_data()
    {
        $this->actingAsAdmin();
        
        $tipoEvento = TipoEvento::factory()->create(['tipo_evento' => 'MAQUINA']);

        $data = [
            'descripcion' => 'Evento de prueba',
            'tipoEvento' => $tipoEvento->id
        ];

        $response = $this->post(route('eventos.store'), $data);

        $response->assertRedirect(route('eventos.index'));
        $response->assertSessionHas('status', 'El evento: Evento de prueba,  se ha creado correctamente');
        
        $this->assertDatabaseHas('eventos', [
            'descripcion' => 'Evento de prueba',
            'tipo_evento_id' => $tipoEvento->id
        ]);
    }

    /**
     * Test: store asigna user_id del usuario autenticado
     */
    public function test_store_assigns_authenticated_user_id()
    {
        $admin = $this->createUserWithRol(1);
        $this->actingAs($admin);
        
        $tipoEvento = TipoEvento::factory()->create();

        $data = [
            'descripcion' => 'Mantenimiento programado',
            'tipoEvento' => $tipoEvento->id
        ];

        $this->post(route('eventos.store'), $data);

        $this->assertDatabaseHas('eventos', [
            'descripcion' => 'Mantenimiento programado',
            'user_id' => $admin->id
        ]);
    }

    /**
     * Test: store requiere autorización admin
     */
    public function test_store_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $tipoEvento = TipoEvento::factory()->create();

        $data = [
            'descripcion' => 'Evento sin permiso',
            'tipoEvento' => $tipoEvento->id
        ];

        $response = $this->post(route('eventos.store'), $data);

        $response->assertStatus(403);
    }

    /**
     * Test: store valida campos requeridos
     */
    public function test_store_validates_required_fields()
    {
        $this->actingAsAdmin();

        $response = $this->post(route('eventos.store'), []);

        $response->assertSessionHasErrors(['descripcion', 'tipoEvento']);
    }

    /**
     * Test: show muestra detalles de evento con tipos
     */
    public function test_show_displays_event_details_with_types()
    {
        $this->actingAsAdmin();
        
        $tipoEvento = TipoEvento::factory()->create();
        $evento = Evento::factory()->create(['tipo_evento_id' => $tipoEvento->id]);

        $response = $this->get(route('eventos.show', $evento));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.eventos.show');
        $response->assertViewHas('evento', function ($viewEvento) use ($evento) {
            return $viewEvento->id === $evento->id;
        });
        $response->assertViewHas('tipo_eventos');
    }

    /**
     * Test: update actualiza evento correctamente
     */
    public function test_update_modifies_event_successfully()
    {
        $this->actingAsAdmin();
        
        $tipoEvento1 = TipoEvento::factory()->create();
        $tipoEvento2 = TipoEvento::factory()->create();
        
        $evento = Evento::factory()->create([
            'descripcion' => 'Evento Original',
            'tipo_evento_id' => $tipoEvento1->id
        ]);

        $data = [
            'descripcion' => 'Evento Actualizado',
            'tipoEvento' => $tipoEvento2->id
        ];

        $response = $this->put(route('eventos.update', $evento), $data);

        $response->assertRedirect(route('eventos.index'));
        $response->assertSessionHas('status', 'El evento: Evento Actualizado,  se ha actualizado correctamente');
        
        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'descripcion' => 'Evento Actualizado',
            'tipo_evento_id' => $tipoEvento2->id
        ]);
    }

    /**
     * Test: update actualiza user_id con usuario autenticado
     */
    public function test_update_updates_user_id_with_authenticated_user()
    {
        $admin = $this->createUserWithRol(1);
        $this->actingAs($admin);
        
        $tipoEvento = TipoEvento::factory()->create();
        $evento = Evento::factory()->create(['tipo_evento_id' => $tipoEvento->id]);

        $data = [
            'descripcion' => 'Evento Modificado',
            'tipoEvento' => $tipoEvento->id
        ];

        $this->put(route('eventos.update', $evento), $data);

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'user_id' => $admin->id
        ]);
    }

    /**
     * Test: update requiere autorización admin
     */
    public function test_update_requires_admin_authorization()
    {
        $this->actingAsCliente();
        
        $evento = Evento::factory()->create();
        $tipoEvento = TipoEvento::factory()->create();

        $response = $this->put(route('eventos.update', $evento), [
            'descripcion' => 'Intento actualización',
            'tipoEvento' => $tipoEvento->id
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test: destroy elimina evento con soft delete
     */
    public function test_destroy_soft_deletes_event()
    {
        $this->actingAsAdmin();
        
        $evento = Evento::factory()->create();

        $response = $this->deleteJson(route('eventos.destroy', $evento));

        $response->assertStatus(200);
        $response->assertJson(['success' => 'Evento eliminado correctamente']);
        
        $this->assertSoftDeleted('eventos', [
            'id' => $evento->id
        ]);
    }

    /**
     * Test: destroy requiere autorización admin
     */
    public function test_destroy_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $evento = Evento::factory()->create();

        $response = $this->deleteJson(route('eventos.destroy', $evento));

        $response->assertStatus(403);
    }

    /**
     * Test: restore recupera evento eliminado
     */
    public function test_restore_recovers_deleted_event()
    {
        $this->actingAsAdmin();
        
        $evento = Evento::factory()->create();
        $evento->delete();

        $response = $this->postJson(route('eventos.restore', $evento->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => 'El evento fue restaurado con éxito']);
        
        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'deleted_at' => null
        ]);
    }

    /**
     * Test: restore retorna error si evento no puede ser restaurado
     */
    public function test_restore_returns_error_on_failure()
    {
        $this->actingAsAdmin();

        $response = $this->postJson(route('eventos.restore', 99999));

        $response->assertStatus(500);
        $response->assertJson(['errors' => 'El evento no pudo ser restaurado']);
    }

    /**
     * Test: update usa mismo Form Request que store
     */
    public function test_update_uses_same_validation_as_store()
    {
        $this->actingAsAdmin();
        
        $evento = Evento::factory()->create();

        $response = $this->put(route('eventos.update', $evento), []);

        $response->assertSessionHasErrors(['descripcion', 'tipoEvento']);
    }

    /**
     * Test: index carga relación con tipos de evento
     */
    public function test_index_loads_all_event_types()
    {
        $this->actingAsAdmin();
        
        TipoEvento::factory()->count(5)->create();

        $response = $this->get(route('eventos.index'));

        $response->assertViewHas('tipo_eventos', function ($tipos) {
            return $tipos->count() === 5;
        });
    }
}
