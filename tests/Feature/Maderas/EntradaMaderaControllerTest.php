<?php

namespace Tests\Feature\Maderas;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\EntradaMadera;
use App\Models\Madera;
use App\Models\Proveedor;
use App\Models\EntradasMaderaMaderas;
use App\Models\User;

class EntradaMaderaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    /**
     * Test: index muestra entradas del último mes del usuario autenticado
     */
    public function test_index_displays_user_entries_from_last_month()
    {
        $user = $this->createUserWithRol(1);
        $this->actingAs($user);
        
        // Entradas del usuario actual
        EntradaMadera::factory()->count(3)->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(15)
        ]);
        
        // Entrada de otro usuario (no debe aparecer)
        $otherUser = User::factory()->create(['rol_id' => 1]);
        EntradaMadera::factory()->create([
            'user_id' => $otherUser->id,
            'created_at' => now()->subDays(10)
        ]);
        
        // Entrada fuera del rango (no debe aparecer)
        EntradaMadera::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subMonths(2)
        ]);

        $response = $this->get(route('entrada-madera.index'));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.entradas-madera.index');
        $response->assertViewHas('entradas', function ($entradas) {
            return $entradas->count() === 3;
        });
        $response->assertViewHas('proveedores');
        $response->assertViewHas('maderas');
    }

    /**
     * Test: store crea nueva entrada cuando id es 0
     */
    public function test_store_creates_new_entry_when_id_is_zero()
    {
        $this->actingAsAdmin();
        
        $proveedor = Proveedor::factory()->create();
        $madera = Madera::factory()->create();

        $data = [
            'entrada' => [
                'proveedor_id' => $proveedor->id,
                'acto_administrativo' => 'ACT-001',
                0 => null, // id de entrada
            ],
            'maderas' => [
                [
                    'madera_id' => $madera->id,
                    'condicion_madera' => 'ROLLIZO',
                    'm3entrada' => 10.5
                ]
            ]
        ];

        $response = $this->postJson(route('entrada-madera.store'), $data);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('entrada_maderas', [
            'proveedor_id' => $proveedor->id,
            'acto_administrativo' => 'ACT-001'
        ]);
    }

    /**
     * Test: store actualiza entrada existente cuando id > 0
     */
    public function test_store_updates_existing_entry_when_id_is_greater_than_zero()
    {
        $this->actingAsAdmin();
        
        $entrada = EntradaMadera::factory()->create([
            'acto_administrativo' => 'ACT-002'
        ]);
        
        $proveedor = Proveedor::factory()->create();

        $data = [
            'entrada' => [
                'proveedor_id' => $proveedor->id,
                'acto_administrativo' => 'ACT-002-UPDATED',
                2 => $entrada->id, // id de entrada existente
            ]
        ];

        $response = $this->postJson(route('entrada-madera.store'), $data);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('entrada_maderas', [
            'id' => $entrada->id,
            'proveedor_id' => $proveedor->id
        ]);
    }

    /**
     * Test: show muestra detalles de entrada con relaciones
     */
    public function test_show_displays_entry_details_with_relationships()
    {
        $this->actingAsAdmin();
        
        $proveedor = Proveedor::factory()->create();
        $madera = Madera::factory()->create();
        
        $entrada = EntradaMadera::factory()->create([
            'proveedor_id' => $proveedor->id
        ]);
        
        EntradasMaderaMaderas::factory()->create([
            'entrada_madera_id' => $entrada->id,
            'madera_id' => $madera->id
        ]);

        $response = $this->get(route('entrada-madera.show', $entrada));

        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.entradas-madera.show');
        $response->assertViewHas('entrada', function ($viewEntrada) use ($entrada) {
            return $viewEntrada->id === $entrada->id &&
                   $viewEntrada->relationLoaded('proveedor') &&
                   $viewEntrada->relationLoaded('maderas');
        });
        $response->assertViewHas('proveedores');
        $response->assertViewHas('maderas');
    }

    /**
     * Test: verificarRegistro retorna error false siempre
     */
    public function test_verificar_registro_returns_error_false()
    {
        $this->actingAsAdmin();

        $response = $this->postJson(route('entrada-madera-verificar-registro'), [
            'acto' => 'ACT-003'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['error' => false]);
    }

    /**
     * Test: ultimaEntrada retorna datos de entrada específica con relaciones
     */
    public function test_ultima_entrada_returns_specific_entry_with_relationships()
    {
        $this->actingAsAdmin();
        
        $proveedor = Proveedor::factory()->create(['nombre' => 'Proveedor Test']);
        $madera = Madera::factory()->create();
        
        $entrada = EntradaMadera::factory()->create([
            'proveedor_id' => $proveedor->id
        ]);
        
        EntradasMaderaMaderas::factory()->create([
            'entrada_madera_id' => $entrada->id,
            'madera_id' => $madera->id,
            'condicion_madera' => 'ROLLIZO',
            'm3entrada' => 15.5
        ]);

        $response = $this->postJson(route('entrada-madera-ultima-entrada'), [
            'id' => $entrada->id
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'proveedor',
            'maderas'
        ]);
    }

    /**
     * Test: index filtra por usuario autenticado correctamente
     */
    public function test_index_filters_by_authenticated_user()
    {
        $user1 = $this->createUserWithRol(1);
        $user2 = $this->createUserWithRol(1);
        
        EntradaMadera::factory()->count(5)->create(['user_id' => $user1->id]);
        EntradaMadera::factory()->count(3)->create(['user_id' => $user2->id]);

        $this->actingAs($user1);
        $response = $this->get(route('entrada-madera.index'));

        $response->assertViewHas('entradas', function ($entradas) use ($user1) {
            return $entradas->every(function ($entrada) use ($user1) {
                return $entrada->user_id === $user1->id;
            });
        });
    }

    /**
     * Test: show carga todas las relaciones necesarias
     */
    public function test_show_loads_all_required_relationships()
    {
        $this->actingAsAdmin();
        
        $entrada = EntradaMadera::factory()->create();
        $madera1 = Madera::factory()->create();
        $madera2 = Madera::factory()->create();
        
        EntradasMaderaMaderas::factory()->create([
            'entrada_madera_id' => $entrada->id,
            'madera_id' => $madera1->id
        ]);
        
        EntradasMaderaMaderas::factory()->create([
            'entrada_madera_id' => $entrada->id,
            'madera_id' => $madera2->id
        ]);

        $response = $this->get(route('entrada-madera.show', $entrada));

        $response->assertViewHas('entrada', function ($viewEntrada) {
            return $viewEntrada->relationLoaded('proveedor') &&
                   $viewEntrada->relationLoaded('maderas') &&
                   $viewEntrada->relationLoaded('entradas_madera_maderas') &&
                   $viewEntrada->maderas->count() === 2;
        });
    }
}
