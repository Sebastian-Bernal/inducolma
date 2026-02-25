# Análisis: Agregar opción CALDERA como tipo de corte

Fecha: 2026-01-14

Resumen ejecutivo ✅

- Se detectó soporte parcial para la opción `CALDERA` en la aplicación (existe la vista `modulos.operaciones.trabajo-maquina.caldera` y el controlador `TrabajoMaquina::create()` ya la utiliza para mostrar la vista cuando la máquina tiene `corte == 'CALDERA'`).
- No obstante, `CALDERA` no está incluida en las validaciones, ni en los formularios de creación/edición de `Maquina`, y no está considerada en partes de la lógica de negocio (p. ej. `ProductosTerminados` trata explícitamente `ENSAMBLE` y podría necesitar incluir `CALDERA` si su comportamiento debe ser equivalente).
- Este documento resume los lugares a modificar, cambios propuestos, ejemplos de código y pruebas sugeridas.

Archivos clave localizados 🗂️

- Controladores / rutas / vistas:
  - `app/Http/Controllers/TrabajoMaquina.php` (ya contiene el caso para `CALDERA` en `create()`)
  - `resources/views/modulos/operaciones/trabajo-maquina/caldera.blade.php` (vista existente)
  - `resources/views/modulos/administrativo/costos/maquinas.blade.php` (modal crear máquina; falta opción CALDERA)
  - `resources/views/modulos/administrativo/costos/edit-maquinas.blade.php` (editar máquina; falta opción CALDERA)
  - `app/Http/Controllers/MaquinaController.php` (guarda `corte` en create/update)

- Validaciones:
  - `app/Http/Requests/StoreMaquinaRequest.php` (regla `in:` no incluye `CALDERA`)
  - `app/Http/Requests/UpdateMaquinaRequest.php` (regla `in:` no incluye `CALDERA`)

- Lógica de negocio:
  - `app/Repositories/ProductosTerminados.php` (en `agregarProducto()` se trata `ENSAMBLE` de forma especial; decidir si `CALDERA` debe comportarse igual)
  - `app/Http/Controllers/OrdenProduccionController.php` (agrupa máquinas por `corte` en programación de rutas)
  - `app/Repositories/Reportes/ConsultaProcesos.php` y controladores de reportes (filtros que usan `maquinas.corte != 'ENSAMBLE'` u `== 'ENSAMBLE'`)

- Migraciones / Modelo:
  - `app/Models/Maquina.php` (tiene `$fillable = ['id','maquina','corte']`)
  - `database/migrations/*add_corte_to_maquinas_table.php` (la columna `corte` ya existe)

Impacto y decisiones necesarias ⚠️

1) Validación y UI
- Decisión: Añadir `CALDERA` a las reglas `in:` y al `<select>` de creación/edición de `Maquina` para permitir crear/editar máquinas con éste corte. (Acción menor, segura)

2) Comportamiento al procesar producto
- Pregunta clave: ¿`CALDERA` debe actuar como `ENSAMBLE` (sincronizar pivot `pedido_producto`, decrementar insumos y preprocesados) o comportarse de forma distinta? Dependiendo de la respuesta:
  - Si SÍ: cambiar la condición en `ProductosTerminados::agregarProducto()` de `if ($corteMaquina == 'ENSAMBLE') { ... }` a `if (in_array($corteMaquina, ['ENSAMBLE','CALDERA'])) { ... }`.
  - Si NO: implementar una rama específica para `CALDERA` con la lógica requerida.

3) Programación de rutas — Pasos a seguir
- Observación: la ruta que dirige a la vista de ensamble es la ruta nombrada `trabajo-ensamble` (definida en `routes/web.php`) y manejada por `TrabajoMaquina::trabajoEnsamble`. Usaremos esta lógica como base para `CALDERA` y `ACABADOS_ENSAMBLE`.

Pasos prácticos:
1. Verificar que los registros `EnsambleAcabado` para pedidos tengan `maquina_id` correctamente asignada a las máquinas tipo `CALDERA` o `ACABADO_ENSAMBLE` cuando corresponda (si no existe, asignarlo al crear la ruta de producción).

2. Reusar la ruta `trabajo-ensamble` y el método `TrabajoMaquina::trabajoEnsamble` para procesar pedidos en `CALDERA` y `ACABADO_ENSAMBLE` cuando el flujo y la UI sean equivalentes. Asegurarse de que las vistas que listan los acabados (por ejemplo `caldera.blade.php`) generen enlaces a `route('trabajo-ensamble', $ensamble->pedido_id)` (ya lo hacen actualmente).

3. Si `CALDERA` requiere comportamiento o UI distinto, crear rutas y métodos explícitos (por ejemplo `trabajo-caldera` / `trabajoAcabado`) y en `TrabajoMaquina` implementar `trabajoCaldera()` o `trabajoAcabado()` que deleguen o repliquen la lógica de `trabajoEnsamble` pero usen vistas o validaciones específicas.

4. Actualizar la interfaz de programación (`seleccion-procesos.blade.php`) si deseas que `CALDERA` aparezca como una tarjeta/sección separada: añadir sección o incluirla en la agrupación de máquinas que corresponda.

5. Ajustar la lógica de asignación en `OrdenProduccionController::rutaProcesos()` o donde se guarden rutas para garantizar que la `maquina_id` asignada apunte a la máquina correcta según el tipo (`CALDERA`/`ACABADO_ENSAMBLE`).

