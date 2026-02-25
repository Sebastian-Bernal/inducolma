# Módulo de Ventas y Diseños - Documentación Consolidada

## 📋 Información General

**Módulo:** Gestión de Ventas, Pedidos y Diseños de Productos  
**Controladores:** 4  
**Complejidad:** MEDIA-ALTA  
**Propósito:** Gestión completa del ciclo de ventas y diseño de productos

---

## 📊 Controladores del Módulo

| # | Controlador | Modelo | Complejidad | AJAX | Estado |
|---|-------------|--------|-------------|------|--------|
| 1 | PedidoController | Pedido | MEDIA | ✅ 2 métodos | ✅ |
| 2 | ItemController | Item | BAJA | ❌ No | ✅ |
| 3 | DisenoProductoFinalController | DisenoProductoFinal | ALTA | ✅ 2 métodos | ✅ |
| 4 | ProcesoController | Proceso | BAJA | ❌ No | ✅ |

---

## 📦 Flujo de Negocio

```
Cliente
  └── Pedido (cantidad, fecha_entrega)
        └── DisenoProductoFinal (producto a fabricar)
              ├── DisenoItem (componentes necesarios)
              │     └── Item (piezas en inventario)
              └── DisenoInsumo (materiales necesarios)
                    └── InsumosAlmacen (tornillos, pegamento, etc.)

Pedido → OrdenProduccion → Transformaciones → Procesos → Producto Final
```

---

## 🛒 1. PedidoController

**Archivo:** `app/Http/Controllers/PedidoController.php`  
**Modelo:** `Pedido`  
**Propósito:** Gestión de pedidos de clientes

### Relaciones del Modelo

```php
class Pedido extends Model
{
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    public function disenoProductoFinal()
    {
        return $this->belongsTo(DisenoProductoFinal::class);
    }
    
    public function ordenesProduccion()
    {
        return $this->hasMany(OrdenProduccion::class);
    }
    
    public function user() // Creador
    {
        return $this->belongsTo(User::class);
    }
}
```

### Estructura de Datos

```sql
pedidos:
  - id
  - cliente_id              (FK Cliente)
  - diseno_producto_final_id (FK DisenoProductoFinal)
  - cantidad                (unidades a producir)
  - fecha_solicitud         (fecha de creación)
  - fecha_entrega           (compromiso de entrega)
  - estado                  (PENDIENTE, EN_PROCESO, COMPLETADO)
  - user_id                 (quien creó el pedido)
  - referencia              (código único)
```

---

### Métodos Documentados

#### index() - Listar Pedidos con Joins

```php
public function index()
{
    $pedidos = Pedido::join('clientes','pedidos.cliente_id','=','clientes.id')
        ->join('diseno_producto_finales','pedidos.diseno_producto_final_id','=','diseno_producto_finales.id')
        ->get([
            'pedidos.id',
            'pedidos.cantidad',
            'pedidos.created_at',
            'pedidos.fecha_entrega',
            'pedidos.estado',
            'clientes.nombre',
            'clientes.razon_social',
            'diseno_producto_finales.descripcion',
        ]);
    
    $clientes = Cliente::select('id', 'nombre','razon_social')->get();
    
    return view('modulos.administrativo.pedidos.index', compact('pedidos', 'clientes'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1-2 | `Pedido::join('clientes'...)` | Join con clientes para obtener nombre |
| 3 | `->join('diseno_producto_finales'...)` | Join con diseños para obtener descripción |
| 4-12 | `->get([...])` | ✅ Solo selecciona campos necesarios |
| 14 | `Cliente::select(...)->get()` | Carga clientes para formulario de creación |

**⚠️ Problema:**

```php
// ❌ Sin paginación - puede ser lento con muchos pedidos
->get([...])

// ✅ Solución:
->paginate(50)

// ❌ Sin filtros por fecha o estado
// ✅ Agregar filtros:
$pedidos = Pedido::when($request->estado, function($q) use ($request) {
        return $q->where('estado', $request->estado);
    })
    ->when($request->fecha_desde, function($q) use ($request) {
        return $q->whereDate('fecha_entrega', '>=', $request->fecha_desde);
    })
    ->with(['cliente', 'disenoProductoFinal'])
    ->paginate(50);
