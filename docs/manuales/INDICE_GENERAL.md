# Índice General - Sistema Industrial Inducolma

**Fecha de creación:** 30 de Enero, 2026  
**Proyecto:** Sistema de Gestión Industrial - Inducolma  
**Framework:** Laravel

---

## 📋 Tabla de Contenidos

1. [Descripción General del Sistema](#descripción-general-del-sistema)
2. [Estructura de Rutas](#estructura-de-rutas)
3. [Módulos del Sistema](#módulos-del-sistema)
4. [Controladores Documentados](#controladores-documentados)
5. [Vistas Documentadas](#vistas-documentadas)
6. [Tests Unitarios](#tests-unitarios)

---

## Descripción General del Sistema

Sistema integral de gestión industrial para la empresa Inducolma, que maneja:
- Control de costos operacionales
- Gestión de maderas y entradas
- Pedidos y clientes
- Producción y procesos
- Reportes administrativos y operativos
- Control de personal y turnos

---

## Estructura de Rutas

El sistema cuenta con **37 controladores principales** distribuidos en los siguientes módulos:

### 1. 💰 Módulo de Costos
- **MaquinaController** - Gestión de costos de maquinaria
- **OperacionController** - Gestión de costos de operaciones
- **DescripcionController** - Gestión de descripciones de costos
- **CostosOperacionController** - Costos operacionales detallados
- **CostosInfraestructuraController** - Costos de infraestructura

📄 **Documentación:** [Ver Módulo de Costos](./rutas/MODULO_COSTOS.md)

### 2. 👥 Módulo de Gestión de Usuarios y Roles
- **UsuarioController** - Gestión de usuarios del sistema
- **RolController** - Gestión de roles y permisos
- **ContratistaController** - Gestión de contratistas

📄 **Documentación:** [Ver Módulo de Usuarios](./rutas/MODULO_USUARIOS.md)

### 3. 🌳 Módulo de Maderas
- **MaderaController** - Catálogo de maderas
- **TipoMaderaController** - Tipos de madera
- **EntradaMaderaController** - Control de entradas de madera
- **CalificacionMaderaController** - Calificación de maderas
- **CubicajeController** - Cálculo de cubicajes

📄 **Documentación:** [Ver Módulo de Maderas](./rutas/MODULO_MADERAS.md)

### 4. 📦 Módulo de Proveedores e Insumos
- **ProveedorController** - Gestión de proveedores
- **InsumosAlmacenController** - Control de inventario de insumos

📄 **Documentación:** [Ver Módulo de Proveedores](./rutas/MODULO_PROVEEDORES.md)

### 5. 🛒 Módulo de Ventas
- **ClienteController** - Gestión de clientes
- **PedidoController** - Gestión de pedidos
- **ItemController** - Catálogo de items

📄 **Documentación:** [Ver Módulo de Ventas](./rutas/MODULO_VENTAS.md)

### 6. 🎨 Módulo de Diseños
- **DisenoProductoFinalController** - Diseños de productos finales
- **DisenoItemController** - Diseños de items
- **DisenoInsumoController** - Diseños de insumos

📄 **Documentación:** [Ver Módulo de Diseños](./rutas/MODULO_DISENOS.md)

### 7. 🏭 Módulo de Producción
- **OrdenProduccionController** - Órdenes de producción
- **ProcesoController** - Gestión de procesos
- **SubprocesoController** - Gestión de subprocesos
- **TrabajoMaquina** - Control de trabajo en máquinas
- **RutaAcabadoProductoController** - Rutas de acabado
- **SobranteTrozasController** - Control de sobrantes

📄 **Documentación:** [Ver Módulo de Producción](./rutas/MODULO_PRODUCCION.md)

### 8. ⏰ Módulo de Personal y Turnos
- **RecepcionController** - Control de recepción de personal
- **TurnoController** - Gestión de turnos
- **TurnoUsuarioController** - Asignación de turnos a usuarios

📄 **Documentación:** [Ver Módulo de Personal](./rutas/MODULO_PERSONAL.md)

### 9. 📊 Módulo de Reportes
- **ReporteController** - Reportes generales
- **ReporteCubicajesController** - Reportes de cubicajes
- **ReportePersonalController** - Reportes de personal
- **ReportePedidosController** - Reportes de pedidos
- **ProcesoConstruccionController** - Reportes de procesos
- **ReporteCostosController** - Reportes de costos

📄 **Documentación:** [Ver Módulo de Reportes](./rutas/MODULO_REPORTES.md)

### 10. 📋 Módulo de Catálogos
- **EstadoController** - Gestión de estados
- **EventoController** - Gestión de eventos
- **TipoEventoController** - Tipos de eventos

📄 **Documentación:** [Ver Módulo de Catálogos](./rutas/MODULO_CATALOGOS.md)

---

## Controladores Documentados

| # | Controlador | Estado | Documentación | Tests |
|---|-------------|--------|---------------|-------|
| 1 | MaquinaController | ✅ | [Ver](./controladores/MaquinaController.md) | [Ver](./tests/MaquinaControllerTest.md) |
| 2 | OperacionController | ⏳ | [Ver](./controladores/OperacionController.md) | [Ver](./tests/OperacionControllerTest.md) |
| 3 | DescripcionController | ⏳ | [Ver](./controladores/DescripcionController.md) | [Ver](./tests/DescripcionControllerTest.md) |
| 4 | CostosOperacionController | ⏳ | [Ver](./controladores/CostosOperacionController.md) | [Ver](./tests/CostosOperacionControllerTest.md) |
| 5 | CostosInfraestructuraController | ⏳ | [Ver](./controladores/CostosInfraestructuraController.md) | [Ver](./tests/CostosInfraestructuraControllerTest.md) |
| 6 | UsuarioController | ⏳ | [Ver](./controladores/UsuarioController.md) | [Ver](./tests/UsuarioControllerTest.md) |
| 7 | RolController | ⏳ | [Ver](./controladores/RolController.md) | [Ver](./tests/RolControllerTest.md) |
| 8 | ProveedorController | ⏳ | [Ver](./controladores/ProveedorController.md) | [Ver](./tests/ProveedorControllerTest.md) |
| 9 | MaderaController | ⏳ | [Ver](./controladores/MaderaController.md) | [Ver](./tests/MaderaControllerTest.md) |
| 10 | EntradaMaderaController | ⏳ | [Ver](./controladores/EntradaMaderaController.md) | [Ver](./tests/EntradaMaderaControllerTest.md) |
| 11 | CubicajeController | ⏳ | [Ver](./controladores/CubicajeController.md) | [Ver](./tests/CubicajeControllerTest.md) |
| 12 | ClienteController | ⏳ | [Ver](./controladores/ClienteController.md) | [Ver](./tests/ClienteControllerTest.md) |
| 13 | PedidoController | ⏳ | [Ver](./controladores/PedidoController.md) | [Ver](./tests/PedidoControllerTest.md) |
| 14 | ItemController | ⏳ | [Ver](./controladores/ItemController.md) | [Ver](./tests/ItemControllerTest.md) |
| 15 | DisenoProductoFinalController | ⏳ | [Ver](./controladores/DisenoProductoFinalController.md) | [Ver](./tests/DisenoProductoFinalControllerTest.md) |
| 16 | OrdenProduccionController | ⏳ | [Ver](./controladores/OrdenProduccionController.md) | [Ver](./tests/OrdenProduccionControllerTest.md) |
| 17 | ProcesoController | ⏳ | [Ver](./controladores/ProcesoController.md) | [Ver](./tests/ProcesoControllerTest.md) |
| 18 | RecepcionController | ⏳ | [Ver](./controladores/RecepcionController.md) | [Ver](./tests/RecepcionControllerTest.md) |
| 19 | TurnoController | ⏳ | [Ver](./controladores/TurnoController.md) | [Ver](./tests/TurnoControllerTest.md) |
| 20 | TrabajoMaquina | ⏳ | [Ver](./controladores/TrabajoMaquina.md) | [Ver](./tests/TrabajoMaquinaTest.md) |

... (y más controladores)

---

## Vistas Documentadas

Las vistas están organizadas por módulo en la carpeta `resources/views/modulos/`

### Estructura de Vistas
```
resources/views/
├── auth/
│   └── login.blade.php
├── modulos/
│   ├── maquinas.blade.php
│   ├── reportes/
│   │   ├── administrativos/
│   │   ├── ventas/
│   │   ├── procesos/
│   │   └── costos/
│   └── [otros módulos]/
└── layouts/
```

📁 **Ver documentación completa:** [Índice de Vistas](./vistas/INDICE_VISTAS.md)

---

## Tests Unitarios

Los tests están organizados siguiendo la estructura de los controladores.

### Ejecución de Tests

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter MaquinaControllerTest

# Ejecutar con cobertura
php artisan test --coverage
```

📋 **Ver guía completa:** [Guía de Tests](./tests/GUIA_TESTS.md)

---

## Leyenda

- ✅ Documentado y revisado
- ⏳ Pendiente de documentación
- 🔄 En proceso de actualización
- ❌ Requiere revisión

---

## Notas de Implementación

### Middleware Aplicado
- Todas las rutas (excepto login y auth) requieren autenticación: `middleware('auth')`
- No hay registro de usuarios habilitado: `'register' => false`

### Patrones de Rutas
- **Resource Routes**: Implementan CRUD completo (index, create, store, show, edit, update, destroy)
- **Custom Routes**: Rutas específicas para funcionalidades especiales
- **API Routes**: Algunas rutas están diseñadas para responder con JSON

### Convenciones de Nombres
- **Rutas**: Se usa nomenclatura en español con guiones (ej: `costos-maquina`)
- **Parámetros**: Se especifican explícitamente (ej: `parameters(['usuarios' => 'usuario'])`)
- **Nombres de rutas**: Se usa notación de punto (ej: `maquinas.index`)

---

## Actualizaciones

| Fecha | Descripción | Autor |
|-------|-------------|-------|
| 2026-01-30 | Creación del índice general y estructura inicial | Sistema |

---

**Última actualización:** 30 de Enero, 2026
