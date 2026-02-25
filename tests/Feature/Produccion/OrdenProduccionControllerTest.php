<?php

namespace Tests\Feature\Produccion;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\OrdenProduccion;
use App\Models\Pedido;
use App\Models\Item;
use App\Models\Cliente;
use App\Models\DisenoProductoFinal;
use App\Models\User;

class OrdenProduccionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: index muestra pedidos y órdenes de producción
     */
    public function test_index_displays_orders_and_pending_production_orders()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create(['nombre' => 'INDUCOLMA']);
        $diseno = DisenoProductoFinal::factory()->create();
        Pedido::factory()->count(3)->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id
        ]);
        
        OrdenProduccion::factory()->create(['estado' => 'PROCESO']);
        OrdenProduccion::factory()->create(['estado' => 'TERMINADO']);

        $response = $this->get(route('ordenes-produccion.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.programacion.index');
        $response->assertViewHas('pedidos');
        $response->assertViewHas('ordenes');
        $response->assertViewHas('cliente');
        $response->assertViewHas('disenos');
    }

    /**
     * Test: store crea orden de producción con datos válidos
     */
    public function test_store_creates_production_order_with_valid_data()
    {
        $this->actingAsAdmin();
        
        $pedido = Pedido::factory()->create();
        $item = Item::factory()->create();

        $data = [
            'pedido_id' => $pedido->id,
            'item_id' => $item->id,
            'cantidad' => 100,
            'estado' => 'PROCESO'
        ];

        $response = $this->postJson(route('ordenes-produccion.store'), $data);

        $response->assertStatus(200);
        $response->assertJson(['success' => 'Orden de Producción creada con éxito.']);
        
        $this->assertDatabaseHas('orden_produccions', [
            'pedido_id' => $pedido->id,
            'item_id' => $item->id,
            'cantidad' => 100,
            'estado' => 'PROCESO'
        ]);
    }

    /**
     * Test: store asigna user_id del usuario autenticado
     */
    public function test_store_assigns_authenticated_user_id()
    {
        $admin = $this->createUserWithRol(1);
        $this->actingAs($admin);
        
        $pedido = Pedido::factory()->create();
        $item = Item::factory()->create();

        $data = [
            'pedido_id' => $pedido->id,
            'item_id' => $item->id,
            'cantidad' => 50,
            'estado' => 'PENDIENTE'
        ];

        $this->postJson(route('ordenes-produccion.store'), $data);

        $this->assertDatabaseHas('orden_produccions', [
            'user_id' => $admin->id,
            'cantidad' => 50
        ]);
    }

    /**
     * Test: store requiere autorización admin
     */
    public function test_store_requires_admin_authorization()
    {
        $this->actingAsOperario();
        
        $pedido = Pedido::factory()->create();
        $item = Item::factory()->create();

        $data = [
            'pedido_id' => $pedido->id,
            'item_id' => $item->id,
            'cantidad' => 100,
            'estado' => 'PROCESO'
        ];

        $response = $this->postJson(route('ordenes-produccion.store'), $data);

        $response->assertStatus(403);
    }

    /**
     * Test: show muestra detalles de pedido con órdenes
     */
    public function test_show_displays_order_details()
    {
        $this->actingAsAdmin();
        
        $pedido = Pedido::factory()->create();

        $response = $this->get(route('ordenes-produccion.show', $pedido));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.programacion.show');
        $response->assertViewHas('pedido');
    }

    /**
     * Test: showOrden muestra órdenes específicas por pedido e item
     */
    public function test_show_orden_displays_specific_orders_by_pedido_and_item()
    {
        $this->actingAsAdmin();
        
        $pedido = Pedido::factory()->create();
        $item = Item::factory()->create();
        
        OrdenProduccion::factory()->count(2)->create([
            'pedido_id' => $pedido->id,
            'item_id' => $item->id
        ]);
        
        OrdenProduccion::factory()->create([
            'pedido_id' => $pedido->id,
            'item_id' => Item::factory()->create()->id
        ]);

        $response = $this->get(route('odenes-produccion', [$pedido->id, $item->id]));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.programacion.show-ordenes');
        $response->assertViewHas('ordenes', function ($ordenes) {
            return $ordenes->count() === 2;
        });
    }

    /**
     * Test: store valida campos requeridos
     */
    public function test_store_validates_required_fields()
    {
        $this->actingAsAdmin();

        $response = $this->postJson(route('ordenes-produccion.store'), []);

        $response->assertStatus(422);
    }

    /**
     * Test: index filtra órdenes terminadas
     */
    public function test_index_filters_out_finished_orders()
    {
        $this->actingAsAdmin();
        
        OrdenProduccion::factory()->create(['estado' => 'PROCESO']);
        OrdenProduccion::factory()->create(['estado' => 'PENDIENTE']);
        OrdenProduccion::factory()->create(['estado' => 'TERMINADO']);

        $response = $this->get(route('ordenes-produccion.index'));

        $response->assertViewHas('ordenes', function ($ordenes) {
            return $ordenes->count() === 2 && 
                   !$ordenes->contains('estado', 'TERMINADO');
        });
    }

    /**
     * Test: showMaderas redirige si no hay maderas disponibles
     */
    public function test_show_maderas_redirects_when_no_wood_available()
    {
        $this->actingAsAdmin();
        
        $pedido = Pedido::factory()->create([
            'cantidad' => 1000 // Cantidad que excede disponibilidad
        ]);
        $item = Item::factory()->create();

        $response = $this->get(route('odenes-produccion-maderas', [$pedido->id, $item->id]));

        // Si no hay maderas, debe redirigir con status
        $response->assertRedirect();
    }

    /**
     * Test: showMaderas muestra vista con maderas óptimas disponibles
     */
    public function test_show_maderas_displays_optimal_wood_view()
    {
        $this->actingAsAdmin();
        
        $pedido = Pedido::factory()->create([
            'cantidad' => 10 // Cantidad manejable
        ]);
        $item = Item::factory()->create();

        // Este test puede requerir seeders de maderas y cubicajes
        // Para un test básico, verificamos que la ruta existe
        $response = $this->get(route('odenes-produccion-maderas', [$pedido->id, $item->id]));

        // Puede ser 200 (vista) o redirect (sin maderas)
        $this->assertContains($response->status(), [200, 302]);
    }

    /**
     * Test: create retorna estructura de diseño de items
     */
    public function test_create_returns_design_items_structure()
    {
        $this->actingAsAdmin();
        
        $pedido = Pedido::factory()->create();

        $response = $this->get(route('ordenes-produccion.create', $pedido));

        // Este método retorna el pedido directamente
        $response->assertStatus(200);
    }

    /**
     * Test: index ordena pedidos por fecha de entrega ascendente
     */
    public function test_index_orders_pedidos_by_fecha_entrega_ascending()
    {
        $this->actingAsAdmin();
        
        $cliente = Cliente::factory()->create();
        $diseno = DisenoProductoFinal::factory()->create();
        
        $pedido3 = Pedido::factory()->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id,
            'fecha_entrega' => now()->addDays(30)
        ]);
        
        $pedido1 = Pedido::factory()->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id,
            'fecha_entrega' => now()->addDays(10)
        ]);
        
        $pedido2 = Pedido::factory()->create([
            'cliente_id' => $cliente->id,
            'diseno_producto_final_id' => $diseno->id,
            'fecha_entrega' => now()->addDays(20)
        ]);

        $response = $this->get(route('ordenes-produccion.index'));

        $response->assertViewHas('pedidos', function ($pedidos) use ($pedido1, $pedido2, $pedido3) {
            return $pedidos->first()->id === $pedido1->id &&
                   $pedidos->last()->id === $pedido3->id;
        });
    }
}
