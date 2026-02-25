# GUÍA PRÁCTICA DE OPTIMIZACIÓN - Ejemplos de Código

## PASO 1: Activar Monitoreo de Queries en Desarrollo

### Opción A: Usando el código comentado en web.php

En [routes/web.php](routes/web.php), línea 48-51, descomenta y modifica:

```php
<?php

use Illuminate\Support\Facades\DB;

// Al inicio del archivo, dentro de los middlewares:
if (env('APP_DEBUG')) {
    DB::listen(function($query) {
        // Log de todas las queries
        \Log::debug('SQL: ' . $query->sql);
        \Log::debug('Bindings: ' . json_encode($query->bindings));
        \Log::debug('Time: ' . $query->time . 'ms');
        \Log::debug('---');
    });
}

// Resto del código...
```

### Opción B: Usando Laravel Debugbar (RECOMENDADO)

```bash
# Instalar
composer require barryvdh/laravel-debugbar --dev

# Automáticamente se registra en APP_DEBUG=true
# Aparecerá un panel en la esquina inferior derecha del navegador
```

---

## PASO 2: Corregir el Modelo Pedido

### Problema Actual: Acceso a relaciones sin eager loading

**Archivo**: [app/Models/Pedido.php](app/Models/Pedido.php)

#### 2.1 Refactorizar el accessor problémático

```php
<?php

namespace App\Models;

use App\Traits\CheckRelations;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory, CheckRelations;

    protected $fillable = ['estado'];
    
    // ✅ Agregar atributos cacheados
    protected $appends = ['dias'];

    // RELACIONES
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function nombre_diseno()
    {
        return $this->belongsTo(DisenoProductoFinal::class);
    }

    public function ordenes_produccion()
    {
        return $this->hasMany(OrdenProduccion::class, 'pedido_id', 'id');
    }

    public function diseno_producto_final()
    {
        return $this->belongsTo(DisenoProductoFinal::class);
    }

    // ✅ REEMPLAZAR: Este accessor es TOO HEAVY
    // ❌ ANTES:
    // public function getItemsPedidoAttribute() { ... MÚLTIPLES QUERIES ... }
    
    // ✅ DESPUÉS: Usar método dedicado
    public function obtenerItemsPedido()
    {
        // Esto se llama SOLO CUANDO SE NECESITA, no automáticamente
        $existencias = OrdenProduccion::whereHas('pedido', function($q) {
                            $q->where('pedidos.id', $this->id);
                        })
                        ->with('item')
                        ->get(['cantidad', 'item_id'])
                        ->keyBy('item_id');

        $diseno = $this->diseno_producto_final;
        if (!$diseno) return collect();

        $items = $diseno->items()->get();
        $items_pedido = collect();

        foreach ($items as $item) {
            $existencia = $existencias->get($item->id);
            
            $items_pedido->push((object)[
                'id' => $item->pivot->id ?? null,
                'descripcion' => $item->descripcion,
                'cantidad' => $item->pivot->cantidad ?? 0,
                'existencias' => $item->existencias,
                'total' => ($item->pivot->cantidad ?? 0) * $this->cantidad - ($existencia->cantidad ?? 0),
                'item_id' => $item->id,
            ]);
        }

        return $items_pedido;
    }

    // ✅ OPTIMIZAR: getDiasAttribute
    public function getDiasAttribute()
    {
        // Cacheado en memoria para evitar recálculos
        if (isset($this->_cached_dias)) {
            return $this->_cached_dias;
        }

        $actual = date_create(date('Y-m-d'));
        $entrega = date_create($this->fecha_entrega);

        if ($actual > $entrega) {
            return $this->_cached_dias = 0;
        }

        $dias = 0;
        while ($actual <= $entrega) {
            $day_week = $actual->format('w');
            if ($day_week > 0 && $day_week < 6) {
                $dias += 1;
            }
            date_add($actual, date_interval_create_from_date_string('1 day'));
        }

        return $this->_cached_dias = $dias - 1;
    }

    // ✅ OPTIMIZAR: datos() - usar con() en lugar de múltiples joins
    public function datos()
    {
        // ❌ ANTES: Múltiples joins
        // $pedido = Pedido::join('clientes',...)
        //              ->join('diseno_producto_finales',...)
        //              ->get();

        // ✅ DESPUÉS: Eager loading
        return $this->load(['cliente', 'diseno_producto_final']);
    }

    // ✅ SCOPES para reutilizar en controllers
    public function scopeConRelaciones($query)
    {
        return $query->with([
            'cliente',
            'diseno_producto_final.insumos',
            'ordenes_produccion.items',
        ]);
    }

    public function scopeParaIndice($query)
    {
        return $query->with('cliente', 'diseno_producto_final')
                     ->select([
                        'id', 'cantidad', 'created_at', 'fecha_entrega', 
                        'estado', 'cliente_id', 'diseno_producto_final_id'
                     ]);
    }
}
```

---

## PASO 3: Corregir Controllers

### Ejemplo: PedidoController

