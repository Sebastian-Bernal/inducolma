# OrdenProduccionController - Documentación Técnica

## 📋 Información General

**Ubicación:** `app/Http/Controllers/OrdenProduccionController.php`  
**Modelo Principal:** `OrdenProduccion`  
**Propósito:** Gestión completa de órdenes de producción con algoritmo de selección óptima de maderas  
**Complejidad:** ⚠️ **MUY ALTA** - Usa múltiples repositories con lógica de optimización  
**Líneas de Código:** 328  

---

## 🏗️ Arquitectura

### Patrón de Diseño

**Repository Pattern con Inyección de Dependencias**

```php
class OrdenProduccionController extends Controller
{
    protected $maderas;        // MaderasOptimas Repository
    protected $delete_orden;   // DeleteOrden Repository
    
    public function __construct(MaderasOptimas $maderas, DeleteOrden $delete_orden)
    {
        $this->maderas = $maderas;
        $this->delete_orden = $delete_orden;
    }
}
```

### Responsabilidades

1. **Selección Óptima de Maderas** → Algoritmo complejo de optimización
2. **Gestión de Transformaciones** → Corte inicial y sobrantes
3. **Control de Cubicajes** → Disponibilidad de paquetas
4. **Manejo de Inventario** → Items pre-procesados
5. **Eliminación en Cascada** → Procesos, transformaciones, cubicajes

---

## 📊 Estructura de Datos

### Relaciones

```
OrdenProduccion
├── BelongsTo Pedido
├── BelongsTo Item
├── BelongsTo User (creador)
├── HasMany Transformaciones
└── HasMany Procesos
    └── HasMany Subprocesos
```

### Estados de Orden

| Estado | Descripción |
|--------|-------------|
| `PENDIENTE` | Recién creada, sin iniciar |
| `EN_PROCESO` | Con procesos activos |
| `COMPLETADA` | Finalizada exitosamente |
| `CANCELADA` | Eliminada/anulada |

---

## 🔍 Métodos Documentados

### 1. index() - Listar Órdenes

**Propósito:** Muestra todas las órdenes de producción del año actual

```php
public function index()
{
    $this->authorize('admin');
    
    $ordenes_produccion = OrdenProduccion::join('pedidos', 'pedidos.id', '=', 'ordenes_produccion.pedido_id')
        ->join('users', 'users.id', '=', 'ordenes_produccion.user_id')
        ->whereYear('ordenes_produccion.created_at', Carbon::now()->year)
        ->get([
            'ordenes_produccion.id',
            'pedidos.referencia',
            'ordenes_produccion.cantidad',
            'users.name',
            'ordenes_produccion.estado',
            'ordenes_produccion.created_at',
        ]);
    
    return view('modulos.administrativo.programacion.index', compact('ordenes_produccion'));
}
```

**Análisis Línea por Línea:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `$this->authorize('admin');` | 🔒 Solo usuarios admin pueden listar órdenes |
| 2-3 | `OrdenProduccion::join('pedidos'...)` | Obtiene referencia del pedido asociado |
| 4 | `->join('users'...)` | Obtiene nombre del usuario que creó la orden |
| 5 | `->whereYear(...Carbon::now()->year)` | ⚠️ **Filtro año actual** - órdenes antiguas no visibles |
| 6-12 | `->get([...])` | Selecciona solo campos necesarios para la vista |

**⚠️ Problemas Identificados:**

1. **Sin Paginación:** Si hay muchas órdenes en el año, puede ser lento
2. **Filtro Rígido:** No permite ver órdenes de años anteriores
3. **Sin Eager Loading:** Aunque usa joins, no carga relaciones completas

**✅ Mejoras Sugeridas:**

```php
// Paginación + filtros dinámicos
$year = request('year', Carbon::now()->year);
$ordenes_produccion = OrdenProduccion::with(['pedido', 'user'])
    ->whereYear('created_at', $year)
    ->paginate(50);
```

---

### 2. create() - Formulario de Creación

**Propósito:** Muestra formulario para seleccionar pedido e item

```php
public function create()
{
    $this->authorize('admin');
    $pedidos = Pedido::select('id', 'referencia')->get();
    return view('modulos.administrativo.programacion.create', compact('pedidos'));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `$this->authorize('admin');` | 🔒 Solo admin puede crear órdenes |
| 2 | `Pedido::select('id', 'referencia')->get()` | Carga todos los pedidos para select |
| 3 | `return view(...)` | Vista con select de pedidos |

**⚠️ Problema:**

- **Sin Filtro:** Carga TODOS los pedidos históricos (puede ser miles)
- **Sin Paginación:** No usa select2 con AJAX

**✅ Mejora:**

```php
// Solo pedidos activos del año actual
$pedidos = Pedido::whereIn('estado', ['PENDIENTE', 'EN_PROCESO'])
    ->whereYear('created_at', Carbon::now()->year)
    ->select('id', 'referencia')
    ->get();
