# Controladores CRUD Simples - Documentación Consolidada

## 📋 Información General

**Tipo:** Controladores CRUD Estándar (Catálogos y Configuración)  
**Controladores:** 5  
**Complejidad:** BAJA  
**Propósito:** Gestión de catálogos básicos del sistema

---

## 📊 Controladores Documentados

| # | Controlador | Modelo | Soft Deletes | Integridad | Mayúsculas |
|---|-------------|--------|--------------|------------|------------|
| 1 | EstadoController | Estado | ❌ No | ❌ No | ✅ Sí |
| 2 | EventoController | Evento | ✅ Sí | ❌ No | ❌ No |
| 3 | TipoEventoController | TipoEvento | ❌ No | ✅ Sí | ✅ Sí |
| 4 | MaderaController | Madera | ❌ No | ✅ Sí | ✅ Sí |
| 5 | TipoMaderaController | TipoMadera | ✅ Sí | ✅ Sí | ✅ Sí |

---

## 🗂️ 1. EstadoController

**Archivo:** `app/Http/Controllers/EstadoController.php`  
**Modelo:** `Estado`  
**Propósito:** Gestión de estados de procesos/pedidos (PENDIENTE, EN_PROCESO, COMPLETADO, etc.)

### Características Especiales

```php
public function index()
{
    $estados = Estado::all()->except([1,2]);
    return view('modulos.administrativo.estados.index', compact('estados'));
}
```

**⚠️ Problema: Excluye IDs 1 y 2**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `Estado::all()->except([1,2])` | ⚠️ **Excluye estados 1 y 2** - ¿Por qué? Magic numbers |

**💡 Posible Razón:**

```php
// Estados 1 y 2 probablemente son:
// 1 = SISTEMA (no editable)
// 2 = DEFAULT (no editable)

// ✅ Mejor práctica con constantes:
class Estado extends Model
{
    const SISTEMA = 1;
    const DEFAULT = 2;
}

// En el controlador:
$estados = Estado::whereNotIn('id', [Estado::SISTEMA, Estado::DEFAULT])->get();
```

---

### store() - Lógica Extraña

```php
public function store(StoreEstadoRequest $request)
{
    if (Estado::where('id',2)->exists()) {
        Estado::firstOrCreate([
            'id' => 3,
            'descripcion' => strtoupper($request->descripcion),
            'user_id' => auth()->user()->id,
        ]);
    }
    
    Estado::firstOrCreate([
        'descripcion' => strtoupper($request->descripcion),
        'user_id' => auth()->user()->id,
    ]);
    
    return redirect()->route('estados.index')
        ->with('status', 'El estado fue creado con éxito.');
}
```

**⚠️ Análisis de Problema CRÍTICO:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1-2 | `if (Estado::where('id',2)->exists())` | ⚠️ **Validación extraña** - siempre será true después de seeding |
| 3-7 | `firstOrCreate(['id' => 3, ...])` | ⚠️ **Fuerza ID 3** - puede causar duplicados |
| 10-13 | `firstOrCreate([...])` | ⚠️ **SIEMPRE se ejecuta** - puede crear duplicados |

**🚨 Problemas:**

1. **Doble Creación:** Ejecuta `firstOrCreate` DOS veces
2. **ID Hardcodeado:** Fuerza `id = 3` sin sentido
3. **Lógica Confusa:** No es claro el propósito

**✅ Solución Recomendada:**

```php
public function store(StoreEstadoRequest $request)
{
    $estado = Estado::create([
        'descripcion' => strtoupper($request->descripcion),
        'user_id' => auth()->id(),
    ]);
    
    return redirect()->route('estados.index')
        ->with('status', 'El estado fue creado con éxito.');
}
```

---

### Métodos Estándar

#### update()

