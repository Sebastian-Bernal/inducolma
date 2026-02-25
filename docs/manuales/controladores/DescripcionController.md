# Documentación: DescripcionController

**Ubicación:** `app/Http/Controllers/DescripcionController.php`  
**Namespace:** `App\Http\Controllers`  
**Extiende:** `Controller`

---

## 📋 Índice

1. [Información General](#información-general)
2. [Dependencias](#dependencias)
3. [Rutas Asociadas](#rutas-asociadas)
4. [Métodos del Controlador](#métodos-del-controlador)
5. [Modelo Asociado](#modelo-asociado)
6. [Jerarquía del Sistema](#jerarquía-del-sistema)

---

## Información General

### Propósito
El `DescripcionController` gestiona las descripciones específicas de las operaciones. Las descripciones son detalles concretos dentro de una categoría de operación (ej: "CORTE INICIAL" dentro de "ASERRADO").

### Funcionalidades Principales
- ✅ Listar todas las descripciones
- ✅ Crear nuevas descripciones asociadas a operaciones
- ✅ Editar descripciones existentes
- ✅ Eliminar descripciones (con validación de relaciones)
- ✅ Control de autorización (solo administradores)

### Jerarquía del Sistema
```
Operación (Padre)
    └── Descripción (Hijo) ← Este controlador
            └── Costo de Operación (Nieto)
```

**Ejemplo:**
```
ASERRADO (Operación)
    ├── CORTE INICIAL (Descripción) ← Gestionado aquí
    ├── DIMENSIONADO (Descripción) ← Gestionado aquí
    └── REASERRADO (Descripción) ← Gestionado aquí
```

---

## Dependencias

```php
use App\Models\Operacion;
use App\Models\Descripcion;
use Illuminate\Http\Response;
use App\Http\Requests\StoreDescripcionRequest;
use App\Http\Requests\UpdateDescripcionRequest;
```

### Modelos Utilizados
- `Descripcion`: Modelo principal para gestión de descripciones
- `Operacion`: Modelo padre para relación

### Form Requests
- `StoreDescripcionRequest`: Validación para crear descripciones
- `UpdateDescripcionRequest`: Validación para actualizar descripciones

---

## Rutas Asociadas

| Método HTTP | URI | Nombre de Ruta | Acción | Middleware |
|-------------|-----|----------------|--------|------------|
| GET | `/costos-descripcion` | `descripciones.index` | index() | auth |
| POST | `/costos-descripcion` | `descripciones.store` | store() | auth |
| GET | `/costos-descripcion/{descripcion}/edit` | `descripciones.edit` | edit() | auth |
| PATCH | `/costos-descripcion/{descripcion}` | `descripciones.update` | update() | auth |
| DELETE | `/costos-descripcion/{descripcion}` | `descripciones.destroy` | destroy() | auth |

---

## Métodos del Controlador

### 1. index()

**Propósito:** Muestra el listado completo de todas las descripciones con sus operaciones asociadas.

**Análisis Línea por Línea:**

```php
public function index()
{
    // Línea 18: Verifica que el usuario tenga rol de administrador
    $this->authorize('admin');
    
    // Línea 19: Obtiene TODAS las descripciones de la BD
    // No usa eager loading, puede causar problema N+1
    $descripciones = Descripcion::all();
    
    // Línea 20: Obtiene TODAS las operaciones disponibles
    // Necesario para el select del formulario de creación
    $operaciones = Operacion::all();
    
    // Línea 21: Retorna vista con ambas colecciones
    // compact() crea array asociativo ['descripciones' => $descripciones, 'operaciones' => $operaciones]
    return view('modulos.administrativo.costos.descripciones', 
        compact(['descripciones', 'operaciones']));
}
```

**Parámetros:** Ninguno

**Retorno:** 
- **Tipo:** `Illuminate\Http\Response`
- **Vista:** `modulos.administrativo.costos.descripciones`
- **Variables:** 
  - `$descripciones` (Colección de objetos Descripcion)
  - `$operaciones` (Colección de objetos Operacion)

**Query SQL Equivalente:**
```sql
-- Query 1: Obtiene descripciones
SELECT * FROM descripciones;

-- Query 2: Obtiene operaciones
SELECT * FROM operaciones;

-- Query N+1: Por cada descripción en la vista si se accede a $descripcion->operacion
SELECT * FROM operaciones WHERE id = ?;  -- (repetido para cada descripción)
```

**Problema de Performance:**
⚠️ **Problema N+1 Query** detectado. Si la vista accede a `$descripcion->operacion`, se ejecutará una query adicional por cada descripción.

**Solución Sugerida:**
```php
$descripciones = Descripcion::with('operacion')->get();
```

**Autorización:** Requiere rol `admin`

---

### 2. create()

**Propósito:** Mostrar formulario de creación (No implementado - se usa modal en index).

```php
public function create()
{
    // Método no implementado
    // El formulario está incluido como modal en la vista index
}
```

**Estado:** ❌ No implementado

---

### 3. store()

**Propósito:** Almacenar una nueva descripción en la base de datos asociándola a una operación.

**Análisis Línea por Línea:**

```php
public function store(StoreDescripcionRequest $request)
{
    // Línea 39: Verificación de autorización - solo administradores
    $this->authorize('admin');
    
    // Línea 40: Crea una nueva instancia del modelo Descripcion
    $descripcion = new Descripcion();
    
    // Línea 41: Asigna el texto de la descripción en MAYÚSCULAS
    // strtoupper() convierte todo el texto a mayúsculas
    $descripcion->descripcion = strtoupper($request->descripcion);
    
    // Línea 42: Asigna la clave foránea de la operación padre
    // 'idOperacion' viene del campo select del formulario
    $descripcion->operacion_id = $request->idOperacion;
    
    // Línea 43: Guarda el registro en la base de datos
    $descripcion->save();
    
    // Línea 44: Redirecciona al índice con mensaje de éxito
    return redirect()
        ->route('descripciones.index')
        ->with('status', 'Descripción creada con éxito');
}
```

**Parámetros:** 
- `$request` (StoreDescripcionRequest): Datos validados del formulario
  - `descripcion`: string (texto de la descripción)
  - `idOperacion`: integer (ID de la operación padre)

**Retorno:** 
- Redirección a `descripciones.index` con mensaje flash

**Query SQL Equivalente:**
```sql
INSERT INTO descripciones (descripcion, operacion_id, created_at, updated_at) 
VALUES ('TEXTO_DESCRIPCION', 1, NOW(), NOW());
```

**Transformaciones:**
- ✅ Conversión a mayúsculas de `descripcion`

**Validaciones Esperadas:**
- `descripcion`: required, string, max:255
- `idOperacion`: required, exists:operaciones,id

**Autorización:** Requiere rol `admin`

---

### 4. show()

**Propósito:** Mostrar detalles de una descripción específica (No implementado).

```php
public function show(Descripcion $descripcion)
{
    // Método no implementado
}
```

**Estado:** ❌ No implementado

---

### 5. edit()

**Propósito:** Mostrar el formulario de edición de una descripción específica.

**Análisis Línea por Línea:**

```php
public function edit(Descripcion $descripcion)
{
    // Línea 63: Verificación de autorización
    $this->authorize('admin');
    
    // Línea 64: Busca la descripción por ID
    // Redundante: $descripcion ya viene inyectada por route model binding
    $descripcion = Descripcion::findOrFail($descripcion->id);
    
    // Línea 65: Obtiene todas las operaciones para el select
    // Necesario para permitir cambiar la operación asociada
    $operaciones = Operacion::all();
    
    // Líneas 66-70: Retorna vista de edición con los datos
    return view('modulos.administrativo.costos.descripciones-edit', [
        'descripcion' => $descripcion,
        'operaciones' => $operaciones
    ]);
}
```

**Parámetros:** 
- `$descripcion` (Descripcion): Instancia del modelo mediante inyección de dependencia

**Retorno:** 
- **Vista:** `modulos.administrativo.costos.descripciones-edit`
- **Variables:** 
  - `$descripcion` (Objeto Descripcion)
  - `$operaciones` (Colección de Operacion para select)

**Query SQL Equivalente:**
```sql
-- Query 1: Obtiene la descripción (redundante por model binding)
SELECT * FROM descripciones WHERE id = ? LIMIT 1;

-- Query 2: Obtiene todas las operaciones
SELECT * FROM operaciones;
```

**Autorización:** Requiere rol `admin`

**Nota:** La línea 64 es redundante porque Laravel ya inyecta la instancia.

---

### 6. update()

**Propósito:** Actualizar los datos de una descripción existente.

**Análisis Línea por Línea:**

```php
public function update(UpdateDescripcionRequest $request, Descripcion $descripcion)
{
    // Línea 82: Verificación de autorización
    $this->authorize('admin');
    
    // Línea 83: Busca la descripción (redundante, ya viene inyectada)
    $descripcion = Descripcion::findOrFail($descripcion->id);
    
    // Línea 84: Actualiza el texto de la descripción en mayúsculas
    $descripcion->descripcion = strtoupper($request->descripcion);
    
    // Línea 85: Actualiza la operación asociada
    // Permite cambiar la descripción a otra operación padre
    $descripcion->operacion_id = $request->idOperacion;
    
    // Línea 86: Guarda los cambios en la base de datos
    $descripcion->save();
    
    // Línea 87: Redirecciona al índice con mensaje personalizado
    // Incluye el texto de la descripción en el mensaje
    return redirect()
        ->route('descripciones.index')
        ->with('status', "La descripción $descripcion->descripcion ha sido actualizada");
}
```

**Parámetros:** 
- `$request` (UpdateDescripcionRequest): Datos validados del formulario
  - `descripcion`: string (texto actualizado)
  - `idOperacion`: integer (ID de la operación, puede cambiar)
- `$descripcion` (Descripcion): Instancia del modelo a actualizar

**Retorno:** 
- Redirección a `descripciones.index` con mensaje flash

**Query SQL Equivalente:**
```sql
UPDATE descripciones 
SET descripcion = 'TEXTO_ACTUALIZADO',
    operacion_id = 2,
    updated_at = NOW()
WHERE id = ?;
```

**Transformaciones:**
- ✅ Conversión a mayúsculas de `descripcion`

**Funcionalidad Especial:**
- ✅ Permite reasignar descripción a otra operación
- ✅ Útil si se clasificó incorrectamente

**Autorización:** Requiere rol `admin`

---

### 7. destroy()

**Propósito:** Eliminar una descripción del sistema (con validación de relaciones).

**Análisis Línea por Línea:**

```php
public function destroy(Descripcion $descripcion)
{
    // Línea 98: Verificación de autorización
    $this->authorize('admin');

    // Líneas 100-102: Valida si la descripción tiene datos relacionados
    // hasAnyRelatedData() verifica si tiene registros en 'costos_operacion'
    // Si tiene costos asociados, no permite eliminar
    if ($descripcion->hasAnyRelatedData(['costos_operacion'])) {
        return back()->withErrors(
            "No se pudo eliminar el recurso porque tiene datos asociados"
        );
    }

    // Línea 104: Elimina la descripción de la base de datos
    $descripcion->delete();
    
    // Línea 105: Retorna al índice sin mensaje específico de éxito
    return redirect()->route('descripciones.index');
}
```

**Parámetros:** 
- `$descripcion` (Descripcion): Instancia del modelo a eliminar

**Retorno:** 
- Redirección a `descripciones.index`
- Con mensaje de error si tiene relaciones
- Sin mensaje si se eliminó exitosamente

**Query SQL Equivalente:**
```sql
-- Primero verifica relaciones
SELECT COUNT(*) FROM costos_operacion WHERE descripcion_id = ?;

-- Si no hay relaciones, elimina
DELETE FROM descripciones WHERE id = ?;
```

**Validaciones:**
- ✅ Verifica que no tenga registros en `costos_operacion`
- ✅ Previene eliminación de descripciones en uso
- ✅ Protege integridad referencial

**Autorización:** Requiere rol `admin`

---

## Modelo Asociado

### Descripcion Model

**Ubicación:** `app/Models/Descripcion.php`

```php
class Descripcion extends Model
{
    use HasFactory, CheckRelations;
    
    protected $table = 'descripciones';
    
    // Relación con padre
    public function operacion()
    {
        return $this->belongsTo(Operacion::class);
    }

    // Relación con hijos
    public function costos_operacion()
    {
        return $this->hasMany(CostosOperacion::class);
    }
}
```

### Campos de la Tabla

| Campo | Tipo | Descripción | Nullable | Default |
|-------|------|-------------|----------|---------|
| id | BIGINT | Clave primaria | NO | AUTO_INCREMENT |
| descripcion | VARCHAR(255) | Texto de la descripción | NO | - |
| operacion_id | BIGINT | FK a operaciones | NO | - |
| created_at | TIMESTAMP | Fecha de creación | YES | NULL |
| updated_at | TIMESTAMP | Fecha de actualización | YES | NULL |

**Índices:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `operacion_id` REFERENCES `operaciones(id)`
- INDEX: `operacion_id` (para búsquedas eficientes)

**Nota:** El modelo no define `$fillable`, por lo que usa asignación masiva deshabilitada.

### Relaciones

#### 1. operacion (Belongs To - Padre)
- **Tipo:** belongsTo
- **Modelo:** Operacion
- **Clave Foránea:** `operacion_id`
- **Descripción:** Cada descripción pertenece a una operación

**Ejemplo:**
```php
$descripcion = Descripcion::find(1);
$operacion = $descripcion->operacion; // Objeto Operacion
echo $descripcion->operacion->operacion; // "ASERRADO"
```

#### 2. costos_operacion (Has Many - Hijos)
- **Tipo:** hasMany
- **Modelo:** CostosOperacion
- **Clave Foránea:** `descripcion_id` (en tabla costos_operacion)
- **Descripción:** Una descripción puede tener múltiples costos

**Ejemplo:**
```php
$descripcion = Descripcion::find(1);
$costos = $descripcion->costos_operacion; // Colección de CostosOperacion
```

---

## Jerarquía del Sistema

### Estructura Completa

```
┌─────────────┐
│  Operacion  │ (Categoría: ej. "ASERRADO")
└──────┬──────┘
       │ hasMany
       ▼
┌─────────────┐
│ Descripcion │ ← ESTE MODELO (ej. "CORTE INICIAL")
└──────┬──────┘
       │ hasMany
       ▼
┌──────────────────┐
│ CostosOperacion  │ (ej. Sierra 1: $50/día)
└──────────────────┘
```

### Ejemplo con Datos Reales

```
Operacion: "ASERRADO" (id: 1)
    │
    ├── Descripcion: "CORTE INICIAL" (id: 1, operacion_id: 1)
    │       ├── CostosOperacion: Maquina "SIERRA 1", $50/día
    │       └── CostosOperacion: Maquina "SIERRA 2", $45/día
    │
    ├── Descripcion: "DIMENSIONADO" (id: 2, operacion_id: 1)
    │       └── CostosOperacion: Maquina "DIMENSIONADORA", $60/día
    │
    └── Descripcion: "REASERRIO" (id: 3, operacion_id: 1)
            └── CostosOperacion: Maquina "REASERRIO 1", $40/día
```

---

## Validaciones

### StoreDescripcionRequest

**Ubicación:** `app/Http/Requests/StoreDescripcionRequest.php`

Reglas de validación esperadas:
```php
public function rules()
{
    return [
        'descripcion' => 'required|string|max:255',
        'idOperacion' => 'required|integer|exists:operaciones,id',
    ];
}

public function messages()
{
    return [
        'descripcion.required' => 'La descripción es obligatoria',
        'idOperacion.required' => 'Debe seleccionar una operación',
        'idOperacion.exists' => 'La operación seleccionada no existe',
    ];
}
```

**Validaciones:**
- ✅ Descripción requerida
- ✅ Tipo string
- ✅ Máximo 255 caracteres
- ✅ Operación debe existir en BD

### UpdateDescripcionRequest

**Ubicación:** `app/Http/Requests/UpdateDescripcionRequest.php`

Reglas de validación esperadas (similares a store):
```php
public function rules()
{
    return [
        'descripcion' => 'required|string|max:255',
        'idOperacion' => 'required|integer|exists:operaciones,id',
    ];
}
```

**Nota:** A diferencia de Operacion, no necesita validación de `unique` porque pueden existir descripciones con el mismo texto en diferentes operaciones.

---

## Flujo de Datos

### Crear Descripción
```
Usuario → Accede a /costos-descripcion
→ Vista muestra listado + formulario
→ Usuario selecciona operación padre
→ Usuario ingresa descripción (ej: "Corte inicial")
→ Submit POST /costos-descripcion
→ StoreDescripcionRequest valida:
    ✓ Descripción no vacía
    ✓ Operación existe
→ DescripcionController@store
→ Conversión a mayúsculas: "CORTE INICIAL"
→ Asignación operacion_id
→ INSERT en BD
→ Redirect a index
→ Mensaje: "Descripción creada con éxito"
```

### Editar Descripción
```
Usuario → Click "Editar" en fila
→ GET /costos-descripcion/{id}/edit
→ Vista con formulario pre-llenado
→ Select muestra todas las operaciones
→ Operación actual viene seleccionada
→ Usuario modifica descripción y/o operación
→ Submit PATCH /costos-descripcion/{id}
→ UpdateDescripcionRequest valida
→ UPDATE en BD
→ Redirect a index
→ Mensaje: "La descripción [NOMBRE] ha sido actualizada"
```

### Eliminar Descripción
```
Usuario → Click "Eliminar"
→ Confirmación JavaScript
→ DELETE /costos-descripcion/{id}
→ Verifica hasAnyRelatedData(['costos_operacion'])
→ SI tiene costos:
    └── Error: "No se pudo eliminar..."
    └── Back con error
→ NO tiene costos:
    └── DELETE en BD
    └── Redirect a index (sin mensaje)
```

### Cambiar Operación Padre
```
Usuario → Edita descripción
→ Cambia select de operación
→ De "ASERRADO" a "ENSAMBLE"
→ Submit actualización
→ UPDATE: operacion_id cambia
→ Descripción ahora pertenece a nueva operación
→ Mantiene todos sus costos asociados
```

---

## Queries Útiles

### Obtener descripción con su operación
```php
$descripcion = Descripcion::with('operacion')->find(1);
echo $descripcion->operacion->operacion; // Sin query adicional
```

### Descripciones de una operación específica
```php
$descripciones = Descripcion::where('operacion_id', 1)->get();
// O usando la relación inversa
$operacion = Operacion::with('descripciones')->find(1);
$descripciones = $operacion->descripciones;
```

### Descripción con todos sus costos
```php
$descripcion = Descripcion::with([
    'operacion',
    'costos_operacion.maquina'
])->find(1);
```

### Descripciones sin costos asignados
```php
$descripcionesSinCostos = Descripcion::doesntHave('costos_operacion')->get();
```

### Contar costos por descripción
```php
$descripciones = Descripcion::withCount('costos_operacion')
    ->orderBy('costos_operacion_count', 'desc')
    ->get();

foreach ($descripciones as $desc) {
    echo "{$desc->descripcion}: {$desc->costos_operacion_count} costos";
}
```

---

## Mejoras Sugeridas

### Código
1. ❌ Eliminar línea redundante en `edit()` y `update()` (findOrFail innecesario)
2. ✅ Usar eager loading en index: `Descripcion::with('operacion')->get()`
3. ✅ Definir `$fillable` en el modelo
4. ✅ Usar `Descripcion::create()` en lugar de `new + save()`
5. ✅ Agregar mensaje de éxito en destroy()

### Funcionalidad
1. ✅ Implementar método `show()` con detalles y costos
2. ✅ Agregar filtro por operación en el listado
3. ✅ Agregar contador de costos en el listado
4. ✅ Implementar búsqueda por texto
5. ✅ Agregar validación para evitar duplicados dentro de misma operación

### Performance
1. ✅ Eager loading para evitar N+1
2. ✅ Índice en columna `operacion_id`
3. ✅ Cache de operaciones para el select

### Seguridad
1. ✅ Ya implementa autorización
2. ✅ Ya usa Form Requests
3. ✅ Ya valida relaciones antes de eliminar

---

## Ejemplos de Uso

### Crear Descripción
```php
// Request
POST /costos-descripcion
{
    "descripcion": "Corte inicial de troza",
    "idOperacion": 1
}

// Response
Redirect to /costos-descripcion
Flash: "Descripción creada con éxito"

// En BD
{
    "id": 10,
    "descripcion": "CORTE INICIAL DE TROZA",
    "operacion_id": 1,
    "created_at": "2026-01-30 10:00:00",
    "updated_at": "2026-01-30 10:00:00"
}
```

### Actualizar y Cambiar Operación
```php
// Antes
descripcion_id: 10
descripcion: "CORTE INICIAL DE TROZA"
operacion_id: 1 (ASERRADO)

// Request
PATCH /costos-descripcion/10
{
    "descripcion": "Armado de estructura",
    "idOperacion": 2  // Cambió de ASERRADO a ENSAMBLE
}

// Después
descripcion_id: 10
descripcion: "ARMADO DE ESTRUCTURA"
operacion_id: 2 (ENSAMBLE)

// Response
Redirect to /costos-descripcion
Flash: "La descripción ARMADO DE ESTRUCTURA ha sido actualizada"
```

### Intentar Eliminar con Costos Asociados
```php
// Descripción tiene 3 costos operacionales
DELETE /costos-descripcion/10

// Response
Redirect back
Errors: "No se pudo eliminar el recurso porque tiene datos asociados"

// En BD: NO se elimina nada
```

---

## Casos de Uso Reales

### Ejemplo 1: Proceso de Aserrado Completo

```php
// Operacion
"ASERRADO"

// Descripciones
├── "CORTE INICIAL"        // Primera transformación de troza
├── "DIMENSIONADO"          // Dar medidas exactas
├── "CEPILLADO"            // Alisar superficies
├── "REASERRIO"            // Reprocess de piezas
└── "CLASIFICACIÓN"         // Separar por calidad
```

### Ejemplo 2: Proceso de Ensamble

```php
// Operacion
"ENSAMBLE"

// Descripciones
├── "ARMADO"                // Unir piezas
├── "PEGADO"               // Aplicar adhesivos
├── "PRENSADO"             // Aplicar presión
├── "AJUSTE"               // Correcciones finales
└── "CONTROL CALIDAD"       // Verificación
```

### Ejemplo 3: Proceso de Acabados

```php
// Operacion
"ACABADOS"

// Descripciones
├── "LIJADO GRUESO"        // Primera pasada
├── "LIJADO FINO"          // Suavizado
├── "SELLADO"              // Impermeabilización
├── "PINTADO"              // Aplicar color
└── "BARNIZADO"            // Protección final
```

---

## Comparación con otros Controladores

### vs OperacionController

| Aspecto | DescripcionController | OperacionController |
|---------|----------------------|---------------------|
| Nivel jerárquico | Hijo | Padre |
| Campos | 2 (descripcion, operacion_id) | 1 (operacion) |
| Relaciones | 2 (padre, hijos) | 1 (hijos) |
| Dependencia | Necesita Operacion | Independiente |
| Complejidad | Media | Baja |

### vs CostosOperacionController

| Aspecto | DescripcionController | CostosOperacionController |
|---------|----------------------|---------------------------|
| Nivel jerárquico | Padre | Hijo |
| Campos | 2 | 6 |
| Vista edit | Carga operaciones | Carga máquinas y descripciones |
| Complejidad | Media | Alta |

---

**Última actualización:** 30 de Enero, 2026  
**Versión:** 1.0  
**Autor:** Sistema de Documentación Inducolma