```

---

### 3. store() - Guardar Nueva Orden

**Propósito:** Crea orden y registra transformaciones/cubicajes iniciales

```php
public function store(Request $request)
{
    $this->authorize('admin');
    
    $orden = new OrdenProduccion();
    $orden->cantidad = $request->cantidad;
    $orden->estado = 'PENDIENTE';
    $orden->user_id = Auth::user()->id;
    $orden->pedido_id = $request->pedido_id;
    $orden->item_id = $request->item_id;
    $orden->save();
    
    $cubicajes = json_decode($request->cubicajes);
    
    foreach ($cubicajes as $cubicaje) {
        $datos_cubicaje = Cubicaje::find((int) $cubicaje);
        
        if ($datos_cubicaje) {
            $this->crearTransformacion($datos_cubicaje, $orden, $request->cantidad);
        }
    }
    
    $transformaciones = Transformacion::where('orden_produccion_id', $orden->id)
                        ->where('tipo_corte', 'INICIAL')
                        ->get(['id', 'cantidad']);
    
    $this->crearProcesosInicial($request, $transformaciones);
    
    $orden->estado = 'EN_PROCESO';
    $orden->save();
    
    return redirect()->back()->with('status', 'La orden se creó con éxito');
}
```

**Análisis Detallado:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 1 | `$this->authorize('admin');` | 🔒 Autorización |
| 3-8 | `$orden = new OrdenProduccion()...` | Crea objeto orden con estado PENDIENTE |
| 9 | `$orden->save();` | Guarda en BD para obtener ID |
| 11 | `json_decode($request->cubicajes)` | Array de IDs de cubicajes seleccionados |
| 13-19 | `foreach ($cubicajes...)` | Procesa cada cubicaje seleccionado |
| 15 | `Cubicaje::find((int) $cubicaje)` | Obtiene datos completos del cubicaje |
| 17 | `$this->crearTransformacion(...)` | ⚡ **Método privado clave** - crea transformación inicial |
| 21-23 | `Transformacion::where(...)` | Obtiene transformaciones recién creadas |
| 25 | `$this->crearProcesosInicial(...)` | ⚡ **Crea procesos automáticos** para cada transformación |
| 27-28 | `$orden->estado = 'EN_PROCESO'` | Cambia estado tras crear procesos |
| 30 | `return redirect()->back()` | Redirecciona con mensaje de éxito |

**🔧 Métodos Auxiliares:**

#### crearTransformacion()

```php
private function crearTransformacion($datos_cubicaje, $orden, $cantidad)
{
    $transformacion = new Transformacion();
    $transformacion->entrada_madera_id = $datos_cubicaje->entrada_madera_id;
    $transformacion->cubicaje_id = $datos_cubicaje->id;
    $transformacion->orden_produccion_id = $orden->id;
    $transformacion->user_id = Auth::user()->id;
    $transformacion->cantidad = $cantidad;
    
    // Dimensiones del cubicaje
    $transformacion->largo = $datos_cubicaje->largo;
    $transformacion->ancho = $datos_cubicaje->ancho;
    $transformacion->alto = $datos_cubicaje->alto;
    $transformacion->pulgadas_cuadradas = $datos_cubicaje->pulgadas_cuadradas;
    
    // Tipo y estado
    $transformacion->tipo_corte = 'INICIAL';
    $transformacion->trnasformacion_final = 'CORTE_ITEM';
    $transformacion->estado = 'EN_PROCESO';
    $transformacion->save();
    
    // Actualizar cubicaje a NO DISPONIBLE
    Cubicaje::where('id', $datos_cubicaje->id)
        ->update(['estado' => 'NO_DISPONIBLE']);
}
```

**Flujo de Transformación:**
```
Cubicaje (DISPONIBLE)
    ↓ seleccionar
Transformación (tipo: INICIAL, estado: EN_PROCESO)
    ↓ cambio estado