```php
public function update(Request $request, Estado $estado)
{
    $estado->descripcion = strtoupper($request->descripcion);
    $estado->user_id = auth()->user()->id;
    $estado->update();
    return redirect()->route('estados.index')
        ->with('status', 'El estado fue actualizado con éxito.');
}
```

**⚠️ Problema:**
- No usa FormRequest (store() sí lo usa)
- Inconsistencia en validación

---

#### destroy()

```php
public function destroy(Estado $estado)
{
    $estado->delete();
    return response()->json([
        'success' => "El estado: {$estado->descripcion} fue eliminado con éxito."
    ]);
}
```

**⚠️ Problema:**
- **NO valida integridad referencial**
- Si hay pedidos/procesos con este estado, puede romper relaciones

**✅ Solución:**

```php
public function destroy(Estado $estado)
{
    if ($estado->hasAnyRelatedData(['pedidos', 'procesos', 'ordenes_produccion'])) {
        return response()->json([
            'error' => "No se puede eliminar, hay datos asociados"
        ], 409);
    }
    
    $estado->delete();
    return response()->json(['success' => "Estado eliminado"]);
}
```

---

### Tests Propuestos

```php
/** @test */
public function index_excluye_estados_sistema()
{
    Estado::factory()->create(['id' => 1]);
    Estado::factory()->create(['id' => 2]);
    $estado3 = Estado::factory()->create(['id' => 3]);
    
    $response = $this->actingAs($this->admin)->get('/estados');
    
    $response->assertDontSee('id="estado-1"');
    $response->assertDontSee('id="estado-2"');
    $response->assertSee($estado3->descripcion);
}

/** @test */
public function descripcion_se_guarda_en_mayusculas()
{
    $this->actingAs($this->admin)->post('/estados', [
        'descripcion' => 'en proceso',
    ]);
    
    $this->assertDatabaseHas('estados', [
        'descripcion' => 'EN PROCESO',
    ]);
}
```

---

## 📅 2. EventoController

**Archivo:** `app/Http/Controllers/EventoController.php`  
**Modelo:** `Evento`  
**Propósito:** Gestión de eventos de personal (permisos, incapacidades, etc.)

### Características

- ✅ Soft Deletes con trait
- ✅ Método restore()
- ✅ Relación con TipoEvento
- ✅ Autorización admin

---

### index() - Incluye Eliminados

