# Módulo de Maderas - Documentación Consolidada

## 📋 Información General

**Módulo:** Gestión de Entrada de Maderas y Cubicajes  
**Controladores:** 3  
**Complejidad:** ALTA  
**Propósito:** Control de recepción de madera, cubicaje y transformaciones

---

## 📊 Controladores del Módulo

| # | Controlador | Modelo | Complejidad | Repository | Estado |
|---|-------------|--------|-------------|------------|--------|
| 1 | EntradaMaderaController | EntradaMadera | ALTA | ✅ RegistroEntradaMadera | ✅ |
| 2 | CubicajeController | Cubicaje | MEDIA | ✅ RegistroCubicajes | ✅ |
| 3 | TransformacionController | Transformacion | - | ❌ No | ⚠️ Vacío |

---

## 📦 Flujo de Negocio

```
Proveedor entrega madera
  └── EntradaMadera (registro legal)
        ├── Salvoconducto
        ├── Acto Administrativo
        └── Maderas (condición: TROZA/ASERRADA/INMUNIZADA)
              └── EntradasMaderaMaderas (pivot con m³)
                    ├── Cubicaje (si es TROZA → tablas/tablones)
                    │     └── Medición real de trozas
                    └── Cubicaje (si es ASERRADA → piezas)
                          └── Inventario en m³
                                └── Transformacion → OrdenProduccion
```

---

## 🌲 1. EntradaMaderaController

**Archivo:** `app/Http/Controllers/EntradaMaderaController.php`  
**Modelo:** `EntradaMadera`  
**Repository:** `RegistroEntradaMadera`  
**Propósito:** Registro legal de entrada de madera con salvoconductos

### Relaciones del Modelo

```php
class EntradaMadera extends Model
{
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
    
    // Relación many-to-many con tabla pivot
    public function maderas()
    {
        return $this->belongsToMany(Madera::class, 'entradas_madera_maderas')
            ->withPivot('m3entrada', 'condicion_madera', 'costo', 'estado');
    }
    
    // Acceso directo a registros pivot
    public function entradas_madera_maderas()
    {
        return $this->hasMany(EntradasMaderaMaderas::class);
    }
    
    public function cubicajes()
    {
        return $this->hasManyThrough(
            Cubicaje::class,
            EntradasMaderaMaderas::class
        );
    }
    
    public function user() // Creador
    {
        return $this->belongsTo(User::class);
    }
}
```

### Estructura de Datos

```sql
entradas_maderas:
  - id
  - mes                      (mes del salvoconducto)
  - ano                      (año del salvoconducto)
  - hora                     (hora de recepción)
  - fecha                    (fecha de recepción)
  - acto_administrativo      (resolución legal)
  - salvoconducto_remision   (número único)
  - titular_salvoconducto    (propietario del bosque)
  - procedencia_madera       (origen geográfico)
  - entidad_vigilante        (CORPOBOYACÁ, CAS, etc.)
  - estado                   (PENDIENTE, CUBICADO, COMPLETADO)
  - proveedor_id             (FK Proveedor)
  - user_id                  (quien registró)
  - created_at
  - updated_at

entradas_madera_maderas: (PIVOT)
  - id
  - entrada_madera_id        (FK EntradaMadera)
  - madera_id                (FK Madera - especie)
  - m3entrada                (metros cúbicos declarados)
  - condicion_madera         (TROZA/ASERRADA/INMUNIZADA)
  - costo                    (precio por cm³)
  - estado                   (PENDIENTE, CUBICADO)
  - created_at
  - updated_at
```

---

### Constructor - Inyección de Repository

