# Tests Unitarios: MaquinaController

**Controlador:** MaquinaController  
**Ubicación Test:** `tests/Feature/MaquinaControllerTest.php`  
**Framework:** PHPUnit + Laravel Testing

---

## 📋 Índice

1. [Configuración Inicial](#configuración-inicial)
2. [Tests Propuestos](#tests-propuestos)
3. [Implementación Completa](#implementación-completa)
4. [Ejecución de Tests](#ejecución-de-tests)
5. [Cobertura Esperada](#cobertura-esperada)

---

## Configuración Inicial

### Archivo de Test

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Maquina;
use App\Models\User;
use App\Models\CostosOperacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class MaquinaControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear usuario administrador para tests
        $this->admin = User::factory()->create(['role' => 'admin']);
        
        // Crear usuario normal para tests de autorización
        $this->user = User::factory()->create(['role' => 'user']);
    }
}
```

---

## Tests Propuestos

### Suite de Tests

| # | Nombre del Test | Propósito | Prioridad |
|---|----------------|-----------|-----------|
| 1 | test_admin_puede_ver_listado_maquinas | Verificar acceso a index | Alta |
| 2 | test_usuario_no_admin_no_puede_ver_listado | Verificar autorización | Alta |
| 3 | test_admin_puede_crear_maquina | Verificar creación | Alta |
| 4 | test_maquina_se_guarda_en_mayusculas | Verificar transformación | Media |
| 5 | test_validacion_campos_requeridos_al_crear | Verificar validaciones | Alta |
| 6 | test_validacion_tipo_corte_valido | Verificar tipos permitidos | Media |
| 7 | test_admin_puede_editar_maquina | Verificar actualización | Alta |
| 8 | test_admin_puede_eliminar_maquina_sin_relaciones | Verificar eliminación | Alta |
| 9 | test_no_puede_eliminar_maquina_con_relaciones | Verificar protección | Alta |
| 10 | test_usuario_no_admin_no_puede_crear_maquina | Verificar autorización | Alta |
| 11 | test_usuario_no_admin_no_puede_editar_maquina | Verificar autorización | Alta |
| 12 | test_usuario_no_admin_no_puede_eliminar_maquina | Verificar autorización | Alta |

---

## Implementación Completa

### 1. Test: Admin puede ver listado de máquinas

```php
/** @test */
public function test_admin_puede_ver_listado_maquinas()
{
    // Arrange: Crear algunas máquinas
    Maquina::factory()->count(3)->create();
    
    // Act: Acceder a la ruta como admin
    $response = $this->actingAs($this->admin)
                     ->get(route('maquinas.index'));
    
    // Assert: Verificar respuesta
    $response->assertStatus(200);
    $response->assertViewIs('modulos.administrativo.costos.maquinas');
    $response->assertViewHas('maquinas');
    
    // Verificar que se muestran las 3 máquinas
    $maquinas = $response->viewData('maquinas');
    $this->assertCount(3, $maquinas);
}
```

**Objetivo:** Verificar que un administrador puede acceder al listado de máquinas.

**Verifica:**
- ✅ Status 200 OK
- ✅ Vista correcta cargada
- ✅ Variable `$maquinas` existe en la vista
- ✅ Cantidad correcta de registros

---

### 2. Test: Usuario no admin no puede ver listado

```php
/** @test */
public function test_usuario_no_admin_no_puede_ver_listado()
{
    // Arrange: Usuario normal (no admin)
    
    // Act & Assert: Intentar acceder como usuario normal
    $response = $this->actingAs($this->user)
                     ->get(route('maquinas.index'));
    
    // Debe retornar 403 Forbidden
    $response->assertStatus(403);
}
```

**Objetivo:** Verificar que usuarios sin rol admin son rechazados.

**Verifica:**
- ✅ Status 403 Forbidden
- ✅ Autorización funcionando correctamente

---

### 3. Test: Admin puede crear máquina

```php
/** @test */
public function test_admin_puede_crear_maquina()
{
    // Arrange: Datos de la nueva máquina
    $datosForm datos = [
        'maquina' => 'Sierra Circular',
        'corte' => 'INICIAL'
    ];
    
    // Act: Enviar petición POST
    $response = $this->actingAs($this->admin)
                     ->post(route('maquinas.store'), $datosMaquina);
    
    // Assert: Verificar redirección y registro en BD
    $response->assertRedirect();
    $response->assertSessionHas('status', 'Maquina creada con éxito');
    
    // Verificar que existe en la base de datos
    $this->assertDatabaseHas('maquinas', [
        'maquina' => 'SIERRA CIRCULAR', // En mayúsculas
        'corte' => 'INICIAL'
    ]);
}
```

**Objetivo:** Verificar que se puede crear una máquina correctamente.

**Verifica:**
- ✅ Redirección exitosa
- ✅ Mensaje flash en sesión
- ✅ Registro creado en base de datos
- ✅ Datos correctos guardados

---

### 4. Test: Máquina se guarda en mayúsculas

```php
/** @test */
public function test_maquina_se_guarda_en_mayusculas()
{
    // Arrange: Datos en minúsculas
    $datosMaquina = [
        'maquina' => 'sierra circular',
        'corte' => 'inicial'
    ];
    
    // Act: Crear máquina
    $response = $this->actingAs($this->admin)
                     ->post(route('maquinas.store'), $datosMaquina);
    
    // Assert: Verificar conversión a mayúsculas
    $this->assertDatabaseHas('maquinas', [
        'maquina' => 'SIERRA CIRCULAR',
        'corte' => 'INICIAL'
    ]);
    
    // Verificar que NO existe en minúsculas
    $this->assertDatabaseMissing('maquinas', [
        'maquina' => 'sierra circular'
    ]);
}
```

**Objetivo:** Verificar que la transformación a mayúsculas funciona.

**Verifica:**
- ✅ Función `strtoupper()` aplicada
- ✅ Datos almacenados en mayúsculas
- ✅ Consistencia de datos

---

### 5. Test: Validación de campos requeridos

```php
/** @test */
public function test_validacion_campos_requeridos_al_crear()
{
    // Arrange: Datos incompletos
    $datosInvalidos = [
        'maquina' => '', // Vacío
        'corte' => ''    // Vacío
    ];
    
    // Act: Intentar crear con datos inválidos
    $response = $this->actingAs($this->admin)
                     ->post(route('maquinas.store'), $datosInvalidos);
    
    // Assert: Verificar errores de validación
    $response->assertSessionHasErrors(['maquina', 'corte']);
    
    // Verificar que NO se creó en la BD
    $this->assertDatabaseCount('maquinas', 0);
}
```

**Objetivo:** Verificar que los campos requeridos son validados.

**Verifica:**
- ✅ Errores de validación presentes
- ✅ No se crea registro inválido
- ✅ FormRequest funciona correctamente

---

### 6. Test: Validación de tipo de corte válido

```php
/** @test */
public function test_validacion_tipo_corte_valido()
{
    // Arrange: Tipo de corte inválido
    $datosInvalidos = [
        'maquina' => 'Sierra Circular',
        'corte' => 'TIPO_INVALIDO' // No está en la lista permitida
    ];
    
    // Act: Intentar crear
    $response = $this->actingAs($this->admin)
                     ->post(route('maquinas.store'), $datosInvalidos);
    
    // Assert: Error de validación en 'corte'
    $response->assertSessionHasErrors('corte');
    
    // Verificar tipos válidos
    $tiposValidos = [
        'INICIAL', 'INTERMEDIO', 'FINAL', 'ACABADOS', 
        'ASERRIO', 'ENSAMBLE', 'ACABADO_ENSAMBLE', 'REASERRIO'
    ];
    
    foreach ($tiposValidos as $tipo) {
        $response = $this->actingAs($this->admin)
                         ->post(route('maquinas.store'), [
                             'maquina' => "Maquina $tipo",
                             'corte' => $tipo
                         ]);
        
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('maquinas', ['corte' => $tipo]);
    }
}
```

**Objetivo:** Verificar que solo se aceptan tipos de corte válidos.

**Verifica:**
- ✅ Tipos inválidos son rechazados
- ✅ Todos los tipos válidos son aceptados
- ✅ Validación de enum funciona

---

### 7. Test: Admin puede editar máquina

```php
/** @test */
public function test_admin_puede_editar_maquina()
{
    // Arrange: Crear una máquina
    $maquina = Maquina::factory()->create([
        'maquina' => 'MAQUINA ORIGINAL',
        'corte' => 'INICIAL'
    ]);
    
    // Datos actualizados
    $datosActualizados = [
        'maquina' => 'maquina actualizada',
        'corte' => 'INTERMEDIO'
    ];
    
    // Act: Actualizar máquina
    $response = $this->actingAs($this->admin)
                     ->patch(route('maquinas.update', $maquina), $datosActualizados);
    
    // Assert: Verificar redirección y actualización
    $response->assertRedirect(route('maquinas.index'));
    $response->assertSessionHas('status');
    
    // Verificar cambios en BD
    $this->assertDatabaseHas('maquinas', [
        'id' => $maquina->id,
        'maquina' => 'MAQUINA ACTUALIZADA',
        'corte' => 'INTERMEDIO'
    ]);
    
    // Verificar que el valor antiguo ya no existe
    $this->assertDatabaseMissing('maquinas', [
        'id' => $maquina->id,
        'maquina' => 'MAQUINA ORIGINAL'
    ]);
}
```

**Objetivo:** Verificar que se puede actualizar una máquina.

**Verifica:**
- ✅ Actualización exitosa
- ✅ Redirección correcta
- ✅ Conversión a mayúsculas en update
- ✅ Datos antiguos reemplazados

---

### 8. Test: Admin puede eliminar máquina sin relaciones

```php
/** @test */
public function test_admin_puede_eliminar_maquina_sin_relaciones()
{
    // Arrange: Crear máquina sin relaciones
    $maquina = Maquina::factory()->create();
    
    // Verificar que existe
    $this->assertDatabaseHas('maquinas', ['id' => $maquina->id]);
    
    // Act: Eliminar máquina
    $response = $this->actingAs($this->admin)
                     ->delete(route('maquinas.destroy', $maquina));
    
    // Assert: Verificar eliminación
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
    
    // Verificar que ya no existe en BD
    $this->assertDatabaseMissing('maquinas', ['id' => $maquina->id]);
    
    // Verificar soft delete si está implementado
    // $this->assertSoftDeleted('maquinas', ['id' => $maquina->id]);
}
```

**Objetivo:** Verificar eliminación de máquinas sin dependencias.

**Verifica:**
- ✅ Eliminación exitosa
- ✅ Registro eliminado de BD
- ✅ Sin errores de sesión

---

### 9. Test: No puede eliminar máquina con relaciones

```php
/** @test */
public function test_no_puede_eliminar_maquina_con_relaciones()
{
    // Arrange: Crear máquina con relaciones
    $maquina = Maquina::factory()->create();
    
    // Crear un costo operacional relacionado
    CostosOperacion::factory()->create([
        'maquina_id' => $maquina->id
    ]);
    
    // Act: Intentar eliminar
    $response = $this->actingAs($this->admin)
                     ->delete(route('maquinas.destroy', $maquina));
    
    // Assert: Verificar que NO se eliminó
    $response->assertRedirect();
    $response->assertSessionHasErrors();
    
    // Verificar mensaje de error
    $errors = session('errors');
    $this->assertTrue($errors->has('default'));
    $this->assertStringContainsString(
        'No se pudo eliminar el recurso porque tiene datos asociados',
        $errors->first()
    );
    
    // Verificar que la máquina aún existe
    $this->assertDatabaseHas('maquinas', ['id' => $maquina->id]);
}
```

**Objetivo:** Verificar protección contra eliminación con dependencias.

**Verifica:**
- ✅ Máquina con relaciones NO se elimina
- ✅ Mensaje de error apropiado
- ✅ Integridad referencial protegida
- ✅ Trait CheckRelations funciona

---

### 10-12. Tests de Autorización para Usuarios No Admin

```php
/** @test */
public function test_usuario_no_admin_no_puede_crear_maquina()
{
    // Arrange
    $datosMaquina = [
        'maquina' => 'Sierra Circular',
        'corte' => 'INICIAL'
    ];
    
    // Act & Assert
    $response = $this->actingAs($this->user)
                     ->post(route('maquinas.store'), $datosMaquina);
    
    $response->assertStatus(403);
    $this->assertDatabaseCount('maquinas', 0);
}

/** @test */
public function test_usuario_no_admin_no_puede_editar_maquina()
{
    // Arrange
    $maquina = Maquina::factory()->create();
    
    // Act & Assert
    $response = $this->actingAs($this->user)
                     ->patch(route('maquinas.update', $maquina), [
                         'maquina' => 'Actualizada',
                         'corte' => 'FINAL'
                     ]);
    
    $response->assertStatus(403);
}

/** @test */
public function test_usuario_no_admin_no_puede_eliminar_maquina()
{
    // Arrange
    $maquina = Maquina::factory()->create();
    
    // Act & Assert
    $response = $this->actingAs($this->user)
                     ->delete(route('maquinas.destroy', $maquina));
    
    $response->assertStatus(403);
    $this->assertDatabaseHas('maquinas', ['id' => $maquina->id]);
}
```

**Objetivo:** Verificar que usuarios normales no tienen acceso.

**Verifica:**
- ✅ Autorización en create
- ✅ Autorización en update
- ✅ Autorización en destroy
- ✅ Status 403 en todos los casos

---

## Factories Requeridas

### MaquinaFactory

```php
<?php

namespace Database\Factories;

use App\Models\Maquina;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaquinaFactory extends Factory
{
    protected $model = Maquina::class;

    public function definition()
    {
        $tiposCorte = [
            'INICIAL', 'INTERMEDIO', 'FINAL', 'ACABADOS',
            'ASERRIO', 'ENSAMBLE', 'ACABADO_ENSAMBLE', 'REASERRIO'
        ];

        return [
            'maquina' => strtoupper($this->faker->words(2, true)),
            'corte' => $this->faker->randomElement($tiposCorte),
        ];
    }
}
```

### CostosOperacionFactory (si no existe)

```php
<?php

namespace Database\Factories;

use App\Models\CostosOperacion;
use App\Models\Maquina;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostosOperacionFactory extends Factory
{
    protected $model = CostosOperacion::class;

    public function definition()
    {
        return [
            'maquina_id' => Maquina::factory(),
            // ... otros campos según el modelo
        ];
    }
}
```

---

## Ejecución de Tests

### Comandos Básicos

```bash
# Ejecutar todos los tests de MaquinaController
php artisan test --filter=MaquinaControllerTest

# Ejecutar un test específico
php artisan test --filter=test_admin_puede_crear_maquina

# Ejecutar con detalles
php artisan test --filter=MaquinaControllerTest --verbose

# Ejecutar con cobertura
php artisan test --filter=MaquinaControllerTest --coverage

# Ejecutar en paralelo
php artisan test --filter=MaquinaControllerTest --parallel
```

### PHPUnit Directo

```bash
# Usando PHPUnit directamente
./vendor/bin/phpunit --filter MaquinaControllerTest

# Con cobertura HTML
./vendor/bin/phpunit --filter MaquinaControllerTest --coverage-html coverage
```

---

## Cobertura Esperada

### Métodos del Controlador

| Método | Cobertura | Tests |
|--------|-----------|-------|
| index() | 100% | 2 tests |
| store() | 100% | 4 tests |
| edit() | 100% | 2 tests |
| update() | 100% | 3 tests |
| destroy() | 100% | 3 tests |

### Casos de Prueba

| Categoría | Cantidad | Estado |
|-----------|----------|--------|
| Happy Path | 6 | ✅ |
| Validaciones | 2 | ✅ |
| Autorización | 4 | ✅ |
| **Total** | **12** | **✅** |

---

## Configuración Adicional

### phpunit.xml

Asegurarse de tener configuración adecuada:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit>
    <testsuites>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    
    <coverage>
        <include>
            <directory suffix=".php">./app</directory>
        </include>
    </coverage>
    
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
    </php>
</phpunit>
```

### Base de Datos de Pruebas

```php
// config/database.php
'connections' => [
    'sqlite' => [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ],
],
```

---

## Checklist de Implementación

- [ ] Crear archivo `MaquinaControllerTest.php`
- [ ] Implementar Factory `MaquinaFactory`
- [ ] Implementar Factory `CostosOperacionFactory`
- [ ] Implementar test 1: Listado admin
- [ ] Implementar test 2: Listado no-admin
- [ ] Implementar test 3: Crear máquina
- [ ] Implementar test 4: Mayúsculas
- [ ] Implementar test 5: Validación requeridos
- [ ] Implementar test 6: Validación tipo corte
- [ ] Implementar test 7: Editar máquina
- [ ] Implementar test 8: Eliminar sin relaciones
- [ ] Implementar test 9: Eliminar con relaciones
- [ ] Implementar test 10-12: Autorizaciones
- [ ] Ejecutar tests y verificar 100% pasan
- [ ] Generar reporte de cobertura
- [ ] Documentar resultados

---

## Troubleshooting

### Problemas Comunes

#### Error: "Policy not found"
```php
// Solución: Definir gate en AuthServiceProvider
Gate::define('admin', function ($user) {
    return $user->role === 'admin';
});
```

#### Error: "Table maquinas doesn't exist"
```bash
# Solución: Ejecutar migraciones en ambiente de test
php artisan migrate --env=testing
```

#### Error: "Factory not found"
```bash
# Solución: Regenerar autoload
composer dump-autoload
```

---

## Mejoras Futuras

### Tests Adicionales Sugeridos

1. **Test de Rendimiento**
   - Verificar tiempo de carga con 1000+ máquinas
   - Optimización de queries

2. **Tests de Integración**
   - Flujo completo: Crear → Editar → Usar → Eliminar
   - Interacción con CostosOperacion

3. **Tests de UI/Browser**
   - Laravel Dusk para interacción con DataTables
   - Tests de modal y JavaScript

4. **Tests de Seguridad**
   - SQL Injection
   - XSS en nombres de máquinas
   - CSRF protection

---

**Última actualización:** 30 de Enero, 2026  
**Versión:** 1.0  
**Framework Testing:** PHPUnit 9.x / Laravel 8.x+
