<?php

namespace Tests\Feature\Catalogos;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\DisenoProductoFinal;
use App\Models\DisenoCliente;
use App\Models\User;

class ClienteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: index muestra listado de clientes incluyendo eliminados
     */
    public function test_index_displays_clients_list_including_soft_deleted()
    {
        $this->actingAsAdmin();
        
        Cliente::factory()->count(3)->create();
        $clienteEliminado = Cliente::factory()->create();
        $clienteEliminado->delete();

        $response = $this->get(route('clientes.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.clientes.index');
        $response->assertViewHas('clientes', function ($clientes) {
            return $clientes->count() === 4; // 3 activos + 1 eliminado
        });
    }

    /**
     * Test: store crea cliente con datos válidos
     */
    public function test_store_creates_client_with_valid_data()
    {
        $this->actingAsAdmin();

        $data = [
            'nit' => '900123456-7',
            'nombre' => 'empresa test',
            'razon_social' => 'empresa test s.a.s',
            'direccion' => 'calle 123',
            'telefono' => '3001234567',
            'email' => 'test@empresa.com'
        ];

        $response = $this->post(route('clientes.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Cliente creado con éxito');
        
        $this->assertDatabaseHas('clientes', [
            'nit' => '900123456-7',
            'nombre' => 'EMPRESA TEST',
            'razon_social' => 'EMPRESA TEST S.A.S',
            'direccion' => 'CALLE 123',
            'telefono' => '3001234567',
            'email' => 'test@empresa.com'
        ]);
    }

    /**
     * Test: store convierte texto a mayúsculas
     */
    public function test_store_converts_text_to_uppercase()
    {
        $this->actingAsAdmin();

        $data = [
            'nit' => '123456789',
            'nombre' => 'cliente minúsculas',
            'razon_social' => 'razón social minúsculas',
            'direccion' => 'dirección minúsculas',
            'telefono' => '3009876543',
            'email' => 'test@test.com'
        ];

        $this->post(route('clientes.store'), $data);

        $this->assertDatabaseHas('clientes', [
            'nombre' => 'CLIENTE MINÚSCULAS',
            'razon_social' => 'RAZÓN SOCIAL MINÚSCULAS',
            'direccion' => 'DIRECCIÓN MINÚSCULAS'
        ]);
    }

    /**
     * Test: store asigna id_usuario del usuario autenticado
     */
    public function test_store_assigns_authenticated_user_id()
    {
        $admin = $this->createUserWithRol(1);
        $this->actingAs($admin);

        $data = [
            'nit' => '987654321',
            'nombre' => 'Cliente Nuevo',
            'razon_social' => 'Cliente Nuevo LTDA',
            'direccion' => 'Avenida 45',
            'telefono' => '3112223344',
            'email' => 'nuevo@cliente.com'
        ];

        $this->post(route('clientes.store'), $data);

        $this->assertDatabaseHas('clientes', [
            'nombre' => 'CLIENTE NUEVO',
            'id_usuario' => $admin->id
        ]);
    }

    /**
     * Test: store requiere autorización admin
     */
    public function test_store_requires_admin_authorization()
    {
        $this->actingAsOperario();

        $data = [
            'nit' => '111222333',
            'nombre' => 'Test',
            'razon_social' => 'Test SAS',
            'direccion' => 'Calle Test',
            'telefono' => '3001111111',
            'email' => 'test@test.com'
        ];

        $response = $this->post(route('clientes.store'), $data);

        $response->assertStatus(403);
    }

    /**
     * Test: store valida campos requeridos
     */
    public function test_store_validates_required_fields()
    {
        $this->actingAsAdmin();

        $response = $this->post(route('clientes.store'), []);

        $response->assertSessionHasErrors(['nit', 'nombre', 'razon_social']);
    }

    /**
     * Test: show muestra detalles de cliente con últimos 5 pedidos
     */
    public function test_show_displays_client_details_with_last_five_orders()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();
        
        // Crear 7 pedidos para verificar que solo muestra 5
        Pedido::factory()->count(7)->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id
        ]);

        $response = $this->get(route('clientes.show', $cliente));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.clientes.show');
        $response->assertViewHas('cliente', function ($viewCliente) use ($cliente) {
            return $viewCliente->id === $cliente->id;
        });
        $response->assertViewHas('pedidos', function ($pedidos) {
            return $pedidos->count() === 5;
        });
    }

    /**
     * Test: show ordena pedidos por fecha descendente
     */
    public function test_show_orders_pedidos_by_date_descending()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();
        
        $pedido1 = Pedido::factory()->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id,
            'created_at' => now()->subDays(10)
        ]);
        
        $pedido2 = Pedido::factory()->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id,
            'created_at' => now()->subDays(5)
        ]);
        
        $pedido3 = Pedido::factory()->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id,
            'created_at' => now()->subDays(1)
        ]);

        $response = $this->get(route('clientes.show', $cliente));

        $response->assertViewHas('pedidos', function ($pedidos) use ($pedido1, $pedido2, $pedido3) {
            return $pedidos->first()->id === $pedido3->id && 
                   $pedidos->last()->id === $pedido1->id;
        });
    }

    /**
     * Test: edit muestra vista de edición con datos de cliente
     */
    public function test_edit_displays_edit_view_with_client_data()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();

        $response = $this->get(route('clientes.edit', $cliente));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.clientes.edit');
        $response->assertViewHas('cliente', function ($viewCliente) use ($cliente) {
            return $viewCliente->id === $cliente->id;
        });
    }

    /**
     * Test: update actualiza cliente correctamente
     */
    public function test_update_modifies_client_successfully()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create([
            'nombre' => 'CLIENTE ORIGINAL',
            'email' => 'original@test.com'
        ]);

        $data = [
            'nit' => '999888777',
            'nombre' => 'cliente actualizado',
            'razon_social' => 'cliente actualizado sas',
            'direccion' => 'nueva dirección',
            'telefono' => '3005556666',
            'email' => 'actualizado@test.com'
        ];

        $response = $this->put(route('clientes.update', $cliente), $data);

        $response->assertRedirect(route('clientes.index'));
        $response->assertSessionHas('status', 'Cliente CLIENTE ACTUALIZADO actualizado con éxito');
        
        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'nombre' => 'CLIENTE ACTUALIZADO',
            'email' => 'actualizado@test.com'
        ]);
    }

    /**
     * Test: update actualiza id_usuario con usuario autenticado
     */
    public function test_update_updates_id_usuario_with_authenticated_user()
    {
        $admin = $this->createUserWithRol(1);
        $this->actingAs($admin);
        
        $cliente = Cliente::factory()->create();

        $data = [
            'nit' => '555444333',
            'nombre' => 'Cliente Modificado',
            'razon_social' => 'Cliente Modificado LTDA',
            'direccion' => 'Carrera 10',
            'telefono' => '3007778888',
            'email' => 'modificado@test.com'
        ];

        $this->put(route('clientes.update', $cliente), $data);

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'id_usuario' => $admin->id
        ]);
    }

    /**
     * Test: update requiere autorización admin
     */
    public function test_update_requires_admin_authorization()
    {
        $this->actingAsCliente();
        
        $cliente = Cliente::factory()->create();

        $response = $this->put(route('clientes.update', $cliente), [
            'nit' => '123456789',
            'nombre' => 'Nuevo Nombre',
            'razon_social' => 'Nueva Razón',
            'direccion' => 'Nueva Dirección',
            'telefono' => '3009999999',
            'email' => 'nuevo@test.com'
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test: destroy elimina cliente sin relaciones
     */
    public function test_destroy_deletes_client_without_relations()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();

        $response = $this->deleteJson(route('clientes.destroy', $cliente));

        $response->assertStatus(200);
        $response->assertJson(['success' => 'Cliente eliminado correctamente']);
        
        $this->assertSoftDeleted('clientes', [
            'id' => $cliente->id
        ]);
    }

    /**
     * Test: destroy no elimina cliente con pedidos relacionados
     */
    public function test_destroy_prevents_deletion_with_related_pedidos()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        Pedido::factory()->create(['cliente_id' => $cliente->id]);

        $response = $this->deleteJson(route('clientes.destroy', $cliente));

        $response->assertStatus(409);
        $response->assertJson(['errors' => 'No se pudo eliminar el recurso porque tiene datos asociados']);
        
        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id
        ]);
    }

    /**
     * Test: destroy no elimina cliente con diseños relacionados
     */
    public function test_destroy_prevents_deletion_with_related_disenos()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();
        
        // Relacionar diseño con cliente
        $cliente->disenos()->attach($diseno->id);

        $response = $this->deleteJson(route('clientes.destroy', $cliente));

        $response->assertStatus(409);
        $response->assertJson(['errors' => 'No se pudo eliminar el recurso porque tiene datos asociados']);
    }

    /**
     * Test: destroy requiere autorización admin
     */
    public function test_destroy_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $cliente = Cliente::factory()->create();

        $response = $this->deleteJson(route('clientes.destroy', $cliente));

        $response->assertStatus(403);
    }

    /**
     * Test: restore recupera cliente eliminado
     */
    public function test_restore_recovers_deleted_client()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $cliente->delete();

        $response = $this->postJson(route('clientes.restore', $cliente->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => 'El cliente fue restaurado con éxito']);
        
        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'deleted_at' => null
        ]);
    }

    /**
     * Test: restore retorna error si cliente no puede ser restaurado
     */
    public function test_restore_returns_error_on_failure()
    {
        $this->actingAsAdmin();

        $response = $this->postJson(route('clientes.restore', 99999));

        $response->assertStatus(500);
        $response->assertJson(['errors' => 'El cliente no pudo ser restaurado']);
    }
}
