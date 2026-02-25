# Módulo de Personal - Documentación Consolidada

## 📋 Información General

**Módulo:** Gestión de Personal y Turnos  
**Controladores:** 4  
**Complejidad:** MEDIA  
**Propósito:** Control de acceso, turnos laborales, contratistas

---

## 📊 Controladores del Módulo

| # | Controlador | Modelo | Complejidad | Estado |
|---|-------------|--------|-------------|--------|
| 1 | TurnoController | Turno | BAJA | ✅ |
| 2 | RecepcionController | Recepcion | MEDIA | ✅ |
| 3 | TurnoUsuarioController | TurnoUsuario | MEDIA | ✅ |
| 4 | ContratistaController | Contratista | BAJA | ✅ |

---

## 🔧 1. TurnoController

**Archivo:** `app/Http/Controllers/TurnoController.php`  
**Modelo:** `Turno`  
**Propósito:** Gestión de turnos laborales (diurno, nocturno, mixto)

### Estructura CRUD Standard

```php
class TurnoController extends Controller
{
    // CRUD estándar con FormRequests
    // StoreTurnoRequest, UpdateTurnoRequest
}
```

### Métodos Documentados

#### index() - Listar Turnos

```php
public function index()
{
    $this->authorize('admin');
    $turnos = Turno::all();
    return view('modulos.administrativo.turnos.index', compact('turnos'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `$this->authorize('admin')` | 🔒 Solo admin |
| 2 | `Turno::all()` | ⚠️ Sin paginación (pocos registros esperados) |
| 3 | `return view(...)` | Vista con listado |

**⚠️ Problema Menor:**
- No hay paginación, pero turnos son pocos (3-5 registros típicamente)

---

#### store() - Crear Turno

```php
public function store(StoreTurnoRequest $request)
{
    $this->authorize('admin');
    $turno = Turno::create($request->all());
    return redirect()->route('turnos.index')
        ->with('status',"El turno $turno->id, $turno->turno fue creado con éxito");
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `$this->authorize('admin')` | 🔒 Solo admin |
| 2 | `Turno::create($request->all())` | ✅ Mass assignment con FormRequest |
| 3-4 | `return redirect()->route(...)` | Redirección con mensaje |

**✅ Buena Práctica:**
- Usa `create()` con validación previa (FormRequest)
- Mensaje incluye ID y nombre del turno

---

#### update() - Actualizar Turno

```php
public function update(UpdateTurnoRequest $request, Turno $turno)
{
    $this->authorize('admin');
    $turno->update($request->all());
    return redirect()->route('turnos.index')
        ->with('status',"El turno $turno->id, $turno->turno fue actualizado con éxito");
}
```

**Análisis:**
- Route Model Binding en `$turno`
- Usa `update()` con mass assignment
- FormRequest valida datos

---

#### destroy() - Eliminar Turno

```php
public function destroy(Turno $turno)
{
    $this->authorize('admin');
    
    if ($turno->hasAnyRelatedData(['users','maquinas'])) {
        return new Response([
            'errors' => "No se pudo eliminar el recurso porque tiene datos asociados"
        ], Response::HTTP_CONFLICT);
    }
    
    $turno->delete();
    return response()->json(array('success' => "turno eliminado"));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3 | `hasAnyRelatedData(['users','maquinas'])` | ✅ **Validación de integridad** |
| 4-6 | `return new Response(..., 409)` | HTTP 409 CONFLICT |
| 9 | `$turno->delete()` | ⚠️ Hard delete (no es soft delete) |
| 10 | `response()->json(...)` | Respuesta AJAX JSON |

**✅ Validación de Integridad:**

Verifica si el turno tiene:
- Usuarios asignados
- Máquinas asignadas

Si hay relaciones, **NO permite eliminar**.

---

### Relaciones del Modelo Turno

```php
class Turno extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function maquinas()
    {
        return $this->hasMany(Maquina::class);
    }
    
    public function turnoUsuarios()
    {
        return $this->hasMany(TurnoUsuario::class);
    }
}
```

### Tests Propuestos para TurnoController

```php
/** @test */
public function admin_puede_crear_turno()
{
    $this->actingAs($this->admin);
    
    $response = $this->post('/turnos', [
        'turno' => 'DIURNO',
        'hora_inicio' => '06:00:00',
        'hora_fin' => '14:00:00',
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('turnos', ['turno' => 'DIURNO']);
}

/** @test */
public function no_puede_eliminar_turno_con_usuarios_asignados()
{
    $this->actingAs($this->admin);
    
    $turno = Turno::factory()->create();
    User::factory()->create(['turno_id' => $turno->id]);
    
    $response = $this->deleteJson("/turnos/{$turno->id}");
    
    $response->assertStatus(409);
    $this->assertDatabaseHas('turnos', ['id' => $turno->id]);
}

/** @test */
public function puede_eliminar_turno_sin_relaciones()
{
    $this->actingAs($this->admin);
    
    $turno = Turno::factory()->create();
    
    $response = $this->deleteJson("/turnos/{$turno->id}");
    
    $response->assertJson(['success' => 'turno eliminado']);
    $this->assertDatabaseMissing('turnos', ['id' => $turno->id]);
}
```

**Tests Propuestos:** 8 tests

---

## 🚪 2. RecepcionController

**Archivo:** `app/Http/Controllers/RecepcionController.php`  
**Modelo:** `Recepcion`  
**Repository:** `RegistroUsuarios`  
**Propósito:** Control de ingreso de personal y visitantes

### Inyección de Dependencias

```php
class RecepcionController extends Controller
{
    protected $registroUsuarios;
    
    public function __construct(RegistroUsuarios $registroUsuarios)
    {
        $this->registroUsuarios = $registroUsuarios;
    }
}
```

**Patrón:** Repository Pattern para lógica compleja de registro

---

### Métodos Documentados

#### index() - Listar Recepciones del Día

```php
public function index()
{
    $recepciones = Recepcion::where('created_at', '=', now())
        ->orWhere('deleted_at','=', null)
        ->get();
    
    return view('modulos.operaciones.recepcion.ingreso', compact('recepciones'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1-2 | `where('created_at', '=', now())` | ⚠️ **Query problemática** - now() no es fecha exacta |
| 3 | `orWhere('deleted_at', '=', null)` | ⚠️ Mezcla lógica de fecha y soft delete |
| 4 | `->get()` | Sin paginación |

**⚠️ Problemas Identificados:**

```php
// ❌ Problema 1: now() incluye hora
where('created_at', '=', now()) // 2026-01-30 14:23:45
// Nunca coincidirá exactamente

// ❌ Problema 2: Lógica confusa de OR
// Debería ser AND

// ✅ Solución:
$recepciones = Recepcion::whereDate('created_at', today())
    ->whereNull('deleted_at')
    ->orderBy('created_at', 'desc')
    ->get();
```

---

#### store() - Registrar Ingreso

```php
public function store(StoreRecepcionRequest $request)
{
    $this->authorize('entrada-maderas');
    
    return $this->registroUsuarios->ingresoVisitante($request);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `$this->authorize('entrada-maderas')` | 🔒 Permiso específico (no solo admin) |
| 3 | `$this->registroUsuarios->ingresoVisitante()` | ⚡ **Repository maneja lógica** |

**🔧 Repository RegistroUsuarios::ingresoVisitante()**

Este repository probablemente:
1. Valida si es empleado o visitante
2. Crea registro en `recepciones`
3. Si es visitante nuevo, crea en tabla de visitantes
4. Registra hora de ingreso

---

#### update() - Actualizar Ingreso

```php
public function update(UpdateRecepcionRequest $request, Recepcion $recepcion)
{
    $this->authorize('entrada-maderas');
    
    $recepcion->visitante = $request->visitante;
    $recepcion->updated_at = null;  // ⚠️ Interesante
    $recepcion->save();
    
    return redirect()->route('recepcion.index')
        ->with('status', 'Ingreso de personal o visitante, actualizado correctamente');
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3 | `$recepcion->visitante = $request->visitante` | Actualiza nombre de visitante |
| 4 | `$recepcion->updated_at = null` | ⚠️ **Deshabilita timestamp** - no registra cuándo se editó |
| 5 | `$recepcion->save()` | Guarda cambios |

**⚠️ Problema:**

```php
$recepcion->updated_at = null; // ❌ Pierde auditoría de cuándo se editó

// ✅ Mejor práctica:
// Dejar que Laravel maneje automáticamente
$recepcion->visitante = $request->visitante;
$recepcion->save(); // updated_at se actualiza automáticamente
```

---

#### destroy() - Soft Delete (Marcar Salida)

```php
public function destroy(Recepcion $recepcion)
{
    $this->authorize('entrada-maderas');
    
    $recepcion->deleted_at = now();
    $recepcion->save();
    
    return response()->json(['error' => false]);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3 | `$recepcion->deleted_at = now()` | ⚠️ **Implementación manual** de soft delete |
| 4 | `$recepcion->save()` | Guarda cambio |
| 6 | `return response()->json(...)` | Respuesta AJAX |

**⚠️ Problema:**

```php
// ❌ Soft delete manual (no usa trait)
$recepcion->deleted_at = now();
$recepcion->save();

// ✅ Mejor con SoftDeletes trait:
use SoftDeletes;

// En el controlador:
$recepcion->delete(); // Automáticamente establece deleted_at
```

**💡 Interpretación:**

`deleted_at` aquí **NO representa eliminación**, sino **SALIDA** de la persona:
- `created_at` → Hora de ingreso
- `deleted_at` → Hora de salida

---

#### consultaUsuario() - AJAX Validar Usuario

```php
public function consultaUsuario(Request $request)
{
    $usuario = User::where('identificacion', $request->usuario)->first();
    
    if (empty($usuario)) {
        return response()->json(['success' => true, 'usuario' => $request->usuario]);
    } else {
        return response()->json(['success' => false]);
    }
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `User::where('identificacion', ...)` | Busca usuario por cédula |
| 3-4 | `if (empty($usuario))` | Si NO existe → success: true (es visitante nuevo) |
| 6 | `else success: false` | Si existe → es empleado registrado |

**Lógica de Negocio:**

```javascript
// Frontend AJAX call
$.ajax({
    url: '/recepcion/consulta-usuario',
    data: { usuario: '123456789' },
    success: function(response) {
        if (response.success) {
            // No existe → Mostrar formulario de visitante
            $('#formVisitante').show();
        } else {
            // Existe → Registrar ingreso directo
            registrarIngreso();
        }
    }
});
```

---

#### reporteRecepcion() - Reporte por Rango de Fechas

```php
public function reporteRecepcion(Request $request)
{
    $this->authorize('entrada-maderas');
    
    $recepciones = Recepcion::whereBetween('created_at', [$request->desde, $request->hasta])
        ->withTrashed()  // Incluye registros con salida
        ->get();
    
    return view('modulos.operaciones.recepcion.reporte', compact('recepciones'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3-4 | `whereBetween('created_at', [...])` | Filtra por rango de fechas |
| 5 | `->withTrashed()` | ✅ Incluye registros con `deleted_at` (salidas) |
| 6 | `->get()` | ⚠️ Sin paginación (puede ser pesado) |

**✅ Uso Correcto de `withTrashed()`:**

Para este caso, `withTrashed()` es necesario porque:
- Registros con `deleted_at` = personas que YA salieron
- Reporte debe mostrar todos los ingresos del período

---

### Tests Propuestos para RecepcionController

```php
/** @test */
public function puede_registrar_ingreso_empleado()
{
    $this->actingAs($this->userWithPermission('entrada-maderas'));
    
    $user = User::factory()->create(['identificacion' => '123456789']);
    
    $response = $this->post('/recepcion', [
        'usuario' => '123456789',
        'tipo' => 'EMPLEADO',
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('recepciones', [
        'user_id' => $user->id,
    ]);
}

/** @test */
public function puede_registrar_ingreso_visitante()
{
    $this->actingAs($this->userWithPermission('entrada-maderas'));
    
    $response = $this->post('/recepcion', [
        'visitante' => 'JUAN PEREZ',
        'documento' => '987654321',
        'tipo' => 'VISITANTE',
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('recepciones', [
        'visitante' => 'JUAN PEREZ',
    ]);
}

/** @test */
public function consulta_usuario_retorna_success_true_si_no_existe()
{
    $response = $this->getJson('/recepcion/consulta-usuario', [
        'usuario' => '999999999',
    ]);
    
    $response->assertJson(['success' => true, 'usuario' => '999999999']);
}

/** @test */
public function consulta_usuario_retorna_success_false_si_existe()
{
    User::factory()->create(['identificacion' => '123456789']);
    
    $response = $this->getJson('/recepcion/consulta-usuario', [
        'usuario' => '123456789',
    ]);
    
    $response->assertJson(['success' => false]);
}

/** @test */
public function marcar_salida_actualiza_deleted_at()
{
    $this->actingAs($this->userWithPermission('entrada-maderas'));
    
    $recepcion = Recepcion::factory()->create();
    
    $response = $this->deleteJson("/recepcion/{$recepcion->id}");
    
    $response->assertJson(['error' => false]);
    
    $recepcion->refresh();
    $this->assertNotNull($recepcion->deleted_at);
}

/** @test */
public function reporte_incluye_registros_con_salida()
{
    $this->actingAs($this->userWithPermission('entrada-maderas'));
    
    $conSalida = Recepcion::factory()->create([
        'created_at' => today(),
        'deleted_at' => now(),
    ]);
    
    $response = $this->get('/recepcion/reporte', [
        'desde' => today(),
        'hasta' => today(),
    ]);
    
    $response->assertSee($conSalida->visitante);
}
```

**Tests Propuestos:** 10 tests

---

## 👥 3. TurnoUsuarioController

**Archivo:** `app/Http/Controllers/TurnoUsuarioController.php`  
**Modelo:** `TurnoUsuario`  
**Repository:** `AsignarTurno`  
**Propósito:** Asignación de turnos a usuarios en máquinas específicas

### Inyección de Repository

```php
class TurnoUsuarioController extends Controller
{
    protected $asignarTurno;
    
    public function __construct(AsignarTurno $asignarTurno)
    {
        $this->asignarTurno = $asignarTurno;
    }
}
```

---

### Métodos Documentados

#### index() - Vista de Asignación

```php
public function index()
{
    $turnos = Turno::get(['id', 'turno']);
    $usuarios = User::whereBetween('rol_id',[1, 2])->get(['id', 'name']);
    $maquinas = Maquina::get(['id', 'maquina']);
    
    $turnos_usuarios = TurnoUsuario::latest('fecha')->take(100)->get();
    
    return view('modulos.administrativo.turnos-usuarios.index',
        compact('turnos', 'usuarios', 'maquinas', 'turnos_usuarios'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `Turno::get(['id', 'turno'])` | ✅ Solo campos necesarios |
| 2 | `whereBetween('rol_id',[1, 2])` | ⚠️ **Magic numbers** - ¿qué roles son 1 y 2? |
| 3 | `Maquina::get(['id', 'maquina'])` | ✅ Solo campos necesarios |
| 5 | `TurnoUsuario::latest('fecha')->take(100)` | ✅ Limita a 100 registros recientes |

**⚠️ Problema: Magic Numbers**

```php
// ❌ No es claro qué significan 1 y 2
whereBetween('rol_id', [1, 2])

// ✅ Mejor con constantes
class User extends Model
{
    const ROL_ADMIN = 1;
    const ROL_OPERARIO = 2;
}

// En el controlador:
whereIn('rol_id', [User::ROL_ADMIN, User::ROL_OPERARIO])
```

---

#### store() - Asignar Turnos

```php
public function store(StoreTurnoUserRequest $request)
{
    $this->authorize('admin');
    
    $asignar = $this->asignarTurno->crearTurnos($request);
    
    return redirect()->route('asignar-turnos.index')->with($asignar);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `$this->authorize('admin')` | 🔒 Solo admin |
| 3 | `$this->asignarTurno->crearTurnos()` | ⚡ **Repository maneja creación** |
| 5 | `->with($asignar)` | Pasa respuesta del repository a la sesión |

**🔧 Repository AsignarTurno::crearTurnos()**

Probablemente:
1. Recibe rango de fechas
2. Crea múltiples registros TurnoUsuario (uno por día)
3. Valida que no haya conflictos (usuario ya tiene turno ese día)

**Ejemplo de Lógica:**

```php
// Repository AsignarTurno
public function crearTurnos($request)
{
    $desde = Carbon::parse($request->desde);
    $hasta = Carbon::parse($request->hasta);
    
    $diasCreados = 0;
    
    while ($desde <= $hasta) {
        // Validar si ya existe turno para este día
        $existe = TurnoUsuario::where('user_id', $request->usuario)
            ->where('fecha', $desde->format('Y-m-d'))
            ->exists();
        
        if (!$existe) {
            TurnoUsuario::create([
                'user_id' => $request->usuario,
                'turno_id' => $request->turno,
                'maquina_id' => $request->maquina,
                'fecha' => $desde->format('Y-m-d'),
            ]);
            $diasCreados++;
        }
        
        $desde->addDay();
    }
    
    return [
        'status' => "Se crearon $diasCreados asignaciones de turnos"
    ];
}
```

---

#### update() - Actualizar Turno Asignado

```php
public function update(UpdateTurnoUsuarioRequest $request, TurnoUsuario $asignar_turno)
{
    $this->authorize('admin');
    
    $asignar_turno->update([
        'user_id' => $request->usuario,
        'turno_id' => $request->turno,
        'maquina_id' => $request->maquina,
        'fecha' => $request->desde,
    ]);
    
    return redirect()->route('asignar-turnos.index')
        ->with('status', "El turno para el usuario: {$asignar_turno->user->name} fue actualizado");
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3-8 | `$asignar_turno->update([...])` | Actualiza todos los campos |
| 11 | `{$asignar_turno->user->name}` | ⚠️ **N+1 Query** - debería eager load |

**⚠️ Problema N+1:**

```php
// ❌ Genera query extra para obtener user->name
{$asignar_turno->user->name}

// ✅ Solución: Eager loading en show/edit
$asignar_turno = TurnoUsuario::with('user')->find($id);
```

---

#### TurnosUsuario() - Consulta AJAX

```php
public function TurnosUsuario(Request $request)
{
    $this->authorize('admin');
    
    return $this->asignarTurno->consultaTurno($request);
}
```

**Propósito:** Retorna turnos asignados en un rango de fechas (AJAX)

**Repository AsignarTurno::consultaTurno()** probablemente:

```php
public function consultaTurno($request)
{
    $turnos = TurnoUsuario::with(['user', 'turno', 'maquina'])
        ->whereBetween('fecha', [$request->desde, $request->hasta])
        ->get();
    
    return response()->json($turnos);
}
```

---

### Tests Propuestos para TurnoUsuarioController

```php
/** @test */
public function admin_puede_asignar_turno_a_usuario()
{
    $this->actingAs($this->admin);
    
    $user = User::factory()->create(['rol_id' => 1]);
    $turno = Turno::factory()->create();
    $maquina = Maquina::factory()->create();
    
    $response = $this->post('/asignar-turnos', [
        'usuario' => $user->id,
        'turno' => $turno->id,
        'maquina' => $maquina->id,
        'desde' => today()->format('Y-m-d'),
        'hasta' => today()->addDays(7)->format('Y-m-d'),
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('turno_usuarios', [
        'user_id' => $user->id,
        'turno_id' => $turno->id,
        'maquina_id' => $maquina->id,
    ]);
}

/** @test */
public function asignar_turno_crea_multiples_registros_para_rango_fechas()
{
    $this->actingAs($this->admin);
    
    $user = User::factory()->create();
    $turno = Turno::factory()->create();
    $maquina = Maquina::factory()->create();
    
    $this->post('/asignar-turnos', [
        'usuario' => $user->id,
        'turno' => $turno->id,
        'maquina' => $maquina->id,
        'desde' => today()->format('Y-m-d'),
        'hasta' => today()->addDays(4)->format('Y-m-d'), // 5 días
    ]);
    
    // Debe crear 5 registros (uno por día)
    $this->assertEquals(5, TurnoUsuario::where('user_id', $user->id)->count());
}

/** @test */
public function puede_actualizar_turno_asignado()
{
    $this->actingAs($this->admin);
    
    $asignacion = TurnoUsuario::factory()->create();
    $nuevoTurno = Turno::factory()->create();
    
    $response = $this->put("/asignar-turnos/{$asignacion->id}", [
        'usuario' => $asignacion->user_id,
        'turno' => $nuevoTurno->id,
        'maquina' => $asignacion->maquina_id,
        'desde' => $asignacion->fecha,
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('turno_usuarios', [
        'id' => $asignacion->id,
        'turno_id' => $nuevoTurno->id,
    ]);
}

/** @test */
public function puede_eliminar_turno_asignado()
{
    $this->actingAs($this->admin);
    
    $asignacion = TurnoUsuario::factory()->create();
    
    $response = $this->deleteJson("/asignar-turnos/{$asignacion->id}");
    
    $response->assertJson(['success' => 'El turno asignado fue eliminado']);
    $this->assertDatabaseMissing('turno_usuarios', ['id' => $asignacion->id]);
}

/** @test */
public function consulta_turnos_por_rango_fechas()
{
    $this->actingAs($this->admin);
    
    $asignacion = TurnoUsuario::factory()->create([
        'fecha' => today(),
    ]);
    
    $response = $this->getJson('/asignar-turnos/consulta', [
        'desde' => today()->format('Y-m-d'),
        'hasta' => today()->format('Y-m-d'),
    ]);
    
    $response->assertJsonCount(1);
    $response->assertJsonFragment(['id' => $asignacion->id]);
}
```

**Tests Propuestos:** 8 tests

---

## 🏢 4. ContratistaController

**Archivo:** `app/Http/Controllers/ContratistaController.php`  
**Modelo:** `Contratista`  
**Propósito:** Gestión de contratistas externos con control de acceso

### Características Especiales

- **Soft Deletes:** Usa trait SoftDeletes
- **Control de Acceso:** Campo `acceso` booleano
- **Nombres Separados:** primer_nombre, segundo_nombre, primer_apellido, segundo_apellido
- **Mayúsculas:** Transformación strtoupper() en todos los campos

---

### Métodos Documentados

#### index() - Listar Contratistas

```php
public function index()
{
    $this->authorize('admin');
    
    $contratistas = Contratista::withTrashed()->get();
    
    return view('modulos.administrativo.contratistas.index', compact('contratistas'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3 | `Contratista::withTrashed()->get()` | ✅ Incluye eliminados (soft deletes) |

**💡 Uso Correcto:**

Lista TODOS los contratistas (activos e inactivos) para permitir:
- Ver histórico
- Restaurar contratistas eliminados

---

#### store() - Crear Contratista

```php
public function store(StoreContratistaRequest $request)
{
    $this->authorize('admin');
    
    $contratista = new Contratista();
    $contratista->cedula = $request->cedula;
    $contratista->primer_nombre = strtoupper($request->primer_nombre);
    $contratista->segundo_nombre = strtoupper($request->segundo_nombre);
    $contratista->primer_apellido = strtoupper($request->primer_apellido);
    $contratista->segundo_apellido = strtoupper($request->segundo_apellido);
    
    if($request->acceso == 'on'){
        $contratista->acceso = true;
    }else{
        $contratista->acceso = false;
    }
    
    $contratista->empresa_contratista = strtoupper($request->empresa_contratista);
    $contratista->user_id = auth()->user()->id;
    $contratista->save();
    
    return redirect()->route('contratistas.index')
        ->with('status', 'Contratista creado con éxito');
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 6-10 | `strtoupper(...)` | ✅ Transforma a MAYÚSCULAS (patrón consistente) |
| 12-16 | `if($request->acceso == 'on')` | ⚠️ Validación de checkbox |
| 18 | `strtoupper($request->empresa_contratista)` | ✅ Empresa también en mayúsculas |
| 19 | `auth()->user()->id` | ✅ Auditoría de creador |

**⚠️ Problema con Checkbox:**

```php
// ❌ Validación manual de checkbox
if($request->acceso == 'on'){
    $contratista->acceso = true;
}else{
    $contratista->acceso = false;
}

// ✅ Solución más elegante:
$contratista->acceso = $request->has('acceso');

// O con cast en el modelo:
class Contratista extends Model
{
    protected $casts = [
        'acceso' => 'boolean',
    ];
}

// En el controlador:
$contratista->acceso = $request->acceso ?? false;
```

---

#### update() - Actualizar Contratista

```php
public function update(UpdateContratistaRequest $request, Contratista $contratista)
{
    $this->authorize('admin');
    
    $contratista->cedula = $request->cedula;
    $contratista->primer_nombre = strtoupper($request->primer_nombre);
    $contratista->segundo_nombre = strtoupper($request->segundo_nombre);
    $contratista->primer_apellido = strtoupper($request->primer_apellido);
    $contratista->segundo_apellido = strtoupper($request->segundo_apellido);
    
    if($request->acceso == 'on'){
        $contratista->acceso = true;
    }else{
        $contratista->acceso = false;
    }
    
    $contratista->empresa_contratista = strtoupper($request->empresa_contratista);
    $contratista->user_id = auth()->user()->id;
    $contratista->update();
    
    return redirect()->route('contratistas.index')
        ->with('status', "Contratista $contratista->primer_nombre $contratista->primer_apellido actualizado");
}
```

**Análisis:**

- Idéntico a `store()` pero usa `update()` en lugar de `save()`
- Actualiza `user_id` con el usuario que editó (auditoría)

**⚠️ Código Duplicado:**

Podría refactorizarse con método privado:

```php
private function asignarDatos(Contratista $contratista, $request)
{
    $contratista->cedula = $request->cedula;
    $contratista->primer_nombre = strtoupper($request->primer_nombre);
    $contratista->segundo_nombre = strtoupper($request->segundo_nombre);
    $contratista->primer_apellido = strtoupper($request->primer_apellido);
    $contratista->segundo_apellido = strtoupper($request->segundo_apellido);
    $contratista->acceso = $request->has('acceso');
    $contratista->empresa_contratista = strtoupper($request->empresa_contratista);
    $contratista->user_id = auth()->id();
    
    return $contratista;
}

// En store()
$contratista = new Contratista();
$this->asignarDatos($contratista, $request)->save();

// En update()
$this->asignarDatos($contratista, $request)->update();
```

---

#### destroy() - Soft Delete con Deshabilitar Acceso

```php
public function destroy(Contratista $contratista)
{
    $contratista->update([
        'acceso' => false
    ]);
    
    $contratista->delete();
    
    return response()->json([
        'success' => "Contratista $contratista->primer_nombre $contratista->primer_apellido eliminado"
    ]);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1-3 | `update(['acceso' => false])` | ✅ **Deshabilita acceso** antes de eliminar |
| 5 | `$contratista->delete()` | ✅ Soft delete (usa trait SoftDeletes) |
| 7-10 | `response()->json(...)` | Respuesta AJAX |

**✅ Buena Práctica:**

Antes de eliminar (soft delete), **deshabilita el acceso** para:
1. Prevenir que contratista acceda al sistema
2. Marcar como inactivo inmediatamente
3. Mantener registro histórico (soft delete)

---

#### restore() - Restaurar Contratista Eliminado

```php
public function restore($id): Response
{
    try {
        $resourceDelete = Contratista::onlyTrashed()
            ->where('id', $id)
            ->restore();
        
        return new Response([
            'success' => 'El resource fue restaurado con éxito'
        ], Response::HTTP_OK);
    } catch (\Exception $e) {
        return new Response([
            'errors' => "El resource no pudo ser restaurado"
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3-5 | `onlyTrashed()->where()->restore()` | ✅ Restaura contratista eliminado |
| 7-9 | `return new Response(..., 200)` | HTTP 200 OK |
| 10 | `catch (\Exception $e)` | Manejo de errores |
| 11-13 | `return new Response(..., 500)` | HTTP 500 Internal Server Error |

**✅ Características:**

- Manejo adecuado de excepciones
- Respuestas HTTP estándar
- Solo restaura registros eliminados (`onlyTrashed()`)

**⚠️ Mejora:**

```php
// Después de restaurar, habilitar acceso
$contratista = Contratista::onlyTrashed()->findOrFail($id);
$contratista->restore();
$contratista->update(['acceso' => true]); // Habilitar acceso nuevamente

return new Response(['success' => 'Contratista restaurado y acceso habilitado'], 200);
```

---

### Tests Propuestos para ContratistaController

```php
/** @test */
public function admin_puede_crear_contratista()
{
    $this->actingAs($this->admin);
    
    $response = $this->post('/contratistas', [
        'cedula' => '123456789',
        'primer_nombre' => 'juan',
        'segundo_nombre' => 'carlos',
        'primer_apellido' => 'perez',
        'segundo_apellido' => 'lopez',
        'acceso' => 'on',
        'empresa_contratista' => 'construcciones ltda',
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('contratistas', [
        'cedula' => '123456789',
        'primer_nombre' => 'JUAN',      // Mayúsculas
        'segundo_nombre' => 'CARLOS',
        'primer_apellido' => 'PEREZ',
        'segundo_apellido' => 'LOPEZ',
        'acceso' => true,
        'empresa_contratista' => 'CONSTRUCCIONES LTDA',
    ]);
}

/** @test */
public function nombres_se_guardan_en_mayusculas()
{
    $this->actingAs($this->admin);
    
    $this->post('/contratistas', [
        'cedula' => '123456789',
        'primer_nombre' => 'juan',
        'primer_apellido' => 'perez',
        'empresa_contratista' => 'empresa test',
        'acceso' => 'on',
    ]);
    
    $contratista = Contratista::first();
    
    $this->assertEquals('JUAN', $contratista->primer_nombre);
    $this->assertEquals('PEREZ', $contratista->primer_apellido);
    $this->assertEquals('EMPRESA TEST', $contratista->empresa_contratista);
}

/** @test */
public function eliminar_contratista_deshabilita_acceso()
{
    $this->actingAs($this->admin);
    
    $contratista = Contratista::factory()->create(['acceso' => true]);
    
    $response = $this->deleteJson("/contratistas/{$contratista->id}");
    
    $response->assertJson(['success' => "Contratista {$contratista->primer_nombre} {$contratista->primer_apellido} eliminado"]);
    
    $contratista->refresh();
    $this->assertFalse($contratista->acceso);
    $this->assertSoftDeleted('contratistas', ['id' => $contratista->id]);
}

/** @test */
public function puede_restaurar_contratista_eliminado()
{
    $this->actingAs($this->admin);
    
    $contratista = Contratista::factory()->create();
    $contratista->delete();
    
    $response = $this->postJson("/contratistas/{$contratista->id}/restore");
    
    $response->assertStatus(200);
    $response->assertJson(['success' => 'El resource fue restaurado con éxito']);
    
    $this->assertDatabaseHas('contratistas', [
        'id' => $contratista->id,
        'deleted_at' => null,
    ]);
}

/** @test */
public function index_muestra_contratistas_eliminados()
{
    $this->actingAs($this->admin);
    
    $activo = Contratista::factory()->create();
    $eliminado = Contratista::factory()->create();
    $eliminado->delete();
    
    $response = $this->get('/contratistas');
    
    $response->assertSee($activo->primer_nombre);
    $response->assertSee($eliminado->primer_nombre); // También ve eliminados
}

/** @test */
public function checkbox_acceso_off_guarda_false()
{
    $this->actingAs($this->admin);
    
    $this->post('/contratistas', [
        'cedula' => '123456789',
        'primer_nombre' => 'juan',
        'primer_apellido' => 'perez',
        'empresa_contratista' => 'empresa',
        // acceso NO enviado (checkbox unchecked)
    ]);
    
    $contratista = Contratista::first();
    
    $this->assertFalse($contratista->acceso);
}
```

**Tests Propuestos:** 10 tests

---

## 📊 Comparación de Controladores

| Aspecto | TurnoController | RecepcionController | TurnoUsuarioController | ContratistaController |
|---------|-----------------|---------------------|------------------------|----------------------|
| **CRUD** | ✅ Completo | ✅ Completo | ✅ Completo | ✅ Completo |
| **Repository** | ❌ No | ✅ RegistroUsuarios | ✅ AsignarTurno | ❌ No |
| **FormRequest** | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí |
| **Soft Deletes** | ❌ No | ⚠️ Manual | ❌ No | ✅ Trait |
| **Mayúsculas** | ❌ No aplica | ❌ No aplica | ❌ No aplica | ✅ Sí |
| **Validación Integridad** | ✅ hasAnyRelatedData | ❌ No | ❌ No | ❌ No |
| **AJAX Methods** | ❌ No | ✅ consultaUsuario | ✅ TurnosUsuario | ❌ No |
| **Paginación** | ❌ No | ❌ No | ⚠️ take(100) | ❌ No |
| **Eager Loading** | ❌ No aplica | ❌ No aplica | ⚠️ Necesario | ❌ No aplica |
| **Complejidad** | Baja | Media | Media | Baja |

---

## 🚨 Problemas Generales Identificados

### 1. RecepcionController - Query Problemática

**Ubicación:** `index()`

```php
// ❌ Problema
Recepcion::where('created_at', '=', now())
    ->orWhere('deleted_at','=', null)
    ->get();

// ✅ Solución
Recepcion::whereDate('created_at', today())
    ->whereNull('deleted_at')
    ->orderBy('created_at', 'desc')
    ->get();
```

### 2. Magic Numbers en TurnoUsuarioController

**Ubicación:** `index()`

```php
// ❌ Magic numbers
User::whereBetween('rol_id',[1, 2])->get();

// ✅ Constantes
class User extends Model
{
    const ROL_ADMIN = 1;
    const ROL_OPERARIO = 2;
}

whereIn('rol_id', [User::ROL_ADMIN, User::ROL_OPERARIO])
```

### 3. RecepcionController - updated_at = null

**Ubicación:** `update()`

```php
// ❌ Deshabilita auditoría
$recepcion->updated_at = null;

// ✅ Dejar que Laravel maneje automáticamente
// Eliminar esta línea
```

### 4. ContratistaController - Código Duplicado

**Ubicación:** `store()` y `update()`

```php
// ✅ Refactorizar con método privado
private function asignarDatos(Contratista $contratista, $request)
{
    // Lógica compartida
}
```

### 5. Sin Paginación en Reportes

**Ubicación:** Todos los controladores

```php
// ❌ Sin paginación
->get();

// ✅ Con paginación
->paginate(50);
```

---

## ✅ Mejores Prácticas Identificadas

### 1. Validación de Integridad (TurnoController)

```php
if ($turno->hasAnyRelatedData(['users','maquinas'])) {
    return new Response(['errors' => "..."], Response::HTTP_CONFLICT);
}
```

### 2. Soft Deletes con Deshabilitación (ContratistaController)

```php
$contratista->update(['acceso' => false]);
$contratista->delete();
```

### 3. Método restore() Implementado (ContratistaController)

```php
public function restore($id): Response
{
    // Implementación completa con try-catch
}
```

### 4. Repository Pattern para Lógica Compleja

- RecepcionController → RegistroUsuarios
- TurnoUsuarioController → AsignarTurno

### 5. Auditoría Consistente

```php
$model->user_id = auth()->user()->id; // En store() y update()
```

---

## 📝 Conclusión del Módulo

### Resumen

El **Módulo de Personal** gestiona:

1. **Turnos Laborales** → Definición de horarios
2. **Control de Acceso** → Ingreso/Salida de personal y visitantes
3. **Asignación de Turnos** → Usuarios en máquinas por fechas
4. **Contratistas** → Personal externo con control de acceso

### Complejidad

| Aspecto | Nivel |
|---------|-------|
| Lógica de Negocio | 🟡 MEDIA |
| Queries de BD | 🟢 BAJA-MEDIA |
| Integridad Referencial | 🟡 MEDIA |
| Testabilidad | 🟢 ALTA |
| Performance | 🟡 NECESITA MEJORAS |

### Archivos Relacionados

- **Controladores:** 4 controladores en `app/Http/Controllers/`
- **Repositories:** 2 repositories en `app/Repositories/`
- **Modelos:** `Turno`, `Recepcion`, `TurnoUsuario`, `Contratista`
- **Vistas:** `resources/views/modulos/administrativo/` y `operaciones/`

---

**Documentación Completa:** ✅  
**Tests Propuestos:** 36 tests  
**Última Actualización:** 30 de Enero, 2026