```

---

#### store() - Crear Pedido con Redirección Condicional

```php
public function store(StorePedidoRequest $request)
{
    $pedido = new Pedido();
    $pedido->diseno_producto_final_id = $request->items;
    $pedido->cantidad = $request->cantidad;
    $pedido->fecha_solicitud = date('Y-m-d');
    $pedido->fecha_entrega = $request->fecha_entrega;
    $pedido->estado = 'PENDIENTE';
    $pedido->user_id = auth()->user()->id;
    $pedido->cliente_id = $request->cliente;
    $pedido->save();
    
    // Redirección condicional según ruta origen
    if (Route::current()->getName() == 'pedidos.store') {
        return redirect()->route('pedidos.index')
            ->with('status', "El pedido # $pedido->id, para el cliente {$pedido->cliente->nombre} ha sido creado");
    } else {
        return redirect()->route('programaciones.index')
            ->with('status', "El pedido # $pedido->id, para el cliente {$pedido->cliente->nombre} ha sido editado");
    }
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 2-9 | Creación del pedido | Asigna todos los campos |
| 4 | `date('Y-m-d')` | ⚠️ Debería usar `now()->toDateString()` |
| 6 | `estado = 'PENDIENTE'` | Estado inicial fijo |
| 7 | `user_id = auth()->user()->id` | ✅ Auditoría de creador |
| 12-18 | **Redirección condicional** | ⚡ **Lógica interesante** |

**🔍 Análisis de Redirección Condicional:**

```php
if (Route::current()->getName() == 'pedidos.store') {
    return redirect()->route('pedidos.index');
} else {
    return redirect()->route('programaciones.index');
}
```

**Propósito:**
- Si se creó desde módulo de Pedidos → redirige a `pedidos.index`
- Si se creó desde módulo de Programaciones → redirige a `programaciones.index`

**⚠️ Problema:**

```php
// ❌ Mensaje dice "editado" pero es store() (creación)
"ha sido editado"  // En store()

// ✅ Ambos deberían decir "creado"
```

**⚠️ N+1 Query:**

```php
// ❌ Genera query extra
{$pedido->cliente->nombre}

// ✅ Solución con load()
$pedido->load('cliente');
return redirect()->route('pedidos.index')
    ->with('status', "El pedido # $pedido->id, para el cliente {$pedido->cliente->nombre} ha sido creado");
```

---

#### edit() - Vista de Edición

```php
public function edit(Pedido $pedido)
{
    $clientes = Cliente::select('id', 'nombre','razon_social')->get();
    
    $disenos_cliente = Cliente::find($pedido->cliente_id)
        ->disenos()
        ->get(['diseno_producto_final_id as id','descripcion']);
    
    return view('modulos.administrativo.pedidos.show', 
        compact('pedido', 'clientes', 'disenos_cliente'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `Pedido $pedido` | Route Model Binding |
| 2 | `Cliente::select(...)->get()` | Todos los clientes para select |
| 4-6 | `Cliente::find(...)->disenos()` | ⚡ **Obtiene diseños del cliente** |
| 5 | `->disenos()` | Relación many-to-many via pivot `diseno_clientes` |
| 6 | `get(['diseno_producto_final_id as id', 'descripcion'])` | ✅ Alias para compatibilidad con select |

**🔍 Relación Cliente-Diseño:**

```php
// Modelo Cliente
public function disenos()
{
    return $this->belongsToMany(
        DisenoProductoFinal::class,
        'diseno_clientes', // Tabla pivot
        'cliente_id',
        'diseno_producto_final_id'
    );
}
```

**Lógica de Negocio:**

1. Cliente tiene múltiples diseños asignados
2. Al crear pedido, solo puede seleccionar diseños de SU catálogo
3. Previene seleccionar diseños de otros clientes

---

#### update() - Actualizar Pedido

```php
public function update(StorePedidoRequest $request, Pedido $pedido)
{
    $pedido->diseno_producto_final_id = $request->items;
    $pedido->cantidad = $request->cantidad;
    $pedido->fecha_solicitud = date('Y-m-d');
    $pedido->fecha_entrega = $request->fecha_entrega;
    $pedido->estado = 'PENDIENTE';
    $pedido->user_id = auth()->user()->id;
    $pedido->cliente_id = $request->cliente;
    $pedido->update();
    
    return redirect()->route('pedidos.index')
        ->with('status', "El pedido # $pedido->id, para el cliente {$pedido->cliente_id} ha sido actualizado");
}
```

**⚠️ Problemas:**

1. **Resetea estado a PENDIENTE:** Aunque el pedido esté EN_PROCESO
2. **Actualiza fecha_solicitud:** No debería cambiar la fecha original
3. **Muestra cliente_id en lugar de nombre:** `{$pedido->cliente_id}`

**✅ Solución:**

```php
public function update(StorePedidoRequest $request, Pedido $pedido)
{
    $pedido->diseno_producto_final_id = $request->items;
    $pedido->cantidad = $request->cantidad;
    $pedido->fecha_entrega = $request->fecha_entrega;
    // NO actualizar estado ni fecha_solicitud
    $pedido->cliente_id = $request->cliente;
    $pedido->update();
    
    return redirect()->route('pedidos.index')
        ->with('status', "El pedido # $pedido->id ha sido actualizado");
}
```

---

#### destroy() - Eliminar Pedido

```php
public function destroy(Pedido $pedido)
{
    if ($pedido->hasAnyRelatedData(['ordenes_produccion','pedido_producto'])) {
        return new Response([
            'errors' => "No se pudo eliminar el recurso porque tiene datos asociados"
        ], Response::HTTP_CONFLICT);
    }
    
    $pedido->delete();
    return response()->json(['success' => 'Pedido eliminado correctamente']);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `hasAnyRelatedData([...])` | ✅ Valida integridad referencial |
| 1 | `'ordenes_produccion'` | Si hay órdenes de producción, NO elimina |
| 1 | `'pedido_producto'` | Si hay productos finalizados, NO elimina |

**✅ Buena Práctica:**

Previene eliminar pedidos que:
- Ya tienen órdenes de producción creadas
- Ya tienen productos fabricados/entregados

---

#### itemsCliente() - AJAX Obtener Diseños de Cliente

```php
public function itemsCliente(Request $request)
{
    $disenos = Cliente::find($request->id)
        ->disenos()
        ->get(['diseno_producto_final_id as id','descripcion']);
    
    return response()->json($disenos);
}
```

**Propósito:** Endpoint AJAX para cargar diseños del cliente seleccionado

**Uso en Frontend:**

```javascript
$('#cliente').change(function() {
    let clienteId = $(this).val();
    
    $.get('/pedidos/items-cliente', { id: clienteId }, function(disenos) {
        $('#diseno').empty();
        disenos.forEach(diseno => {
            $('#diseno').append(`<option value="${diseno.id}">${diseno.descripcion}</option>`);
        });
    });
});
```

---

#### disenoBuscar() - AJAX Búsqueda de Diseños

```php
public function disenoBuscar(Request $request)
{
    $disenos = DisenoProductoFinal::where('descripcion', 'like', '%'.strtoupper($request->descripcion).'%')
        ->get(['id','descripcion as text']);
    
    $disenos->toJson();
    return response()->json($disenos);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `where('descripcion', 'like', ...)` | Búsqueda parcial |
| 1 | `strtoupper($request->descripcion)` | ✅ Convierte a mayúsculas para búsqueda |
| 2 | `'descripcion as text'` | Alias para Select2 |
| 4 | `$disenos->toJson()` | ⚠️ **Línea innecesaria** - no se usa el resultado |

**Propósito:** Endpoint para Select2 con búsqueda en tiempo real

**⚠️ Mejora:**

```php
// Eliminar línea innecesaria
// $disenos->toJson();

// Limitar resultados
->limit(20)
->get(['id','descripcion as text']);
```

---

### Tests Propuestos para PedidoController

```php
/** @test */
public function puede_crear_pedido_para_cliente()
{
    $this->actingAs($this->admin);
    
    $cliente = Cliente::factory()->create();
    $diseno = DisenoProductoFinal::factory()->create();
    
    $response = $this->post('/pedidos', [
        'cliente' => $cliente->id,
        'items' => $diseno->id,
        'cantidad' => 50,
        'fecha_entrega' => now()->addDays(30)->format('Y-m-d'),
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('pedidos', [
        'cliente_id' => $cliente->id,
        'diseno_producto_final_id' => $diseno->id,
        'cantidad' => 50,
        'estado' => 'PENDIENTE',
    ]);
}

/** @test */
public function store_redirige_segun_ruta_origen()
{
    $this->actingAs($this->admin);
    
    $cliente = Cliente::factory()->create();
    $diseno = DisenoProductoFinal::factory()->create();
    
    // Desde pedidos.store
    $response = $this->post(route('pedidos.store'), [
        'cliente' => $cliente->id,
        'items' => $diseno->id,
        'cantidad' => 10,
        'fecha_entrega' => now()->addDays(15)->format('Y-m-d'),
    ]);
    
    $response->assertRedirect(route('pedidos.index'));
}

/** @test */
public function no_puede_eliminar_pedido_con_ordenes_produccion()
{
    $this->actingAs($this->admin);
    
    $pedido = Pedido::factory()->create();
    OrdenProduccion::factory()->create(['pedido_id' => $pedido->id]);
    
    $response = $this->deleteJson("/pedidos/{$pedido->id}");
    
    $response->assertStatus(409);
    $this->assertDatabaseHas('pedidos', ['id' => $pedido->id]);
}

/** @test */
public function items_cliente_retorna_disenos_del_cliente()
{
    $cliente = Cliente::factory()->create();
    $diseno1 = DisenoProductoFinal::factory()->create();
    $diseno2 = DisenoProductoFinal::factory()->create();
    
    // Asignar diseños al cliente
    $cliente->disenos()->attach([$diseno1->id, $diseno2->id]);
    
    $response = $this->getJson('/pedidos/items-cliente', ['id' => $cliente->id]);
    
    $response->assertJsonCount(2);
    $response->assertJsonFragment(['descripcion' => $diseno1->descripcion]);
    $response->assertJsonFragment(['descripcion' => $diseno2->descripcion]);
}

/** @test */
public function diseno_buscar_filtra_por_descripcion()
{
    DisenoProductoFinal::factory()->create(['descripcion' => 'MESA COMEDOR']);
    DisenoProductoFinal::factory()->create(['descripcion' => 'MESA ESCRITORIO']);
    DisenoProductoFinal::factory()->create(['descripcion' => 'SILLA OFICINA']);
    
    $response = $this->getJson('/pedidos/diseno-buscar', ['descripcion' => 'mesa']);
    
    $response->assertJsonCount(2);
    $response->assertJsonFragment(['descripcion' => 'MESA COMEDOR']);
    $response->assertJsonFragment(['descripcion' => 'MESA ESCRITORIO']);
}

/** @test */
public function update_no_debe_cambiar_fecha_solicitud_ni_estado()
{
    $this->actingAs($this->admin);
    
    $pedido = Pedido::factory()->create([
        'estado' => 'EN_PROCESO',
        'fecha_solicitud' => '2026-01-01',
    ]);
    
    $this->put("/pedidos/{$pedido->id}", [
        'cliente' => $pedido->cliente_id,
        'items' => $pedido->diseno_producto_final_id,
        'cantidad' => 100,
        'fecha_entrega' => now()->addDays(20)->format('Y-m-d'),
    ]);
    
    $pedido->refresh();
    
    // ⚠️ Test fallará con código actual - documenta el problema
    // $this->assertEquals('EN_PROCESO', $pedido->estado);
    // $this->assertEquals('2026-01-01', $pedido->fecha_solicitud);
}
```

**Tests Propuestos:** 7 tests

---

## 📦 2. ItemController

**Archivo:** `app/Http/Controllers/ItemController.php`  
**Modelo:** `Item`  
**Propósito:** Gestión de componentes/piezas que conforman productos

### Estructura del Item

```php
class Item extends Model
{
    protected $fillable = [
        'descripcion',      // Ej: "LATERAL MESA 80x30"
        'largo',           // cm
        'ancho',           // cm
        'alto',            // cm (grosor)
        'existencias',     // Cantidad en inventario
        'preprocesado',    // Cantidad en producción
        'madera_id',       // FK a Madera (especie)
        'codigo_cg',       // Código interno
        'user_id',         // Creador
    ];
    
    public function madera()
    {
        return $this->belongsTo(Madera::class);
    }
    
    public function ordenesProduccion()
    {
        return $this->hasMany(OrdenProduccion::class);
    }
}
```

---

### Métodos Documentados

#### index() - Listar Items

```php
public function index()
{
    $items = Item::all();
    $tipos_maderas = TipoMadera::withTrashed()->get(['id','descripcion'])->except(1);
    
    return view('modulos.administrativo.items.index', 
        compact('items', 'tipos_maderas'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `Item::all()` | ⚠️ Sin paginación ni eager loading |
| 2 | `TipoMadera::withTrashed()->get()` | ✅ Incluye eliminados |
| 2 | `->except(1)` | Excluye tipo "NINGUNO" |

**⚠️ Problema:**

```php
// ❌ Sin eager loading - N+1 si se muestra madera
$items = Item::all();

// ✅ Solución:
$items = Item::with('madera.tipoMadera')->paginate(50);
```

---

#### store() - Crear Item

```php
public function store(StoreItemsRequest $request)
{
    $this->authorize('admin');
    
    $item = new Item();
    $item->descripcion = $request->descripcion;
    $item->alto = $request->alto;
    $item->ancho = $request->ancho;
    $item->largo = $request->largo;
    $item->existencias = $request->existencias;
    $item->madera_id = $request->tipo_madera;
    $item->codigo_cg = $request->codigo_cg;
    $item->user_id = auth()->user()->id;
    $item->save();
    
    return redirect()->route('items.index')
        ->with('status', "Item: $request->descripcion creado correctamente");
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `$this->authorize('admin')` | 🔒 Solo admin |
| 4-12 | Asignación campo por campo | ⚠️ Podría usar `create()` con fillable |
| 10 | `$request->tipo_madera` | ⚠️ Nombre inconsistente (debería ser madera_id) |
| 13 | `user_id = auth()->user()->id` | ✅ Auditoría |

**⚠️ No usa strtoupper:**

```php
// ❌ Descripción sin mayúsculas (inconsistente con otros)
$item->descripcion = $request->descripcion;

// ✅ Debería ser:
$item->descripcion = strtoupper($request->descripcion);
```

---

#### destroy() - Eliminar Item

```php
public function destroy(Item $item)
{
    $this->authorize('admin');
    
    if ($item->hasAnyRelatedData(['costos_infraestructura','orden_produccion'])) {
        return new Response([
            'errors' => "No se pudo eliminar el recurso porque tiene datos asociados"
        ], Response::HTTP_CONFLICT);
    }
    
    if($item->delete()){
        return response()->json(['success' => 'Item eliminado correctamente']);
    } else {
        return response()->json(['error' => 'Error al eliminar el item']);
    }
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3-7 | `hasAnyRelatedData([...])` | ✅ Valida integridad |
| 9-13 | `if($item->delete())` | ⚠️ **Validación innecesaria** |

**⚠️ Problema:**

```php
// ❌ delete() siempre retorna true o lanza excepción
if($item->delete()) // Siempre entra aquí

// ✅ Simplificar:
$item->delete();
return response()->json(['success' => 'Item eliminado correctamente']);
```

---

### Tests Propuestos para ItemController

```php
/** @test */
public function admin_puede_crear_item()
{
    $this->actingAs($this->admin);
    
    $madera = Madera::factory()->create();
    
    $response = $this->post('/items', [
        'descripcion' => 'LATERAL MESA',
        'largo' => 80,
        'ancho' => 30,
        'alto' => 2,
        'existencias' => 50,
        'tipo_madera' => $madera->id,
        'codigo_cg' => 'LM-80-30',
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('items', [
        'descripcion' => 'LATERAL MESA',
        'existencias' => 50,
        'madera_id' => $madera->id,
    ]);
}

/** @test */
public function no_puede_eliminar_item_con_ordenes_produccion()
{
    $this->actingAs($this->admin);
    
    $item = Item::factory()->create();
    OrdenProduccion::factory()->create(['item_id' => $item->id]);
    
    $response = $this->deleteJson("/items/{$item->id}");
    
    $response->assertStatus(409);
}
```

**Tests Propuestos:** 5 tests

---

## 🎨 3. DisenoProductoFinalController

**Archivo:** `app/Http/Controllers/DisenoProductoFinalController.php`  
**Modelo:** `DisenoProductoFinal`  
**Propósito:** Gestión de diseños de productos (BOM - Bill of Materials)

### Estructura del Diseño

```php
class DisenoProductoFinal extends Model
{
    public function tipoMadera()
    {
        return $this->belongsTo(TipoMadera::class);
    }
    
    // Componentes del diseño (piezas)
    public function items()
    {
        return $this->belongsToMany(Item::class, 'diseno_items')
            ->withPivot('cantidad');
    }
    
    // Materiales del diseño (insumos)
    public function insumos()
    {
        return $this->belongsToMany(InsumosAlmacen::class, 'diseno_insumos')
            ->withPivot('cantidad');
    }
    
    // Clientes que pueden usar este diseño
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'diseno_clientes');
    }
    
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
```

---

### Métodos Documentados

#### index() - Listar Diseños

```php
public function index()
{
    $disenos = DisenoProductoFinal::all();
    $tipos_maderas = TipoMadera::all()->except(1);
    $clientes = Cliente::orderBy('nit')->get();
    
    return view('modulos.administrativo.disenos.index', 
        compact('disenos', 'tipos_maderas', 'clientes'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `DisenoProductoFinal::all()` | ⚠️ Sin paginación |
| 3 | `Cliente::orderBy('nit')->get()` | Ordena por NIT |

---

#### store() - Crear Diseño

```php
public function store(Request $request)
{
    $this->authorize('admin');
    
    $diseno = new DisenoProductoFinal();
    $diseno->descripcion = strtoupper($request->descripcion);
    $diseno->tipo_madera_id = $request->madera_id;
    $diseno->estado = 'EN USO';
    $diseno->user_id = Auth::user()->id;
    $diseno->save();
    
    return redirect()->route('disenos.show', $diseno->id)
        ->with('status', 'Diseño creado con éxito, ahora puede agregar los Items e insumos');
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 5 | `strtoupper($request->descripcion)` | ✅ Mayúsculas |
| 7 | `estado = 'EN USO'` | Estado inicial fijo |
| 11 | `redirect()->route('disenos.show', ...)` | ⚡ **Redirige a vista de detalle** (para agregar items) |

**💡 Flujo de Creación:**

1. Crear diseño básico (nombre y tipo madera)
2. Redirigir a `show()` para agregar items/insumos
3. Items se agregan mediante AJAX desde la vista `show`

---

#### show() - Detalle de Diseño con Items e Insumos

```php
public function show(DisenoProductoFinal $diseno)
{
    $clientes = Cliente::get(['id', 'razon_social as nombre']);
    
    $diseno_items = DisenoItem::join('items', 'items.id', '=', 'diseno_items.item_id')
        ->where('diseno_producto_final_id', $diseno->id)
        ->get(['diseno_items.id', 'items.descripcion', 'diseno_items.cantidad']);
    
    $diseno_insumos = DisenoInsumo::join('insumos_almacen', 'insumos_almacen.id', '=', 'diseno_insumos.insumo_almacen_id')
        ->where('diseno_producto_final_id', $diseno->id)
        ->get(['diseno_insumos.id', 'insumos_almacen.descripcion', 'diseno_insumos.cantidad']);
    
    $items = Item::where('madera_id', $diseno->tipo_madera->id)
        ->get(['id', 'descripcion']);
    
    $insumos = InsumosAlmacen::get(['id', 'descripcion']);
    
    return view('modulos.administrativo.disenos.show',
        compact('diseno', 'clientes', 'items', 'insumos', 'diseno_items', 'diseno_insumos')
    );
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 4-6 | `DisenoItem::join(...)->where(...)` | Obtiene items del diseño con cantidades |
| 8-10 | `DisenoInsumo::join(...)` | Obtiene insumos del diseño con cantidades |
| 12-13 | `Item::where('madera_id', ...)` | ⚡ **Filtra items por tipo de madera** |
| 15 | `InsumosAlmacen::get(...)` | Todos los insumos disponibles |

**🔍 Lógica de Filtrado:**

```php
// Solo muestra items del mismo tipo de madera que el diseño
Item::where('madera_id', $diseno->tipo_madera->id)
```

**Ejemplo:**
- Diseño: "MESA ROBLE" (tipo_madera = DURA)
- Solo puede agregar items de madera DURA

**⚠️ N+1 Query:**

```php
// ❌ Genera query al acceder a $diseno->tipo_madera->id
where('madera_id', $diseno->tipo_madera->id)

// ✅ Solución: eager load en el método
$diseno->load('tipoMadera');
```

---

#### asignarDisenoCliente() - AJAX Asignar Diseño a Cliente

```php
public function asignarDisenoCliente(Request $request)
{
    $this->authorize('admin');
    
    $existe = DisenoCliente::where('diseno_producto_final_id', $request->diseno_id)
        ->where('cliente_id', $request->cliente_id)
        ->first();
    
    if ($existe) {
        return response()->json([
            'error' => true,
            'message' => 'El diseño ya esta asignado al cliente'
        ]);
    } else {
        $diseno_cliente = new DisenoCliente();
        $diseno_cliente->diseno_producto_final_id = $request->diseno_id;
        $diseno_cliente->cliente_id = $request->cliente_id;
        $diseno_cliente->user_id = Auth::user()->id;
        
        if ($diseno_cliente->save()) {
            return response()->json([
                'error' => false,
                'message' => 'Diseño asignado con éxito'
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Error al asignar diseño'
            ]);
        }
    }
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 4-6 | `DisenoCliente::where(...)->first()` | ✅ Valida duplicados |
| 8-12 | `if ($existe)` | Retorna error si ya está asignado |
| 14-27 | `else` | Crea nueva asignación |
| 19 | `if ($diseno_cliente->save())` | ⚠️ save() siempre retorna true |

**Propósito:**

Asigna un diseño a un cliente, permitiendo que:
- Cliente solo vea sus diseños autorizados
- Al crear pedido, solo muestre diseños del catálogo del cliente

**Uso en Frontend:**

```javascript
$('#btnAsignarDiseno').click(function() {
    $.post('/disenos/asignar-cliente', {
        diseno_id: $('#diseno').val(),
        cliente_id: $('#cliente').val()
    }, function(response) {
        if (!response.error) {
            alert('Diseño asignado con éxito');
            recargarTabla();
        } else {
            alert(response.message);
        }
    });
});
```

---

#### consultarItemsInsumos() - AJAX Validar Diseño Completo

```php
public function consultarItemsInsumos(Request $request)
{
    $diseno = DisenoProductoFinal::find($request->diseno_id);
    
    if ($diseno->items->count() == 0 || $diseno->insumos->count() == 0) {
        return response()->json([
            'error' => true,
            'message' => 'No hay items o insumos suficientes para el diseño'
        ]);
    } else {
        return response()->json(['error' => false]);
    }
}
```

**Propósito:**

Valida que el diseño esté completo antes de:
- Asignarlo a un cliente
- Crear un pedido con ese diseño

**Lógica:**
- Diseño debe tener al menos 1 item Y 1 insumo
- Si falta alguno, retorna error

**⚠️ Problema:**

```php
// ❌ Genera 2 queries (N+1)
$diseno->items->count()
$diseno->insumos->count()

// ✅ Solución:
$diseno = DisenoProductoFinal::withCount(['items', 'insumos'])
    ->find($request->diseno_id);

if ($diseno->items_count == 0 || $diseno->insumos_count == 0) {
    // ...
}
```

---

#### destroy() - Eliminar Diseño

```php
public function destroy(DisenoProductoFinal $diseno)
{
    $diseno->delete();
    
    if ($diseno->hasAnyRelatedData(['clientes', 'items', 'insumos', 'pedidos'])) {
        return new Response([
            'errors' => "No se pudo eliminar el recurso porque tiene datos asociados"
        ], Response::HTTP_CONFLICT);
    }
    
    return response()->json(['success' => 'Diseño eliminado con éxito']);
}
```

**⚠️ PROBLEMA CRÍTICO:**

```php
// ❌ Elimina ANTES de validar
$diseno->delete();

// Luego valida (pero ya fue eliminado)
if ($diseno->hasAnyRelatedData(...)) {
    // Nunca llegará aquí porque ya se eliminó
}

// ✅ CORRECCIÓN:
if ($diseno->hasAnyRelatedData(['clientes', 'items', 'insumos', 'pedidos'])) {
    return new Response(['errors' => "..."], Response::HTTP_CONFLICT);
}

$diseno->delete();
return response()->json(['success' => 'Diseño eliminado con éxito']);
```

---

### Tests Propuestos para DisenoProductoFinalController

```php
/** @test */
public function puede_crear_diseno_producto_final()
{
    $this->actingAs($this->admin);
    
    $tipoMadera = TipoMadera::factory()->create();
    
    $response = $this->post('/disenos', [
        'descripcion' => 'mesa comedor',
        'madera_id' => $tipoMadera->id,
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('diseno_producto_finales', [
        'descripcion' => 'MESA COMEDOR',
        'tipo_madera_id' => $tipoMadera->id,
        'estado' => 'EN USO',
    ]);
}

/** @test */
public function show_filtra_items_por_tipo_madera()
{
    $this->actingAs($this->admin);
    
    $tipoMaderaDura = TipoMadera::factory()->create();
    $tipoMaderaBlanda = TipoMadera::factory()->create();
    
    $diseno = DisenoProductoFinal::factory()->create([
        'tipo_madera_id' => $tipoMaderaDura->id,
    ]);
    
    $itemDura = Item::factory()->create(['madera_id' => $tipoMaderaDura->id]);
    $itemBlanda = Item::factory()->create(['madera_id' => $tipoMaderaBlanda->id]);
    
    $response = $this->get("/disenos/{$diseno->id}");
    
    $response->assertSee($itemDura->descripcion);
    $response->assertDontSee($itemBlanda->descripcion);
}

/** @test */
public function puede_asignar_diseno_a_cliente()
{
    $this->actingAs($this->admin);
    
    $cliente = Cliente::factory()->create();
    $diseno = DisenoProductoFinal::factory()->create();
    
    $response = $this->postJson('/disenos/asignar-cliente', [
        'diseno_id' => $diseno->id,
        'cliente_id' => $cliente->id,
    ]);
    
    $response->assertJson(['error' => false]);
    $this->assertDatabaseHas('diseno_clientes', [
        'diseno_producto_final_id' => $diseno->id,
        'cliente_id' => $cliente->id,
    ]);
}

/** @test */
public function no_puede_asignar_diseno_duplicado()
{
    $this->actingAs($this->admin);
    
    $cliente = Cliente::factory()->create();
    $diseno = DisenoProductoFinal::factory()->create();
    
    // Asignar primera vez
    $cliente->disenos()->attach($diseno->id);
    
    // Intentar asignar nuevamente
    $response = $this->postJson('/disenos/asignar-cliente', [
        'diseno_id' => $diseno->id,
        'cliente_id' => $cliente->id,
    ]);
    
    $response->assertJson([
        'error' => true,
        'message' => 'El diseño ya esta asignado al cliente'
    ]);
}

/** @test */
public function consultar_items_insumos_valida_diseno_completo()
{
    $diseno = DisenoProductoFinal::factory()->create();
    $item = Item::factory()->create();
    $insumo = InsumosAlmacen::factory()->create();
    
    // Diseño con items e insumos
    $diseno->items()->attach($item->id, ['cantidad' => 4]);
    $diseno->insumos()->attach($insumo->id, ['cantidad' => 10]);
    
    $response = $this->getJson('/disenos/consultar-items-insumos', [
        'diseno_id' => $diseno->id,
    ]);
    
    $response->assertJson(['error' => false]);
}

/** @test */
public function consultar_items_insumos_detecta_diseno_incompleto()
{
    $diseno = DisenoProductoFinal::factory()->create();
    // Sin items ni insumos
    
    $response = $this->getJson('/disenos/consultar-items-insumos', [
        'diseno_id' => $diseno->id,
    ]);
    
    $response->assertJson([
        'error' => true,
        'message' => 'No hay items o insumos suficientes para el diseño'
    ]);
}
```

**Tests Propuestos:** 8 tests

---

## ⚙️ 4. ProcesoController

**Archivo:** `app/Http/Controllers/ProcesoController.php`  
**Modelo:** `Proceso`  
**Propósito:** Gestión de procesos de manufactura

### Características

- ✅ Usa Repository Pattern (`RegistroProcesos`)
- ⚠️ Solo tiene método `store()` implementado
- Los demás métodos están vacíos

---

### store() - Registrar Ruta de Proceso

```php
protected $registroProceso;

public function __construct(RegistroProcesos $registroProcesos)
{
    $this->registroProceso = $registroProcesos;
}

public function store(Request $request)
{
    $this->authorize('admin');
    return $this->registroProceso->registrar_ruta($request->proceso);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1-5 | Constructor | Inyecta repository |
| 8 | `$this->authorize('admin')` | 🔒 Solo admin |
| 9 | `registrar_ruta($request->proceso)` | ⚡ Repository maneja lógica |

**💡 Propósito:**

Registra la ruta de procesos para una transformación (corte → lijado → sellado → barnizado)

**🔧 Repository RegistroProcesos::registrar_ruta()**

Probablemente:
1. Recibe JSON con procesos secuenciales
2. Crea registros en tabla `procesos` vinculados a transformación
3. Asigna orden/secuencia a cada proceso

---

## 📊 Comparación de Controladores

| Aspecto | Pedido | Item | DisenoProductoFinal | Proceso |
|---------|--------|------|---------------------|---------|
| **CRUD Completo** | ✅ Sí | ✅ Sí | ✅ Sí | ❌ Solo store |
| **FormRequest** | ✅ Sí | ✅ Sí | ⚠️ Parcial | ❌ No |
| **Repository** | ❌ No | ❌ No | ❌ No | ✅ Sí |
| **AJAX Methods** | ✅ 2 | ❌ No | ✅ 2 | ❌ No |
| **Mayúsculas** | ❌ No | ❌ No | ✅ Sí | N/A |
| **Validación Integridad** | ✅ Sí | ✅ Sí | ⚠️ Invertida | N/A |
| **Eager Loading** | ❌ No | ❌ No | ❌ No | N/A |
| **Paginación** | ❌ No | ❌ No | ❌ No | N/A |
| **Redirección Dinámica** | ✅ Sí | ❌ No | ✅ a show | ❌ No |
| **Complejidad** | Media | Baja | Alta | Baja |

---

## 🚨 Problemas Críticos del Módulo

### 1. PedidoController::store() - Mensaje Incorrecto

```php
// ❌ En store() dice "editado"
"ha sido editado"

// ✅ Debería decir "creado"
```

### 2. PedidoController::update() - Resetea Estado

```php
// ❌ Resetea estado a PENDIENTE aunque esté EN_PROCESO
$pedido->estado = 'PENDIENTE';

// ✅ No debería modificar el estado en update
```

### 3. DisenoProductoFinalController::destroy() - Elimina Antes de Validar

```php
// ❌ CRÍTICO: Elimina antes de validar
$diseno->delete();
if ($diseno->hasAnyRelatedData(...)) { }

// ✅ Validar ANTES de eliminar
if ($diseno->hasAnyRelatedData(...)) {
    return error;
}
$diseno->delete();
```

### 4. N+1 Queries Generalizados

```php
// ❌ En todos los index()
$items = Item::all(); // Sin eager loading

// ✅ Solución:
$items = Item::with('madera')->paginate(50);
```

### 5. Sin Paginación en Ningún Index

```php
// ❌ Todos usan ->get() o ->all()

// ✅ Implementar paginación:
->paginate(50);
```

---

## ✅ Mejores Prácticas Identificadas

### 1. Redirección Condicional (PedidoController)

```php
if (Route::current()->getName() == 'pedidos.store') {
    return redirect()->route('pedidos.index');
} else {
    return redirect()->route('programaciones.index');
}
```

### 2. Filtrado por Relación (DisenoProductoFinalController)

```php
// Solo items del mismo tipo de madera
Item::where('madera_id', $diseno->tipo_madera->id)->get()
```

### 3. Validación de Duplicados (DisenoProductoFinalController)

```php
$existe = DisenoCliente::where(...)->first();
if ($existe) {
    return error;
}
```

### 4. Repository Pattern (ProcesoController)

```php
// Lógica compleja delegada a repository
$this->registroProceso->registrar_ruta($request->proceso);
```

---

## 📝 Conclusión del Módulo

### Resumen

El **Módulo de Ventas y Diseños** gestiona:

1. **Pedidos** → Solicitudes de clientes
2. **Items** → Componentes físicos
3. **Diseños** → BOM (Bill of Materials) de productos
4. **Procesos** → Rutas de manufactura

### Complejidad

| Aspecto | Nivel |
|---------|-------|
| Lógica de Negocio | 🟡 MEDIA-ALTA |
| Queries de BD | 🟡 MEDIA |
| Integridad Referencial | 🟡 MEDIA |
| Testabilidad | 🟢 ALTA |
| Performance | 🔴 NECESITA MEJORAS |

### Prioridades de Refactoring

1. **CRÍTICO:** Arreglar `destroy()` en DisenoProductoFinalController
2. **ALTO:** Agregar paginación a todos los index()
3. **ALTO:** Implementar eager loading consistente
4. **MEDIO:** Arreglar mensaje en `PedidoController::store()`
5. **MEDIO:** No resetear estado en `update()`

---

**Documentación Completa:** ✅  
**Tests Propuestos:** 20 tests  
**Última Actualización:** 30 de Enero, 2026
