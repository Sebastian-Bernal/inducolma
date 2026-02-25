# Análisis de Sistema de Logs y Queries Constantes

## 1. ESTADO DEL SISTEMA DE LOGS

### ✅ Logs ACTIVOS Y Configurados

El proyecto **SÍ tiene un sistema de logs funcional**:

- **Configuración**: [config/logging.php](config/logging.php)
- **Tipo**: Stack logging con Monolog
- **Ubicación de logs**: `storage/logs/laravel.log`
- **Nivel configurado**: `debug` (definido en `.env.example`)
- **Estado**: Completamente funcional

```php
// Configuración en config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single'],
        'ignore_exceptions' => false,
    ],
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
    ],
]
```

**Para verificar los logs**: Revisa el archivo en `storage/logs/laravel.log`

---

## 2. CAUSAS DE LAS QUERIES CONSTANTES

Se identificaron **TRES PROBLEMAS PRINCIPALES** que causan múltiples queries al ejecutar código en el navegador:

### 🔴 PROBLEMA 1: N+1 Query Problem (CRÍTICO)

**Ubicación**: Controllers sin eager loading

**Ejemplo en [app/Http/Controllers/PedidoController.php](app/Http/Controllers/PedidoController.php#L21)**:
```php
public function index()
{
    // Query 1: Obtiene todos los pedidos
    $pedidos = Pedido::join('clientes','pedidos.cliente_id','=','clientes.id')
                    ->join('diseno_producto_finales',...)
                    ->get([...]);
    
    // En la vista, si accedes a relaciones:
    // Genera N queries adicionales (una por cada pedido)
}
```

**Efecto**: Si tienes 100 pedidos, en lugar de 1 query obtienes 101+ queries.

**Solución implementar**:
```php
// ❌ ACTUAL (MALO)
$pedidos = Pedido::get();

// ✅ CORRECTO
$pedidos = Pedido::with('cliente', 'diseno_producto_final')->get();
```

### 🔴 PROBLEMA 2: Acceso a Propiedades Computadas en Loop

**Ubicación**: [app/Models/Pedido.php](app/Models/Pedido.php#L30)

```php
public function getDiasAttribute()
{
    // Este método hace while loop CADA VEZ que accedes a $pedido->dias
    $actual = date_create(date('Y-m-d'));
    $entrega = date_create($this->fecha_entrega);
    
    while ($actual <= $entrega) {
        // ... cálculos
        date_add($actual, date_interval_create_from_date_string('1 day'));
    }
}
```

**Efecto**: En la vista con `@foreach ($pedidos as $pedido)`, cada acceso a `$pedido->dias` ejecuta este while loop.

**Problema adicional en `getItemsPedidoAttribute()`** ([línea 51](app/Models/Pedido.php#L51)):
```php
public function getItemsPedidoAttribute()
{
    // Realiza MÚLTIPLES queries cada vez que se accede
    $existencias = OrdenProduccion::join(...)->where(...)->get();
    $pedido = Pedido::where('id',$this->id)->get(); // ❌ Query dentro de accessor
    $items = DisenoItem::join(...)->where(...)->get(); // ❌ Otra query
    
    foreach ($items as $item) { // ❌ Loop adicional
        $existencia = $existencias->where(...); // Query implícita
    }
}
```

### 🔴 PROBLEMA 3: Relaciones sin Eager Loading

**Ubicación**: [resources/views/modulos/operaciones/trabajo-maquina/trabajo-ensamble.blade.php](resources/views/modulos/operaciones/trabajo-maquina/trabajo-ensamble.blade.php#L66)

```blade
{{-- Esto genera queries adicionales --}}
@foreach ($pedido->items_pedido as $item)
    {{-- Cada acceso a items_pedido ejecuta getItemsPedidoAttribute() --}}
@endforeach

@foreach ($pedido->diseno_producto_final->insumos as $insumo)
    {{-- Si no hay eager loading, una query por cada pedido --}}
@endforeach
```

---

## 3. EJEMPLO REAL DE IMPACTO

### Escenario: Mostrar 10 Pedidos

**ACTUAL (CON PROBLEMAS)**:
```
1 query: SELECT * FROM pedidos
10 queries: SELECT * FROM items WHERE item_id = ? (una por pedido)
10 queries: SELECT * FROM diseno_items (una por pedido)
10 queries: SELECT * FROM insumos (una por diseno)
50+ queries: Cálculos en el while loop de getDiasAttribute()
─────────────────────
TOTAL: ~80+ queries
```

**ÓPTIMO (CON SOLUCIONES)**:
```
1 query: SELECT * FROM pedidos WITH relaciones
─────────────────────
TOTAL: 1-2 queries máximo
```

---

## 4. RECOMENDACIONES INMEDIATAS

### 4.1 Habilitar Query Logging Temporal

En [routes/web.php](routes/web.php#L48), está comentado un sistema de debugging:

```php
/*
use Illuminate\Support\Facades\DB;
DB::listen(function($query){
    var_dump($query->sql);
});
*/
```

**Para activar y ver TODAS las queries en consola del navegador**, descomenta esto en desarrollo.

### 4.2 Corregir el Modelo Pedido

```php
// app/Models/Pedido.php

// ❌ ELIMINAR - muy lento
public function getItemsPedidoAttribute()
{
    // Este accessor hace múltiples queries
}

// ✅ REEMPLAZAR con método que use eager loading
public function scopeWithItems($query)
{
    return $query->with(['ordenes_produccion.items', 'diseno_producto_final.insumos']);
}

// En controlador:
$pedidos = Pedido::withItems()->get();
```

### 4.3 Optimizar Controllers

```php
// ❌ ACTUAL
$pedidos = Pedido::get();

// ✅ MEJORADO
$pedidos = Pedido::with([
    'cliente',
    'diseno_producto_final.insumos',
    'ordenes_produccion.items'
])->get();
```

### 4.4 Usar Debugbar (Recomendado)

Instala Laravel Debugbar para monitorear queries en tiempo real:

```bash
composer require barryvdh/laravel-debugbar --dev
```

---

## 5. VERIFICACIÓN

Para confirmar que el sistema de logs funciona:

```bash
# Ejecuta esto en la terminal del proyecto
php artisan tinker

# Luego escribe:
Log::info('Test de logs');
```

Verifica que aparezca en `storage/logs/laravel.log`

---

## RESUMEN

| Aspecto | Estado | Acción |
|--------|--------|--------|
| **Sistema de Logs** | ✅ Activo | Verificar `storage/logs/laravel.log` |
| **N+1 Queries** | ❌ Crítico | Agregar `.with()` en controllers |
| **Propiedades Computadas** | ❌ Lento | Refactorizar accessors a métodos |
| **Eager Loading** | ❌ No implementado | Usar `with()` y `load()` |
| **Monitoreo Real** | ⚠️ Manual | Instalar Debugbar |

**Prioridad**: Implementar eager loading reducirá queries de 80+ a 1-2 en la mayoría de vistas.
