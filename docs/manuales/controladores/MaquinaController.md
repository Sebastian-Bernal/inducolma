# Documentación: MaquinaController

**Ubicación:** `app/Http/Controllers/MaquinaController.php`  
**Namespace:** `App\Http\Controllers`  
**Extiende:** `Controller`

---

## 📋 Índice

1. [Información General](#información-general)
2. [Dependencias](#dependencias)
3. [Rutas Asociadas](#rutas-asociadas)
4. [Métodos del Controlador](#métodos-del-controlador)
5. [Modelo Asociado](#modelo-asociado)
6. [Vistas Asociadas](#vistas-asociadas)
7. [Validaciones](#validaciones)
8. [Autorización](#autorización)

---

## Información General

### Propósito
El `MaquinaController` gestiona el CRUD (Crear, Leer, Actualizar, Eliminar) de las máquinas utilizadas en los procesos de producción de la empresa. Las máquinas se clasifican por tipo de corte y están vinculadas a costos operacionales e infraestructura.

### Funcionalidades Principales
- ✅ Listar todas las máquinas
- ✅ Crear nuevas máquinas
- ✅ Editar máquinas existentes
- ✅ Eliminar máquinas (con validación de relaciones)
- ✅ Control de autorización (solo administradores)

---

## Dependencias

```php
use App\Models\Maquina;
use App\Http\Requests\StoreMaquinaRequest;
use App\Http\Requests\UpdateMaquinaRequest;
use GuzzleHttp\Middleware;
```

### Modelos Utilizados
- `Maquina`: Modelo principal para gestión de máquinas

### Form Requests
- `StoreMaquinaRequest`: Validación para crear máquinas
- `UpdateMaquinaRequest`: Validación para actualizar máquinas

---

## Rutas Asociadas

| Método HTTP | URI | Nombre de Ruta | Acción | Middleware |
|-------------|-----|----------------|--------|------------|
| GET | `/costos-maquina` | `maquinas.index` | index() | auth |
| POST | `/costos-maquina` | `maquinas.store` | store() | auth |
| GET | `/costos-maquina/{maquina}/edit` | `maquinas.edit` | edit() | auth |
| PATCH | `/costos-maquina/{maquina}` | `maquinas.update` | update() | auth |
| DELETE | `/costos-maquina/{maquina}` | `maquinas.destroy` | destroy() | auth |

---

## Métodos del Controlador

### 1. index()

**Propósito:** Muestra el listado completo de todas las máquinas registradas.

**Análisis Línea por Línea:**

```php
public function index()
{
    // Línea 19: Verifica que el usuario autenticado tenga rol de administrador
    // Si no es admin, lanza excepción 403 Forbidden
    $this->authorize('admin');
    
    // Línea 20: Consulta todas las máquinas de la base de datos
    // latest() ordena por fecha de creación descendente (más reciente primero)
    // get() ejecuta la consulta y devuelve una colección
    $maquinas = Maquina::latest()->get();

    // Línea 22: Retorna la vista con los datos de las máquinas
    // compact('maquinas') equivale a ['maquinas' => $maquinas]
    return view('modulos.administrativo.costos.maquinas', compact('maquinas'));
}
```

**Parámetros:** Ninguno

**Retorno:** 
- **Tipo:** `Illuminate\Http\Response`
- **Vista:** `modulos.administrativo.costos.maquinas`
- **Variables:** `$maquinas` (Colección de objetos Maquina)

**Query SQL Equivalente:**
```sql
SELECT * FROM maquinas ORDER BY created_at DESC;
```

**Autorización:** Requiere rol `admin`

---

### 2. create()

**Propósito:** Mostrar formulario de creación (No implementado - se usa modal en index).

```php
public function create()
{
    // Método no implementado
    // El formulario de creación está incluido como modal en la vista index
}
```

**Estado:** ❌ No implementado

---

### 3. store()

**Propósito:** Almacenar una nueva máquina en la base de datos.

**Análisis Línea por Línea:**

```php
public function store(StoreMaquinaRequest $request)
{
    // Línea 40: Verificación de autorización - solo administradores
    $this->authorize('admin');
    
    // Líneas 41-46: Crea un nuevo registro en la tabla maquinas
    Maquina::create(
        [
            // strtoupper() convierte el texto a MAYÚSCULAS
            'maquina' => strtoupper($request->maquina), // Nombre de la máquina
            'corte' => strtoupper($request->corte),     // Tipo de corte
        ]
    );
    
    // Línea 48: Redirecciona a la página anterior con mensaje de éxito
    // with('status', ...) flashea el mensaje en la sesión
    return back()->with('status', 'Maquina creada con éxito');
}
```

**Parámetros:** 
- `$request` (StoreMaquinaRequest): Datos validados del formulario
  - `maquina`: string (nombre de la máquina)
  - `corte`: string (tipo de corte)

**Retorno:** 
- Redirección a la página anterior con mensaje flash

**Query SQL Equivalente:**
```sql
INSERT INTO maquinas (maquina, corte, created_at, updated_at) 
VALUES ('NOMBRE_MAQUINA', 'TIPO_CORTE', NOW(), NOW());
```

**Transformaciones:**
- ✅ Conversión a mayúsculas de `maquina` y `corte`

**Autorización:** Requiere rol `admin`

---

### 4. show()

**Propósito:** Mostrar detalles de una máquina específica (No implementado).

```php
public function show(Maquina $maquina)
{
    // Método no implementado
}
```

**Estado:** ❌ No implementado

---

### 5. edit()

**Propósito:** Mostrar el formulario de edición de una máquina específica.

**Análisis Línea por Línea:**

```php
public function edit(Maquina $maquina)
{
    // Línea 71: Verificación de autorización
    $this->authorize('admin');
    
    // Línea 72: Busca la máquina por ID
    // findOrFail() lanza excepción 404 si no encuentra el registro
    // Nota: Redundante ya que $maquina viene por inyección de dependencia
    $maquina = Maquina::findOrFail($maquina->id);
    
    // Líneas 73-76: Retorna vista de edición con los datos de la máquina
    return view('modulos.administrativo.costos.edit-maquinas', [
        'maquina' => $maquina,
    ]);
}
```

**Parámetros:** 
- `$maquina` (Maquina): Instancia del modelo mediante inyección de dependencia

**Retorno:** 
- **Vista:** `modulos.administrativo.costos.edit-maquinas`
- **Variables:** `$maquina` (Objeto Maquina)

**Query SQL Equivalente:**
```sql
SELECT * FROM maquinas WHERE id = ? LIMIT 1;
```

**Autorización:** Requiere rol `admin`

**Nota:** La línea 72 es redundante porque Laravel ya inyecta la instancia del modelo.

---

### 6. update()

**Propósito:** Actualizar los datos de una máquina existente.

**Análisis Línea por Línea:**

```php
public function update(UpdateMaquinaRequest $request, Maquina $maquina)
{
    // Línea 92: Verificación de autorización
    $this->authorize('admin');
    
    // Línea 94: Busca la máquina (redundante, ya viene inyectada)
    $maquina = Maquina::findOrFail($maquina->id);
    
    // Líneas 95-96: Actualiza los atributos del modelo
    $maquina->maquina = strtoupper($request->maquina);
    $maquina->corte = strtoupper($request->corte);
    
    // Línea 97: Guarda los cambios en la base de datos
    $maquina->save();
    
    // Línea 98: Redirecciona al índice con mensaje de éxito
    return redirect()
        ->route('maquinas.index')
        ->with('status', "Maquina $maquina->maquina actualizada con éxito");
}
```

**Parámetros:** 
- `$request` (UpdateMaquinaRequest): Datos validados del formulario
- `$maquina` (Maquina): Instancia del modelo a actualizar

**Retorno:** 
- Redirección a `maquinas.index` con mensaje flash

**Query SQL Equivalente:**
```sql
UPDATE maquinas 
SET maquina = 'NOMBRE_ACTUALIZADO', 
    corte = 'TIPO_CORTE_ACTUALIZADO',
    updated_at = NOW()
WHERE id = ?;
```

**Transformaciones:**
- ✅ Conversión a mayúsculas de `maquina` y `corte`

**Autorización:** Requiere rol `admin`

---

### 7. destroy()

**Propósito:** Eliminar una máquina del sistema (con validación de relaciones).

**Análisis Línea por Línea:**

```php
public function destroy(Maquina $maquina)
{
    // Línea 109: Verificación de autorización
    $this->authorize('admin');
    
    // Líneas 110-112: Valida si la máquina tiene datos relacionados
    // hasAnyRelatedData() es un método del trait CheckRelations
    // Verifica si existen registros en la relación 'costos_operacion'
    if ($maquina->hasAnyRelatedData(['costos_operacion'])) {
        // Si tiene relaciones, retorna error y no elimina
        return back()->withErrors("No se pudo eliminar el recurso porque tiene datos asociados");
    }
    
    // Línea 113: Elimina la máquina de la base de datos
    $maquina->delete();
    
    // Línea 114: Retorna a la página anterior
    return back();
}
```

**Parámetros:** 
- `$maquina` (Maquina): Instancia del modelo a eliminar

**Retorno:** 
- Redirección a la página anterior
- Con mensaje de error si tiene relaciones
- Sin mensaje si se eliminó exitosamente

**Query SQL Equivalente:**
```sql
-- Primero verifica relaciones
SELECT COUNT(*) FROM costos_operacion WHERE maquina_id = ?;

-- Si no hay relaciones, elimina
DELETE FROM maquinas WHERE id = ?;
```

**Validaciones:**
- ✅ Verifica que no tenga registros en `costos_operacion`
- ✅ Previene eliminación en cascada no deseada

**Autorización:** Requiere rol `admin`

---

## Modelo Asociado

### Maquina Model

**Ubicación:** `app/Models/Maquina.php`

```php
class Maquina extends Model
{
    use HasFactory, CheckRelations;

    protected $fillable = ['id', 'maquina', 'corte'];
    
    // Relaciones
    public function costos_operacion()
    {
        return $this->hasMany(CostosOperacion::class);
    }

    public function costos_infraestructura()
    {
        return $this->hasOne(CostosInfraestructura::class);
    }

    public function turnos()
    {
        return $this->belongsToMany(Turno::class, 'turno_usuarios');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'turno_usuarios');
    }
}
```

### Campos de la Tabla

| Campo | Tipo | Descripción | Nullable | Default |
|-------|------|-------------|----------|---------|
| id | BIGINT | Clave primaria | NO | AUTO_INCREMENT |
| maquina | VARCHAR(255) | Nombre de la máquina | NO | - |
| corte | VARCHAR(255) | Tipo de corte | NO | - |
| created_at | TIMESTAMP | Fecha de creación | YES | NULL |
| updated_at | TIMESTAMP | Fecha de actualización | YES | NULL |

### Relaciones

#### 1. costos_operacion (One to Many)
- **Tipo:** hasMany
- **Modelo:** CostosOperacion
- **Descripción:** Una máquina puede tener múltiples costos operacionales

#### 2. costos_infraestructura (One to One)
- **Tipo:** hasOne
- **Modelo:** CostosInfraestructura
- **Descripción:** Una máquina tiene un costo de infraestructura

#### 3. turnos (Many to Many)
- **Tipo:** belongsToMany
- **Modelo:** Turno
- **Tabla Pivote:** turno_usuarios
- **Descripción:** Una máquina puede operar en múltiples turnos

#### 4. users (Many to Many)
- **Tipo:** belongsToMany
- **Modelo:** User
- **Tabla Pivote:** turno_usuarios
- **Descripción:** Una máquina puede ser operada por múltiples usuarios

---

## Vistas Asociadas

### 1. Vista Index: maquinas.blade.php

**Ubicación:** `resources/views/modulos/administrativo/costos/maquinas.blade.php`

**Propósito:** Listar todas las máquinas y permitir crear nuevas mediante modal.

#### Componentes de la Vista

##### A. Estructura HTML
```blade
@extends('layouts.web')
@section('title', ' Maquinas | inducolma')
@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
```

##### B. Modal de Creación
- **ID Modal:** `#creaMaquina`
- **Trigger:** Botón "Crear maquina"
- **Método:** POST
- **Ruta:** `{{ route('maquinas.store') }}`

**Campos del Formulario:**
1. **Maquina** (input text)
   - Name: `maquina`
   - Clase: `text-uppercase`
   - Requerido: ✅
   - Placeholder: "Nombre maquina"

2. **Corte** (select)
   - Name: `corte`
   - Requerido: ✅
   - Opciones:
     - `INICIAL`
     - `INTERMEDIO`
     - `FINAL`
     - `ACABADOS` (Acabados de item)
     - `ASERRIO`
     - `ENSAMBLE`
     - `ACABADO_ENSAMBLE`
     - `REASERRIO`

##### C. Tabla de Datos (DataTable)
- **ID Tabla:** `#listaMaquinas`
- **Librerías:** DataTables + Bootstrap 5
- **Idioma:** Español (`/DataTables/Spanish.json`)
- **Responsive:** ✅

**Columnas:**
1. ID
2. Maquina
3. Tipo de corte
4. Acciones (Eliminar, Editar)

##### D. JavaScript
```javascript
$('#listaMaquinas').DataTable({
    "language": {
        "url": "/DataTables/Spanish.json"
    },
    "responsive": true
});
```

#### Validaciones Frontend
- ✅ Campos requeridos con atributo HTML `required`
- ✅ Confirmación antes de eliminar: `confirm('¿desea eliminar...')`
- ✅ Conversión automática a mayúsculas en input

#### Mensajes de Usuario
- **Éxito (store):** "Maquina creada con éxito"
- **Error (destroy):** "No se pudo eliminar el recurso porque tiene datos asociados"
- **Confirmación (delete):** "¿desea eliminar la maquina: [nombre]?"

---

### 2. Vista Edit: edit-maquinas.blade.php

**Ubicación:** `resources/views/modulos/administrativo/costos/edit-maquinas.blade.php`

**Propósito:** Editar los datos de una máquina existente.

**Variables Recibidas:**
- `$maquina`: Objeto con los datos actuales de la máquina

---

## Validaciones

### StoreMaquinaRequest

**Ubicación:** `app/Http/Requests/StoreMaquinaRequest.php`

Reglas de validación esperadas:
```php
public function rules()
{
    return [
        'maquina' => 'required|string|max:255',
        'corte' => 'required|string|in:INICIAL,INTERMEDIO,FINAL,ACABADOS,ASERRIO,ENSAMBLE,ACABADO_ENSAMBLE,REASERRIO',
    ];
}
```

### UpdateMaquinaRequest

**Ubicación:** `app/Http/Requests/UpdateMaquinaRequest.php`

Reglas de validación esperadas (similares a store):
```php
public function rules()
{
    return [
        'maquina' => 'required|string|max:255',
        'corte' => 'required|string|in:INICIAL,INTERMEDIO,FINAL,ACABADOS,ASERRIO,ENSAMBLE,ACABADO_ENSAMBLE,REASERRIO',
    ];
}
```

---

## Autorización

### Policy Utilizada
El controlador usa `$this->authorize('admin')` en todos los métodos públicos.

### Implementación
```php
// Verifica que el usuario autenticado tenga rol de administrador
$this->authorize('admin');
```

### Excepciones
- Si el usuario no es admin: `403 Forbidden`
- Si el usuario no está autenticado: Redirección a login (middleware auth)

---

## Flujo de Datos

### Crear Máquina
```
Usuario → Click "Crear maquina" 
→ Modal se abre 
→ Usuario llena formulario 
→ Submit (POST /costos-maquina)
→ StoreMaquinaRequest valida
→ MaquinaController@store
→ Conversión a mayúsculas
→ Maquina::create()
→ INSERT en BD
→ Redirección con mensaje éxito
```

### Editar Máquina
```
Usuario → Click "Editar" 
→ GET /costos-maquina/{id}/edit
→ MaquinaController@edit
→ Carga vista con datos
→ Usuario modifica
→ Submit (PATCH /costos-maquina/{id})
→ UpdateMaquinaRequest valida
→ MaquinaController@update
→ UPDATE en BD
→ Redirect a index con mensaje
```

### Eliminar Máquina
```
Usuario → Click "Eliminar"
→ Confirmación JavaScript
→ DELETE /costos-maquina/{id}
→ MaquinaController@destroy
→ Verifica relaciones (hasAnyRelatedData)
→ Si tiene relaciones: Error
→ Si no tiene: DELETE en BD
→ Back con/sin mensaje
```

---

## Mejoras Sugeridas

### Código
1. ❌ Eliminar líneas redundantes en `edit()` y `update()` (findOrFail innecesario)
2. ⚠️ Remover dependencia no utilizada: `GuzzleHttp\Middleware`
3. ✅ Considerar soft deletes para preservar historial
4. ✅ Agregar mensaje de éxito en destroy()

### Funcionalidad
1. ✅ Implementar método `show()` para ver detalles
2. ✅ Agregar búsqueda y filtros en el listado
3. ✅ Exportar listado a Excel/PDF
4. ✅ Registro de auditoría de cambios

### Seguridad
1. ✅ Ya implementa autorización correctamente
2. ✅ Ya usa Form Requests para validación
3. ✅ Ya valida relaciones antes de eliminar

---

## Ejemplos de Uso

### Crear una Máquina
```php
// Request
POST /costos-maquina
{
    "maquina": "Sierra Circular",
    "corte": "INICIAL"
}

// Response
Redirect to /costos-maquina
Flash: "Maquina creada con éxito"
```

### Actualizar una Máquina
```php
// Request
PATCH /costos-maquina/5
{
    "maquina": "Sierra Circular Actualizada",
    "corte": "INTERMEDIO"
}

// Response
Redirect to /costos-maquina
Flash: "Maquina SIERRA CIRCULAR ACTUALIZADA actualizada con éxito"
```

### Eliminar una Máquina
```php
// Request
DELETE /costos-maquina/5

// Response (sin relaciones)
Redirect to previous page

// Response (con relaciones)
Redirect to previous page
Errors: "No se pudo eliminar el recurso porque tiene datos asociados"
```

---

## Notas Adicionales

### Tipos de Corte
Los tipos de corte definen las etapas del proceso productivo:

1. **INICIAL**: Primer corte de la troza
2. **INTERMEDIO**: Cortes de procesamiento
3. **FINAL**: Corte final de piezas
4. **ACABADOS**: Acabados de items individuales
5. **ASERRIO**: Proceso de aserrado
6. **ENSAMBLE**: Ensamblaje de productos
7. **ACABADO_ENSAMBLE**: Acabados de productos ensamblados
8. **REASERRIO**: Re-proceso de aserrado

### Dependencias del Sistema
- Bootstrap 5
- DataTables 1.11.4
- jQuery
- SweetAlert (para mensajes)

---

**Última actualización:** 30 de Enero, 2026  
**Versión:** 1.0  
**Autor:** Sistema de Documentación Inducolma
