# Módulo de Reportes - Documentación Consolidada

## 📋 Información General

**Módulo:** Sistema de Generación de Reportes  
**Controladores:** 4  
**Complejidad:** MEDIA  
**Propósito:** Generación de reportes en PDF, Excel y CSV con filtros dinámicos

---

## 📊 Controladores del Módulo

| # | Controlador | Namespace | Complejidad | Repository | Formatos |
|---|-------------|-----------|-------------|------------|----------|
| 1 | ReporteController | Base | MEDIA | ✅ ConsultasReportes | PDF/Excel/CSV/Vista |
| 2 | ReporteCubicajesController | Administrativos | MEDIA | ✅ ConsultasCubicajes | PDF/Excel/CSV/Vista |
| 3 | ReportePersonalController | Administrativos | MEDIA | ✅ ConsultasPersonal | PDF/Excel/CSV/Vista |
| 4 | ReportePedidosController | Pedidos | MEDIA | ✅ ConsultaPedidos | PDF/Excel/CSV/Vista |
| 5 | ReporteCostosController | Costos | MEDIA | ✅ ConsultaCostos | PDF/Excel/CSV/Vista |

---

## 📦 Arquitectura del Módulo

```
Usuario selecciona:
  ├── Tipo de Reporte (Ingreso Maderas, Cubicajes, Personal, etc.)
  ├── Rango de Fechas (desde - hasta)
  ├── Filtros Específicos (proveedor, empleado, pedido, etc.)
  └── Formato de Salida
        ├── 1 → PDF (DomPDF)
        ├── 2 → Excel (.xlsx)
        ├── 3 → CSV (.csv)
        └── 4 → Vista HTML (default)

Controller → Repository → Query Builder → JSON
                                            ↓
                                    View/Export Class
```

---

## 🔧 Patrón de Diseño Común

**TODOS los controladores siguen el mismo patrón:**

```php
public function reporteXXX(Request $request)
{
    // 1. Extraer parámetros
    $desde = $request->xxxDesde;
    $hasta = $request->xxxHasta;
    $tipoReporte = $request->tipoReporteXXX;
    $especifico = $request->filtroXXX;
    $generar = $request->generar;
    
    // 2. Consultar datos vía Repository
    $datos = $this->repository->consultaDatos($request);
    $encabezado = $datos[1];
    $data = json_decode(json_encode($datos[0]));
    
    // 3. Validar datos vacíos
    if (count($data) == 0 ) {
        return redirect()->back()
            ->with('status','No se encontraron datos...');
    }
    
    // 4. Generar según formato
    if ($generar == '1') {
        // PDF
        $pdf = Pdf::loadView($datos[3], compact('data', 'encabezado'));
        return $pdf->stream(...);
    } elseif ($generar == '2') {
        // Excel
        return Excel::download(new XXXExport($data), "...");
    } elseif ($generar == '3') {
        // CSV
        return Excel::download(new XXXExport($data), "...");
    } else {
        // Vista HTML
        return view($datos[2], compact('data', 'encabezado', ...));
    }
}
```

---

## 📄 1. ReporteController

**Archivo:** `app/Http/Controllers/ReporteController.php`  
**Repository:** `ConsultasReportes`  
**Propósito:** Reportes de ingreso de madera

### Constructor

```php
protected $reporte;

public function __construct(ConsultasReportes $reporte)
{
    $this->reporte = $reporte;
}
```

---

### ingresoMaderas() - Reporte de Entrada de Maderas