6. Pruebas: crear máquina `CALDERA`, asignarla a un turno, comprobar que la lista en `caldera.blade.php` enlace correctamente y que al abrir `trabajo-ensamble` el controlador encuentre el `EnsambleAcabado` con `maquina_id` correcto; probar procesamiento y efectos en inventarios según la decisión de comportamiento.

7. Revisar reportes y consultas que filtran por `maquinas.corte` y actualizar condiciones para incluir/excluir `CALDERA` según sea necesario.

4) Reportes y consultas
- Revisar consultas que filtran por `corte` para incluir o excluir `CALDERA` según corresponda (p.ej. reportes que excluyen `ENSAMBLE` podrían necesitar incluir `CALDERA` o no).

Cambios propuestos (fragmentos de código) ✍️

1) Añadir `CALDERA` a validaciones

En `StoreMaquinaRequest.php` y `UpdateMaquinaRequest.php`, sustituir la regla `in:` por (ejemplo):

```php
'corte' => [
    'required',
    'string',
    'in:INICIAL,INTERMEDIO,FINAL,ACABADOS,ENSAMBLE,ASERRIO,ACABADO_ENSAMBLE,REASERRIO,CALDERA',
],
```

2) Añadir la opción en los selects de vistas (crear/editar)

En `resources/views/modulos/administrativo/costos/maquinas.blade.php` y `edit-maquinas.blade.php`:

```blade
<option value="CALDERA" {{ (isset($maquina) && $maquina->corte == 'CALDERA') ? 'selected' : '' }}>CALDERA</option>
```

3) Tratar `CALDERA` como `ENSAMBLE` (opcional, según decisión)

En `app/Repositories/ProductosTerminados.php`, dentro de `agregarProducto()`:

```php
$corteMaquina = Maquina::find($request->maquinaId)->corte;

if (in_array($corteMaquina, ['ENSAMBLE', 'CALDERA'])) {
    // lógica existente para ensamble
}
```

4) Incluir `CALDERA` en rutas de acabados (si corresponde)

En `RutaAcabadoProductoController` (o donde aplique):

```php
$maquinas = Maquina::whereIn('corte', ['ENSAMBLE', 'ACABADO_ENSAMBLE', 'CALDERA'])->get()->groupBy('corte')->toArray();
```

Checklist de implementación ✅

- [ ] Añadir `CALDERA` a `StoreMaquinaRequest` y `UpdateMaquinaRequest`.
- [ ] Añadir opción `<option value="CALDERA">CALDERA</option>` en `maquinas.blade.php` y `edit-maquinas.blade.php`.
- [ ] Decidir el comportamiento en `ProductosTerminados` (igual que `ENSAMBLE` o distinto) y aplicar cambios en consecuencia.
- [ ] Revisar `OrdenProduccionController` y vista `seleccion-procesos.blade.php` para decidir dónde incluir `CALDERA` en la programación de rutas.
- [ ] Revisar y ajustar consultas/ reportes que filtran por `corte` para incluir/excluir `CALDERA` según sea necesario.
- [ ] Pruebas manuales: crear máquina CALDERA, asignarla a turno, procesar un pedido y verificar flujo y reportes.
- [ ] (Opcional) Añadir pruebas automatizadas (unit/integración) para la nueva opción.

Pruebas sugeridas 🔬

- Crear una máquina con `corte = CALDERA` desde la UI (se deben mostrar las opciones tras aplicar cambios).
- Asignar turno a un usuario para esa máquina y comprobar que `TrabajoMaquina::index()` redirige a la vista `caldera` (ya existe).
- Procesar un pedido en esa máquina y verificar:
  - Si CALDERA se comporta como ENSAMBLE: el pedido sincroniza `pedido_producto`, se decrementan insumos y se actualiza `cantidad_producida` del ensamble.
  - Si CALDERA tiene comportamiento distinto: verificar la lógica nueva.
- Ejecutar reportes que antes excluían `ENSAMBLE` y confirmar si deben incluir o excluir CALDERA.

Notas y riesgos 🚨

- Agregar `CALDERA` a las validaciones y selects es de bajo riesgo.
- Cambiar comportamiento en `ProductosTerminados` puede afectar inventarios e insumos; requiere pruebas y/o backup de datos si se modifica en producción.

Siguientes pasos propuestos ➕

- Opción A (rápida): Implemento los cambios mínimos (validación y UI) y te dejo todo listo para que pruebes; no toco lógica de negocio hasta que confirmes el comportamiento deseado de `CALDERA`.
- Opción B (completa): Implemento cambios completos (incluyendo `ProductosTerminados` y ajustes en programación/reportes) y preparo pruebas manuales; requiere que confirmes si `CALDERA` debe comportarse como `ENSAMBLE`.

¿Quieres que aplique los cambios ahora? Indica si `CALDERA` debe funcionar igual que `ENSAMBLE` en `ProductosTerminados` o si quieres un comportamiento distinto.

---

Archivo creado en: `docs/ANALISIS_AGREGAR_CALDERA.md`

Si prefieres, puedo convertirlo en una tarea con checklist en Trello/GitHub Issues o aplicar directamente los cambios y dejar un commit/PR listo para revisión.