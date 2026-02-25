# Documentación: OperacionController

**Ubicación:** `app/Http/Controllers/OperacionController.php`  
**Namespace:** `App\Http\Controllers`  
**Extiende:** `Controller`

---

## 📋 Índice

1. [Información General](#información-general)
2. [Dependencias](#dependencias)
3. [Rutas Asociadas](#rutas-asociadas)
4. [Métodos del Controlador](#métodos-del-controlador)
5. [Modelo Asociado](#modelo-asociado)
6. [Relaciones del Sistema](#relaciones-del-sistema)

---

## Información General

### Propósito
El `OperacionController` gestiona el catálogo de operaciones que se realizan en el proceso productivo. Las operaciones son categorías generales de trabajo (ej: "ASERRADO", "ENSAMBLE") que posteriormente tienen descripciones específicas asociadas.

### Funcionalidades Principales
- ✅ Listar todas las operaciones
- ✅ Crear nuevas operaciones
- ✅ Editar operaciones existentes
- ✅ Eliminar operaciones (con validación de relaciones)
- ✅ Control de autorización (solo administradores)

### Jerarquía del Sistema
```
Operación (Categoría general)
    └── Descripción (Detalle específico)
            └── Costo de Operación (Valores)
```

---

## Dependencias

```php
use App\Models\Operacion;
use App\Http\Requests\StoreOperacionRequest;
use App\Http\Requests\UpdateOperacionRequest;
```

### Modelos Utilizados
- `Operacion`: Modelo principal para gestión de operaciones

### Form Requests
- `StoreOperacionRequest`: Validación para crear operaciones
- `UpdateOperacionRequest`: Validación para actualizar operaciones

---

## Rutas Asociadas

| Método HTTP | URI | Nombre de Ruta | Acción | Middleware |
|-------------|-----|----------------|--------|------------|
| GET | `/costos-operacion` | `operaciones.index` | index() | auth |
| POST | `/costos-operacion` | `operaciones.store` | store() | auth |
| GET | `/costos-operacion/{operacion}/edit` | `operaciones.edit` | edit() | auth |
| PATCH | `/costos-operacion/{operacion}` | `operaciones.update` | update() | auth |
| DELETE | `/costos-operacion/{operacion}` | `operaciones.destroy` | destroy() | auth |

---

## Métodos del Controlador

### 1. index()

**Propósito:** Muestra el listado completo de todas las operaciones registradas.

**Análisis Línea por Línea:**

```php
public function index()
{
    // Línea 18: Verifica que el usuario tenga rol de administrador
    // Lanza excepción 403 si no es admin
    $this->authorize('admin');
    
    // Líneas 19-21: Retorna la vista con todas las operaciones
    // Operacion::all() obtiene todos los registros sin ordenamiento específico
    return view('modulos.administrativo.costos.operaciones', [
        'operaciones' => Operacion::all()
    ]);
}
```

**Parámetros:** Ninguno

**Retorno:** 
- **Tipo:** `Illuminate\Http\Response`
- **Vista:** `modulos.administrativo.costos.operaciones`
- **Variables:** `$operaciones` (Colección de objetos Operacion)

**Query SQL Equivalente:**
```sql
SELECT * FROM operaciones;
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

**Propósito:** Almacenar una nueva operación en la base de datos.

**Análisis Línea por Línea:**

```php
public function store(StoreOperacionRequest $request)
{
    // Línea 41: Verificación de autorización - solo administradores
    $this->authorize('admin');
    
    // Línea 42: Crea una nueva instancia del modelo Operacion
    // No usa ::create() sino new + save()
    $operacion = new Operacion();
    
    // Línea 43: Asigna el nombre de la operación en MAYÚSCULAS
    // strtoupper() convierte todo el texto a mayúsculas
    $operacion->operacion = strtoupper($request->operacion);
    
    // Línea 44: Guarda el registro en la base de datos
    $operacion->save();
    
    // Línea 45: Redirecciona al índice con mensaje de éxito
    return redirect()
        ->route('operaciones.index')
        ->with('status', 'Operación creada con éxito');
}
```

**Parámetros:** 
- `$request` (StoreOperacionRequest): Datos validados del formulario
  - `operacion`: string (nombre de la operación)

**Retorno:** 
- Redirección a `operaciones.index` con mensaje flash

**Query SQL Equivalente:**
```sql
INSERT INTO operaciones (operacion, created_at, updated_at) 
VALUES ('NOMBRE_OPERACION', NOW(), NOW());
```

**Transformaciones:**
- ✅ Conversión a mayúsculas de `operacion`

**Diferencias con MaquinaController:**
- Usa `new Operacion()` + `save()` en lugar de `Operacion::create()`
- Solo un campo a guardar (operacion)

**Autorización:** Requiere rol `admin`

---

### 4. show()

**Propósito:** Mostrar detalles de una operación específica (No implementado).

```php
public function show(Operacion $operacion)
{
    // Método no implementado
}
```

**Estado:** ❌ No implementado

---

### 5. edit()

**Propósito:** Mostrar el formulario de edición de una operación específica.

**Análisis Línea por Línea:**

```php
public function edit(Operacion $operacion)
{
    // Línea 67: Verificación de autorización
    $this->authorize('admin');
    
    // Línea 68: Busca la operación por ID
    // Redundante: $operacion ya viene inyectada por route model binding
    $operacion = Operacion::findOrFail($operacion->id);
    
    // Líneas 69-72: Retorna vista de edición con los datos
    return view('modulos.administrativo.costos.operaciones-edit', [
        'operacion' => $operacion,
    ]);
}
```

**Parámetros:** 
- `$operacion` (Operacion): Instancia del modelo mediante inyección de dependencia

**Retorno:** 
- **Vista:** `modulos.administrativo.costos.operaciones-edit`
- **Variables:** `$operacion` (Objeto Operacion)

**Query SQL Equivalente:**
```sql
SELECT * FROM operaciones WHERE id = ? LIMIT 1;
```

**Autorización:** Requiere rol `admin`

**Nota:** La línea 68 es redundante porque Laravel ya inyecta la instancia.

---

### 6. update()

**Propósito:** Actualizar los datos de una operación existente.

**Análisis Línea por Línea:**

```php
public function update(UpdateOperacionRequest $request, Operacion $operacion)
{
    // Línea 84: Verificación de autorización
    $this->authorize('admin');
    
    // Línea 85: Busca la operación (redundante, ya viene inyectada)
    $operacion = Operacion::findOrFail($operacion->id);
    
    // Línea 86: Actualiza el atributo del modelo en mayúsculas
    $operacion->operacion = strtoupper($request->operacion);
    
    // Línea 87: Guarda los cambios en la base de datos
    $operacion->save();
    
    // Línea 88: Redirecciona al índice con mensaje personalizado
    // Incluye el nombre de la operación en el mensaje
    return redirect()
        ->route('operaciones.index')
        ->with('status', "Operación $operacion->operacion actualizada con éxito");
}
```

**Parámetros:** 
- `$request` (UpdateOperacionRequest): Datos validados del formulario
- `$operacion` (Operacion): Instancia del modelo a actualizar

**Retorno:** 
- Redirección a `operaciones.index` con mensaje flash

**Query SQL Equivalente:**
```sql
UPDATE operaciones 
SET operacion = 'NOMBRE_ACTUALIZADO',
    updated_at = NOW()
WHERE id = ?;
```

**Transformaciones:**
- ✅ Conversión a mayúsculas de `operacion`

**Autorización:** Requiere rol `admin`

---

### 7. destroy()

**Propósito:** Eliminar una operación del sistema (con validación de relaciones).

**Análisis Línea por Línea:**

```php
public function destroy(Operacion $operacion)
{
    // Línea 99: Verificación de autorización
    $this->authorize('admin');

    // Líneas 101-103: Valida si la operación tiene datos relacionados
    // hasAnyRelatedData() verifica relaciones en la tabla 'descripciones'
    // Si tiene descripciones asociadas, no permite eliminar
    if ($operacion->hasAnyRelatedData(['descripciones'])) {
        return back()->withErrors("No se puede eliminar el recurso porque tiene datos asociados. ");
    }
    
    // Línea 104: Elimina la operación de la base de datos
    $operacion->delete();
    
    // Línea 105: Retorna al índice sin mensaje específico
    return redirect()->route('operaciones.index');
}
```

**Parámetros:** 
- `$operacion` (Operacion): Instancia del modelo a eliminar

**Retorno:** 
- Redirección a `operaciones.index`
- Con mensaje de error si tiene relaciones
- Sin mensaje si se eliminó exitosamente

**Query SQL Equivalente:**
```sql
-- Primero verifica relaciones
SELECT COUNT(*) FROM descripciones WHERE operacion_id = ?;

-- Si no hay relaciones, elimina
DELETE FROM operaciones WHERE id = ?;
```

**Validaciones:**
- ✅ Verifica que no tenga registros en `descripciones`
- ✅ Previene eliminación de operaciones en uso
- ⚠️ Mensaje de error tiene espacio extra al final

**Autorización:** Requiere rol `admin`

---

## Modelo Asociado

### Operacion Model

**Ubicación:** `app/Models/Operacion.php`

```php
class Operacion extends Model
{
    use HasFactory, CheckRelations;
    
    protected $table = 'operaciones';
    
    // Relaciones
    public function descripciones()
    {
        return $this->hasMany(Descripcion::class);
    }
}
```

### Campos de la Tabla

| Campo | Tipo | Descripción | Nullable | Default |
|-------|------|-------------|----------|---------|
| id | BIGINT | Clave primaria | NO | AUTO_INCREMENT |
| operacion | VARCHAR(255) | Nombre de la operación | NO | - |
| created_at | TIMESTAMP | Fecha de creación | YES | NULL |
| updated_at | TIMESTAMP | Fecha de actualización | YES | NULL |

**Nota:** El modelo no define `$fillable`, por lo que usa asignación masiva deshabilitada por defecto.

### Relaciones

#### 1. descripciones (One to Many)
- **Tipo:** hasMany
- **Modelo:** Descripcion
- **Clave Foránea:** `operacion_id` (en tabla descripciones)
- **Descripción:** Una operación puede tener múltiples descripciones

**Ejemplo:**
```php
$operacion = Operacion::find(1);
$descripciones = $operacion->descripciones; // Colección de descripciones
```

---

## Relaciones del Sistema

### Jerarquía Completa

```
Operacion (Categoría)
    ├── Descripcion (Detalle 1)
    │       └── CostosOperacion (Costo 1.1)
    │       └── CostosOperacion (Costo 1.2)
    ├── Descripcion (Detalle 2)
    │       └── CostosOperacion (Costo 2.1)
    └── Descripcion (Detalle 3)
```

### Ejemplo Real

```
Operacion: "ASERRADO"
    ├── Descripcion: "CORTE INICIAL"
    │       └── CostosOperacion: Máquina "SIERRA 1", $50/día
    ├── Descripcion: "CORTE SECUNDARIO"
    │       └── CostosOperacion: Máquina "SIERRA 2", $40/día
    └── Descripcion: "DIMENSIONADO"
            └── CostosOperacion: Máquina "DIMENSIONADORA", $60/día
```

### Modelos Relacionados

1. **Descripcion**
   - Relación: `operacion()` - belongsTo
   - Cada descripción pertenece a una operación

2. **CostosOperacion** (indirecto)
   - Relación: A través de Descripcion
   - Query: `$operacion->descripciones()->with('costos_operacion')->get()`

---

## Validaciones

### StoreOperacionRequest

**Ubicación:** `app/Http/Requests/StoreOperacionRequest.php`

Reglas de validación esperadas:
```php
public function rules()
{
    return [
        'operacion' => 'required|string|max:255|unique:operaciones,operacion',
    ];
}
```

**Validaciones:**
- ✅ Campo requerido
- ✅ Tipo string
- ✅ Máximo 255 caracteres
- ✅ Único en la tabla (no duplicados)

### UpdateOperacionRequest

**Ubicación:** `app/Http/Requests/UpdateOperacionRequest.php`

Reglas de validación esperadas:
```php
public function rules()
{
    return [
        'operacion' => 'required|string|max:255|unique:operaciones,operacion,' . $this->operacion->id,
    ];
}
```

**Validaciones:**
- ✅ Campo requerido
- ✅ Tipo string
- ✅ Máximo 255 caracteres
- ✅ Único excepto el registro actual

---

## Ejemplos de Operaciones Comunes

### Operaciones Típicas en el Sistema

| ID | Operación | Descripciones Asociadas |
|----|-----------|------------------------|
| 1 | ASERRADO | Corte inicial, Dimensionado, Reaserrado |
| 2 | ENSAMBLE | Armado, Pegado, Prensado |
| 3 | ACABADOS | Lijado, Pintado, Barnizado |
| 4 | SECADO | Secado natural, Secado artificial |
| 5 | TRANSPORTE | Carga, Descarga, Traslado |

---

## Flujo de Datos

### Crear Operación
```
Usuario → Modal de creación 
→ Ingresa nombre (ej: "Aserrado")
→ Submit POST /costos-operacion
→ StoreOperacionRequest valida
→ Conversión a mayúsculas: "ASERRADO"
→ INSERT en BD
→ Redirect a index con mensaje
→ "Operación creada con éxito"
```

### Editar Operación
```
Usuario → Click "Editar"
→ GET /costos-operacion/{id}/edit
→ Vista con formulario pre-llenado
→ Usuario modifica nombre
→ Submit PATCH /costos-operacion/{id}
→ UpdateOperacionRequest valida
→ Conversión a mayúsculas
→ UPDATE en BD
→ Redirect a index
→ "Operación [NOMBRE] actualizada con éxito"
```

### Eliminar Operación
```
Usuario → Click "Eliminar"
→ Confirmación JavaScript
→ DELETE /costos-operacion/{id}
→ Verifica hasAnyRelatedData(['descripciones'])
→ SI tiene descripciones:
    └── Error: "No se puede eliminar..."
    └── Retorna a página anterior
→ NO tiene descripciones:
    └── DELETE en BD
    └── Redirect a index (sin mensaje)
```

---

## Mejoras Sugeridas

### Código
1. ❌ Eliminar línea redundante en `edit()` y `update()` (findOrFail innecesario)
2. ✅ Definir `$fillable` en el modelo para permitir asignación masiva
3. ✅ Usar `Operacion::create()` en lugar de `new + save()` en store()
4. ✅ Agregar mensaje de éxito en destroy()
5. ⚠️ Corregir espacio extra en mensaje de error de destroy()

### Funcionalidad
1. ✅ Implementar método `show()` para ver detalles con descripciones
2. ✅ Agregar contador de descripciones en el listado
3. ✅ Ordenar operaciones alfabéticamente en index
4. ✅ Implementar soft deletes
5. ✅ Agregar campo de descripción larga para la operación

### Seguridad
1. ✅ Ya implementa autorización correctamente
2. ✅ Ya usa Form Requests para validación
3. ✅ Ya valida relaciones antes de eliminar

---

## Comparación con MaquinaController

### Similitudes
- ✅ Estructura CRUD similar
- ✅ Autorización con `$this->authorize('admin')`
- ✅ Conversión a mayúsculas
- ✅ Validación de relaciones antes de eliminar

### Diferencias

| Aspecto | OperacionController | MaquinaController |
|---------|---------------------|-------------------|
| Método store() | `new + save()` | `::create()` |
| Mensaje destroy() | Sin mensaje éxito | Con mensaje |
| Campos | Solo 1 campo | 2 campos (maquina, corte) |
| Relaciones | 1 (descripciones) | 4 (costos, turnos, users) |
| Complejidad | Baja | Media |

---

## Ejemplos de Uso

### Crear una Operación
```php
// Request
POST /costos-operacion
{
    "operacion": "Secado"
}

// Response
Redirect to /costos-operacion
Flash: "Operación creada con éxito"

// En BD
{
    "id": 5,
    "operacion": "SECADO",
    "created_at": "2026-01-30 10:00:00",
    "updated_at": "2026-01-30 10:00:00"
}
```

### Actualizar una Operación
```php
// Request
PATCH /costos-operacion/5
{
    "operacion": "Secado Industrial"
}

// Response
Redirect to /costos-operacion
Flash: "Operación SECADO INDUSTRIAL actualizada con éxito"
```

### Eliminar con Descripciones
```php
// Request
DELETE /costos-operacion/5

// Si tiene descripciones
Response: Redirect back
Errors: "No se puede eliminar el recurso porque tiene datos asociados. "

// Si NO tiene descripciones
Response: Redirect to /costos-operacion
(Sin mensaje flash)
```

---

## Queries Útiles

### Obtener operación con descripciones
```php
$operacion = Operacion::with('descripciones')->find(1);
```

### Contar descripciones de una operación
```php
$operacion = Operacion::withCount('descripciones')->find(1);
$cantidad = $operacion->descripciones_count;
```

### Operaciones sin descripciones
```php
$operaciones = Operacion::doesntHave('descripciones')->get();
```

### Operaciones con más descripciones
```php
$operaciones = Operacion::withCount('descripciones')
    ->orderBy('descripciones_count', 'desc')
    ->get();
```

---

**Última actualización:** 30 de Enero, 2026  
**Versión:** 1.0  
**Autor:** Sistema de Documentación Inducolma