Cubicaje (NO_DISPONIBLE)
```

#### crearProcesosInicial()

```php
private function crearProcesosInicial($request, $transformaciones)
{
    $ruta = json_decode($request->ruta_acabado_producto_id);
    
    foreach ($transformaciones as $transformacion) {
        $proceso = new Proceso();
        $proceso->ruta_acabado_producto_id = (int) $ruta[0];
        $proceso->orden_produccion_id = $request->orden_produccion_id ?? $orden->id;
        $proceso->transformacion_id = $transformacion->id;
        $proceso->item_cantidad = $transformacion->cantidad;
        $proceso->estado = 'PENDIENTE';
        $proceso->save();
    }
}
```

**Explicación:**
- Crea un proceso por cada transformación
- Asocia ruta de acabado (primer elemento del array)
- Estado inicial: PENDIENTE
- Vincula transformación específica

---

### 4. show() - Ver Detalle de Orden

**Propósito:** Muestra orden con sus transformaciones y procesos

```php
public function show(OrdenProduccion $ordenProduccion)
{
    $this->authorize('admin');
    
    $ruta_acabados_producto = RutaAcabadoProducto::where('orden_produccion_id', $ordenProduccion->id)
                                ->with('subproceso')
                                ->get();
    
    $transformaciones = Transformacion::where('orden_produccion_id', $ordenProduccion->id)
                        ->where('tipo_corte', 'INICIAL')
                        ->with('madera', 'cubicaje', 'proceso.subproceso')
                        ->get();
    
    return view('modulos.administrativo.programacion.show', compact(
        'ordenProduccion',
        'transformaciones',
        'ruta_acabados_producto'
    ));
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `OrdenProduccion $ordenProduccion` | 🎯 **Route Model Binding** - Laravel resuelve automáticamente |
| 2 | `$this->authorize('admin');` | 🔒 Autorización |
| 4-6 | `RutaAcabadoProducto::where(...)` | Obtiene rutas de acabado con subprocesos |
| 5 | `->with('subproceso')` | ✅ **Eager Loading** - evita N+1 |
| 8-11 | `Transformacion::where(...)` | Solo transformaciones tipo INICIAL |
| 10 | `->with('madera', 'cubicaje', 'proceso.subproceso')` | ✅ **Eager Loading anidado** - carga 4 relaciones |
| 13-17 | `return view(...)` | Vista con 3 variables compactadas |

**✅ Buenas Prácticas:**
- Usa Route Model Binding
- Eager loading para evitar N+1
- Solo carga transformaciones iniciales (no sobrantes)

---

### 5. showMaderas() - Seleccionar Maderas Óptimas

**Propósito:** Muestra maderas disponibles óptimas para producir el item

```php
public function showMaderas(Request $request)
{
    $this->authorize('admin');
    $pedido = Pedido::find($request->id_pedido);
    $item = $request->id_item;
    
    $entradas_maderas = DB::table('entrada_maderas')
                            ->select('id', 'proveedor', 'ingreso', 'codigo')
                            ->orderBy('ingreso', 'desc')
                            ->get();
    
    $disenos_item = DB::table('diseno_items')
                        ->join('diseno_producto_finales', 'diseno_producto_finales.id', '=', 'diseno_items.diseno_producto_final_id')
                        ->join('items', 'items.id', '=', 'diseno_items.item_id')
                        ->where('diseno_items.diseno_producto_final_id', (int) $pedido->diseno_producto_final_id)
                        ->where('diseno_items.item_id', (int) $item)
                        ->first(['diseno_items.largo', 'diseno_items.ancho', 'diseno_items.alto', 'diseno_items.cantidad', 'items.existencias', 'items.madera_id', 'items.descripcion']);
    
    $tipo_madera = DB::table('tipo_maderas')
                        ->where('id', (int) $disenos_item->madera_id)
                        ->first(['madera']);
    
    $maderas_primas = DB::table('cubicajes')
                        ->join('entrada_maderas', 'entrada_maderas.id', '=', 'cubicajes.entrada_madera_id')
                        ->where('cubicajes.madera_id', (int) $disenos_item->madera_id)
                        ->where('cubicajes.largo', '>=', (int) $disenos_item->largo)
                        ->where('cubicajes.ancho', '>=', (int) $disenos_item->ancho)
                        ->where('cubicajes.alto', '>=', (int) $disenos_item->alto)
                        ->where('cubicajes.estado', 'DISPONIBLE')
                        ->orderBy('cubicajes.pulgadas_cuadradas', 'asc')
                        ->get([
                            'cubicajes.id',
                            'entrada_maderas.proveedor',
                            'entrada_maderas.ingreso',
                            'cubicajes.entrada_madera_id',
                            'cubicajes.paqueta',
                            'cubicajes.bloque',
                            'cubicajes.largo',
                            'cubicajes.ancho',
                            'cubicajes.alto',
                            'cubicajes.pulgadas_cuadradas',
                        ]);
    
    $rutas = RutaAcabadoProducto::where('item_id', (int) $item)
                ->with('subproceso')
                ->get();
    
    return view('modulos.administrativo.programacion.select-maderas', compact(
        'pedido',
        'item',
        'entradas_maderas',
        'disenos_item',
        'tipo_madera',
        'maderas_primas',
        'rutas'
    ));
}
```

**Análisis del Algoritmo de Selección:**

| Líneas | Criterio | Explicación |
|--------|----------|-------------|
| 6-10 | Entradas de Madera | Lista todas las entradas ordenadas por fecha DESC |
| 12-17 | Diseño del Item | Obtiene dimensiones del item a producir |
| 19-21 | Tipo de Madera | Identifica especie de madera requerida |
| 23-44 | **Cubicajes Óptimos** | ⚡ **Query clave de optimización** |

**Criterios de Selección de Maderas:**

```sql
WHERE cubicajes.madera_id = :madera_requerida
  AND cubicajes.largo >= :largo_item        -- ✅ Suficiente largo
  AND cubicajes.ancho >= :ancho_item        -- ✅ Suficiente ancho
  AND cubicajes.alto >= :alto_item          -- ✅ Suficiente grosor
  AND cubicajes.estado = 'DISPONIBLE'       -- ✅ No está en uso
ORDER BY cubicajes.pulgadas_cuadradas ASC   -- 🎯 Minimizar desperdicio
```

**Estrategia de Optimización:**
1. **Orden ASC por pulgadas²** → Selecciona maderas más pequeñas primero
2. **Minimiza Desperdicio** → Usa maderas justas, evita cortar grandes
3. **Prioridad FIFO** → Entradas más antiguas primero (orderBy ingreso)

---

### 6. maderasOptimas() - Algoritmo de Optimización

**Propósito:** Ejecuta algoritmo complejo que retorna maderas óptimas para producir

```php
public function maderasOptimas(Request $request)
{
    $pedido = Pedido::find($request->id_pedido);
    $item = $request->id_item;
    $optimas = $this->maderas->Optimas($request);
    
    if (isset($optimas['maderas_usar'], $optimas['sobrantes_usar'])) {
        if (count($optimas['maderas_usar']) > 0 || count($optimas['sobrantes_usar']) > 0) {
            return view('modulos.administrativo.programacion.maderas-optimas', 
                compact('optimas', 'pedido', 'item'));
        } else {
            $status = 'no hay maderas disponibles...';
            return redirect()->back()->with('status', $status);
        }
    } else {
        $status = 'no hay maderas disponibles...';
        return redirect()->back()->with('status', $status);
    }
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 3 | `$pedido = Pedido::find(...)` | Obtiene datos del pedido |
| 4 | `$item = $request->id_item` | ID del item a producir |
| 5 | `$this->maderas->Optimas($request)` | ⚡ **ALGORITMO CLAVE** - Repository MaderasOptimas |
| 7-8 | `if (isset(...))` | Valida que el algoritmo retornó maderas |
| 8 | `count(...) > 0` | Verifica que hay maderas o sobrantes disponibles |
| 9-10 | `return view(...)` | Muestra vista con maderas óptimas seleccionadas |
| 12-13 | `else redirect()->back()` | No hay maderas disponibles |

**🔧 Repository MaderasOptimas::Optimas()**

Este método del repository implementa el algoritmo de optimización:

```php
public function Optimas($request)
{
    $pedido = $this->datosPedido($request);
    $item_diseno = $this->datosItemDiseno($pedido, $request);
    
    // Obtiene sobrantes que pueden ser reutilizados
    $sobrantesCorte = $this->sobrantesCorte($item_diseno);
    $sobrantesTroza = $this->sobrantesTroza($item_diseno);
    $sobrantes = $sobrantesCorte->merge($sobrantesTroza);
    
    // Obtiene maderas primas disponibles
    $maderas = $this->Maderas($item_diseno);
    $producir = $this->producir($request, $item_diseno, $pedido);
    
    if ($maderas->count() == 0 && $sobrantes->count() == 0) {
        return ['status' => 'No hay maderas disponibles.'];
    }
    
    return [
        'maderas_usar' => $this->corteInicial($maderas, $item_diseno, 0, 0),
        'sobrantes_usar' => $sobrantes,
        'item' => $item_diseno,
        'producir' => $producir,
    ];
}
```

**Lógica de Sobrantes:**

```php
// Sobrantes de Corte - Piezas ya cortadas que pueden reutilizarse
public function sobrantesCorte($item_diseno)
{
    return Transformacion::where('trnasformacion_final', 'SOBRANTE_CORTE')
        ->where('estado', 'DISPONIBLE')
        ->where('largo', (int)$item_diseno->largo)              // ✅ Largo EXACTO
        ->where('ancho', '>', (int)($item_diseno->ancho + 0.5) + 0.5)  // ✅ Ancho con holgura
        ->where('alto', '>', (int)($item_diseno->alto + 0.5) + 0.5)    // ✅ Alto con holgura
        ->where('madera_id', (int)$item_diseno->madera_id)      // ✅ Misma especie
        ->get();
}
```

**Estrategia:**
1. Prioriza **sobrantes** antes que maderas primas
2. Valida dimensiones con **holgura de 0.5"** (tolerancia de corte)
3. Largo debe ser **EXACTO**, ancho/alto pueden ser mayores
4. Reduce desperdicio reutilizando sobrantes

---

### 7. crearOrdenItemsInventario() - AJAX

**Propósito:** Crea orden para items que ya existen en inventario (sin corte de madera)

```php
public function crearOrdenItemsInventario(Request $request)
{
    $this->authorize('admin');
    
    // Crear la orden
    $ordenProduccion = new OrdenProduccion();
    $ordenProduccion->pedido_id = $request->pedido_id;
    $ordenProduccion->item_id = $request->item_id;
    $ordenProduccion->cantidad = $request->cantidad;
    $ordenProduccion->user_id = auth()->user()->id;
    $ordenProduccion->estado = $request->estado;
    $ordenProduccion->save();
    
    // Actualizar existencias de items
    $item = Item::findOrFail($request->item_id);
    $item->existencias -= (int)$request->cantidad;  // ⚠️ Reduce existencias
    $item->preprocesado += (int)$request->cantidad; // ⚠️ Aumenta preprocesado
    $item->save();
    
    return response()->json(['success' => 'Orden de Producción creada con éxito.']);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `$this->authorize('admin');` | 🔒 Solo admin |
| 4-11 | `$ordenProduccion = new...` | Crea orden sin transformaciones |
| 10 | `$ordenProduccion->estado = $request->estado` | ⚠️ Estado viene del request (puede ser cualquiera) |
| 14 | `$item = Item::findOrFail(...)` | Obtiene item para actualizar inventario |
| 15 | `$item->existencias -= ...` | ⚠️ **Reduce inventario** - item pasa a producción |
| 16 | `$item->preprocesado += ...` | ⚠️ **Aumenta preprocesado** - item en proceso |
| 19 | `return response()->json(...)` | ⚡ Respuesta AJAX JSON |

**Flujo de Inventario:**

```
Item:
  existencias: 100 → 90  (-10)
  preprocesado: 5 → 15   (+10)
  
Orden creada:
  cantidad: 10
  estado: EN_PROCESO
```

**⚠️ Problema Potencial:**

```php
// No valida si hay suficientes existencias
if ($item->existencias < $request->cantidad) {
    return response()->json(['error' => 'Stock insuficiente'], 400);
}
```

---

### 8. verPaqueta() - AJAX Detalles de Paqueta

**Propósito:** Retorna bloques de una paqueta específica en JSON

```php
public function verPaqueta(Request $request)
{
    $paqueta = Cubicaje::where('entrada_madera_id', $request->entrada_madera_id)
        ->where('paqueta', $request->paqueta)
        ->where('estado', 'DISPONIBLE')
        ->orderBy('pulgadas_cuadradas', 'desc')
        ->get(['pulgadas_cuadradas', 'bloque']);
    
    return response()->json($paqueta);
}
```

**Explicación:**

- Filtra por entrada de madera y número de paqueta
- Solo bloques DISPONIBLES
- Ordena por tamaño DESC (muestra más grandes primero)
- Retorna JSON con pulgadas² y número de bloque

**Uso en Frontend:**

```javascript
// AJAX call para ver detalles de paqueta
$.ajax({
    url: '/ordenes-produccion/ver-paqueta',
    data: {
        entrada_madera_id: 123,
        paqueta: 5
    },
    success: function(data) {
        // data = [{pulgadas_cuadradas: 450, bloque: 1}, ...]
        mostrarBloquesPaqueta(data);
    }
});
```

---

### 9. dividirPaqueta() - Dividir Paqueta

**Propósito:** Divide una paqueta en dos partes para optimizar uso

```php
public function dividirPaqueta(Request $request)
{
    $this->authorize('admin');
    $ver = 1;
    $cubicaje = $this->maderas->cubicaje($request, $ver);
    return $cubicaje;
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `$this->authorize('admin');` | 🔒 Solo admin |
| 3 | `$ver = 1;` | Flag de operación (1 = dividir, 2 = seleccionar) |
| 4 | `$this->maderas->cubicaje($request, $ver)` | ⚡ Repository calcula división óptima |
| 5 | `return $cubicaje;` | Retorna resultado (JSON o array) |

**Lógica de División:**

El repository `MaderasOptimas::cubicaje()` implementa:

1. Recibe paqueta completa
2. Analiza dimensiones y cantidad de bloques
3. Divide en dos grupos óptimos:
   - **Grupo A:** Para orden actual
   - **Grupo B:** Sobrante disponible

---

### 10. seleccionar() - Confirmar Selección

**Propósito:** Selecciona definitivamente las maderas y crea orden + transformaciones

```php
public function seleccionar(Request $request)
{
    $this->authorize('admin');
    $guardar = 2; // Flag para guardar
    
    try {
        DB::beginTransaction();
        
        // Crear orden de producción
        $orden = $this->crearOrden($request);
        
        // Seleccionar y reservar paquetas
        $this->maderas->seleccionaPaqueta($request, $guardar, $orden->id);
        
        DB::commit();
        return new Response(['error' => false], Response::HTTP_OK);
    } catch (\Exception $e) {
        DB::rollBack();
        return new Response([
            'error' => true,
            'datos_error' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
```

**Análisis de Transacción:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `$this->authorize('admin');` | 🔒 Solo admin |
| 3 | `$guardar = 2;` | Flag: 1=ver, 2=guardar |
| 6 | `DB::beginTransaction();` | ✅ **Inicia transacción** - crucial para integridad |
| 9 | `$orden = $this->crearOrden($request)` | Crea orden PENDIENTE |
| 12 | `$this->maderas->seleccionaPaqueta(...)` | ⚡ **Repository clave** - reserva maderas |
| 14 | `DB::commit();` | ✅ Confirma transacción si todo OK |
| 15 | `return new Response(['error' => false])` | HTTP 200 con JSON |
| 16-20 | `catch (\Exception $e)` | Manejo de errores |
| 17 | `DB::rollBack();` | ✅ **Revierte todo** si algo falla |
| 18-20 | `return new Response([...])` | HTTP 500 con mensaje de error |

**Flujo de Operaciones:**

```
1. BEGIN TRANSACTION
    ↓
2. Crear OrdenProduccion
    ↓
3. Repository: seleccionaPaqueta()
    ├── Actualiza Cubicajes → NO_DISPONIBLE
    ├── Crea Transformaciones (tipo: INICIAL)
    └── Registra sobrantes (si aplica)
    ↓
4. COMMIT (si todo OK) o ROLLBACK (si error)
```

**✅ Buenas Prácticas:**

- Usa transacciones DB para operaciones complejas
- Manejo adecuado de excepciones
- Respuestas HTTP estándar (200, 500)
- Rollback automático en caso de error

---

### 11. crearOrden() - Método Privado

**Propósito:** Crea instancia de OrdenProduccion (método auxiliar)

```php
public function crearOrden($request)
{
    $orden = new OrdenProduccion();
    $orden->cantidad = $request->cantidad;
    $orden->estado = 'PENDIENTE';
    $orden->user_id = Auth::user()->id;
    $orden->pedido_id = $request->id_pedido;
    $orden->item_id = $request->id_item;
    $orden->save();
    
    return $orden;
}
```

**Explicación:**

- Método privado reutilizable
- Siempre crea con estado PENDIENTE
- Registra usuario creador
- Retorna objeto guardado con ID

---

### 12. destroy() - Eliminar Orden

**Propósito:** Elimina orden usando Repository DeleteOrden

```php
public function destroy(OrdenProduccion $ordenProduccion)
{
    $this->authorize('admin');
    return $this->delete_orden->deleteOrden($ordenProduccion);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 1 | `OrdenProduccion $ordenProduccion` | Route Model Binding |
| 2 | `$this->authorize('admin');` | 🔒 Solo admin puede eliminar |
| 3 | `$this->delete_orden->deleteOrden(...)` | ⚡ **Repository maneja eliminación compleja** |

**🔧 Repository DeleteOrden::deleteOrden()**

```php
public function deleteOrden($orden)
{
    // 1. Eliminar procesos asociados
    $delete_proceso = $this->eliminarProcesos($orden->id);
    
    // 2. Liberar cubicajes (estado → DISPONIBLE)
    $update_cubicaje = $this->actualizarCubicajes($orden->id);
    
    // 3. Eliminar transformaciones
    $delete_transformacion = $this->eliminarTransformacion($orden->id);
    
    // 4. Validar que todo se eliminó correctamente
    if ($delete_proceso && $update_cubicaje && $delete_transformacion) {
        $orden->update(['user_id' => Auth::user()->id]); // Auditoría
        $orden->delete(); // Soft delete
        return response()->json([
            'error' => false,
            'mensaje' => 'la orden se elimino con éxito'
        ]);
    } else {
        return response()->json([
            'error' => true,
            'mensaje' => 'No se pudo eliminar, contacte al administrador'
        ]);
    }
}
```

**Orden de Eliminación:**

```
1. eliminarProcesos()
    ├── Busca Procesos con orden_produccion_id
    └── Soft delete de cada proceso
    
2. actualizarCubicajes()
    ├── Busca Transformaciones con orden_produccion_id
    ├── Obtiene cubicaje_id de cada transformación
    └── UPDATE cubicajes SET estado = 'DISPONIBLE'
    
3. eliminarTransformacion()
    ├── Busca Transformaciones con orden_produccion_id
    └── DELETE transformaciones
    
4. delete() Orden
    └── Soft delete de la orden
```

**✅ Importante:**

- **Libera cubicajes** para que puedan ser usados en nuevas órdenes
- **Elimina en cascada** procesos y transformaciones
- **Soft delete** en orden (recuperable)
- **Auditoría** actualiza user_id al eliminar

---

## 🧪 Tests Propuestos

### Suite de Tests para OrdenProduccionController

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pedido;
use App\Models\Item;
use App\Models\Cubicaje;
use App\Models\OrdenProduccion;
use App\Models\Transformacion;
use App\Models\Proceso;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrdenProduccionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_puede_ver_listado_ordenes_produccion()
    {
        $this->actingAs($this->admin);
        
        OrdenProduccion::factory()->count(5)->create();
        
        $response = $this->get('/ordenes-produccion');
        
        $response->assertStatus(200);
        $response->assertViewIs('modulos.administrativo.programacion.index');
        $response->assertViewHas('ordenes_produccion');
    }

    /** @test */
    public function usuario_no_admin_no_puede_ver_listado_ordenes()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);
        
        $response = $this->get('/ordenes-produccion');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_puede_crear_orden_produccion_con_cubicajes()
    {
        $this->actingAs($this->admin);
        
        $pedido = Pedido::factory()->create();
        $item = Item::factory()->create();
        $cubicaje = Cubicaje::factory()->create(['estado' => 'DISPONIBLE']);
        
        $response = $this->post('/ordenes-produccion', [
            'pedido_id' => $pedido->id,
            'item_id' => $item->id,
            'cantidad' => 10,
            'cubicajes' => json_encode([$cubicaje->id]),
            'ruta_acabado_producto_id' => json_encode([1]),
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('ordenes_produccion', [
            'pedido_id' => $pedido->id,
            'item_id' => $item->id,
            'cantidad' => 10,
            'estado' => 'EN_PROCESO',
            'user_id' => $this->admin->id,
        ]);
    }

    /** @test */
    public function crear_orden_actualiza_estado_cubicaje_a_no_disponible()
    {
        $this->actingAs($this->admin);
        
        $cubicaje = Cubicaje::factory()->create(['estado' => 'DISPONIBLE']);
        $pedido = Pedido::factory()->create();
        $item = Item::factory()->create();
        
        $this->post('/ordenes-produccion', [
            'pedido_id' => $pedido->id,
            'item_id' => $item->id,
            'cantidad' => 5,
            'cubicajes' => json_encode([$cubicaje->id]),
            'ruta_acabado_producto_id' => json_encode([1]),
        ]);
        
        $this->assertDatabaseHas('cubicajes', [
            'id' => $cubicaje->id,
            'estado' => 'NO_DISPONIBLE',
        ]);
    }

    /** @test */
    public function crear_orden_genera_transformaciones_tipo_inicial()
    {
        $this->actingAs($this->admin);
        
        $cubicaje = Cubicaje::factory()->create(['estado' => 'DISPONIBLE']);
        $pedido = Pedido::factory()->create();
        $item = Item::factory()->create();
        
        $this->post('/ordenes-produccion', [
            'pedido_id' => $pedido->id,
            'item_id' => $item->id,
            'cantidad' => 5,
            'cubicajes' => json_encode([$cubicaje->id]),
            'ruta_acabado_producto_id' => json_encode([1]),
        ]);
        
        $orden = OrdenProduccion::latest()->first();
        
        $this->assertDatabaseHas('transformaciones', [
            'orden_produccion_id' => $orden->id,
            'cubicaje_id' => $cubicaje->id,
            'tipo_corte' => 'INICIAL',
            'estado' => 'EN_PROCESO',
        ]);
    }

    /** @test */
    public function crear_orden_genera_procesos_automaticamente()
    {
        $this->actingAs($this->admin);
        
        $cubicaje = Cubicaje::factory()->create(['estado' => 'DISPONIBLE']);
        $pedido = Pedido::factory()->create();
        $item = Item::factory()->create();
        
        $this->post('/ordenes-produccion', [
            'pedido_id' => $pedido->id,
            'item_id' => $item->id,
            'cantidad' => 5,
            'cubicajes' => json_encode([$cubicaje->id]),
            'ruta_acabado_producto_id' => json_encode([1]),
        ]);
        
        $orden = OrdenProduccion::latest()->first();
        
        $this->assertTrue($orden->procesos()->exists());
    }

    /** @test */
    public function crear_orden_items_inventario_reduce_existencias()
    {
        $this->actingAs($this->admin);
        
        $item = Item::factory()->create([
            'existencias' => 100,
            'preprocesado' => 5
        ]);
        
        $pedido = Pedido::factory()->create();
        
        $response = $this->postJson('/ordenes-produccion/crear-items-inventario', [
            'pedido_id' => $pedido->id,
            'item_id' => $item->id,
            'cantidad' => 10,
            'estado' => 'EN_PROCESO',
        ]);
        
        $response->assertJson(['success' => 'Orden de Producción creada con éxito.']);
        
        $item->refresh();
        $this->assertEquals(90, $item->existencias);
        $this->assertEquals(15, $item->preprocesado);
    }

    /** @test */
    public function ver_paqueta_devuelve_bloques_disponibles_en_json()
    {
        $this->actingAs($this->admin);
        
        $cubicaje1 = Cubicaje::factory()->create([
            'entrada_madera_id' => 1,
            'paqueta' => 5,
            'estado' => 'DISPONIBLE',
            'pulgadas_cuadradas' => 450,
            'bloque' => 1,
        ]);
        
        $cubicaje2 = Cubicaje::factory()->create([
            'entrada_madera_id' => 1,
            'paqueta' => 5,
            'estado' => 'DISPONIBLE',
            'pulgadas_cuadradas' => 400,
            'bloque' => 2,
        ]);
        
        $response = $this->getJson('/ordenes-produccion/ver-paqueta', [
            'entrada_madera_id' => 1,
            'paqueta' => 5,
        ]);
        
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['bloque' => 1]);
        $response->assertJsonFragment(['bloque' => 2]);
    }

    /** @test */
    public function eliminar_orden_libera_cubicajes()
    {
        $this->actingAs($this->admin);
        
        $orden = OrdenProduccion::factory()->create();
        $cubicaje = Cubicaje::factory()->create(['estado' => 'NO_DISPONIBLE']);
        
        $transformacion = Transformacion::factory()->create([
            'orden_produccion_id' => $orden->id,
            'cubicaje_id' => $cubicaje->id,
            'tipo_corte' => 'INICIAL',
        ]);
        
        $response = $this->deleteJson("/ordenes-produccion/{$orden->id}");
        
        $response->assertJson(['error' => false]);
        
        $cubicaje->refresh();
        $this->assertEquals('DISPONIBLE', $cubicaje->estado);
    }

    /** @test */
    public function eliminar_orden_elimina_procesos_en_cascada()
    {
        $this->actingAs($this->admin);
        
        $orden = OrdenProduccion::factory()->create();
        $transformacion = Transformacion::factory()->create([
            'orden_produccion_id' => $orden->id,
        ]);
        $proceso = Proceso::factory()->create([
            'orden_produccion_id' => $orden->id,
            'transformacion_id' => $transformacion->id,
        ]);
        
        $response = $this->deleteJson("/ordenes-produccion/{$orden->id}");
        
        $response->assertJson(['error' => false]);
        $this->assertSoftDeleted('procesos', ['id' => $proceso->id]);
    }

    /** @test */
    public function eliminar_orden_elimina_transformaciones()
    {
        $this->actingAs($this->admin);
        
        $orden = OrdenProduccion::factory()->create();
        $transformacion = Transformacion::factory()->create([
            'orden_produccion_id' => $orden->id,
        ]);
        
        $response = $this->deleteJson("/ordenes-produccion/{$orden->id}");
        
        $response->assertJson(['error' => false]);
        $this->assertDatabaseMissing('transformaciones', [
            'id' => $transformacion->id,
        ]);
    }

    /** @test */
    public function seleccionar_maderas_usa_transaccion()
    {
        $this->actingAs($this->admin);
        
        // Mock repository que falla
        $this->mock(MaderasOptimas::class, function ($mock) {
            $mock->shouldReceive('seleccionaPaqueta')
                ->andThrow(new \Exception('Error simulado'));
        });
        
        $response = $this->postJson('/ordenes-produccion/seleccionar', [
            'id_pedido' => 1,
            'id_item' => 1,
            'cantidad' => 5,
        ]);
        
        $response->assertStatus(500);
        $response->assertJson(['error' => true]);
        
        // Verificar que no se creó la orden (rollback funcionó)
        $this->assertDatabaseCount('ordenes_produccion', 0);
    }

    /** @test */
    public function show_carga_transformaciones_con_eager_loading()
    {
        $this->actingAs($this->admin);
        
        $orden = OrdenProduccion::factory()->create();
        
        $transformacion = Transformacion::factory()->create([
            'orden_produccion_id' => $orden->id,
            'tipo_corte' => 'INICIAL',
        ]);
        
        $response = $this->get("/ordenes-produccion/{$orden->id}");
        
        $response->assertStatus(200);
        $response->assertViewHas('transformaciones');
        
        // Verificar que se cargaron las relaciones
        $transformaciones = $response->viewData('transformaciones');
        $this->assertTrue($transformaciones->first()->relationLoaded('madera'));
        $this->assertTrue($transformaciones->first()->relationLoaded('cubicaje'));
    }
}
```

### Resumen de Tests

| # | Test | Cobertura |
|---|------|-----------|
| 1-2 | Autorización admin | index() |
| 3-6 | Crear orden completa | store() + crearTransformacion() + crearProcesosInicial() |
| 7 | Crear desde inventario | crearOrdenItemsInventario() |
| 8 | Ver paqueta AJAX | verPaqueta() |
| 9-11 | Eliminar orden en cascada | destroy() + DeleteOrden repository |
| 12 | Transacción funciona | seleccionar() |
| 13 | Eager loading | show() |

**Total:** 13 tests  
**Cobertura Estimada:** 85%

---

## 🔧 Repositories Documentados

### MaderasOptimas Repository

**Ubicación:** `app/Repositories/MaderasOptimas.php`  
**Líneas:** 750  
**Complejidad:** MUY ALTA

**Métodos Principales:**

```php
// 1. Algoritmo principal
public function Optimas($request)
{
    // Retorna maderas óptimas + sobrantes disponibles
}

// 2. Obtener datos de pedido
public function datosPedido($request)
{
    return Pedido::select('cantidad', 'id', 'diseno_producto_final_id')
        ->find($request->id_pedido);
}

// 3. Obtener diseño del item
public function datosItemDiseno($pedido, $request)
{
    return DisenoItem::join('items', 'items.id', '=', 'diseno_items.item_id')
        ->where('diseno_producto_final_id', $pedido->diseno_producto_final_id)
        ->where('item_id', $request->id_item)
        ->first(['cantidad', 'descripcion', 'existencias', 'largo', 'ancho', 'alto', 'item_id', 'madera_id']);
}

// 4. Sobrantes de corte reutilizables
public function sobrantesCorte($item_diseno)
{
    return Transformacion::where('trnasformacion_final', 'SOBRANTE_CORTE')
        ->where('estado', 'DISPONIBLE')
        ->where('largo', $item_diseno->largo)
        ->where('ancho', '>', ($item_diseno->ancho + 0.5) + 0.5)
        ->where('alto', '>', ($item_diseno->alto + 0.5) + 0.5)
        ->where('madera_id', $item_diseno->madera_id)
        ->get();
}

// 5. Sobrantes de troza
private function sobrantesTroza($item_diseno)
{
    // Similar a sobrantesCorte pero busca en SobranteTrozas
}

// 6. Maderas primas disponibles
public function Maderas($item_diseno)
{
    return Cubicaje::where('madera_id', $item_diseno->madera_id)
        ->where('largo', '>=', $item_diseno->largo)
        ->where('ancho', '>=', $item_diseno->ancho)
        ->where('alto', '>=', $item_diseno->alto)
        ->where('estado', 'DISPONIBLE')
        ->orderBy('pulgadas_cuadradas', 'asc')
        ->get();
}

// 7. Calcular cantidad a producir
public function producir($request, $item_diseno, $pedido)
{
    // Lógica compleja para calcular cuántas piezas producir
}

// 8. Corte inicial
public function corteInicial($maderas, $item_diseno, $var1, $var2)
{
    // Algoritmo de corte inicial con minimización de desperdicio
}

// 9. Seleccionar y reservar paquetas
public function seleccionaPaqueta($request, $guardar, $orden_id)
{
    // Reserva cubicajes y crea transformaciones
}

// 10. División de paquetas
public function cubicaje($request, $ver)
{
    // Divide paqueta en grupos óptimos
}
```

**Estrategia del Algoritmo:**

1. **Recolectar Opciones:**
   - Sobrantes de corte
   - Sobrantes de troza
   - Maderas primas

2. **Priorización:**
   - Primero: sobrantes (reduce desperdicio)
   - Segundo: maderas primas más pequeñas

3. **Validación:**
   - Dimensiones con holgura (+0.5")
   - Estado DISPONIBLE
   - Misma especie de madera

4. **Optimización:**
   - Ordenar por pulgadas² ASC
   - Minimizar desperdicio
   - Calcular sobrantes resultantes

---

### DeleteOrden Repository

**Ubicación:** `app/Repositories/DeleteOrden.php`  
**Líneas:** 100  
**Complejidad:** MEDIA

**Métodos:**

```php
// 1. Eliminar orden completa
public function deleteOrden($orden)
{
    $delete_proceso = $this->eliminarProcesos($orden->id);
    $update_cubicaje = $this->actualizarCubicajes($orden->id);
    $delete_transformacion = $this->eliminarTransformacion($orden->id);
    
    if ($delete_proceso && $update_cubicaje && $delete_transformacion) {
        $orden->update(['user_id' => Auth::user()->id]);
        $orden->delete();
        return response()->json(['error' => false, 'mensaje' => 'Orden eliminada']);
    }
    
    return response()->json(['error' => true, 'mensaje' => 'Error al eliminar']);
}

// 2. Eliminar procesos asociados
private function eliminarProcesos($orden_id): bool
{
    $procesos = Proceso::where('orden_produccion_id', $orden_id)->get();
    
    foreach ($procesos as $proceso) {
        $proceso->delete();
    }
    
    return Proceso::where('orden_produccion_id', $orden_id)->count() == 0;
}

// 3. Liberar cubicajes
public function actualizarCubicajes($orden_id): bool
{
    $transformaciones = Transformacion::where('orden_produccion_id', $orden_id)
        ->where('tipo_corte', 'INICIAL')
        ->get();
    
    $actualizados = 0;
    foreach ($transformaciones as $transformacion) {
        $actualiza = Cubicaje::where('id', $transformacion->cubicaje_id)
            ->update(['estado' => 'DISPONIBLE']);
        $actualizados += $actualiza;
    }
    
    return count($transformaciones) == $actualizados;
}

// 4. Eliminar transformaciones
public function eliminarTransformacion($orden_id): bool
{
    Transformacion::where('orden_produccion_id', $orden_id)->delete();
    
    return Transformacion::where('orden_produccion_id', $orden_id)->count() == 0;
}
```

**Orden de Ejecución:**

```
deleteOrden()
    ↓
1. eliminarProcesos()       → DELETE procesos WHERE orden_produccion_id = X
    ↓
2. actualizarCubicajes()    → UPDATE cubicajes SET estado = 'DISPONIBLE'
    ↓
3. eliminarTransformacion() → DELETE transformaciones WHERE orden_produccion_id = X
    ↓
4. delete()                 → Soft delete de la orden
```

**Validación:**

Cada método retorna `bool`:
- `true` → Operación exitosa
- `false` → Algo falló

Si alguno falla, no se elimina la orden.

---

## 📊 Diagramas

### Flujo de Creación de Orden

```
[Cliente hace Pedido]
        ↓
[Admin selecciona item del pedido]
        ↓
[showMaderas() - Lista maderas disponibles]
        ↓
[maderasOptimas() - Algoritmo de optimización]
        ↓
    ┌───────────────┐
    │ MaderasOptimas│
    │   Repository  │
    └───────────────┘
        ↓
[Optimas() → retorna maderas + sobrantes]
        ↓
[Vista muestra opciones]
        ↓
[Admin selecciona paquetas específicas]
        ↓
[seleccionar() - Confirma selección]
        ↓
    BEGIN TRANSACTION
        ├─ crearOrden() → OrdenProduccion (PENDIENTE)
        ├─ seleccionaPaqueta()
        │   ├─ Cubicaje → NO_DISPONIBLE
        │   ├─ Crea Transformacion (INICIAL)
        │   └─ Crea Transformacion (SOBRANTE)
        └─ crearProcesosInicial()
            └─ Crea Proceso por cada Transformacion
    COMMIT
        ↓
[orden->estado = 'EN_PROCESO']
        ↓
[Orden creada exitosamente]
```

### Diagrama ER

```
OrdenProduccion
├── id
├── pedido_id          → Pedido
├── item_id            → Item
├── cantidad
├── estado             (PENDIENTE, EN_PROCESO, COMPLETADA)
├── user_id            → User
├── created_at
└── updated_at

    ↓ hasMany

Transformacion
├── id
├── orden_produccion_id     → OrdenProduccion
├── cubicaje_id             → Cubicaje
├── entrada_madera_id       → EntradaMadera
├── cantidad
├── largo, ancho, alto
├── pulgadas_cuadradas
├── tipo_corte              (INICIAL, INTERMEDIO, FINAL)
├── trnasformacion_final    (CORTE_ITEM, SOBRANTE_CORTE, SOBRANTE_TROZA)
├── estado                  (DISPONIBLE, EN_PROCESO, USADO)
└── user_id                 → User

    ↓ hasMany

Proceso
├── id
├── orden_produccion_id     → OrdenProduccion
├── transformacion_id       → Transformacion
├── ruta_acabado_producto_id→ RutaAcabadoProducto
├── item_cantidad
├── estado                  (PENDIENTE, EN_PROCESO, COMPLETADO)
└── created_at
```

---

## 🚨 Problemas y Recomendaciones

### Problemas Identificados

#### 1. index() Sin Paginación

**Problema:**
```php
$ordenes_produccion = OrdenProduccion::join(...)
    ->whereYear('created_at', Carbon::now()->year)
    ->get(); // ❌ Puede ser lento con muchos registros
```

**Solución:**
```php
$ordenes_produccion = OrdenProduccion::with(['pedido', 'user'])
    ->whereYear('created_at', $year)
    ->orderBy('created_at', 'desc')
    ->paginate(50); // ✅ Paginado
```

#### 2. create() Carga TODOS los Pedidos

**Problema:**
```php
$pedidos = Pedido::select('id', 'referencia')->get(); // ❌ Miles de pedidos
```

**Solución:**
```php
// Solo pedidos activos y recientes
$pedidos = Pedido::whereIn('estado', ['PENDIENTE', 'EN_PROCESO'])
    ->whereYear('created_at', '>=', Carbon::now()->subYear())
    ->orderBy('created_at', 'desc')
    ->limit(100)
    ->get(['id', 'referencia']);
```

O mejor aún, usar **Select2 con AJAX**.

#### 3. crearOrdenItemsInventario() Sin Validación

**Problema:**
```php
$item->existencias -= (int)$request->cantidad; // ❌ No valida si hay stock
```

**Solución:**
```php
if ($item->existencias < $request->cantidad) {
    return response()->json([
        'error' => 'Stock insuficiente. Disponible: ' . $item->existencias
    ], 400);
}

$item->existencias -= (int)$request->cantidad;
$item->preprocesado += (int)$request->cantidad;
$item->save();
```

#### 4. showMaderas() Query Pesado

**Problema:**
```php
$maderas_primas = DB::table('cubicajes')
    ->join('entrada_maderas', ...) // Puede retornar miles de registros
    ->where(...)
    ->get(); // ❌ Sin limit
```

**Solución:**
```php
->orderBy('cubicajes.pulgadas_cuadradas', 'asc')
->limit(50) // ✅ Limitar resultados
->get();
```

#### 5. verPaqueta() Sin Validación

**Problema:**
```php
public function verPaqueta(Request $request)
{
    // No valida que entrada_madera_id y paqueta existan
    $paqueta = Cubicaje::where(...)->get();
    return response()->json($paqueta);
}
```

**Solución:**
```php
public function verPaqueta(VerPaquetaRequest $request)
{
    $validated = $request->validated();
    
    $paqueta = Cubicaje::where('entrada_madera_id', $validated['entrada_madera_id'])
        ->where('paqueta', $validated['paqueta'])
        ->where('estado', 'DISPONIBLE')
        ->orderBy('pulgadas_cuadradas', 'desc')
        ->get(['pulgadas_cuadradas', 'bloque']);
    
    if ($paqueta->isEmpty()) {
        return response()->json(['error' => 'Paqueta no encontrada'], 404);
    }
    
    return response()->json($paqueta);
}
```

---

### Mejoras Sugeridas

#### 1. Implementar FormRequests

```php
// app/Http/Requests/StoreOrdenProduccionRequest.php
class StoreOrdenProduccionRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role === 'admin';
    }
    
    public function rules()
    {
        return [
            'pedido_id' => 'required|exists:pedidos,id',
            'item_id' => 'required|exists:items,id',
            'cantidad' => 'required|integer|min:1',
            'cubicajes' => 'required|json',
            'ruta_acabado_producto_id' => 'required|json',
        ];
    }
}
```

#### 2. Usar Jobs para Procesos Pesados

```php
// app/Jobs/CrearOrdenProduccionJob.php
class CrearOrdenProduccionJob implements ShouldQueue
{
    public function handle()
    {
        // Lógica de creación de orden
        // Se ejecuta en background
    }
}

// En el controlador
public function store(StoreOrdenProduccionRequest $request)
{
    CrearOrdenProduccionJob::dispatch($request->validated());
    
    return redirect()->back()->with('status', 'Orden en proceso de creación...');
}
```

#### 3. Cache para Maderas Disponibles

```php
public function showMaderas(Request $request)
{
    $key = "maderas_disponibles_{$request->id_pedido}_{$request->id_item}";
    
    $maderas_primas = Cache::remember($key, 300, function() use ($request) {
        return DB::table('cubicajes')
            ->join('entrada_maderas', ...)
            ->where(...)
            ->get();
    });
    
    return view(...);
}
```

#### 4. Eventos para Auditoría

```php
// app/Events/OrdenProduccionCreada.php
class OrdenProduccionCreada
{
    public $orden;
    
    public function __construct(OrdenProduccion $orden)
    {
        $this->orden = $orden;
    }
}

// En el controlador
event(new OrdenProduccionCreada($orden));

// Listener registra en log
class RegistrarCreacionOrden
{
    public function handle(OrdenProduccionCreada $event)
    {
        Log::info('Orden creada', [
            'orden_id' => $event->orden->id,
            'user_id' => auth()->id(),
            'cantidad' => $event->orden->cantidad,
        ]);
    }
}
```

---

## 📝 Conclusiones

### Resumen

**OrdenProduccionController** es el controlador más complejo del sistema con:

1. **Algoritmo de Optimización** mediante MaderasOptimas repository
2. **Gestión de Inventario** con control de cubicajes y transformaciones
3. **Eliminación en Cascada** con DeleteOrden repository
4. **Transacciones DB** para integridad de datos
5. **AJAX Methods** para operaciones asíncronas

### Complejidad

| Aspecto | Nivel |
|---------|-------|
| Lógica de Negocio | ⚠️ MUY ALTA |
| Queries de BD | ⚠️ MUY ALTA |
| Integridad Referencial | ⚠️ CRÍTICA |
| Testabilidad | 🟡 MEDIA |
| Performance | ⚠️ NECESITA OPTIMIZACIÓN |

### Archivos Relacionados

- **Controlador:** `app/Http/Controllers/OrdenProduccionController.php`
- **Repository 1:** `app/Repositories/MaderasOptimas.php` (750 líneas)
- **Repository 2:** `app/Repositories/DeleteOrden.php` (100 líneas)
- **Modelo:** `app/Models/OrdenProduccion.php`
- **Vistas:** `resources/views/modulos/administrativo/programacion/*`

---

**Documentación Completa:** ✅  
**Última Actualización:** 30 de Enero, 2026  
**Autor:** Equipo de Desarrollo Inducolma