```php
protected $registroEntradaMadera;

public function __construct(RegistroEntradaMadera $registroEntradaMadera)
{
    $this->registroEntradaMadera = $registroEntradaMadera;
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `protected $registroEntradaMadera` | Propiedad para repository |
| 3-6 | `__construct(RegistroEntradaMadera $registroEntradaMadera)` | ✅ Inyección de dependencia |

**💡 Patrón Repository:** Toda la lógica de negocio está en `RegistroEntradaMadera` (182 líneas)

---

### index() - Listar Entradas del Usuario

```php
public function index()
{
    $entradas = EntradaMadera::whereBetween(
        'created_at',
        [date('Y-m-d', strtotime('-1 month')), date('Y-m-d', strtotime('+1 day'))]
    )
        ->where('user_id', auth()->user()->id)
        ->get();

    $proveedores = Proveedor::select('id', 'nombre', 'razon_social')->get();
    $maderas = Madera::select('id', 'nombre_cientifico')->get();
    return view('modulos.administrativo.entradas-madera.index', compact('entradas', 'proveedores', 'maderas'));
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 2-4 | `whereBetween('created_at', ...)` | ⚡ **Filtra último mes de entradas** |
| 3 | `date('Y-m-d', strtotime('-1 month'))` | Desde hace 1 mes |
| 3 | `date('Y-m-d', strtotime('+1 day'))` | Hasta mañana |
| 5 | `->where('user_id', auth()->user()->id)` | ✅ Solo entradas del usuario actual |

**🔍 Lógica de Negocio:**

Cada usuario solo ve:
- Sus entradas registradas
- Del último mes (reducir carga en vista)

**⚠️ Problema:**

```php
// ❌ Sin eager loading
->get();

// ✅ Solución:
->with(['proveedor', 'maderas'])
->paginate(50);
```

---

### store() - Crear/Actualizar Entrada

```php
public function store(Request $request)
{
    if ($request->entrada[2] == 0) {
        return $this->registroEntradaMadera->guardar($request);
    } else {
        return $this->registroEntradaMadera->actualizar($request);
    }
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `if ($request->entrada[2] == 0)` | ⚡ **Detecta modo: crear o actualizar** |
| 3 | `guardar($request)` | Crear nueva entrada |
| 5 | `actualizar($request)` | Actualizar entrada existente |

**🔍 Estructura del Request:**

```javascript
{
  entrada: [
    {/* [0] Datos de entrada */
      mes: '01',
      ano: '2026',
      hora: '10:30',
      fecha: '2026-01-30',
      actoAdministrativo: 'RES-001-2026',
      salvoconducto: 'SC-12345',
      titularSalvoconducto: 'JUAN PEREZ',
      procedencia: 'VEREDA EL BOSQUE',
      entidadVigilante: 'CORPOBOYACÁ',
      proveedor: 5,
      id_ultima: 123 // Si actualiza
    },
    [/* [1] Array de maderas */
      {
        id: 2,              // madera_id
        metrosCubicos: 15.5,
        condicion: 'TROZA',
        entrada_id: 45      // Si actualiza (ID del pivot)
      },
      {
        id: 3,
        metrosCubicos: 8.3,
        condicion: 'ASERRADA'
      }
    ],
    0 // [2] Flag: 0 = crear, != 0 = actualizar
  ]
}
```

**⚠️ Problema:**

```php
// ❌ Magic index - difícil de entender
$request->entrada[2] == 0

// ✅ Solución:
$isNewEntry = $request->input('entrada.2') == 0;
// O mejor: agregar campo explícito
$isNewEntry = $request->input('is_new_entry', true);
```

---

### show() - Detalle de Entrada con Maderas

```php
public function show(EntradaMadera $entrada)
{
    $entrada = EntradaMadera::find($entrada->id)->load('proveedor', 'maderas', 'entradas_madera_maderas');
    
    $proveedores = Proveedor::select('id', 'nombre', 'razon_social')->get();
    $maderas = Madera::select('id', 'nombre_cientifico')->get();
    
    return view(
        'modulos.administrativo.entradas-madera.show',
        compact('entrada', 'proveedores', 'maderas')
    );
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `EntradaMadera $entrada` | Route Model Binding |
| 2 | `->load('proveedor', 'maderas', ...)` | ✅ Eager loading de relaciones |
| 2 | `'entradas_madera_maderas'` | Carga registros pivot completos |

**⚠️ Query Innecesaria:**

```php
// ❌ Ya se resolvió con Route Model Binding
$entrada = EntradaMadera::find($entrada->id)

// ✅ Solo cargar relaciones:
$entrada->load('proveedor', 'maderas', 'entradas_madera_maderas');
```

---

### verificarRegistro() - AJAX Validar Acto Administrativo

```php
public function verificarRegistro(Request $request)
{
    return response()->json(['error' => false]);
    $entrada = EntradaMadera::where(trim('acto_administrativo'), trim($request->acto));
    if ($entrada->count() > 0) {
        return response()->json(['error' => true]);
    } else {
        return response()->json(['error' => false]);
    }
}
```

**⚠️ PROBLEMA CRÍTICO:**

```php
// ❌ Return en línea 2 hace que el resto NUNCA se ejecute
return response()->json(['error' => false]);

// Todo el código siguiente es código muerto (dead code)
```

**✅ CORRECCIÓN:**

```php
public function verificarRegistro(Request $request)
{
    $entrada = EntradaMadera::where('acto_administrativo', trim($request->acto));
    
    if ($entrada->count() > 0) {
        return response()->json(['error' => true, 'message' => 'El acto administrativo ya existe']);
    }
    
    return response()->json(['error' => false]);
}
```

**Propósito:**

Validar que el acto administrativo (resolución legal) no esté duplicado

---

### ultimaEntrada() - AJAX Cargar Última Entrada

```php
public function ultimaEntrada(Request $request)
{
    $ultimaEntrada = EntradaMadera::findOrFail($request->id)
        ->load('proveedor');
        
    $maderas = DB::table('entradas_madera_maderas')
        ->join('maderas', 'entradas_madera_maderas.madera_id', '=', 'maderas.id')
        ->select(
            'entradas_madera_maderas.id',
            'maderas.nombre_cientifico',
            'entradas_madera_maderas.condicion_madera',
            'entradas_madera_maderas.m3entrada',
            'entradas_madera_maderas.madera_id',
            'entradas_madera_maderas.entrada_madera_id'
        )
        ->where('entrada_madera_id', $request->id)
        ->get();
        
    return response()->json(compact('ultimaEntrada', 'maderas'));
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 2-3 | `findOrFail(...)->load('proveedor')` | Carga entrada con proveedor |
| 5-15 | `DB::table(...)->join(...)` | ⚠️ Query Builder en lugar de Eloquent |
| 7-13 | `->select(...)` | Selecciona campos específicos |

**Propósito:**

Endpoint AJAX para duplicar una entrada anterior (facilita ingreso repetitivo)

**⚠️ Mejora:**

```php
// ✅ Usar Eloquent en lugar de Query Builder
$ultimaEntrada = EntradaMadera::with(['proveedor', 'entradas_madera_maderas.madera'])
    ->findOrFail($request->id);

return response()->json([
    'entrada' => $ultimaEntrada->only(['mes', 'ano', 'proveedor_id', 'entidad_vigilante']),
    'maderas' => $ultimaEntrada->entradas_madera_maderas->map(function($emm) {
        return [
            'id' => $emm->id,
            'nombre_cientifico' => $emm->madera->nombre_cientifico,
            'condicion_madera' => $emm->condicion_madera,
            'm3entrada' => $emm->m3entrada,
            'madera_id' => $emm->madera_id,
        ];
    })
]);
```

---

### eliminarMadera() - AJAX Eliminar Madera de Entrada

```php
public function eliminarMadera(Request $request)
{
    $madera = EntradasMaderaMaderas::findOrFail($request->id);
    $madera->delete();
    return response()->json(['error' => false]);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `EntradasMaderaMaderas::findOrFail(...)` | Encuentra registro pivot |
| 3 | `$madera->delete()` | Elimina madera de la entrada |

**Propósito:**

Permite quitar una especie de madera de una entrada sin eliminar toda la entrada

**⚠️ Falta Validación:**

```php
// ✅ Validar que no tenga cubicajes
$madera = EntradasMaderaMaderas::findOrFail($request->id);

if ($madera->cubicajes()->exists()) {
    return response()->json([
        'error' => true,
        'message' => 'No se puede eliminar porque tiene cubicajes registrados'
    ]);
}

$madera->delete();
return response()->json(['error' => false, 'message' => 'Madera eliminada']);
```

---

### indexEntradas() - Listar Maderas sin Costo

```php
public function indexEntradas()
{
    $entradas = EntradasMaderaMaderas::where('costo', 0)->with('entrada_madera')->get();
    
    return view('modulos.administrativo.entradas-madera.index-maderas', compact('entradas'));
}
```

**Propósito:**

Muestra maderas que AÚN NO tienen precio asignado (costo = 0)

**Flujo:**
1. Se registra entrada con m³ pero sin precio
2. Aparecen en este listado
3. Admin asigna costo posteriormente

**⚠️ Sin Paginación:**

```php
// ✅ Agregar paginación:
$entradas = EntradasMaderaMaderas::where('costo', 0)
    ->with('entrada_madera.proveedor')
    ->paginate(50);
```

---

### editEntrada() - Formulario de Asignación de Costo

```php
public function editEntrada(EntradasMaderaMaderas $entrada)
{
    return view('modulos.administrativo.entradas-madera.edit-madera', compact('entrada'));
}
```

Simple: muestra formulario para asignar precio

---

### updateEntrada() - Actualizar Costo de Madera

```php
public function updateEntrada(Request $request, EntradasMaderaMaderas $entrada)
{
    $costo = 0;
    switch ($request->medida) {
        case 'CENTIMETROS CUBICOS':
            $costo = (float)$request->costo;
            break;
        case 'METRO CUBICO':
            $costo = (float)$request->costo / 1000000;
            break;
        case 'PULGADA CUADRADA POR 3 METROS':
            $costo = (float)$request->costo / 1935.48;
            break;
    }

    $entrada->costo = $costo;
    try {
        $entrada->save();
        return redirect()->route('costo-madera')
            ->with('status', "El precio de compra de la entrada $entrada->id se actualizo correctamente");
    } catch (\Throwable $th) {
        return back()->with('status','no se pudo actualizar el precio de compra de la entrada');
    }
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 2-13 | `switch ($request->medida)` | ⚡ **Convierte diferentes unidades a cm³** |
| 5-6 | `'CENTIMETROS CUBICOS'` | Ya está en cm³ (no convierte) |
| 8-9 | `'METRO CUBICO'` | 1 m³ = 1,000,000 cm³ |
| 11-12 | `'PULGADA CUADRADA POR 3 METROS'` | Fórmula específica: / 1935.48 |
| 15 | `$entrada->costo = $costo` | Guarda costo en cm³ |

**🔍 Lógica de Conversión:**

```
Sistema almacena TODO en cm³ (centímetros cúbicos)

Proveedor dice:
  "Vendo a $500,000 el metro cúbico"
  → $500,000 / 1,000,000 = $0.5 por cm³

O dice:
  "Vendo a $50 la pulgada cuadrada por 3 metros"
  → $50 / 1935.48 = $0.0258 por cm³
```

**⚠️ Magic Number:**

```php
// ❌ 1935.48 sin explicación
$costo = (float)$request->costo / 1935.48;

// ✅ Constante con documentación:
const CM3_POR_PULGADA_CUADRADA_3M = 1935.48;

// 1 pulgada² × 3 metros = 2.54² × 300 = 1935.48 cm³
$costo = (float)$request->costo / self::CM3_POR_PULGADA_CUADRADA_3M;
```

---

### showEntradas() - Últimas 50 Entradas

```php
public function showEntradas()
{
    $entradas = EntradasMaderaMaderas::orderBy('id', 'desc')->take(50)->get();
    return view('modulos.administrativo.entradas-madera.show-entradas', compact('entradas'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `->orderBy('id', 'desc')` | Más recientes primero |
| 2 | `->take(50)` | ⚠️ Limita a 50 pero sin paginación |

**⚠️ Problema:**

```php
// ❌ take(50) sin paginación - usuario no puede ver más
->take(50)->get()

// ✅ Usar paginación:
->orderBy('id', 'desc')->paginate(50)
```

---

## 🔧 Repository: RegistroEntradaMadera

**Archivo:** `app/Repositories/RegistroEntradaMadera.php`  
**Líneas:** 182 líneas  
**Propósito:** Lógica de negocio para entrada de maderas

### Métodos del Repository

| Método | Líneas | Propósito |
|--------|--------|-----------|
| `validarDatosEntrada()` | 5 | Valida con FormRequest |
| `guardar()` | 20 | Orquesta guardado completo |
| `guardarEtrada()` | 40 | Guarda registro EntradaMadera |
| `guardarMaderas()` | 25 | Guarda registros pivot |
| `actualizar()` | 35 | Actualiza entrada existente |
| `actualizarMaderas()` | 35 | Actualiza/crea maderas pivot |

---

### guardar() - Orquestador Principal

```php
public function guardar($entrada)
{
    $registroEntrada = $this->guardarEtrada($entrada);
    
    if ($registroEntrada['error']) {
        return response()->json(['error' => $registroEntrada['error'], 'message' => $registroEntrada['message']]);
    } else {
        $registroMaderas = $this->guardarMaderas($entrada, $registroEntrada['id']);
        
        if ($registroMaderas['error']) {
            return response()->json(['error' => $registroMaderas['error'], 'message' => $registroMaderas['message']]);
        } else {
            return response()->json([
                'error' => false,
                'message' => 'Registro de entrada de madera guardado correctamente',
                'id' => $registroMaderas['idRegisto']
            ]);
        }
    }
}
```

**Flujo:**

1. Guarda `EntradaMadera` → obtiene ID
2. Con ese ID, guarda maderas en `EntradasMaderaMaderas`
3. Retorna JSON con resultado

**⚠️ Sin Transacción:**

```php
// ✅ Debería usar transacción:
DB::beginTransaction();
try {
    $registroEntrada = $this->guardarEtrada($entrada);
    $registroMaderas = $this->guardarMaderas($entrada, $registroEntrada['id']);
    
    DB::commit();
    return response()->json(['error' => false, 'id' => $registroMaderas['idRegisto']]);
} catch (\Exception $e) {
    DB::rollBack();
    return response()->json(['error' => true, 'message' => $e->getMessage()]);
}
```

---

### guardarEtrada() - Guardar Registro Principal

```php
public function guardarEtrada($entradaMadera)
{
    $entrada = new EntradaMadera();
    $entrada->mes = $entradaMadera->entrada[0]['mes'];
    $entrada->ano = $entradaMadera->entrada[0]['ano'];
    $entrada->hora = $entradaMadera->entrada[0]['hora'];
    $entrada->fecha = $entradaMadera->entrada[0]['fecha'];
    $entrada->acto_administrativo = mb_strtoupper($entradaMadera->entrada[0]['actoAdministrativo']);
    $entrada->salvoconducto_remision = $entradaMadera->entrada[0]['salvoconducto'];
    $entrada->titular_salvoconducto = mb_strtoupper($entradaMadera->entrada[0]['titularSalvoconducto']);
    $entrada->procedencia_madera = mb_strtoupper($entradaMadera->entrada[0]['procedencia']);
    $entrada->entidad_vigilante = mb_strtoupper($entradaMadera->entrada[0]['entidadVigilante']);
    $entrada->estado = 'PENDIENTE';
    $entrada->proveedor_id = $entradaMadera->entrada[0]['proveedor'];
    $entrada->user_id = auth()->user()->id;

    if ($entrada->save() == 1) {
        $respuesta['error'] = false;
        $respuesta['id'] = $entrada->id;
        return $respuesta;
    } else {
        return $respuesta = ['error' => true, 'message' => 'Error al guardar la entrada de madera'];
    }
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 7-11 | `mb_strtoupper(...)` | ✅ Mayúsculas con soporte UTF-8 |
| 12 | `estado = 'PENDIENTE'` | Estado inicial hasta cubicaje |
| 16 | `if ($entrada->save() == 1)` | ⚠️ save() retorna boolean |

---

### guardarMaderas() - Guardar Maderas en Pivot

```php
public function guardarMaderas($entradaMadera, $idEntrada)
{
    $maderas = $entradaMadera->entrada[1];
    $maderasGuardadas = 0;
    
    foreach ($maderas as $madera) {
        $registroMadera = new EntradasMaderaMaderas();
        $registroMadera->entrada_madera_id = $idEntrada;
        $registroMadera->madera_id = $madera['id'];
        $registroMadera->m3entrada = $madera['metrosCubicos'];
        $registroMadera->condicion_madera = $madera['condicion'];
        $registroMadera->save();
        $maderasGuardadas++;
    }
    
    if ($maderasGuardadas == count($maderas)) {
        return ['error' => false, 'idRegisto' => $idEntrada];
    } else {
        return ['error' => true, 'message' => 'Error al guardar las maderas'];
    }
}
```

**Propósito:**

Guarda cada especie de madera declarada en el salvoconducto

**Ejemplo:**

```
Salvoconducto SC-12345:
  - Roble: 15.5 m³ TROZA
  - Cedro: 8.3 m³ ASERRADA
  - Pino: 10.0 m³ INMUNIZADA

→ Crea 3 registros en entradas_madera_maderas
```

---

### actualizar() - Actualizar Entrada Existente

```php
public function actualizar($entradaMadera)
{
    $entrada = EntradaMadera::find($entradaMadera->entrada[0]['id_ultima']);
    $entrada->mes = $entradaMadera->entrada[0]['mes'];
    // ... resto de campos
    
    if ($entrada->update() == 1) {
        $respuesta = $this->actualizarMaderas($entradaMadera, $entradaMadera->entrada[0]['id_ultima']);
        return $respuesta;
    } else {
        return $respuesta = ['error' => true, 'message' => 'Error al actualizar la entrada de madera'];
    }
}
```

---

### actualizarMaderas() - Actualizar/Crear Maderas

```php
function actualizarMaderas($entradaMadera, $idEntrada)
{
    $maderas = $entradaMadera->entrada[1];
    $maderasGuardadas = 0;
    
    foreach ($maderas as $madera) {
        if (isset($madera['entrada_id'])) {
            // Actualizar madera existente
            $registroMadera = EntradasMaderaMaderas::find($madera['entrada_id']);
            $registroMadera->entrada_madera_id = $idEntrada;
            $registroMadera->madera_id = $madera['id'];
            $registroMadera->m3entrada = $madera['metrosCubicos'];
            $registroMadera->condicion_madera = $madera['condicion'];
            $registroMadera->update();
            $maderasGuardadas++;
        } else {
            // Crear nueva madera
            $registroMadera = new EntradasMaderaMaderas();
            $registroMadera->entrada_madera_id = $idEntrada;
            $registroMadera->madera_id = $madera['id'];
            $registroMadera->m3entrada = $madera['metrosCubicos'];
            $registroMadera->condicion_madera = $madera['condicion'];
            $registroMadera->save();
            $maderasGuardadas++;
        }
    }
    
    if ($maderasGuardadas == count($maderas)) {
        return ['error' => false, 'id' => $idEntrada, 'message' => 'Registros actualizados correctamente'];
    } else {
        return ['error' => true, 'message' => 'Error al actualizar las maderas'];
    }
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 6 | `if (isset($madera['entrada_id']))` | ⚡ **Detecta si es update o insert** |
| 7-14 | Update de madera existente | Busca por ID y actualiza |
| 16-23 | Insert de nueva madera | Crea registro nuevo |

**Lógica:**

Permite agregar maderas nuevas a una entrada existente sin duplicar

---

## 📏 2. CubicajeController

**Archivo:** `app/Http/Controllers/CubicajeController.php`  
**Modelo:** `Cubicaje`  
**Repository:** `RegistroCubicajes`  
**Propósito:** Medición física de madera recibida

### Estructura del Cubicaje

```php
class Cubicaje extends Model
{
    public function entradaMaderaMadera()
    {
        return $this->belongsTo(EntradasMaderaMaderas::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

```sql
cubicajes:
  - id
  - entradas_madera_maderas_id  (FK a pivot)
  - largo                       (cm)
  - ancho                       (cm)
  - alto                        (cm o diámetro para troza)
  - cantidad                    (piezas)
  - m3                          (calculado)
  - observaciones
  - user_id
  - created_at
```

---

### Constructor

```php
protected $registroCubicaje;

public function __construct(RegistroCubicajes $registroCubicaje)
{
    $this->registroCubicaje = $registroCubicaje;
}
```

✅ Patrón Repository

---

### index() - Listar Cubicajes del Día

```php
public function index()
{
    $this->authorize('cubicaje');
    
    $cubicajes = Cubicaje::where('created_at', '>=', date('Y-m-d'))
        ->where('user_id', auth()->user()->id)
        ->get();
        
    return view('modulos.operaciones.cubicaje.index', compact('cubicajes'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `$this->authorize('cubicaje')` | 🔒 Permiso específico |
| 4 | `where('created_at', '>=', date('Y-m-d'))` | Solo cubicajes del día actual |
| 5 | `where('user_id', auth()->user()->id)` | Solo del usuario |

**Propósito:**

Operario solo ve sus cubicajes del día (reducir carga)

---

### create() - Formulario de Cubicaje Aserrada

```php
public function create(Request $request)
{
    $this->authorize('cubicaje');
    
    $entrada = EntradasMaderaMaderas::where('id', (integer)$request->entrada)
        ->get();
        
    if(count($entrada)==0){
        return redirect()->route('cubicaje.index')
            ->with('status', 'No se encontró ninguna entrada con ese número');
    } else {
        $entrada = EntradasMaderaMaderas::find($request->entrada);
        
        if ($entrada->condicion_madera == 'TROZA') {
            return back()->with('status', 'La condicion de la madera es TROZA por favor haga el cubicaje con el boton Cubicar madera en troza');
        }
        
        return view('modulos.operaciones.cubicaje.create', compact('entrada'));
    }
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 4-6 | `where('id', ...)->get()` | ⚠️ Query innecesario |
| 7-10 | `if(count($entrada)==0)` | Valida existencia |
| 13-15 | `if ($entrada->condicion_madera == 'TROZA')` | ⚡ **Valida tipo de madera** |
| 15 | Mensaje de error | Redirige a método específico para TROZA |

**Lógica:**

- **ASERRADA/INMUNIZADA** → `create()` (piezas ya cortadas)
- **TROZA** → `cubicajeTroza()` (troncos completos)

**⚠️ Problema:**

```php
// ❌ Query innecesario
$entrada = EntradasMaderaMaderas::where('id', (integer)$request->entrada)->get();
if(count($entrada)==0) { }
else {
    $entrada = EntradasMaderaMaderas::find($request->entrada); // Vuelve a consultar
}

// ✅ Solución:
$entrada = EntradasMaderaMaderas::find($request->entrada);
if (!$entrada) {
    return redirect()->route('cubicaje.index')
        ->with('status', 'No se encontró ninguna entrada con ese número');
}

if ($entrada->condicion_madera == 'TROZA') {
    return back()->with('status', '...');
}

return view('modulos.operaciones.cubicaje.create', compact('entrada'));
```

---

### cubicajeTroza() - Formulario Específico para Trozas

```php
public function cubicajeTroza(Request $request)
{
    $entrada = EntradasMaderaMaderas::find((integer)$request->entrada);
    
    if ($entrada == null) {
        return back()->with('status', "No se encontro la entrada de madera $request->entrada");
    }

    $contiene_troza = $entrada->condicion_madera;
    if ($contiene_troza != 'TROZA') {
        return back()->with('status',"La entrada de madera $request->entrada, no contiene maderas en troza");
    }

    return view('modulos.operaciones.cubicaje.cubicaje-troza', compact('entrada'));
}
```

**Propósito:**

Vista especializada para medir troncos completos (diámetros, largos)

**Diferencia con `create()`:**

```
create():
  - Madera ASERRADA (tablas, tablones)
  - Medición: largo × ancho × alto × cantidad
  - Resultado: piezas uniformes

cubicajeTroza():
  - Madera TROZA (troncos)
  - Medición: diámetros (punta, mitad, base) × largo
  - Resultado: m³ por fórmula de Smalian o Huber
```

---

### store() - Guardar Cubicaje

```php
public function store(Request $request)
{
    $this->authorize('cubicaje');
    $datos = $request->cubicajes;

    if ($request->troza == 1){
        return $this->registroCubicaje->guardarTroza($datos);
    }

    return $this->registroCubicaje->guardar($datos);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3 | `$request->cubicajes` | Array con mediciones |
| 5-7 | `if ($request->troza == 1)` | ⚡ **Detecta tipo de cubicaje** |
| 6 | `guardarTroza($datos)` | Repository para trozas |
| 9 | `guardar($datos)` | Repository para aserrada |

**Repository Methods:**

- `guardarTroza()`: Usa fórmula de Smalian para calcular m³
- `guardar()`: Usa fórmula simple (largo × ancho × alto × cantidad)

---

### cubicajeTransformacion() - Actualizar Trozas

```php
public function cubicajeTransformacion(Request $request)
{
    $this->authorize('cubicaje');
    $datos_actualizar = $request->cubicajesTransformacion;

    try {
        return $this->registroCubicaje->actualizarTrozas($datos_actualizar);
    } catch (Exception $e) {
        return new Response([
            'error' => true,
            'message' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
```

**Propósito:**

Actualiza cubicajes de trozas cuando se transforman (ej: se aserraron)

---

## 🔄 3. TransformacionController

**Archivo:** `app/Http/Controllers/TransformacionController.php`  
**Modelo:** `Transformacion`  
**Estado:** ⚠️ **COMPLETAMENTE VACÍO**

```php
class TransformacionController extends Controller
{
    public function index() { }
    public function create() { }
    public function store(Request $request) { }
    public function show(Transformacion $transformacion) { }
    public function edit(Transformacion $transformacion) { }
    public function update(Request $request, Transformacion $transformacion) { }
    public function destroy(Transformacion $transformacion) { }
}
```

**⚠️ Problema:**

Controlador scaffold sin implementación. Probablemente:
- Transformaciones se gestionan desde `OrdenProduccionController`
- O desde vistas sin CRUD completo

---

## 📊 Comparación de Controladores

| Aspecto | EntradaMadera | Cubicaje | Transformacion |
|---------|---------------|----------|----------------|
| **CRUD Completo** | ⚠️ Parcial | ⚠️ Parcial | ❌ Vacío |
| **Repository** | ✅ Sí (182 líneas) | ✅ Sí | ❌ No |
| **Authorization** | ❌ No | ✅ Gate | ❌ No |
| **AJAX Methods** | ✅ 3 | ❌ No | ❌ No |
| **Validación Integridad** | ⚠️ Parcial | ❌ No | ❌ No |
| **Transacciones** | ❌ No | ❌ No | ❌ No |
| **Magic Numbers** | ✅ Sí (1935.48) | ❌ No | ❌ No |
| **Dead Code** | ✅ Sí (verificarRegistro) | ❌ No | ❌ No |
| **Complejidad** | Alta | Media | Nula |

---

## 🚨 Problemas Críticos del Módulo

### 1. EntradaMaderaController::verificarRegistro() - Dead Code

```php
// ❌ CRÍTICO: Return temprano hace el resto código muerto
public function verificarRegistro(Request $request)
{
    return response()->json(['error' => false]); // SIEMPRE retorna aquí
    
    // TODO ESTO NUNCA SE EJECUTA:
    $entrada = EntradaMadera::where(trim('acto_administrativo'), trim($request->acto));
    if ($entrada->count() > 0) {
        return response()->json(['error' => true]);
    }
}
```

### 2. Sin Transacciones en Repository

```php
// ❌ Si guardarMaderas() falla, queda EntradaMadera huérfana
$registroEntrada = $this->guardarEtrada($entrada);
$registroMaderas = $this->guardarMaderas($entrada, $registroEntrada['id']);

// ✅ Debería usar DB::transaction()
```

### 3. Magic Index en store()

```php
// ❌ Difícil de entender
if ($request->entrada[2] == 0) { }

// ✅ Usar nombres explícitos
```

### 4. Queries Duplicadas

```php
// ❌ En show()
$entrada = EntradaMadera::find($entrada->id) // Ya se resolvió con binding

// ❌ En create()
$entrada = EntradasMaderaMaderas::where('id', ...)->get(); // Primera query
$entrada = EntradasMaderaMaderas::find($request->entrada); // Segunda query
```

### 5. Magic Number Sin Documentación

```php
// ❌ 1935.48 sin explicación
$costo = (float)$request->costo / 1935.48;
```

---

## ✅ Mejores Prácticas Identificadas

### 1. Repository Pattern Complejo

Toda la lógica de guardado delegada a repository (182 líneas)

### 2. Filtros por Usuario

```php
->where('user_id', auth()->user()->id)
```

Cada usuario solo ve sus datos

### 3. Filtros Temporales Inteligentes

```php
// Último mes
whereBetween('created_at', [
    date('Y-m-d', strtotime('-1 month')),
    date('Y-m-d', strtotime('+1 day'))
])

// Solo hoy
where('created_at', '>=', date('Y-m-d'))
```

### 4. Conversión de Unidades

Sistema unificado en cm³ con conversión desde múltiples unidades

### 5. Validación de Tipo de Madera

```php
if ($entrada->condicion_madera == 'TROZA') {
    return back()->with('status', 'Use el formulario para trozas');
}
```

---

## 🧪 Tests Propuestos

```php
/** @test */
public function puede_registrar_entrada_madera()
{
    $this->actingAs($this->admin);
    
    $proveedor = Proveedor::factory()->create();
    $madera = Madera::factory()->create();
    
    $response = $this->postJson('/entradas-madera', [
        'entrada' => [
            [
                'mes' => '01',
                'ano' => '2026',
                'hora' => '10:30',
                'fecha' => '2026-01-30',
                'actoAdministrativo' => 'RES-001-2026',
                'salvoconducto' => 'SC-12345',
                'titularSalvoconducto' => 'Juan Perez',
                'procedencia' => 'Vereda El Bosque',
                'entidadVigilante' => 'CORPOBOYACÁ',
                'proveedor' => $proveedor->id,
            ],
            [
                ['id' => $madera->id, 'metrosCubicos' => 15.5, 'condicion' => 'TROZA']
            ],
            0 // Flag: es nueva entrada
        ]
    ]);
    
    $response->assertJson(['error' => false]);
    $this->assertDatabaseHas('entradas_maderas', [
        'acto_administrativo' => 'RES-001-2026',
        'estado' => 'PENDIENTE',
    ]);
    $this->assertDatabaseHas('entradas_madera_maderas', [
        'madera_id' => $madera->id,
        'm3entrada' => 15.5,
        'condicion_madera' => 'TROZA',
    ]);
}

/** @test */
public function puede_actualizar_entrada_existente()
{
    $this->actingAs($this->admin);
    
    $entrada = EntradaMadera::factory()->create();
    $madera = Madera::factory()->create();
    
    $response = $this->postJson('/entradas-madera', [
        'entrada' => [
            [
                'id_ultima' => $entrada->id,
                'mes' => '02',
                'ano' => '2026',
                // ... otros campos
            ],
            [
                ['id' => $madera->id, 'metrosCubicos' => 20, 'condicion' => 'ASERRADA']
            ],
            1 // Flag: actualizar
        ]
    ]);
    
    $response->assertJson(['error' => false]);
    $entrada->refresh();
    $this->assertEquals('02', $entrada->mes);
}

/** @test */
public function convierte_costo_metro_cubico_a_cm3()
{
    $this->actingAs($this->admin);
    
    $entrada = EntradasMaderaMaderas::factory()->create(['costo' => 0]);
    
    $response = $this->put("/entradas-madera/entrada/{$entrada->id}", [
        'costo' => 500000,
        'medida' => 'METRO CUBICO',
    ]);
    
    $entrada->refresh();
    $this->assertEquals(0.5, $entrada->costo); // 500000 / 1000000
}

/** @test */
public function create_rechaza_madera_troza()
{
    $this->actingAs($this->userWithPermission('cubicaje'));
    
    $entrada = EntradasMaderaMaderas::factory()->create([
        'condicion_madera' => 'TROZA'
    ]);
    
    $response = $this->get('/cubicaje/create', ['entrada' => $entrada->id]);
    
    $response->assertSessionHas('status');
    $this->assertStringContainsString('TROZA', session('status'));
}

/** @test */
public function cubicaje_troza_acepta_solo_trozas()
{
    $this->actingAs($this->userWithPermission('cubicaje'));
    
    $entrada = EntradasMaderaMaderas::factory()->create([
        'condicion_madera' => 'ASERRADA'
    ]);
    
    $response = $this->get('/cubicaje/cubicaje-troza', ['entrada' => $entrada->id]);
    
    $response->assertSessionHas('status');
    $this->assertStringContainsString('no contiene maderas en troza', session('status'));
}

/** @test */
public function puede_eliminar_madera_sin_cubicajes()
{
    $this->actingAs($this->admin);
    
    $madera = EntradasMaderaMaderas::factory()->create();
    
    $response = $this->deleteJson('/entradas-madera/eliminar-madera', [
        'id' => $madera->id
    ]);
    
    $response->assertJson(['error' => false]);
    $this->assertDatabaseMissing('entradas_madera_maderas', ['id' => $madera->id]);
}

/** @test */
public function index_entradas_muestra_solo_maderas_sin_costo()
{
    $this->actingAs($this->admin);
    
    $conCosto = EntradasMaderaMaderas::factory()->create(['costo' => 0.5]);
    $sinCosto = EntradasMaderaMaderas::factory()->create(['costo' => 0]);
    
    $response = $this->get('/costo-madera');
    
    $response->assertViewHas('entradas', function($entradas) use ($sinCosto, $conCosto) {
        return $entradas->contains($sinCosto) && !$entradas->contains($conCosto);
    });
}
```

**Tests Propuestos:** 15 tests

---

## 📝 Conclusión del Módulo

### Resumen

El **Módulo de Maderas** gestiona:

1. **Entrada Legal** → Salvoconductos y actos administrativos
2. **Pivot Complejo** → `EntradasMaderaMaderas` con m³ y condición
3. **Cubicaje** → Medición física (TROZA vs ASERRADA)
4. **Costos** → Conversión de unidades a cm³

### Complejidad

| Aspecto | Nivel |
|---------|-------|
| Lógica de Negocio | 🔴 ALTA |
| Repository Pattern | 🔴 COMPLEJO (182 líneas) |
| Queries de BD | 🟡 MEDIA |
| Integridad Referencial | 🔴 DÉBIL |
| Testabilidad | 🟡 MEDIA |
| Performance | 🟡 NECESITA MEJORAS |

### Prioridades de Refactoring

1. **CRÍTICO:** Arreglar dead code en `verificarRegistro()`
2. **CRÍTICO:** Agregar transacciones en repository
3. **ALTO:** Eliminar queries duplicadas
4. **ALTO:** Documentar magic number 1935.48
5. **MEDIO:** Eliminar magic index `entrada[2]`
6. **MEDIO:** Implementar TransformacionController

---

**Documentación Completa:** ✅  
**Tests Propuestos:** 15 tests  
**Última Actualización:** 30 de Enero, 2026
