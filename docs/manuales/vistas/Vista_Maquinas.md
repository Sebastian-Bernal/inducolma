# Manual de Vista: Gestión de Máquinas

**Vista Index:** `resources/views/modulos/administrativo/costos/maquinas.blade.php`  
**Vista Edit:** `resources/views/modulos/administrativo/costos/edit-maquinas.blade.php`  
**Módulo:** Costos - Máquinas  
**Controlador:** MaquinaController

---

## 📋 Índice

1. [Vista Index - Listado de Máquinas](#vista-index---listado-de-máquinas)
2. [Vista Edit - Editar Máquina](#vista-edit---editar-máquina)
3. [Componentes Compartidos](#componentes-compartidos)
4. [Flujos de Interacción](#flujos-de-interacción)
5. [Assets y Dependencias](#assets-y-dependencias)

---

## Vista Index - Listado de Máquinas

### 1. Información General

**Ubicación:** `resources/views/modulos/administrativo/costos/maquinas.blade.php`

**Propósito:** Mostrar un listado completo de las máquinas registradas en el sistema y permitir la creación, edición y eliminación de las mismas.

**Ruta URL:** `/costos-maquina`  
**Nombre de Ruta:** `maquinas.index`  
**Método HTTP:** GET  
**Controlador:** `MaquinaController@index`

### 2. Estructura HTML

```blade
@extends('layouts.web')
@section('css')
    <!-- CSS de DataTables -->
@endsection
@section('title', ' Maquinas | inducolma')
@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <!-- Contenido principal -->
@endsection
@section('js')
    <!-- JavaScript de DataTables -->
@endsection
```

### 3. Layout Base

**Layout Extendido:** `layouts.web`

**Secciones Definidas:**
- `@section('css')`: Estilos específicos
- `@section('title')`: Título de la página
- `@section('submenu')`: Sidebar de navegación
- `@section('content')`: Contenido principal
- `@section('js')`: Scripts JavaScript

### 4. Componentes de la Vista

#### A. Header y Botón de Creación

```html
<div class="col-12 col-sm-10 col-lg-6 mx-auto">
    <h4 class="display-6">Crear Maquina</h4>
    <hr>
    
    <!-- Botón que activa el modal -->
    <button type="button" 
            class="btn btn-primary" 
            data-bs-toggle="modal" 
            data-bs-target="#creaMaquina">
        Crear maquina
    </button>
</div>
```

**Elementos:**
- Título principal con clase `display-6`
- Separador horizontal
- Botón Bootstrap 5 con trigger de modal

#### B. Manejo de Errores

```blade
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            - {{ $error }} <br>
        @endforeach
    </div>
@endif
```

**Comportamiento:**
- Muestra todos los errores de validación
- Aparece solo si hay errores en la sesión
- Usa clase Bootstrap `alert-danger`

### 5. Modal de Creación

#### Estructura del Modal

```html
<div class="modal fade" id="creaMaquina" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Header -->
            <!-- Body -->
            <!-- Footer -->
        </div>
    </div>
</div>
```

**Clases Bootstrap:**
- `modal fade`: Efecto de desvanecimiento
- `modal-dialog-centered`: Centrado vertical
- `modal-dialog-scrollable`: Scroll interno si es necesario

#### Modal Header

```html
<div class="modal-header">
    <h4 class="modal-title" id="exampleModalLabel">Crea Maquina</h4>
    <button type="button" 
            class="btn-close" 
            data-bs-dismiss="modal" 
            aria-label="Close">
    </button>
</div>
```

**Elementos:**
- Título del modal
- Botón de cierre (X)

#### Modal Body - Formulario

**Formulario Principal:**
```html
<form action="{{ route('maquinas.store') }}" method="POST">
    @csrf
    <!-- Campos del formulario -->
</form>
```

**Protección CSRF:** `@csrf` - Token de seguridad Laravel

##### Campo: Nombre de Máquina

```html
<div class="input-group mb-3">
    <span class="input-group-text">Maquina:</span>
    <input type="text"
           class="form-control text-uppercase"
           placeholder="Nombre maquina"
           name="maquina"
           id="maquina"
           required>
</div>
```

**Propiedades:**
| Atributo | Valor | Descripción |
|----------|-------|-------------|
| type | text | Campo de texto |
| class | form-control text-uppercase | Estilo Bootstrap + Mayúsculas |
| placeholder | "Nombre maquina" | Texto de ayuda |
| name | maquina | Nombre del campo para POST |
| id | maquina | Identificador único |
| required | - | Validación HTML5 |

**Clases CSS:**
- `form-control`: Estilo Bootstrap para inputs
- `text-uppercase`: Convierte visualmente a mayúsculas
- `input-group`: Agrupa label e input

##### Campo: Tipo de Corte

```html
<div class="input-group mb-3">
    <span class="input-group-text">Corte:</span>
    <select name="corte" id="corte" class="form-select" required>
        <option selected>Seleccione un tipo de corte</option>
        <option value="INICIAL">INICIAL</option>
        <option value="INTERMEDIO">INTERMEDIO</option>
        <option value="FINAL">FINAL</option>
        <option value="ACABADOS">ACABADOS DE ITEM</option>
        <option value="ASERRIO">ASERRIO</option>
        <option value="ENSAMBLE">ENSAMBLE</option>
        <option value="ACABADO_ENSAMBLE">ACAMBADOS DE ENSAMBLE</option>
        <option value="REASERRIO">REASERRIO</option>
    </select>
</div>
```

**Opciones Disponibles:**

| Valor | Etiqueta | Descripción del Proceso |
|-------|----------|-------------------------|
| INICIAL | INICIAL | Primer corte de troza |
| INTERMEDIO | INTERMEDIO | Cortes intermedios |
| FINAL | FINAL | Corte final de pieza |
| ACABADOS | ACABADOS DE ITEM | Acabados de items individuales |
| ASERRIO | ASERRIO | Proceso de aserrado |
| ENSAMBLE | ENSAMBLE | Ensamblaje de productos |
| ACABADO_ENSAMBLE | ACABADOS DE ENSAMBLE | Acabados de ensamblados |
| REASERRIO | REASERRIO | Re-aserrado de piezas |

**Propiedades:**
- `form-select`: Clase Bootstrap 5 para selects
- `required`: Validación obligatoria
- Primera opción como placeholder (sin value)

#### Modal Footer

```html
<div class="modal-footer">
    <button type="button" 
            class="btn btn-secondary" 
            data-bs-dismiss="modal">
        Cerrar
    </button>
    <button type="submit" 
            class="btn btn-primary">
        Guardar maquina
    </button>
</div>
```

**Botones:**
1. **Cerrar**: Cierra el modal sin guardar
2. **Guardar**: Envía el formulario (submit)

### 6. Tabla de Datos (DataTable)

#### Estructura de la Tabla

```html
<table id="listaMaquinas" 
       class="table table-bordered table-striped dt-responsive">
    <thead>
        <tr>
            <th>ID</th>
            <th>Maquina</th>
            <th>Tipo de corte</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($maquinas as $maquina)
            <tr>
                <!-- Datos de la máquina -->
            </tr>
        @endforeach
    </tbody>
</table>
```

**Clases CSS:**
- `table`: Clase base de tabla Bootstrap
- `table-bordered`: Bordes en todas las celdas
- `table-striped`: Filas alternadas con color
- `dt-responsive`: Responsivo de DataTables

#### Columnas de la Tabla

##### Columna 1: ID
```html
<td>{{ $maquina->id }}</td>
```
- Muestra el identificador único

##### Columna 2: Nombre de Máquina
```html
<td>{{ $maquina->maquina }}</td>
```
- Muestra el nombre (en mayúsculas)

##### Columna 3: Tipo de Corte
```html
<td>{{ $maquina->corte }}</td>
```
- Muestra el tipo de proceso

##### Columna 4: Acciones

```html
<td>
    <div class="d-flex align-items-center">
        <!-- Formulario de Eliminación -->
        <form action="{{ route('maquinas.destroy', $maquina) }}" 
              method="POST">
            @method('DELETE')
            @csrf
            <input type="submit"
                   value="Elminar"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('¿desea eliminar la maquina: {{ $maquina->maquina }}?')">
        </form>
        
        <!-- Botón de Edición -->
        <a href="{{ route('maquinas.edit', $maquina) }}" 
           class="btn btn-sm btn-warning">
            Editar
        </a>
    </div>
</td>
```

**Botón Eliminar:**
- Método: DELETE (spoofing con `@method('DELETE')`)
- Confirmación JavaScript antes de enviar
- Clase: `btn-danger` (rojo)
- Size: `btn-sm` (pequeño)

**Botón Editar:**
- Link a ruta de edición
- Clase: `btn-warning` (amarillo)
- Size: `btn-sm` (pequeño)

**Layout:**
- `d-flex`: Display flex
- `align-items-center`: Alineación vertical centrada

### 7. JavaScript - Inicialización DataTables

```javascript
$(document).ready(function() {
    $('#listaMaquinas').DataTable({
        "language": {
            "url": "/DataTables/Spanish.json"
        },
        "responsive": true
    });
});
```

**Configuración:**

| Opción | Valor | Descripción |
|--------|-------|-------------|
| language.url | /DataTables/Spanish.json | Traduce la interfaz al español |
| responsive | true | Habilita diseño responsivo |

**Funcionalidades Activadas:**
- ✅ Paginación
- ✅ Búsqueda en tiempo real
- ✅ Ordenamiento por columnas
- ✅ Control de registros por página
- ✅ Información de registros
- ✅ Responsive en móviles

### 8. CSS Externo

```blade
@section('css')
<link rel="stylesheet" 
      type="text/css" 
      href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css"/>
@endsection
```

**Dependencia:** DataTables 1.11.4 con tema Bootstrap 5

### 9. Variables Blade Recibidas

| Variable | Tipo | Descripción | Origen |
|----------|------|-------------|--------|
| `$maquinas` | Collection | Colección de objetos Maquina | MaquinaController@index |

**Estructura de `$maquina`:**
```php
{
    "id": 1,
    "maquina": "SIERRA CIRCULAR",
    "corte": "INICIAL",
    "created_at": "2026-01-30 10:00:00",
    "updated_at": "2026-01-30 10:00:00"
}
```

### 10. Mensajes de Usuario

#### Mensaje de Éxito
```blade
@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
```

**Tipos de Mensajes:**
- "Maquina creada con éxito"
- "Maquina [NOMBRE] actualizada con éxito"

#### Mensaje de Error
```blade
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            - {{ $error }} <br>
        @endforeach
    </div>
@endif
```

---

## Vista Edit - Editar Máquina

### 1. Información General

**Ubicación:** `resources/views/modulos/administrativo/costos/edit-maquinas.blade.php`

**Propósito:** Editar los datos de una máquina existente.

**Ruta URL:** `/costos-maquina/{maquina}/edit`  
**Nombre de Ruta:** `maquinas.edit`  
**Método HTTP:** GET  
**Controlador:** `MaquinaController@edit`

### 2. Estructura HTML

```blade
@extends('layouts.web')
@section('title', 'Editar maquina | Inducolma')
@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <!-- Formulario de edición -->
@endsection
```

### 3. Formulario de Edición

#### Estructura Principal

```html
<form action="{{ route('maquinas.update', $maquina->id) }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <!-- Campos del formulario -->
</form>
```

**Características:**
- **Action:** Ruta `maquinas.update` con ID de máquina
- **Method:** PATCH (spoofing desde POST)
- **CSRF:** Token de protección
- **Enctype:** multipart/form-data (aunque no se usan archivos)

#### Layout del Formulario

```html
<div class="container">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Header -->
            <!-- Body -->
            <!-- Footer -->
        </div>
    </div>
</div>
```

**Nota:** Usa clases de modal pero SIN modal real (formulario en página completa)

#### Header

```html
<div class="modal-header">
    <h4 class="modal-title">Editar Maquina</h4>
</div>
```

### 4. Campos del Formulario

#### Campo: Nombre de Máquina

```html
<div class="input-group mb-3">
    <span class="input-group-text">Maquina:</span>
    <input type="text"
           class="form-control text-uppercase"
           placeholder="Nombre maquina"
           name="maquina"
           id="maquina"
           required
           value="{{ $maquina->maquina }}">
</div>
```

**Diferencia con Create:**
- Incluye `value="{{ $maquina->maquina }}"` con valor actual

#### Campo: Tipo de Corte

```html
<div class="input-group mb-3">
    <span class="input-group-text">Corte:</span>
    <select name="corte" id="corte" class="form-select" required>
        <option selected>Seleccione un tipo de corte</option>
        <option value="INICIAL" {{ $maquina->corte == 'INICIAL' ? 'selected' : '' }}>
            INICIAL
        </option>
        <option value="INTERMEDIO" {{ $maquina->corte == 'INTERMEDIO' ? 'selected' : '' }}>
            INTERMEDIO
        </option>
        <!-- ... más opciones ... -->
    </select>
</div>
```

**Diferencia con Create:**
- Cada opción verifica si es el valor actual con operador ternario
- Marca como `selected` la opción correspondiente

**Lógica de Selección:**
```php
{{ $maquina->corte == 'INICIAL' ? 'selected' : '' }}
```
- Si el corte actual es 'INICIAL', añade atributo `selected`
- Si no, no añade nada

### 5. Manejo de Errores

```blade
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            - {{ $error }} <br>
        @endforeach
    </div>
@endif
```

**Ubicación:** Antes del footer del formulario

### 6. Footer con Botones

```html
<div class="modal-footer">
    <a href="{{ route('maquinas.index') }}" 
       class="btn btn-secondary">
        Volver
    </a>
    <button type="submit" 
            class="btn btn-primary">
        Modificar maquina
    </button>
</div>
```

**Botones:**
1. **Volver**: Link a índice de máquinas (cancela edición)
2. **Modificar**: Submit del formulario

### 7. Variables Blade Recibidas

| Variable | Tipo | Descripción | Origen |
|----------|------|-------------|--------|
| `$maquina` | Maquina | Objeto con datos de la máquina | MaquinaController@edit |

---

## Componentes Compartidos

### 1. Sidebar de Navegación

```blade
@include('modulos.sidebars.costos-side')
```

**Ubicación:** `resources/views/modulos/sidebars/costos-side.blade.php`

**Propósito:** Menú lateral para el módulo de costos

### 2. Layout Web

```blade
@extends('layouts.web')
```

**Ubicación:** `resources/views/layouts/web.blade.php`

**Características:**
- Header del sistema
- Barra de navegación principal
- Footer
- Includes de CSS y JS base
- Autenticación

---

## Flujos de Interacción

### Flujo 1: Crear Nueva Máquina

```
1. Usuario accede a /costos-maquina
   ↓
2. Vista muestra listado de máquinas
   ↓
3. Usuario click en "Crear maquina"
   ↓
4. Modal #creaMaquina se abre (Bootstrap)
   ↓
5. Usuario llena campos:
   - Nombre de máquina
   - Tipo de corte
   ↓
6. Usuario click "Guardar maquina"
   ↓
7. Validación HTML5 (required)
   ↓
8. POST a /costos-maquina
   ↓
9. Validación backend (StoreMaquinaRequest)
   ↓
10. Si válido:
    - Crear registro en BD
    - Redirect con mensaje éxito
    - Modal se cierra
    - Tabla se actualiza
    ↓
11. Si inválido:
    - Redirect back con errores
    - Modal permanece abierto
    - Errores se muestran
```

### Flujo 2: Editar Máquina Existente

```
1. Usuario en listado de máquinas
   ↓
2. Usuario click "Editar" en fila específica
   ↓
3. GET /costos-maquina/{id}/edit
   ↓
4. Vista edit-maquinas.blade.php se carga
   ↓
5. Formulario pre-llenado con datos actuales
   ↓
6. Usuario modifica campos
   ↓
7. Usuario click "Modificar maquina"
   ↓
8. Validación HTML5
   ↓
9. PATCH /costos-maquina/{id}
   ↓
10. Validación backend (UpdateMaquinaRequest)
    ↓
11. Si válido:
    - Actualizar registro en BD
    - Redirect a index con mensaje
    ↓
12. Si inválido:
    - Redirect back con errores
    - Formulario mantiene datos
```

### Flujo 3: Eliminar Máquina

```
1. Usuario en listado de máquinas
   ↓
2. Usuario click "Eliminar" en fila
   ↓
3. Confirmación JavaScript:
   "¿desea eliminar la maquina: [NOMBRE]?"
   ↓
4. Si acepta:
   DELETE /costos-maquina/{id}
   ↓
5. Backend verifica relaciones
   ↓
6. Si NO tiene relaciones:
   - Eliminar de BD
   - Redirect back
   - Fila desaparece de tabla
   ↓
7. Si TIENE relaciones:
   - NO eliminar
   - Redirect back con error
   - Mensaje mostrado al usuario
   ↓
8. Si cancela:
   - No hace nada
   - Permanece en listado
```

### Flujo 4: Búsqueda en DataTable

```
1. Usuario escribe en campo de búsqueda
   ↓
2. DataTables filtra en tiempo real
   ↓
3. Solo muestra filas que coinciden
   ↓
4. Si no hay coincidencias:
   - Muestra "No matching records found"
   ↓
5. Si limpia búsqueda:
   - Muestra todos los registros de nuevo
```

### Flujo 5: Ordenamiento por Columna

```
1. Usuario click en header de columna
   ↓
2. DataTables ordena ascendente
   ↓
3. Click de nuevo:
   - Ordena descendente
   ↓
4. Click una vez más:
   - Vuelve a orden original
```

---

## Assets y Dependencias

### 1. CSS Requerido

#### DataTables Bootstrap 5
```html
<link rel="stylesheet" 
      href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css"/>
```

#### Bootstrap 5 (desde layout)
```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" 
      rel="stylesheet"/>
```

### 2. JavaScript Requerido

#### jQuery (debe cargarse primero)
```html
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
```

#### Bootstrap 5 Bundle
```html
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
```

#### DataTables
```html
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
```

### 3. Archivos de Traducción

#### DataTables Spanish
```
/public/DataTables/Spanish.json
```

**Contenido esperado:**
```json
{
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sSearch": "Buscar:",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    }
}
```

---

## Responsive Design

### Breakpoints

#### Columna del Formulario
```html
<div class="col-12 col-sm-10 col-lg-6 mx-auto">
```

**Comportamiento:**
- **Móvil (< 576px):** col-12 (100% ancho)
- **Tablet (≥ 576px):** col-sm-10 (83.33% ancho)
- **Desktop (≥ 992px):** col-lg-6 (50% ancho)
- `mx-auto`: Centrado horizontal

#### Tabla DataTables
```html
<table class="dt-responsive">
```

**Características:**
- En móviles: Columnas se colapsan
- Botón "+" para expandir detalles
- Scroll horizontal si es necesario

---

## Validaciones Frontend

### 1. Validación HTML5

```html
<input required>
<select required>
```

**Comportamiento:**
- Navegador previene submit si está vacío
- Muestra mensaje nativo del navegador

### 2. Confirmación de Eliminación

```javascript
onclick="return confirm('¿desea eliminar la maquina: {{ $maquina->maquina }}?')"
```

**Flujo:**
- Muestra diálogo del navegador
- Si usuario acepta: `return true` → form se envía
- Si usuario cancela: `return false` → form NO se envía

### 3. Conversión a Mayúsculas

```html
<input class="text-uppercase">
```

**Comportamiento:**
- CSS transform: Muestra texto en mayúsculas
- Valor real puede estar en minúsculas
- Backend hace conversión definitiva con `strtoupper()`

---

## Mejoras Sugeridas

### UX/UI
1. ✅ Agregar SweetAlert en lugar de confirm() nativo
2. ✅ Feedback visual al guardar (spinner, disable button)
3. ✅ Validación en tiempo real con JavaScript
4. ✅ Toasts para mensajes de éxito/error
5. ✅ Iconos en botones (FontAwesome)

### Funcionalidad
1. ✅ Exportar tabla a Excel/PDF
2. ✅ Filtros adicionales por tipo de corte
3. ✅ Búsqueda avanzada
4. ✅ Ordenamiento multi-columna
5. ✅ Vista previa antes de eliminar

### Accesibilidad
1. ✅ Agregar labels con `for` apropiados
2. ✅ Mejorar atributos ARIA
3. ✅ Navegación con teclado
4. ✅ Alto contraste para lectores de pantalla

### Performance
1. ✅ Paginación server-side para muchos registros
2. ✅ Lazy loading de DataTables
3. ✅ Cache de consultas frecuentes

---

## Troubleshooting

### Problema: DataTable no se inicializa

**Síntomas:**
- Tabla muestra todos los registros sin paginación
- No aparece campo de búsqueda

**Soluciones:**
```javascript
// Verificar que jQuery se cargó
console.log(typeof jQuery); // debe ser "function"

// Verificar que DataTables se cargó
console.log($.fn.DataTable); // debe existir

// Error de ID duplicado
$('#listaMaquinas').DataTable(); // Verificar que ID es único
```

### Problema: Modal no se abre

**Síntomas:**
- Click en botón no hace nada
- Modal no aparece

**Soluciones:**
```javascript
// Verificar Bootstrap JS
console.log(typeof bootstrap); // debe ser "object"

// Verificar ID del modal
data-bs-target="#creaMaquina" // debe coincidir con ID del modal

// Verificar que no hay errores JS
// Abrir consola de desarrollador (F12)
```

### Problema: Formulario no se envía

**Síntomas:**
- Click en submit no hace nada
- Validación no funciona

**Soluciones:**
```html
<!-- Verificar token CSRF -->
@csrf

<!-- Verificar atributo action -->
<form action="{{ route('maquinas.store') }}" method="POST">

<!-- Verificar button type -->
<button type="submit">Guardar</button>
```

---

**Última actualización:** 30 de Enero, 2026  
**Versión:** 1.0  
**Autor:** Sistema de Documentación Inducolma