**Archivo**: [app/Http/Controllers/PedidoController.php](app/Http/Controllers/PedidoController.php)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        // ❌ ANTES: Sin eager loading
        // $pedidos = Pedido::join('clientes',...)
        //                 ->join('diseno_producto_finales',...)
        //                 ->get();

        // ✅ DESPUÉS: Con eager loading
        $pedidos = Pedido::paraIndice()->orderBy('fecha_entrega', 'asc')->get();
        
        $clientes = Cliente::select('id', 'nombre', 'razon_social')->get();

        return view('modulos.administrativo.pedidos.index', compact('pedidos', 'clientes'));
    }

    public function show(Pedido $pedido)
    {
        // ✅ Cargar relaciones necesarias
        $pedido->load([
            'cliente',
            'diseno_producto_final.insumos',
            'ordenes_produccion.items'
        ]);

        return view('modulos.administrativo.pedidos.show', compact('pedido'));
    }

    public function edit(Pedido $pedido)
    {
        $pedido->load('cliente', 'diseno_producto_final');
        
        $clientes = Cliente::select('id', 'nombre')->get();
        $disenos = DisenoProductoFinal::select('id', 'descripcion')->get();

        return view('modulos.administrativo.pedidos.edit', 
            compact('pedido', 'clientes', 'disenos'));
    }
}
```

---

## PASO 4: Optimizar Vistas (Blade)

### Antes: Genera múltiples queries

**Archivo**: [resources/views/modulos/operaciones/trabajo-maquina/trabajo-ensamble.blade.php](resources/views/modulos/operaciones/trabajo-maquina/trabajo-ensamble.blade.php#L66)

```blade
{{-- ❌ ANTES - Genera queries adicionales --}}
@foreach ($pedido->items_pedido as $item)
    {{-- Cada acceso a items_pedido ejecuta obtenerItemsPedido() con queries --}}
    <td>{{ $item->descripcion }}</td>
@endforeach

{{-- ❌ ANTES - Sin eager loading de relación --}}
@foreach ($pedido->diseno_producto_final->insumos as $insumo)
    {{-- Si no hay with('diseno_producto_final.insumos'), genera N queries --}}
    <td>{{ $insumo->nombre }}</td>
@endforeach
```

### Después: Optimizado

```blade
{{-- ✅ DESPUÉS - Datos ya cargados en el controller --}}
@if ($pedido->items_cacheados)
    @foreach ($pedido->items_cacheados as $item)
        <td>{{ $item->descripcion }}</td>
    @endforeach
@endif

{{-- ✅ DESPUÉS - Relación eager loaded --}}
@forelse ($pedido->diseno_producto_final?->insumos ?? [] as $insumo)
    <td>{{ $insumo->nombre }}</td>
@empty
    <td>Sin insumos</td>
@endforelse
```

---

## PASO 5: Patrón Recomendado en Controllers

```php
<?php

class MiController extends Controller
{
    public function index()
    {
        // 1️⃣ Definir qué necesito
        $datos = Modelo::with([
            'relacion1',
            'relacion2.sub_relacion',
            'relacion3' => function($q) {
                $q->where('estado', 'activo');
            }
        ])
        ->select('id', 'nombre', 'relacion1_id', 'relacion2_id') // Solo columnas necesarias
        ->get();

        // 2️⃣ Pasar al view
        return view('vista', compact('datos'));

        // RESULTADO: 1 query en lugar de N queries
    }

    public function show(Modelo $modelo)
    {
        // 3️⃣ Route Model Binding + Eager Loading
        $modelo->load([
            'relacion1.sub_relacion',
            'relacion2' => function($q) {
                $q->latest()->limit(10);
            }
        ]);

        return view('detalle', compact('modelo'));
    }
}
```

---

## PASO 6: Testing - Verificar Mejoras

Crea un test para confirmar que las optimizaciones funcionan:

```php
<?php
// tests/Feature/OptimizacionQueriesTest.php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class OptimizacionQueriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_pedidos_index_debe_ejecutar_pocas_queries()
    {
        // Crear datos de prueba
        \App\Models\Cliente::factory()->count(5)->create();
        \App\Models\DisenoProductoFinal::factory()->count(5)->create();
        Pedido::factory()->count(10)->create();

        DB::flushQueryLog();
        DB::enableQueryLog();

        // Ejecutar la acción
        $response = $this->get(route('pedidos.index'));

        // Verificar
        $queryCount = count(DB::getQueryLog());
        
        // Debería ser máximo 3 queries (pedidos + clientes + diseños)
        $this->assertLessThanOrEqual(3, $queryCount, 
            "Se ejecutaron {$queryCount} queries, se esperaban máximo 3");

        $response->assertSuccessful();
    }
}
```

Ejecuta:
```bash
php artisan test tests/Feature/OptimizacionQueriesTest.php
```

---

## RESUMEN DE CAMBIOS

| Problema | Solución | Ubicación |
|----------|----------|-----------|
| N+1 Queries | Usar `.with()` en controllers | Controllers |
| Accessor pesado | Refactorizar a método | Models |
| Relaciones sin cargar | Eager loading en controlador | Controllers/Models |
| Múltiples joins | Usar `.with()` en lugar de `.join()` | Controllers |
| Cálculos repetidos | Cachear en propiedad privada | Models |

**Impacto esperado**: Reducción de 80+ queries a 2-3 queries en listados principales.