```php
public function ingresoMaderas(Request $request)
{
    $desde = $request->desdeIm;
    $hasta = $request->hastaIm;
    $tipoReporte = $request->tipoReporte;
    $especifico = $request->especifico;
    $generar = $request->generar;
    
    $datos = $this->reporte->seleccionarReporte($request);
    $encabezado = $datos[1];
    $data = json_decode(json_encode($datos[0]));

    if (count($data) == 0 ) {
        return redirect()
            ->back()
            ->with('status','No se encontraron datos para el reporte ingreso de madera en los filtros seleccionados.');
    } else {
        if ($generar == '1') {
            $pdf = Pdf::loadView('modulos.reportes.administrativos.ingresos-madera.ingreso-madera-pdf', 
                compact('data', 'encabezado'));
            $pdf->setPaper('a4');
            return $pdf->stream($encabezado.'-'.$desde.'-'.$hasta.'.pdf');

        } elseif ($generar == '2') {
            return Excel::download(new EntradaMaderaExport($data), "$encabezado-$desde-$hasta.xlsx");

        } elseif ($generar == '3') {
            return Excel::download(new EntradaMaderaExport($data), "$encabezado-$desde-$hasta.csv");

        } else {
            return view('modulos.reportes.administrativos.ingresos-madera.ingreso-madera',
                compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','especifico'));
        }
    }
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 2-6 | Extracción de parámetros | Fechas, tipo, filtro, formato |
| 8 | `seleccionarReporte($request)` | ⚡ **Repository retorna array** |
| 9 | `$datos[1]` | Título del reporte |
| 10 | `json_decode(json_encode($datos[0]))` | ⚠️ **Conversión innecesaria** |
| 16-19 | `if ($generar == '1')` | PDF con DomPDF |
| 21-22 | `elseif ($generar == '2')` | Excel (.xlsx) |
| 24-25 | `elseif ($generar == '3')` | CSV (.csv) |
| 27-29 | `else` | Vista HTML (DataTables) |

**🔍 Estructura de $datos:**

```php
$datos = [
    0 => Collection,  // Datos del reporte
    1 => String,      // Encabezado/Título
    2 => String,      // Ruta de vista HTML
    3 => String,      // Ruta de vista PDF
];
```

**⚠️ Conversión Innecesaria:**

```php
// ❌ Convierte Collection → JSON → Object
$data = json_decode(json_encode($datos[0]));

// ✅ Usar directamente:
$data = $datos[0];

