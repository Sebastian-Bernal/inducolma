# FLUJO COMPLETO: Reporte Cubicajes - Ejecución y Aperturas de Ventana

## 📍 UBICACIONES CLAVE EN EL CÓDIGO

### 1. Vista Principal (HTML/Blade)
**Archivo**: [resources/views/modulos/reportes/administrativos/index.blade.php](resources/views/modulos/reportes/administrativos/index.blade.php#L115)

```blade
<!-- Línea 138: Parámetro crítico -->
<form action="{{ route('reporte-cubicajes') }}" 
      id="formReporteCubicajes"
      method="GET" 
      target="_blank"          <!-- ✅ AQUÍ ABRE NUEVA VENTANA -->
      rel="noopener noreferrer">
```

---

## 🔴 POR QUÉ ABRE NUEVA VENTANA

### La causa: Atributo `target="_blank"`

En la línea 138 del formulario HTML:
```html
target="_blank"
```

**Esto hace que**:
- El navegador abra la respuesta en una **nueva pestaña/ventana**
- En lugar de reemplazar la página actual
- Es un comportamiento estándar HTML

---

## 🔄 FLUJO COMPLETO DE EJECUCIÓN

### PASO 1: Usuario Selecciona Opciones en la Vista

**Vista**: [administrativos/index.blade.php líneas 119-207](resources/views/modulos/reportes/administrativos/index.blade.php#L119)

```blade
<!-- Selector del tipo de reporte -->
<select id="tipoReporteCubicaje" name="tipoReporteCubicaje" onchange="filtroCubicajes()">
    <option value="1">Cubicaje por viaje</option>
    <option value="2">Transformacion de madera por viaje</option>
    <option value="3">Calificacion de paquetas por viaje</option>
    <option value="4">Calificacion de paquetas por proveedor por viaje</option>
</select>

<!-- Input para número de viaje o proveedor según el tipo -->
<input id="filtroCubiaje1" name="filtroCubiaje1" type="number">  <!-- Viaje -->
<select id="filtroCubiaje2" name="filtroCubiaje2"></select>       <!-- Proveedor -->

<!-- Fechas (solo para tipo 4) -->
<input id="cubicajeDesde" name="cubicajeDesde" type="date">
<input id="cubicajeHasta" name="cubicajeHasta" type="date">

<!-- Botón que ejecuta -->
<button type="button" onclick="reporteCubicajes()">Generar reporte</button>
```

---

### PASO 2: JavaScript Valida y Envía Form

**Archivo**: [public/js/modulos/reportes/administrativos/reportes-cubicajes.js](public/js/modulos/reportes/administrativos/reportes-cubicajes.js)

#### 2.1 Función `filtroCubicajes()` (línea 6)
```javascript
function filtroCubicajes() {
    let reporte = $('#tipoReporteCubicaje');
    
    switch (reporte.val()) {
        case '1':  // Cubicaje por viaje
        case '2':  // Transformacion
        case '3':  // Calificacion
            mostrarInputViaje();      // Muestra input para número de viaje
            $('#divDesde').hide();    // Oculta fechas
            break;
        case '4':  // Por proveedor
            proveedores();            // Carga select de proveedores
            $('#divDesde').show();    // Muestra fechas
            break;
    }
}
```

**Efecto**: Muestra/oculta campos dinámicamente según el tipo seleccionado.

#### 2.2 Función `reporteCubicajes()` (línea 95)
```javascript
function reporteCubicajes() {
    // Obtiene valores del formulario
    let desde = $('#cubicajeDesde');
    let hasta = $('#cubicajeHasta');
    let viaje = $('#filtroCubiaje1');
    let reporte = $('#tipoReporteCubicaje');
    let especifico = $('#filtroCubiaje2');
    
    // Validaciones
    if (reporte.val() == "") {
        alertaErrorSimple('Seleccione un tipo de reporte!');
        return;
    }
    
    if (viaje.val() == "" && reporte.val() != '4') {
        alertaErrorSimple('Ingrese el numero de viaje a consultar');
        return;
    }
    
    if (reporte.val() == '4' && (especifico.val() == "" || desde.val() == "" || hasta.val() == "")) {
        alertaErrorSimple('Seleccione el filtro de busqueda y el rango de fechas');
        return;
    }
    
    // ✅ ENVÍA EL FORMULARIO
    $('#formReporteCubicajes').submit();  // <-- AQUÍ ES DONDE SE ABRE LA VENTANA
    
    // Limpia campos
    desde.val('');
    hasta.val('');
    reporte.val('');
}
```

**Lo importante**: `$('#formReporteCubicajes').submit()` envía el form con `target="_blank"` → **ABRE NUEVA VENTANA**

---

### PASO 3: Laravel Backend Procesa la Solicitud

**Ruta**: [routes/web.php línea 443](routes/web.php#L443)
```php
Route::controller(ReporteCubicajesController::class)->group(function () {
    Route::get('reporte-cubicajes', 'reporteCubicajes')
        ->name('reporte-cubicajes')
        ->middleware('auth');
});
```

**Controlador**: [app/Http/Controllers/Reportes/Administrativos/ReporteCubicajesController.php](app/Http/Controllers/Reportes/Administrativos/ReporteCubicajesController.php)

```php
public function reporteCubicajes(Request $request)
{
    // 1. Obtiene los parámetros del formulario
    $desde = $request->cubicajeDesde;
    $hasta = $request->cubicajeHasta;
    $proveedor = $request->filtroCubiaje2;
    $tipoReporte = $request->tipoReporteCubicaje;
    $especifico = $request->filtroCubiaje1;
    $generar = $request->generar;  // <-- Tipo de salida (1=PDF, 2=XLSX, 3=CSV)
    
    // 2. Ejecuta consulta de datos
    $datos = $this->consultaCubicaje->consultaDatos($request);
    
    // Si no hay datos, redirige
    if (count($datos[0]) == 0) {
        return redirect()->back()
            ->with('status','No se encontraron datos...');
    }
    
    // 3. Según generar, retorna diferente respuesta
    if ($generar == '1') {
        // ✅ OPCIÓN 1: PDF - Se abre en nueva ventana
        $pdf = Pdf::loadView($datos[3], compact('data', 'encabezado'));
        $pdf->setPaper('a4');
        return $pdf->stream($encabezado.'pdf');
        
    } elseif ($generar == '2') {
        // ✅ OPCIÓN 2: EXCEL - Descarga el archivo
        switch ($tipoReporte) {
            case '1':
                return Excel::download(new CubicajesExport($data), 
                    "$encabezado-$desde-$hasta.xlsx");
            // ... más casos
        }
        
    } elseif ($generar == '3') {
        // ✅ OPCIÓN 3: CSV - Descarga el archivo
        return Excel::download(new CubicajesExport($data), 
            "$encabezado-$desde-$hasta.csv");
            
    } else {
        // ✅ OPCIÓN 4: Vista HTML - Muestra en nueva ventana
        return view($datos[2], compact('data', 'encabezado', ...));
    }
}
```

---

### PASO 4: Renderiza Vista de Resultados

**Vistas de Resultado** (se abre en nueva ventana porque está en `target="_blank"`):
- [resources/views/modulos/reportes/administrativos/cubicajes/index-cubicajes.blade.php](resources/views/modulos/reportes/administrativos/cubicajes/index-cubicajes.blade.php)
- [resources/views/modulos/reportes/administrativos/cubicajes/index-transformaciones.blade.php](resources/views/modulos/reportes/administrativos/cubicajes/index-transformaciones.blade.php)
- [resources/views/modulos/reportes/administrativos/cubicajes/index-calificaciones-viaje.blade.php](resources/views/modulos/reportes/administrativos/cubicajes/index-calificaciones-viaje.blade.php)

Ejemplo de vista de resultado:
```blade
<form id="formGenerarReporteCubicajes" method="POST">
    <!-- Datos del reporte mostrados en tabla -->
    <table>
        @foreach ($data as $registro)
            <tr>
                <td>{{ $registro->campo1 }}</td>
                <td>{{ $registro->campo2 }}</td>
            </tr>
        @endforeach
    </table>
    
    <!-- Botones para descargar en diferentes formatos -->
    <button type="button" onclick="generarReporteCubicajes('1')">
        <i class="fa-regular fa-file-pdf"></i> PDF
    </button>
    <button type="button" onclick="generarReporteCubicajes('2')">
        <i class="fa-regular fa-file-excel"></i> EXCEL
    </button>
    <button type="button" onclick="generarReporteCubicajes('3')">
        <i class="fa-solid fa-file-csv"></i> CSV
    </button>
</form>
```

---

### PASO 5: Usuario Descarga o Visualiza

**Función `generarReporteCubicajes(tipo)` en nueva ventana**:

```javascript
function generarReporteCubicajes(tipo_reporte) {
    switch (tipo_reporte) {
        case '1':
            $('#generar').val('1');  // PDF
            $('#formGenerarReporteCubicajes').submit();
            break;
        case '2':
            $('#generar').val('2');  // EXCEL
            $('#formGenerarReporteCubicajes').submit();
            break;
        case '3':
            $('#generar').val('3');  // CSV
            $('#formGenerarReporteCubicajes').submit();
            break;
    }
}
```

---

## 📊 DIAGRAMA DE FLUJO

```
Usuario en Vista Principal
    ↓
Selecciona tipo de reporte (dropdown)
    ↓ onchange="filtroCubicajes()"
Muestra campos dinámicamente (viaje o proveedor)
    ↓
Llena datos (viaje/proveedor, fechas)
    ↓
Click en "Generar reporte"
    ↓ onclick="reporteCubicajes()"
JavaScript valida datos
    ↓
Envía: $('#formReporteCubicajes').submit()
    ↓ (target="_blank" abre NUEVA VENTANA)
┌─────────────────────────────────────────┐
│ NUEVA VENTANA - En target="_blank"      │
├─────────────────────────────────────────┤
│  Controlador: reporteCubicajes()        │
│  ↓                                      │
│  Ejecuta consultaDatos()                │
│  ↓                                      │
│  Retorna vista con resultados           │
│  ↓                                      │
│  HTML + Tabla de datos                  │
│  + Botones (PDF/EXCEL/CSV)              │
│                                         │
│  Usuario hace click en PDF/EXCEL/CSV    │
│  ↓ onclick="generarReporteCubicajes()"  │
│  Descarga archivo                       │
└─────────────────────────────────────────┘
```

---

## 🔧 CÓMO CAMBIAR COMPORTAMIENTO

### Opción 1: Abrir en MISMA VENTANA (Sin nueva ventana)

**Cambiar en** [resources/views/modulos/reportes/administrativos/index.blade.php línea 138](resources/views/modulos/reportes/administrativos/index.blade.php#L138):

```blade
<!-- ❌ ACTUAL -->
<form action="{{ route('reporte-cubicajes') }}" 
      target="_blank"
      rel="noopener noreferrer">

<!-- ✅ CAMBIAR A -->
<form action="{{ route('reporte-cubicajes') }}">
      <!-- Elimina target="_blank" -->
```

**Efecto**: Cargará el reporte en la misma ventana/pestaña.

---

### Opción 2: Descargar DIRECTAMENTE (Sin vista intermedia)

**Cambiar en** [app/Http/Controllers/Reportes/Administrativos/ReporteCubicajesController.php línea 45](app/Http/Controllers/Reportes/Administrativos/ReporteCubicajesController.php#L45):

```php
// ❌ ACTUAL - Muestra vista HTML primero
} else {
    return view($datos[2], compact('data', 'encabezado', ...));
}

// ✅ CAMBIAR A - Descarga directo
} else {
    // Descargar XLSX directamente
    return Excel::download(new CubicajesExport($data), 
        "$encabezado-$desde-$hasta.xlsx");
}
```

---

## 📋 RESUMEN

| Aspecto | Dónde | Línea |
|--------|-------|-------|
| **Atributo que abre ventana** | Formulario HTML | [index.blade.php:138](resources/views/modulos/reportes/administrativos/index.blade.php#L138) |
| **Validación de datos** | JavaScript | [reportes-cubicajes.js:95](public/js/modulos/reportes/administrativos/reportes-cubicajes.js#L95) |
| **Envío del form** | JavaScript | [reportes-cubicajes.js:116](public/js/modulos/reportes/administrativos/reportes-cubicajes.js#L116) |
| **Procesamiento backend** | Controlador | [ReporteCubicajesController.php:28](app/Http/Controllers/Reportes/Administrativos/ReporteCubicajesController.php#L28) |
| **Opción de descargas** | Controlador | [ReporteCubicajesController.php:45-90](app/Http/Controllers/Reportes/Administrativos/ReporteCubicajesController.php#L45) |
| **Vistas de resultado** | Templates Blade | [resources/views/.../cubicajes/](resources/views/modulos/reportes/administrativos/cubicajes/) |

---

## 🎯 CONCLUSIÓN

**La nueva ventana se abre porque**:
1. El formulario tiene `target="_blank"` en HTML
2. JavaScript válida y envía el formulario con `.submit()`
3. Laravel retorna una vista HTML
4. El navegador la abre en nueva pestaña/ventana automáticamente

**Es intencional**: Permite que el usuario siga viendo la pantalla de filtros mientras visualiza/descarga el reporte.
