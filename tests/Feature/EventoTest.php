<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TipoEvento;
use App\Models\Evento;

class EventoTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_evento_with_valid_tipoEvento_creates_evento()
    {
        $user = User::factory()->create(['rol_id' => 3]);
        $tipo = TipoEvento::create(['tipo_evento' => 'MAQUINA']);

        $response = $this->actingAs($user)->post(route('eventos.store'), [
            'descripcion' => 'EVENTO PRUEBA',
            'tipoEvento' => $tipo->id,
        ]);

        $response->assertRedirect(route('eventos.index'));
        $this->assertDatabaseHas('eventos', [
            'descripcion' => 'EVENTO PRUEBA',
            'tipo_evento_id' => $tipo->id,
        ]);
    }

    public function test_update_evento_changes_tipo_evento()
    {
        $user = User::factory()->create(['rol_id' => 3]);
        $tipo1 = TipoEvento::create(['tipo_evento' => 'MAQUINA']);
        $tipo2 = TipoEvento::create(['tipo_evento' => 'USUARIO']);

        $evento = Evento::create([
            'descripcion' => 'ORIGINAL',
            'tipo_evento_id' => $tipo1->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->put(route('eventos.update', $evento), [
            'descripcion' => 'ORIGINAL',
            'tipoEvento' => $tipo2->id,
        ]);

        $response->assertRedirect(route('eventos.index'));
        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'tipo_evento_id' => $tipo2->id,
        ]);
    }

    public function test_store_evento_fails_if_tipoEvento_invalid()
    {
        $user = User::factory()->create(['rol_id' => 3]);
        $response = $this->actingAs($user)->post(route('eventos.store'), [
            'descripcion' => 'EVENTO INVALIDO',
            'tipoEvento' => 9999,
        ]);

        $response->assertSessionHasErrors('tipoEvento');
        $this->assertDatabaseMissing('eventos', [
            'descripcion' => 'EVENTO INVALIDO',
        ]);
    }
}