// O si necesitas array:
$data = $datos[0]->toArray();
```

---

### getProveedores() - AJAX Búsqueda de Proveedores

```php
public function getProveedores(Request $request)
{
    $disenos = Proveedor::where('razon_social', 'like', '%'.strtoupper($request->descripcion).'%')
        ->get(['id','razon_social as text']);
    $disenos->toJson();
    return response()->json($disenos);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `where('razon_social', 'like', ...)` | Búsqueda parcial |
| 2 | `strtoupper($request->descripcion)` | ✅ Mayúsculas |
| 3 | `'razon_social as text'` | Alias para Select2 |
| 4 | `$disenos->toJson()` | ⚠️ **Línea innecesaria** |

**Propósito:**

Endpoint AJAX para Select2 - filtro de proveedores en reportes

**⚠️ Nombre de Variable:**

```php
// ❌ Variable llamada $disenos cuando son proveedores
$disenos = Proveedor::where(...)

// ✅ Nombre correcto:
$proveedores = Proveedor::where(...)
```

---

### getTipoMadera() - AJAX Búsqueda de Tipos de Madera

```php
public function getTipoMadera(Request $request)
{
    $disenos = TipoMadera::where('descripcion', 'like', '%'.strtoupper($request->descripcion).'%')
        ->get(['id','descripcion as text'])->except(1);
    $disenos->toJson();
    return response()->json($disenos);
}
```

**Análisis:**

Similar a `getProveedores()`, pero:
- Busca en `TipoMadera`
- `->except(1)`: Excluye tipo "NINGUNO"

---

## 📊 2. ReporteCubicajesController

**Archivo:** `app/Http/Controllers/Reportes/Administrativos/ReporteCubicajesController.php`  
**Repository:** `ConsultasCubicajes`  
**Propósito:** Reportes de cubicajes, transformaciones y calificaciones de viaje

### Constructor

```php
protected $consultaCubicaje;

public function __construct(ConsultasCubicajes $consultasCubicajes){
    $this->consultaCubicaje = $consultasCubicajes;
}
```

---

### getEntradas() - AJAX Obtener Entradas de Madera

```php
public function getEntradas()
{
    $entradas = EntradaMadera::orderBy('id')->get(['id', 'id as text']);
    $entradas->toJson();
    return response()->json($entradas);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `get(['id', 'id as text'])` | ⚡ **ID duplicado para Select2** |
| 3 | `$entradas->toJson()` | ⚠️ Línea innecesaria |

**Propósito:**

Select2 para filtrar cubicajes por entrada de madera

---

### reporteCubicajes() - Generar Reporte de Cubicajes

```php
public function reporteCubicajes(Request  $request)
{
    $desde = $request->cubicajeDesde;
    $hasta = $request->cubicajeHasta;
    $proveedor = $request->filtroCubiaje2;
    $tipoReporte = $request->tipoReporteCubicaje;
    $especifico = $request->filtroCubiaje1;
    $generar = $request->generar;
    
    $datos = $this->consultaCubicaje->consultaDatos($request);
    $encabezado = $datos[1];
    $data = json_decode(json_encode($datos[0]));

    if (count($data) == 0 ) {
        return redirect()
            ->back()
            ->with('status','No se encontraron datos de cubicajes en los filtros seleccionados.');
    } else {
        if ($generar == '1') {
            $pdf = Pdf::loadView($datos[3], compact('data', 'encabezado'));
            $pdf->setPaper('a4');
            return $pdf->stream($encabezado.'pdf');

        } elseif ($generar == '2') {
            switch ($tipoReporte) {
                case '1':
                    return Excel::download(new CubicajesExport($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
                case '2':
                    return Excel::download(new TransformacionesExport($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
                case '3':
                    return Excel::download(new CalificacionesViajeExport($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
                case '4':
                    return Excel::download(new CalificacionesViajeExport($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
                default:
                    # code...
                    break;
            }

        } elseif ($generar == '3') {
            switch ($tipoReporte) {
                case '1':
                    return Excel::download(new CubicajesExport($data), "$encabezado-$desde-$hasta.csv");
                    break;
                case '2':
                    return Excel::download(new TransformacionesExport($data), "$encabezado-$desde-$hasta.csv");
                    break;
                case '3':
                    return Excel::download(new CalificacionesViajeExport($data), "$encabezado-$desde-$hasta.csv");
                    break;
                case '4':
                    return Excel::download(new CalificacionesViajeExport($data), "$encabezado-$desde-$hasta.csv");
                    break;
                default:
                    # code...
                    break;
            }

        } else {
            return view($datos[2],
                compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','especifico', 'proveedor'));
        }
    }
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 23-38 | `switch ($tipoReporte)` | ⚡ **Diferentes Export classes** |
| 25-26 | `case '1'` | CubicajesExport |
| 28-29 | `case '2'` | TransformacionesExport |
| 31-35 | `case '3', '4'` | CalificacionesViajeExport (duplicado) |

**🔍 Tipos de Reporte:**

```
tipoReporteCubicaje:
  1 → Cubicajes (mediciones de madera)
  2 → Transformaciones (procesos de transformación)
  3 → Calificaciones de viaje (evaluación de transporte)
  4 → [Duplicado de caso 3]
```

**⚠️ Caso 3 y 4 Duplicados:**

```php
// ❌ Casos 3 y 4 hacen lo mismo
case '3':
case '4':
    return Excel::download(new CalificacionesViajeExport($data), ...);

// ✅ Unificar o documentar diferencia
case '3':
case '4':
    // Ambos usan CalificacionesViajeExport
    return Excel::download(new CalificacionesViajeExport($data), ...);
```

---

## 👥 3. ReportePersonalController

**Archivo:** `app/Http/Controllers/Reportes/Administrativos/ReportePersonalController.php`  
**Repository:** `ConsultasPersonal`  
**Propósito:** Reportes de personal, turnos, eventos y horas trabajadas

### Constructor

```php
protected $personal;

public function __construct( ConsultasPersonal $personal)
{
    $this->personal = $personal;
}
```

---

### reportePersonal() - Generar Reporte de Personal

```php
public function reportePersonal(Request  $request)
{
    $desde = $request->personalDesde;
    $hasta = $request->personalHasta;
    $proveedor = $request->filtroCubiaje2;
    $tipoReporte = $request->tipoReportePersonal;
    $especifico = $request->filtroPersonal;
    $generar = $request->generar;
    
    $datos = $this->personal->consultaDatos($request);
    $encabezado = $datos[1];
    $data = json_decode(json_encode($datos[0]));

    if (count($data) == 0 ) {
        return redirect()
            ->back()
            ->with('status','No se encontraron datos de cubicajes en los filtros seleccionados.');
    } else {
        if ($generar == '1') {
            $pdf = Pdf::loadView($datos[3], compact('data', 'encabezado'));
            $pdf->setPaper('a4');
            return $pdf->stream($encabezado.'pdf');

        } elseif ($generar == '2') {
            switch ($tipoReporte) {
                case '1':
                    return Excel::download(new TurnoPersonal($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
                case '2':
                    return Excel::download(new EventosPersonal($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
                case '3':
                    return Excel::download(new HorasTrabajadas($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
                case '4':
                    return Excel::download(new HorasTrabajadasEmpleado($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
                case '5':
                    return Excel::download(new IngresoTerceros($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
            }

        } elseif ($generar == '3') {
            switch ($tipoReporte) {
                case '1':
                    return Excel::download(new TurnoPersonal($data), "$encabezado-$desde-$hasta.csv");
                    break;
                case '2':
                    return Excel::download(new EventosPersonal($data), "$encabezado-$desde-$hasta.csv");
                    break;
                case '3':
                    return Excel::download(new HorasTrabajadas($data), "$encabezado-$desde-$hasta.csv");
                    break;
                case '4':
                    return Excel::download(new HorasTrabajadasEmpleado($data), "$encabezado-$desde-$hasta.csv");
                    break;
                case '5':
                    return Excel::download(new IngresoTerceros($data), "$encabezado-$desde-$hasta.csv");
                    break;
            }

        } else {
            return view($datos[2],
                compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','especifico', 'proveedor'));
        }
    }
}
```

**🔍 Tipos de Reporte Personal:**

```
tipoReportePersonal:
  1 → TurnoPersonal (turnos asignados)
  2 → EventosPersonal (incidencias, permisos, vacaciones)
  3 → HorasTrabajadas (resumen general)
  4 → HorasTrabajadasEmpleado (detalle por empleado)
  5 → IngresoTerceros (contratistas/visitantes)
```

**⚠️ Mensaje de Error Incorrecto:**

```php
// ❌ Dice "cubicajes" en reporte de personal
'No se encontraron datos de cubicajes en los filtros seleccionados.'

// ✅ Debería decir:
'No se encontraron datos de personal en los filtros seleccionados.'
```

---

### getEmpleados() - AJAX Búsqueda de Empleados

```php
public function getEmpleados(Request $request)
{
    $empleados = User::where('name', 'like', '%'.strtoupper($request->descripcion).'%')
        ->whereBetween('rol_id', [1,2])
        ->get(['id','name as text']);
    $empleados->toJson();
    return response()->json($empleados);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `where('name', 'like', ...)` | Búsqueda por nombre |
| 3 | `->whereBetween('rol_id', [1,2])` | ⚠️ **Magic numbers** |
| 3 | `[1,2]` | Solo admin y operarios (excluye clientes) |

**⚠️ Magic Numbers:**

```php
// ❌ [1,2] sin contexto
->whereBetween('rol_id', [1,2])

// ✅ Usar constantes:
const ROL_ADMIN = 1;
const ROL_OPERARIO = 2;

->whereBetween('rol_id', [self::ROL_ADMIN, self::ROL_OPERARIO])
```

---

### getTerceros() - AJAX Búsqueda de Contratistas

```php
public function getTerceros(Request $request)
{
    $empleados = Contratista::where('cedula', 'like', '%'.strtoupper($request->descripcion).'%')
        ->withTrashed()
        ->get(['id','cedula as text']);
    $empleados->toJson();
    return response()->json($empleados);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `where('cedula', 'like', ...)` | Búsqueda por cédula |
| 3 | `->withTrashed()` | ✅ Incluye eliminados |

**Propósito:**

Incluye contratistas inactivos para reportes históricos

---

## 📦 4. ReportePedidosController

**Archivo:** `app/Http/Controllers/Reportes/Pedidos/ReportePedidosController.php`  
**Repository:** `ConsultaPedidos`  
**Propósito:** Reportes de pedidos, procesos y usuarios

### Constructor

```php
protected $pedidos;

public function __construct( ConsultaPedidos $pedidos)
{
    $this->pedidos = $pedidos;
}
```

---

### getClientes() - AJAX Búsqueda de Clientes

```php
public function getClientes(Request $request)
{
    $empleados = Cliente::where('razon_social', 'like', '%'.strtoupper($request->descripcion).'%')
        ->orWhere('nit', 'like', '%'.$request->descripcion.'%')
        ->withTrashed()
        ->get(['id','razon_social as text']);
    $empleados->toJson();
    return response()->json($empleados);
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 2 | `where('razon_social', 'like', ...)` | Búsqueda por nombre |
| 3 | `->orWhere('nit', 'like', ...)` | ⚡ **O por NIT** |
| 3 | `'%'.$request->descripcion.'%'` | ⚠️ NIT sin strtoupper |
| 4 | `->withTrashed()` | ✅ Incluye clientes inactivos |

**⚠️ Nombre de Variable:**

```php
// ❌ Variable llamada $empleados cuando son clientes
$empleados = Cliente::where(...)

// ✅ Nombre correcto:
$clientes = Cliente::where(...)
```

---

### reportePedidos() - Generar Reporte de Pedidos

```php
public function reportePedidos(Request  $request)
{
    $desde = $request->pedidoDesde;
    $hasta = $request->pedidoHasta;
    $tipoReporte = $request->tipoReportePedidos;
    $especifico = $request->filtroPedido1;
    $numeroP = $request->nPedido;
    $generar = $request->generar;
    
    $datos = $this->pedidos->consultaDatos($request);
    $encabezado = $datos[1];
    $data = json_decode(json_encode($datos[0]));
    $cliente = $datos[4];
    
    if (count($data) == 0 ) {
        return redirect()
            ->back()
            ->with('status','No se encontraron datos de cubicajes en los filtros seleccionados.');
    } else {
        if ($generar == '1') {
            $pdf = Pdf::loadView($datos[3], compact('data', 'encabezado'));
            $pdf->setPaper('a4');
            return $pdf->stream($encabezado.'pdf');

        } elseif ($generar == '2') {
            switch ($tipoReporte) {
                case '6':
                    return Excel::download(new ProcesosPedido($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
                case '7':
                    return Excel::download(new UsuariosPedido($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
                default :
                    return Excel::download(new PedidosCliente($data), "$encabezado-$desde-$hasta.xlsx");
                    break;
            }

        } elseif ($generar == '3') {
            switch ($tipoReporte) {
                case '6':
                    return Excel::download(new ProcesosPedido($data), "$encabezado-$desde-$hasta.csv");
                    break;
                case '7':
                    return Excel::download(new UsuariosPedido($data), "$encabezado-$desde-$hasta.csv");
                    break;
                default :
                    return Excel::download(new PedidosCliente($data), "$encabezado-$desde-$hasta.csv");
                    break;
            }

        } else {
            return view($datos[2],
                compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','especifico', 'cliente'));
        }
    }
}
```

**Análisis:**

| Líneas | Código | Explicación |
|--------|--------|-------------|
| 6 | `$numeroP = $request->nPedido` | ⚠️ **Variable no usada** |
| 12 | `$cliente = $datos[4]` | ⚡ Datos extra del repository |
| 25-34 | `switch ($tipoReporte)` | 3 Export classes |
| 32 | `default` | PedidosCliente (por defecto) |

**🔍 Tipos de Reporte Pedidos:**

```
tipoReportePedidos:
  6 → ProcesosPedido (estado de procesos por pedido)
  7 → UsuariosPedido (quién trabajó en cada pedido)
  default → PedidosCliente (listado general de pedidos)
```

**⚠️ Magic Numbers:**

```php
// ❌ Casos 6 y 7 sin constantes
case '6':
case '7':

// ✅ ¿Por qué no 1, 2, 3...? Debe haber tipos 1-5 en el frontend
```

---

## 💰 5. ReporteCostosController

**Archivo:** `app/Http/Controllers/Reportes/Costos/ReporteCostosController.php`  
**Repository:** `ConsultaCostos`  
**Propósito:** Reportes de costos de producción

### Constructor

```php
protected $costos;

public function __construct( ConsultaCostos $costos)
{
    $this->costos = $costos;
}
```

---

### reporteCostos() - Generar Reporte de Costos

```php
public function reporteCostos(Request $request)
{
    $desde = $request->costoDesde;
    $hasta = $request->costoHasta;
    $tipoReporte = $request->tipoReporteCosotos;
    $maquina = $request->maquina;
    $pedidoId = $request->pedido ? $request->pedido : null;
    $usuario = $request->usuario;
    $item = $request->item;

    $generar = $request->generar;
    $datos = $this->costos->consultaDatos($request);
    $encabezado = $datos[1];
    $data = json_decode(json_encode($datos[0]));

    if (count($data) == 0 ) {
        return redirect()
            ->back()
            ->with('status','No se encontraron datos en los filtros seleccionados.');
    } else {
        $data = $data[0];
        
        if ($generar == '1') {
            $pdf = Pdf::loadView($datos[3], compact('data', 'encabezado'));
            $pdf->setPaper('a4');
            return $pdf->stream($encabezado.'pdf');

        } elseif ($generar == '2') {
            return Excel::download(new CostosExport($data), "$encabezado-$desde-$hasta.xlsx");

        } elseif ($generar == '3') {
            return Excel::download(new CostosExport($data), "$encabezado-$desde-$hasta.csv");

        } else {
            return view($datos[2],
                compact('data',
                'encabezado',
                'desde',
                'hasta',
                'tipoReporte',
                'maquina',
                'usuario',
                'item',
                'pedidoId'
            ));
        }
    }
}
```

**Análisis:**

| Línea | Código | Explicación |
|-------|--------|-------------|
| 4 | `$request->tipoReporteCosotos` | ⚠️ **Typo: Cosotos** |
| 6 | `$pedidoId = $request->pedido ? : null` | ⚡ Operador ternario corto |
| 20 | `$data = $data[0]` | ⚠️ **Accede a índice de $data** |

**⚠️ Typo:**

```php
// ❌ Nombre con error ortográfico
$tipoReporte = $request->tipoReporteCosotos;
//                                    ^^^^ Debería ser "Costos"
```

**⚠️ Línea Peligrosa:**

```php
// ❌ Si $data no es array, genera error
$data = $data[0];

// ✅ Validar antes:
if (is_array($data) && isset($data[0])) {
    $data = $data[0];
}
```

**Diferencia con otros:**

- **NO usa switch** para diferentes Export classes
- Solo **un tipo de export:** `CostosExport`
- Más filtros específicos: maquina, usuario, item, pedidoId

---

## 📊 Comparación de Controladores

| Aspecto | Reporte | Cubicajes | Personal | Pedidos | Costos |
|---------|---------|-----------|----------|---------|--------|
| **Repository** | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí |
| **PDF** | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí |
| **Excel** | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí |
| **CSV** | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí |
| **Export Classes** | 1 | 3 | 5 | 3 | 1 |
| **AJAX Methods** | ✅ 2 | ✅ 1 | ✅ 2 | ✅ 1 | ❌ No |
| **Magic Numbers** | ❌ No | ✅ Sí | ✅ Sí | ✅ Sí | ❌ No |
| **Variables Mal Nombradas** | ✅ Sí | ❌ No | ✅ Sí | ✅ Sí | ❌ No |
| **Typos** | ❌ No | ❌ No | ❌ No | ❌ No | ✅ Sí |
| **Dead Code (.toJson())** | ✅ Sí | ✅ Sí | ✅ Sí | ✅ Sí | N/A |

---

## 🚨 Problemas Críticos del Módulo

### 1. Conversión JSON Innecesaria en TODOS

```php
// ❌ En TODOS los métodos reporteXXX()
$data = json_decode(json_encode($datos[0]));

// Esto convierte Collection → JSON → Object sin razón
```

**Impacto:**

- Performance degradada
- Pérdida de métodos de Collection
- Uso de memoria innecesario

**✅ Solución:**

```php
// Opción 1: Usar Collection directamente
$data = $datos[0];

// Opción 2: Convertir a array si es necesario
$data = $datos[0]->toArray();
```

---

### 2. Línea Innecesaria .toJson() en TODOS los AJAX

```php
// ❌ En getProveedores(), getTipoMadera(), getEmpleados(), etc.
$disenos->toJson();  // No se usa el resultado
return response()->json($disenos);  // Ya convierte a JSON

// ✅ Eliminar línea:
return response()->json($disenos);
```

---

### 3. Variables Mal Nombradas

```php
// ❌ En ReporteController::getProveedores()
$disenos = Proveedor::where(...) // Son proveedores, no diseños

// ❌ En ReportePedidosController::getClientes()
$empleados = Cliente::where(...) // Son clientes, no empleados
```

---

### 4. Magic Numbers en Switches

```php
// ❌ Casos 1, 2, 3, 6, 7 sin constantes
switch ($tipoReporte) {
    case '1':
    case '2':
    case '6':
    case '7':
}

// ✅ Usar constantes:
const REPORTE_CUBICAJES = '1';
const REPORTE_TRANSFORMACIONES = '2';
const REPORTE_PROCESOS_PEDIDO = '6';
```

---

### 5. Magic Numbers en whereBetween

```php
// ❌ En getEmpleados()
->whereBetween('rol_id', [1,2])

// ✅ Constantes de roles
```

---

### 6. Mensajes de Error Incorrectos

```php
// ❌ En reportePersonal() y reportePedidos()
'No se encontraron datos de cubicajes...' // Dice cubicajes en todos

// ✅ Personalizar mensaje según tipo
```

---

### 7. Typo en Nombre de Variable

```php
// ❌ En ReporteCostosController
$tipoReporte = $request->tipoReporteCosotos;
//                                    ^^^^^ Debería ser "Costos"
```

---

### 8. Acceso a Índice sin Validación

```php
// ❌ En reporteCostos()
$data = $data[0]; // Si no es array, error

// ✅ Validar:
if (is_array($data) && isset($data[0])) {
    $data = $data[0];
}
```

---

## ✅ Mejores Prácticas Identificadas

### 1. Patrón Consistente

✅ TODOS los controladores siguen el mismo flujo:

```
Request → Repository → Validación → Switch Format → Output
```

### 2. Múltiples Formatos de Salida

```php
if ($generar == '1') { PDF }
elseif ($generar == '2') { Excel }
elseif ($generar == '3') { CSV }
else { Vista HTML }
```

### 3. Validación de Datos Vacíos

```php
if (count($data) == 0 ) {
    return redirect()->back()->with('status', '...');
}
```

### 4. Repository Pattern

Toda la lógica de consultas delegada a repositories

### 5. Uso de DomPDF y Laravel Excel

```php
$pdf = Pdf::loadView(...);
Excel::download(new XXXExport(...), ...);
```

### 6. Filtro con withTrashed()

```php
->withTrashed() // Incluye registros eliminados para reportes históricos
```

---

## 🧪 Tests Propuestos

```php
/** @test */
public function puede_generar_reporte_ingreso_maderas_pdf()
{
    $this->actingAs($this->admin);
    
    EntradaMadera::factory()->count(5)->create();
    
    $response = $this->post('/reportes/ingreso-maderas', [
        'desdeIm' => now()->subDays(30)->format('Y-m-d'),
        'hastaIm' => now()->format('Y-m-d'),
        'tipoReporte' => 1,
        'generar' => '1', // PDF
    ]);
    
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
}

/** @test */
public function puede_generar_reporte_cubicajes_excel()
{
    $this->actingAs($this->admin);
    
    Cubicaje::factory()->count(10)->create();
    
    $response = $this->post('/reportes/cubicajes', [
        'cubicajeDesde' => now()->subDays(30)->format('Y-m-d'),
        'cubicajeHasta' => now()->format('Y-m-d'),
        'tipoReporteCubicaje' => '1',
        'generar' => '2', // Excel
    ]);
    
    $response->assertStatus(200);
    $this->assertEquals(
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        $response->headers->get('Content-Type')
    );
}

/** @test */
public function redirige_cuando_no_hay_datos()
{
    $this->actingAs($this->admin);
    
    $response = $this->post('/reportes/ingreso-maderas', [
        'desdeIm' => '2020-01-01',
        'hastaIm' => '2020-01-31',
        'tipoReporte' => 1,
        'generar' => '1',
    ]);
    
    $response->assertRedirect();
    $response->assertSessionHas('status');
    $this->assertStringContainsString('No se encontraron datos', session('status'));
}

/** @test */
public function get_proveedores_busca_por_razon_social()
{
    Proveedor::factory()->create(['razon_social' => 'MADERERA DEL NORTE']);
    Proveedor::factory()->create(['razon_social' => 'FORESTAL DEL SUR']);
    
    $response = $this->getJson('/reportes/get-proveedores', ['descripcion' => 'norte']);
    
    $response->assertJsonCount(1);
    $response->assertJsonFragment(['text' => 'MADERERA DEL NORTE']);
}

/** @test */
public function get_empleados_filtra_por_rol()
{
    $admin = User::factory()->create(['rol_id' => 1]); // Admin
    $operario = User::factory()->create(['rol_id' => 2]); // Operario
    $cliente = User::factory()->create(['rol_id' => 3]); // Cliente
    
    $response = $this->getJson('/reportes/personal/get-empleados', ['descripcion' => '']);
    
    $response->assertJsonCount(2); // Solo admin y operario
    $response->assertJsonFragment(['id' => $admin->id]);
    $response->assertJsonFragment(['id' => $operario->id]);
    $response->assertJsonMissing(['id' => $cliente->id]);
}

/** @test */
public function get_terceros_incluye_eliminados()
{
    $activo = Contratista::factory()->create();
    $eliminado = Contratista::factory()->create();
    $eliminado->delete();
    
    $response = $this->getJson('/reportes/personal/get-terceros', ['descripcion' => '']);
    
    $response->assertJsonCount(2);
    $response->assertJsonFragment(['id' => $activo->id]);
    $response->assertJsonFragment(['id' => $eliminado->id]);
}

/** @test */
public function reporte_personal_genera_diferentes_exports()
{
    $this->actingAs($this->admin);
    
    TurnoUsuario::factory()->count(5)->create();
    
    // Tipo 1: TurnoPersonal
    $response = $this->post('/reportes/personal', [
        'personalDesde' => now()->subDays(30)->format('Y-m-d'),
        'personalHasta' => now()->format('Y-m-d'),
        'tipoReportePersonal' => '1',
        'generar' => '2',
    ]);
    
    $response->assertStatus(200);
    $this->assertStringContainsString('TurnoPersonal', $response->headers->get('Content-Disposition'));
}

/** @test */
public function get_clientes_busca_por_razon_social_o_nit()
{
    Cliente::factory()->create(['razon_social' => 'EMPRESA XYZ', 'nit' => '123456789']);
    Cliente::factory()->create(['razon_social' => 'EMPRESA ABC', 'nit' => '987654321']);
    
    $response1 = $this->getJson('/reportes/pedidos/get-clientes', ['descripcion' => 'xyz']);
    $response1->assertJsonCount(1);
    
    $response2 = $this->getJson('/reportes/pedidos/get-clientes', ['descripcion' => '987654321']);
    $response2->assertJsonCount(1);
    $response2->assertJsonFragment(['text' => 'EMPRESA ABC']);
}
```

**Tests Propuestos:** 12 tests

---

## 📊 Export Classes Utilizadas

| Export Class | Controlador | Propósito |
|--------------|-------------|-----------|
| `EntradaMaderaExport` | ReporteController | Entrada de maderas |
| `CubicajesExport` | ReporteCubicajesController | Cubicajes |
| `TransformacionesExport` | ReporteCubicajesController | Transformaciones |
| `CalificacionesViajeExport` | ReporteCubicajesController | Calificaciones |
| `TurnoPersonal` | ReportePersonalController | Turnos |
| `EventosPersonal` | ReportePersonalController | Eventos |
| `HorasTrabajadas` | ReportePersonalController | Horas totales |
| `HorasTrabajadasEmpleado` | ReportePersonalController | Horas por empleado |
| `IngresoTerceros` | ReportePersonalController | Terceros |
| `PedidosCliente` | ReportePedidosController | Pedidos |
| `ProcesosPedido` | ReportePedidosController | Procesos |
| `UsuariosPedido` | ReportePedidosController | Usuarios |
| `CostosExport` | ReporteCostosController | Costos |

**Total:** 13 Export Classes

---

## 📝 Conclusión del Módulo

### Resumen

El **Módulo de Reportes** gestiona:

1. **Múltiples Formatos** → PDF, Excel, CSV, HTML
2. **Repository Pattern** → Lógica compleja delegada
3. **Filtros Dinámicos** → Fechas, proveedores, empleados, etc.
4. **AJAX Endpoints** → Select2 para filtros

### Complejidad

| Aspecto | Nivel |
|---------|-------|
| Lógica de Negocio | 🟡 MEDIA (delegada a repositories) |
| Consistencia de Código | 🟢 ALTA (patrón uniforme) |
| Queries de BD | 🟡 MEDIA (en repositories) |
| Testabilidad | 🟢 ALTA |
| Performance | 🔴 NECESITA MEJORAS (json_encode innecesario) |
| Documentación | 🔴 BAJA (magic numbers) |

### Prioridades de Refactoring

1. **ALTO:** Eliminar conversión `json_decode(json_encode(...))` (5 lugares)
2. **ALTO:** Eliminar líneas `.toJson()` innecesarias (7 lugares)
3. **MEDIO:** Renombrar variables mal nombradas ($disenos, $empleados)
4. **MEDIO:** Agregar constantes para magic numbers
5. **MEDIO:** Corregir mensajes de error genéricos
6. **BAJO:** Arreglar typo "tipoReporteCosotos"

---

**Documentación Completa:** ✅  
**Tests Propuestos:** 12 tests  
**Export Classes:** 13 clases  
**Última Actualización:** 30 de Enero, 2026
