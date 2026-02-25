<?php

namespace Tests\Feature\Ventas;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\DisenoProductoFinal;
use App\Models\OrdenProduccion;
use App\Models\User;

class PedidoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: index muestra listado de pedidos con clientes
     */
    public function test_index_displays_pedidos_list_with_clients()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();
        
        Pedido::factory()->count(3)->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id
        ]);

        $response = $this->get(route('pedidos.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.pedidos.index');
        $response->assertViewHas('pedidos');
        $response->assertViewHas('clientes');
    }

    /**
     * Test: store crea pedido con datos válidos
     */
    public function test_store_creates_pedido_with_valid_data()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();

        $data = [
            'cliente' => $cliente->id,
            'items' => $diseno->id,
            'cantidad' => 100,
            'fecha_entrega' => now()->addDays(30)->format('Y-m-d')
        ];

        $response = $this->post(route('pedidos.store'), $data);

        $response->assertRedirect(route('pedidos.index'));
        $response->assertSessionHas('status');
        
        $this->assertDatabaseHas('pedidos', [
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id,
            'cantidad' => 100,
            'estado' => 'PENDIENTE'
        ]);
    }

    /**
     * Test: store asigna user_id del usuario autenticado
     */
    public function test_store_assigns_authenticated_user_id()
    {
        $admin = $this->createUserWithRol(1);
        $this->actingAs($admin);
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();

        $data = [
            'cliente' => $cliente->id,
            'items' => $diseno->id,
            'cantidad' => 50,
            'fecha_entrega' => now()->addDays(15)->format('Y-m-d')
        ];

        $this->post(route('pedidos.store'), $data);

        $this->assertDatabaseHas('pedidos', [
            'user_id' => $admin->id,
            'cantidad' => 50
        ]);
    }

    /**
     * Test: store establece fecha_solicitud como fecha actual
     */
    public function test_store_sets_fecha_solicitud_to_current_date()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();

        $data = [
            'cliente' => $cliente->id,
            'items' => $diseno->id,
            'cantidad' => 75,
            'fecha_entrega' => now()->addDays(20)->format('Y-m-d')
        ];

        $this->post(route('pedidos.store'), $data);

        $this->assertDatabaseHas('pedidos', [
            'fecha_solicitud' => now()->format('Y-m-d')
        ]);
    }

    /**
     * Test: store valida campos requeridos
     */
    public function test_store_validates_required_fields()
    {
        $this->actingAsAdmin();

        $response = $this->post(route('pedidos.store'), []);

        $response->assertSessionHasErrors(['cliente', 'items', 'cantidad', 'fecha_entrega']);
    }

    /**
     * Test: edit muestra vista de edición con datos de pedido
     */
    public function test_edit_displays_edit_view_with_pedido_data()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();
        
        // Asociar diseño con cliente
        $cliente->disenos()->attach($diseno->id);
        
        $pedido = Pedido::factory()->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id
        ]);

        $response = $this->get(route('pedidos.edit', $pedido));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.pedidos.show');
        $response->assertViewHas('pedido', function ($viewPedido) use ($pedido) {
            return $viewPedido->id === $pedido->id;
        });
        $response->assertViewHas('clientes');
        $response->assertViewHas('disenos_cliente');
    }

    /**
     * Test: update actualiza pedido correctamente
     */
    public function test_update_modifies_pedido_successfully()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();
        
        $pedido = Pedido::factory()->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id,
            'cantidad' => 50
        ]);

        $data = [
            'cliente' => $cliente->id,
            'items' => $diseno->id,
            'cantidad' => 150,
            'fecha_entrega' => now()->addDays(30)->format('Y-m-d')
        ];

        $response = $this->put(route('pedidos.update', $pedido), $data);

        $response->assertRedirect(route('pedidos.index'));
        $response->assertSessionHas('status');
        
        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'cantidad' => 150
        ]);
    }

    /**
     * Test: update actualiza user_id con usuario autenticado
     */
    public function test_update_updates_user_id_with_authenticated_user()
    {
        $admin = $this->createUserWithRol(1);
        $this->actingAs($admin);
        
        $pedido = Pedido::factory()->create();

        $data = [
            'cliente' => $pedido->cliente_id,
            'items' => $pedido->diseno_producto_final_id,
            'cantidad' => 200,
            'fecha_entrega' => now()->addDays(25)->format('Y-m-d')
        ];

        $this->put(route('pedidos.update', $pedido), $data);

        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'user_id' => $admin->id
        ]);
    }

    /**
     * Test: destroy elimina pedido sin relaciones
     */
    public function test_destroy_deletes_pedido_without_relations()
    {
        $this->actingAsAdmin();
        
        $pedido = Pedido::factory()->create();

        $response = $this->deleteJson(route('pedidos.destroy', $pedido));

        $response->assertStatus(200);
        $response->assertJson(['success' => 'Pedido eliminado correctamente']);
        
        $this->assertDatabaseMissing('pedidos', [
            'id' => $pedido->id
        ]);
    }

    /**
     * Test: destroy no elimina pedido con órdenes de producción
     */
    public function test_destroy_prevents_deletion_with_related_orders()
    {
        $this->actingAsAdmin();
        
        $pedido = Pedido::factory()->create();
        OrdenProduccion::factory()->create(['pedido_id' => $pedido->id]);

        $response = $this->deleteJson(route('pedidos.destroy', $pedido));

        $response->assertStatus(409);
        $response->assertJson(['errors' => 'No se pudo eliminar el recurso porque tiene datos asociados']);
        
        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id
        ]);
    }

    /**
     * Test: itemsCliente retorna diseños de un cliente específico
     */
    public function test_items_cliente_returns_client_designs()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno1 = DisenoProductoFinal::factory()->create(['descripcion' => 'DISEÑO 1']);
        $diseno2 = DisenoProductoFinal::factory()->create(['descripcion' => 'DISEÑO 2']);
        
        $cliente->disenos()->attach([$diseno1->id, $diseno2->id]);

        $response = $this->postJson(route('items-cliente'), ['id' => $cliente->id]);

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['descripcion' => 'DISEÑO 1']);
        $response->assertJsonFragment(['descripcion' => 'DISEÑO 2']);
    }

    /**
     * Test: store redirige a programaciones.index según ruta
     */
    public function test_store_redirects_based_on_route_name()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();

        $data = [
            'cliente' => $cliente->id,
            'items' => $diseno->id,
            'cantidad' => 100,
            'fecha_entrega' => now()->addDays(30)->format('Y-m-d')
        ];

        $response = $this->post(route('pedidos.store'), $data);

        $response->assertRedirect(route('pedidos.index'));
    }
}