```php
public function index()
{
    $eventos = Evento::withTrashed()->get();
    $tipo_eventos = TipoEvento::all();
    return view('modulos.administrativo.eventos.index', 
        compact('eventos', 'tipo_eventos'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `Evento::withTrashed()->get()` | ✅ Incluye eliminados para poder restaurar |
| 2 | `TipoEvento::all()` | Carga tipos para el formulario |

---

### store() - Creación Simple

```php
public function store(StoreEventoRequest $request)
{
    $this->authorize('admin');
    
    $evento = new Evento();
    $evento->descripcion = $request->descripcion;
    $evento->tipo_evento_id = $request->tipoEvento;
    $evento->user_id = auth()->user()->id;
    $evento->save();
    
    return redirect()->route('eventos.index')
        ->with('status', "El evento: $request->descripcion, se ha creado correctamente");
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `$this->authorize('admin')` | 🔒 Solo admin |
| 5 | `$evento->descripcion = ...` | ⚠️ **NO usa strtoupper** (inconsistente) |
| 6 | `tipo_evento_id = $request->tipoEvento` | Relación con catálogo |
| 7 | `user_id = auth()->user()->id` | ✅ Auditoría |

---

### restore() - Recuperar Eliminados

```php
public function restore($id): Response
{
    try {
        $eventoDelete = Evento::onlyTrashed()
            ->where('id', $id)
            ->restore();
        
        return new Response([
            'success' => 'El evento fue restaurado con éxito'
        ], Response::HTTP_OK);
    } catch (\Exception $e) {
        return new Response([
            'errors' => "El evento no pudo ser restaurado"
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3-5 | `onlyTrashed()->where()->restore()` | ✅ Solo restaura eliminados |
| 7-9 | `return new Response(..., 200)` | HTTP 200 OK |
| 10-13 | `catch (\Exception $e)` | ✅ Manejo de errores |

---

### Relaciones del Modelo

```php
class Evento extends Model
{
    use SoftDeletes;
    
    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

### Tests Propuestos

```php
/** @test */
public function admin_puede_crear_evento()
{
    $this->actingAs($this->admin);
    
    $tipoEvento = TipoEvento::factory()->create();
    
    $response = $this->post('/eventos', [
        'descripcion' => 'PERMISO MEDICO',
        'tipoEvento' => $tipoEvento->id,
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('eventos', [
        'descripcion' => 'PERMISO MEDICO',
        'tipo_evento_id' => $tipoEvento->id,
        'user_id' => $this->admin->id,
    ]);
}

/** @test */
public function puede_restaurar_evento_eliminado()
{
    $this->actingAs($this->admin);
    
    $evento = Evento::factory()->create();
    $evento->delete();
    
    $response = $this->postJson("/eventos/{$evento->id}/restore");
    
    $response->assertStatus(200);
    $this->assertDatabaseHas('eventos', [
        'id' => $evento->id,
        'deleted_at' => null,
    ]);
}

/** @test */
public function index_muestra_eventos_eliminados()
{
    $this->actingAs($this->admin);
    
    $activo = Evento::factory()->create();
    $eliminado = Evento::factory()->create();
    $eliminado->delete();
    
    $response = $this->get('/eventos');
    
    $response->assertSee($activo->descripcion);
    $response->assertSee($eliminado->descripcion);
}
```

---

## 🏷️ 3. TipoEventoController

**Archivo:** `app/Http/Controllers/TipoEventoController.php`  
**Modelo:** `TipoEvento`  
**Propósito:** Catálogo de tipos de eventos (PERMISO, INCAPACIDAD, VACACIONES, etc.)

### Características

- ✅ Validación de integridad referencial
- ✅ Transformación a mayúsculas
- ✅ CRUD completo

---

### store() - Con Mayúsculas

```php
public function store(StoreTipoEventoRequest $request)
{
    $this->authorize('admin');
    
    $tipo_evento = new TipoEvento();
    $tipo_evento->tipo_evento = strtoupper($request->tipo_evento);
    $tipo_evento->save();
    
    return redirect()->route('tipo-eventos.index')
        ->with('status', 'Tipo de evento creado con éxito');
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 5 | `strtoupper($request->tipo_evento)` | ✅ Transforma a MAYÚSCULAS |
| 6 | `$tipo_evento->save()` | Usa save() en lugar de create() |

---

### destroy() - Con Validación de Integridad

```php
public function destroy(TipoEvento $tipo_evento)
{
    $this->authorize('admin');
    
    if ($tipo_evento->hasAnyRelatedData(['eventos'])) {
        return new Response([
            'errors' => "No se pudo eliminar el recurso porque tiene datos asociados"
        ], Response::HTTP_CONFLICT);
    }
    
    $tipo_evento->delete();
    return response()->json(['success' => 'Tipo de evento eliminado con éxito']);
}
```

**✅ Buena Práctica:**

- Valida que no haya eventos asociados
- HTTP 409 CONFLICT si hay relaciones
- Previene errores de integridad referencial

---

## 🌳 4. MaderaController

**Archivo:** `app/Http/Controllers/MaderaController.php`  
**Modelo:** `Madera`  
**Propósito:** Catálogo de especies de madera con propiedades físicas

### Estructura del Modelo

```php
class Madera extends Model
{
    protected $fillable = [
        'tipo_madera_id',      // FK a TipoMadera (DURA, BLANDA, MDF)
        'nombre_cientifico',   // Ej: "Tabebuia rosea"
        'nombre_comun',        // Ej: "ROBLE"
        'densidad',            // Kg/m³
    ];
    
    public function tipoMadera()
    {
        return $this->belongsTo(TipoMadera::class);
    }
}
```

---

### index() - Con Eager Loading

```php
public function index()
{
    $this->authorize('admin');
    
    $maderas = Madera::with('tipo_madera')->get();
    $tipos_maderas = TipoMadera::all()->except(1);
    
    return view('modulos.administrativo.maderas.index', 
        compact('maderas', 'tipos_maderas'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3 | `Madera::with('tipo_madera')->get()` | ✅ **Eager Loading** - evita N+1 |
| 4 | `TipoMadera::all()->except(1)` | ⚠️ Excluye ID 1 (tipo SISTEMA?) |

**✅ Buena Práctica:**

Usa `with('tipo_madera')` para cargar la relación de una vez.

---

### store() - Con trim() y strtoupper()

```php
public function store(StoreMaderaRequest $request)
{
    $this->authorize('admin');
    
    $madera = new Madera();
    $madera->tipo_madera_id = $request->tipo_madera_id;
    $madera->nombre_cientifico = trim(strtoupper($request->nombre_cientifico));
    $madera->nombre_comun = trim(strtoupper($request->nombre_comun));
    $madera->densidad = $request->densidad;
    $madera->save();
    
    return redirect()->route('maderas.index')
        ->with('status', 'Madera creada con éxito');
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 6 | `trim(strtoupper(...))` | ✅ **Elimina espacios** + convierte a mayúsculas |
| 7 | `trim(strtoupper(...))` | ✅ Aplica a ambos nombres |
| 8 | `$madera->densidad = ...` | Valor numérico sin transformación |

**✅ Buena Práctica:**

Usa `trim()` antes de `strtoupper()` para eliminar espacios innecesarios.

---

### destroy() - Con Validación Compleja

```php
public function destroy(Madera $madera)
{
    $this->authorize('admin');
    
    if ($madera->hasAnyRelatedData([
        'entradas_madera_maderas',
        'costos_infraestructura',
        'items'
    ])) {
        return new Response([
            'errors' => "No se pudo eliminar el recurso porque tiene datos asociados"
        ], Response::HTTP_CONFLICT);
    }
    
    $madera->delete();
    return response()->json(['success' => 'Madera eliminada correctamente']);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 4-7 | `hasAnyRelatedData([...])` | ⚠️ **Valida 3 relaciones** |
| 5 | `'entradas_madera_maderas'` | Pivot table de entradas de madera |
| 6 | `'costos_infraestructura'` | Costos asociados a esta madera |
| 7 | `'items'` | Items fabricados con esta madera |

**✅ Validación Exhaustiva:**

Verifica múltiples relaciones antes de eliminar, previniendo:
- Pérdida de histórico de entradas
- Inconsistencia en costos
- Items sin madera asociada

---

## 📦 5. TipoMaderaController

**Archivo:** `app/Http/Controllers/TipoMaderaController.php`  
**Modelo:** `TipoMadera`  
**Propósito:** Clasificación de maderas (DURA, BLANDA, MDF, CONTRACHAPADO, etc.)

### Características

- ✅ Soft Deletes
- ✅ Método restore()
- ✅ Validación de integridad
- ✅ Mayúsculas

---

### index() - Con Excepción

```php
public function index()
{
    $tiposMadera = TipoMadera::withTrashed()->get()->except(1);
    return view('modulos.administrativo.tipo_madera.index', compact('tiposMadera'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `withTrashed()->get()->except(1)` | ✅ Incluye eliminados pero excluye ID 1 |

**💡 Posible Razón:**

```php
// ID 1 probablemente es "NINGUNO" o "NO DEFINIDO"
// Tipo predeterminado del sistema que no debe mostrarse/editarse
```

---

### store() - Con Auditoría

```php
public function store(StoreTipoMaderaRequest $request)
{
    $tipoMadera = new TipoMadera();
    $tipoMadera->descripcion = strtoupper($request->descripcion);
    $tipoMadera->user_id = auth()->user()->id;
    $tipoMadera->save();
    
    return redirect()->route('tipos-maderas.index')
        ->with('status', "El tipo de madera $tipoMadera->descripcion ha sido creado correctamente");
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `strtoupper($request->descripcion)` | ✅ Mayúsculas |
| 3 | `user_id = auth()->user()->id` | ✅ Auditoría de creador |

**⚠️ Nota:**

No tiene validación `authorize('admin')` (inconsistente con otros).

---

### destroy() - Con Validación Múltiple

```php
public function destroy(TipoMadera $tipoMadera)
{
    if ($tipoMadera->hasAnyRelatedData(['maderas','items','disenos'])) {
        return new Response([
            'errors' => "No se pudo eliminar el recurso porque tiene datos asociados"
        ], Response::HTTP_CONFLICT);
    }
    
    $tipoMadera->delete();
    return response()->json(['success' => 'Tipo de madera eliminado correctamente']);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `hasAnyRelatedData([...])` | Valida 3 relaciones |
| 1 | `'maderas'` | Especies de madera de este tipo |
| 1 | `'items'` | Items que usan este tipo |
| 1 | `'disenos'` | Diseños con este tipo de madera |

---

### restore() - Recuperar Tipos Eliminados

```php
public function restore($id): Response
{
    try {
        $resourceDelete = TipoMadera::onlyTrashed()
            ->where('id', $id)
            ->restore();
        
        return new Response([
            'success' => 'El tipo de madera fue restaurado con éxito'
        ], Response::HTTP_OK);
    } catch (\Exception $e) {
        return new Response([
            'errors' => "El tipo de madera no pudo ser restaurado"
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
```

**✅ Implementación Correcta:**

- Try-catch para manejo de errores
- HTTP 200 en éxito
- HTTP 500 en error

---

## 📊 Comparación de Controladores

| Aspecto | Estado | Evento | TipoEvento | Madera | TipoMadera |
|---------|--------|--------|------------|--------|------------|
| **FormRequest** | ⚠️ Parcial | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí |
| **Soft Deletes** | ❌ No | ✅ Sí | ❌ No | ❌ No | ✅ Sí |
| **Método restore()** | ❌ No | ✅ Sí | ❌ No | ❌ No | ✅ Sí |
| **Validación Integridad** | ❌ No | ❌ No | ✅ Sí | ✅ Sí | ✅ Sí |
| **Mayúsculas** | ✅ Sí | ❌ No | ✅ Sí | ✅ Sí | ✅ Sí |
| **trim()** | ❌ No | ❌ No | ❌ No | ✅ Sí | ❌ No |
| **Eager Loading** | ❌ No aplica | ❌ No aplica | ❌ No aplica | ✅ Sí | ❌ No aplica |
| **Auditoría (user_id)** | ✅ Sí | ✅ Sí | ❌ No | ❌ No | ✅ Sí |
| **Excluye IDs** | ✅ [1,2] | ❌ No | ❌ No | ⚠️ [1] | ✅ [1] |
| **Complejidad** | Baja | Baja | Baja | Baja | Baja |

---

## 🚨 Problemas Críticos Identificados

### 1. EstadoController::store() - Doble Creación

```php
// ❌ PROBLEMA CRÍTICO
if (Estado::where('id',2)->exists()) {
    Estado::firstOrCreate(['id' => 3, ...]);
}
Estado::firstOrCreate([...]); // SIEMPRE se ejecuta

// ✅ SOLUCIÓN
Estado::create([...]);
```

### 2. Magic Numbers en index()

```php
// ❌ No es claro por qué se excluyen
Estado::all()->except([1,2])
TipoMadera::all()->except(1)

// ✅ Con constantes
class Estado extends Model
{
    const SISTEMA = 1;
    const DEFAULT = 2;
}

Estado::whereNotIn('id', [Estado::SISTEMA, Estado::DEFAULT])->get()
```

### 3. Inconsistencia en Autorización

```php
// TipoMaderaController NO tiene authorize()
public function store(StoreTipoMaderaRequest $request)
{
    // ❌ Falta $this->authorize('admin');
}

// Otros controladores SÍ tienen
public function store(...)
{
    $this->authorize('admin'); // ✅
}
```

### 4. Inconsistencia en Mayúsculas

```php
// EventoController NO usa strtoupper
$evento->descripcion = $request->descripcion; // ❌

// Otros SÍ usan
$estado->descripcion = strtoupper($request->descripcion); // ✅
```

---

## ✅ Mejores Prácticas Identificadas

### 1. trim() + strtoupper() (MaderaController)

```php
$madera->nombre_cientifico = trim(strtoupper($request->nombre_cientifico));
```

Elimina espacios antes de convertir a mayúsculas.

### 2. Validación Múltiple de Integridad (MaderaController)

```php
if ($madera->hasAnyRelatedData([
    'entradas_madera_maderas',
    'costos_infraestructura',
    'items'
])) {
    return new Response(['errors' => "..."], 409);
}
```

### 3. Eager Loading (MaderaController)

```php
$maderas = Madera::with('tipo_madera')->get();
```

Previene N+1 queries.

### 4. Soft Deletes + restore() (EventoController, TipoMaderaController)

```php
// Incluir eliminados en index
$eventos = Evento::withTrashed()->get();

// Método restore implementado
public function restore($id): Response { ... }
```

### 5. HTTP Status Codes Correctos

```php
// 200 OK para éxito
return new Response(['success' => '...'], Response::HTTP_OK);

// 409 CONFLICT para integridad
return new Response(['errors' => '...'], Response::HTTP_CONFLICT);

// 500 ERROR para excepciones
return new Response(['errors' => '...'], Response::HTTP_INTERNAL_SERVER_ERROR);
```

---

## 🧪 Tests Consolidados

### Suite de Tests General

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\{Estado, Evento, TipoEvento, Madera, TipoMadera, User};
use Illuminate\Foundation\Testing\RefreshDatabase;

class CatalogosControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    // EstadoController Tests
    
    /** @test */
    public function estado_se_guarda_en_mayusculas()
    {
        $this->actingAs($this->admin)->post('/estados', [
            'descripcion' => 'en proceso',
        ]);
        
        $this->assertDatabaseHas('estados', [
            'descripcion' => 'EN PROCESO',
        ]);
    }

    // EventoController Tests
    
    /** @test */
    public function puede_crear_evento_con_tipo()
    {
        $this->actingAs($this->admin);
        
        $tipoEvento = TipoEvento::factory()->create();
        
        $this->post('/eventos', [
            'descripcion' => 'PERMISO MEDICO',
            'tipoEvento' => $tipoEvento->id,
        ]);
        
        $this->assertDatabaseHas('eventos', [
            'descripcion' => 'PERMISO MEDICO',
            'tipo_evento_id' => $tipoEvento->id,
        ]);
    }

    /** @test */
    public function puede_restaurar_evento_eliminado()
    {
        $this->actingAs($this->admin);
        
        $evento = Evento::factory()->create();
        $evento->delete();
        
        $response = $this->postJson("/eventos/{$evento->id}/restore");
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'deleted_at' => null,
        ]);
    }

    // TipoEventoController Tests
    
    /** @test */
    public function no_puede_eliminar_tipo_evento_con_eventos()
    {
        $this->actingAs($this->admin);
        
        $tipoEvento = TipoEvento::factory()->create();
        Evento::factory()->create(['tipo_evento_id' => $tipoEvento->id]);
        
        $response = $this->deleteJson("/tipo-eventos/{$tipoEvento->id}");
        
        $response->assertStatus(409);
        $this->assertDatabaseHas('tipo_eventos', ['id' => $tipoEvento->id]);
    }

    // MaderaController Tests
    
    /** @test */
    public function madera_nombres_se_guardan_con_trim_y_mayusculas()
    {
        $this->actingAs($this->admin);
        
        $tipoMadera = TipoMadera::factory()->create();
        
        $this->post('/maderas', [
            'tipo_madera_id' => $tipoMadera->id,
            'nombre_cientifico' => '  tabebuia rosea  ',
            'nombre_comun' => '  roble  ',
            'densidad' => 650,
        ]);
        
        $madera = Madera::first();
        
        $this->assertEquals('TABEBUIA ROSEA', $madera->nombre_cientifico);
        $this->assertEquals('ROBLE', $madera->nombre_comun);
    }

    /** @test */
    public function no_puede_eliminar_madera_con_relaciones()
    {
        $this->actingAs($this->admin);
        
        $madera = Madera::factory()->create();
        // Simular relación
        $madera->items()->create([...]);
        
        $response = $this->deleteJson("/maderas/{$madera->id}");
        
        $response->assertStatus(409);
    }

    /** @test */
    public function index_maderas_usa_eager_loading()
    {
        $this->actingAs($this->admin);
        
        Madera::factory()->count(3)->create();
        
        \DB::enableQueryLog();
        
        $response = $this->get('/maderas');
        
        $queries = \DB::getQueryLog();
        
        // Debe ser 2 queries (maderas + tipos) no 4 (N+1)
        $this->assertCount(2, $queries);
    }

    // TipoMaderaController Tests
    
    /** @test */
    public function puede_restaurar_tipo_madera_eliminado()
    {
        $this->actingAs($this->admin);
        
        $tipoMadera = TipoMadera::factory()->create();
        $tipoMadera->delete();
        
        $response = $this->postJson("/tipos-maderas/{$tipoMadera->id}/restore");
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('tipo_maderas', [
            'id' => $tipoMadera->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function no_puede_eliminar_tipo_madera_con_maderas_asociadas()
    {
        $this->actingAs($this->admin);
        
        $tipoMadera = TipoMadera::factory()->create();
        Madera::factory()->create(['tipo_madera_id' => $tipoMadera->id]);
        
        $response = $this->deleteJson("/tipos-maderas/{$tipoMadera->id}");
        
        $response->assertStatus(409);
    }
}
```

---

## 📝 Conclusión

### Resumen de Controladores CRUD Simples

Estos 5 controladores gestionan catálogos básicos del sistema:

1. **EstadoController** → Estados de procesos (⚠️ tiene problemas críticos)
2. **EventoController** → Eventos de personal (✅ bien implementado)
3. **TipoEventoController** → Tipos de eventos (✅ completo)
4. **MaderaController** → Especies de madera (✅ mejor práctica con trim)
5. **TipoMaderaController** → Clasificación de maderas (✅ soft deletes)

### Complejidad General

| Aspecto | Nivel |
|---------|-------|
| Lógica de Negocio | 🟢 BAJA |
| Queries de BD | 🟢 SIMPLE |
| Integridad Referencial | 🟡 MEDIA (3 de 5 la validan) |
| Testabilidad | 🟢 ALTA |
| Performance | 🟢 BUENA |

### Prioridades de Refactoring

1. **CRÍTICO:** Arreglar `EstadoController::store()` - doble creación
2. **ALTO:** Agregar constantes para IDs excluidos (magic numbers)
3. **MEDIO:** Consistencia en autorización (TipoMaderaController)
4. **BAJO:** Consistencia en mayúsculas (EventoController)

---

**Documentación Completa:** ✅  
**Tests Propuestos:** 15 tests  
**Última Actualización:** 30 de Enero, 2026
