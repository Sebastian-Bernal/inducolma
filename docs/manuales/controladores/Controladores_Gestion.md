# Documentación Consolidada: Controladores de Gestión

**Fecha:** 30 de Enero, 2026  
**Sistema:** Inducolma - Gestión Industrial  
**Módulos:** Clientes, Proveedores, Usuarios, Roles

---

## 📋 Índice de Controladores

1. [ClienteController](#clientecontroller)
2. [ProveedorController](#proveedorcontroller)
3. [UsuarioController](#usuariocontroller)
4. [RolController](#rolcontroller)

---

## ClienteController

**Ubicación:** `app/Http/Controllers/ClienteController.php`

### Propósito
Gestionar el catálogo de clientes de la empresa con soporte para soft deletes.

### Rutas

```php
GET    /clientes              → index()   // Listar (incluye eliminados)
POST   /clientes              → store()   // Crear
GET    /clientes/{id}         → show()    // Ver con pedidos
GET    /clientes/{id}/edit    → edit()    // Formulario edición
PUT    /clientes/{id}         → update()  // Actualizar
DELETE /clientes/{id}         → destroy() // Soft delete
PUT    /restore-cliente/{id}  → restore() // Restaurar eliminado
```

### Campos del Modelo

| Campo | Tipo | Descripción | Validación |
|-------|------|-------------|------------|
| nit | VARCHAR | NIT del cliente | required, unique |
| nombre | VARCHAR | Nombre comercial | required, MAYÚSCULAS |
| razon_social | VARCHAR | Razón social | required, MAYÚSCULAS |
| direccion | VARCHAR | Dirección física | required, MAYÚSCULAS |
| telefono | VARCHAR | Teléfono contacto | required |
| email | EMAIL | Correo electrónico | required, email |
| id_usuario | BIGINT | Usuario que registró | FK users |

### Métodos Importantes

#### index() - Listar Clientes
```php
public function index()
{
    // Incluye registros eliminados (soft deletes)
    $clientes = Cliente::withTrashed()->get();
    return view('modulos.administrativo.clientes.index', compact('clientes'));
}
```

**Características:**
- ✅ Muestra clientes activos e inactivos
- ✅ Permite restaurar clientes eliminados
- ⚠️ No tiene paginación (puede ser lento con muchos registros)

#### show() - Ver Cliente con Pedidos
```php
public function show(Cliente $cliente)
{
    // Obtiene últimos 5 pedidos del cliente
    $pedidos = Pedido::join('diseno_producto_finales',
                           'pedidos.diseno_producto_final_id',
                           '=',
                           'diseno_producto_finales.id')
                    ->where('cliente_id', $cliente->id)
                    ->orderBy('pedidos.created_at', 'desc')
                    ->take(5)
                    ->get([
                        'pedidos.id',
                        'pedidos.cantidad',
                        'pedidos.created_at',
                        'pedidos.fecha_entrega',
                        'pedidos.estado',
                        'diseno_producto_finales.descripcion',
                    ]);

    return view('modulos.administrativo.clientes.show', 
                compact('cliente', 'pedidos'));
}
```

**Características:**
- ✅ Muestra historial de pedidos
- ✅ Solo últimos 5 pedidos (performance)
- ✅ Join con diseños para mostrar productos

#### store() - Crear Cliente
```php
public function store(StoreClienteRequest $request)
{
    $this->authorize('admin'); // Solo administradores
    
    $cliente = new Cliente();
    $cliente->nit = $request->nit;
    $cliente->nombre = strtoupper($request->nombre);
    $cliente->razon_social = strtoupper($request->razon_social);
    $cliente->direccion = strtoupper($request->direccion);
    $cliente->telefono = $request->telefono;
    $cliente->email = $request->email;
    $cliente->id_usuario = Auth::user()->id;
    $cliente->save();
    
    return back()->with('status', 'Cliente creado con éxito');
}
```

**Transformaciones:**
- ✅ Nombre, razón social y dirección a MAYÚSCULAS
- ✅ Registro automático del usuario creador

#### restore() - Restaurar Cliente Eliminado
```php
public function restore($id)
{
    $this->authorize('admin');
    
    $cliente = Cliente::withTrashed()->findOrFail($id);
    $cliente->restore();
    
    return back()->with('status', 
                       "El cliente $cliente->nombre ha sido restaurado");
}
```

**Características:**
- ✅ Implementa soft deletes
- ✅ Permite recuperar registros eliminados
- ✅ Útil para correcciones de errores

### Relaciones

```php
class Cliente extends Model
{
    use SoftDeletes;
    
    // Relación Many-to-Many con diseños
    public function disenos()
    {
        return $this->belongsToMany(DisenoProductoFinal::class, 
                                   'diseno_producto_final_clientes');
    }
    
    // Relación One-to-Many con pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
```

### Casos de Uso

**1. Crear Cliente**
```
Admin → /clientes
→ Click "Crear Cliente"
→ Formulario:
    NIT: 900123456-7
    Nombre: Muebles del Valle
    Razón: Muebles del Valle S.A.S.
    Dirección: Calle 50 #30-20
    Teléfono: 3001234567
    Email: ventas@mueblesdelv alle.com
→ Submit POST /clientes
→ Validación StoreClienteRequest
→ Conversión a mayúsculas
→ INSERT en BD con id_usuario actual
→ Mensaje: "Cliente creado con éxito"
```

**2. Ver Historial de Cliente**
```
Usuario → /clientes
→ Click en cliente "Muebles del Valle"
→ GET /clientes/5
→ Muestra información + últimos 5 pedidos
→ Lista pedidos con estado y fechas
```

**3. Restaurar Cliente Eliminado**
```
Admin → /clientes
→ Ve cliente marcado como "INACTIVO"
→ Click "Restaurar"
→ PUT /restore-cliente/5
→ Cliente activo nuevamente
→ Mensaje: "Cliente MUEBLES DEL VALLE ha sido restaurado"
```

---

## ProveedorController

**Ubicación:** `app/Http/Controllers/ProveedorController.php`

### Propósito
Gestionar proveedores de materia prima (madera) con soft deletes.

### Estructura Similar a ClienteController

**Campos del Modelo:**
- nit
- nombre
- razon_social
- direccion
- telefono
- email
- id_usuario

**Diferencias clave:**
- Relacionado con `EntradaMadera` (compras)
- No tiene relación con diseños
- Usado en módulo de compras/entradas

### Rutas
```php
GET    /proveedores              → index()
POST   /proveedores              → store()
GET    /proveedores/{id}         → show()
GET    /proveedores/{id}/edit    → edit()
PUT    /proveedores/{id}         → update()
DELETE /proveedores/{id}         → destroy()
PUT    /restore-proveedor/{id}   → restore()
```

### Relaciones
```php
class Proveedor extends Model
{
    use SoftDeletes;
    
    public function entradas_madera()
    {
        return $this->hasMany(EntradaMadera::class);
    }
}
```

---

## UsuarioController

**Ubicación:** `app/Http/Controllers/UsuarioController.php`

### Propósito
Gestionar usuarios del sistema con roles y permisos.

### Campos del Modelo User

| Campo | Tipo | Descripción |
|-------|------|-------------|
| name | VARCHAR | Nombre completo |
| email | EMAIL | Correo (login) |
| password | HASH | Contraseña encriptada |
| role | VARCHAR | Rol del usuario |
| deleted_at | TIMESTAMP | Soft delete |

### Métodos Principales

#### index()
```php
public function index()
{
    $usuarios = User::withTrashed()->get();
    $roles = ['admin', 'supervisor', 'operario', 'bodega'];
    
    return view('modulos.administrativo.usuarios.index', 
                compact('usuarios', 'roles'));
}
```

#### store()
```php
public function store(Request $request)
{
    $this->authorize('admin');
    
    $usuario = new User();
    $usuario->name = strtoupper($request->name);
    $usuario->email = $request->email;
    $usuario->password = bcrypt($request->password);
    $usuario->role = $request->role;
    $usuario->save();
    
    return back()->with('status', 'Usuario creado con éxito');
}
```

**Características:**
- ✅ Contraseña encriptada con bcrypt()
- ✅ Roles predefinidos del sistema
- ✅ Solo administradores pueden crear

### Roles del Sistema

```php
const ROLES = [
    'admin' => 'Administrador (acceso total)',
    'supervisor' => 'Supervisor (reportes y consultas)',
    'operario' => 'Operario (registro de producción)',
    'bodega' => 'Bodega (entradas y salidas)'
];
```

### Casos de Uso

**1. Crear Usuario Operario**
```
Admin → /usuarios
→ Formulario:
    Nombre: Juan Pérez
    Email: juan.perez@inducolma.com
    Password: (generado o asignado)
    Rol: operario
→ POST /usuarios
→ Password hasheado con bcrypt
→ Usuario creado
→ Puede iniciar sesión con email/password
```

**2. Cambiar Rol de Usuario**
```
Admin → /usuarios/{id}/edit
→ Cambia rol de "operario" a "supervisor"
→ PUT /usuarios/{id}
→ Usuario ahora tiene permisos de supervisor
```

---

## RolController

**Ubicación:** `app/Http/Controllers/RolController.php`

### Propósito
Gestionar roles y permisos del sistema (si se usa Spatie o similar).

### Estructura Típica

```php
class RolController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }
    
    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);
        
        return back()->with('status', 'Rol creado');
    }
}
```

### Permisos Comunes

```php
const PERMISSIONS = [
    // Módulo Costos
    'costos.view',
    'costos.create',
    'costos.edit',
    'costos.delete',
    
    // Módulo Producción
    'produccion.view',
    'produccion.registrar',
    
    // Módulo Reportes
    'reportes.view',
    'reportes.export',
    
    // Módulo Administración
    'usuarios.manage',
    'configuracion.manage',
];
```

---

## Comparación de Controladores

| Aspecto | ClienteController | ProveedorController | UsuarioController |
|---------|-------------------|---------------------|-------------------|
| **Propósito** | Gestión clientes | Gestión proveedores | Gestión usuarios sistema |
| **Soft Deletes** | ✅ | ✅ | ✅ |
| **Autorización** | admin | admin | admin |
| **Relaciones principales** | pedidos, diseños | entradas_madera | turnos, recepciones |
| **Campos únicos** | - | - | password, role |
| **MAYÚSCULAS** | ✅ | ✅ | ✅ |
| **Vista show()** | Con pedidos | Con entradas | Con estadísticas |

---

## Patrón Repository (EntradaMadera)

El `EntradaMaderaController` usa un patrón especial con Repository para lógica compleja.

### Estructura

```
EntradaMaderaController
    ↓ (inyección de dependencia)
RegistroEntradaMadera (Repository)
    ├── validarDatosEntrada()
    ├── guardar()
    ├── guardarEntrada()
    ├── guardarMaderas()
    └── actualizar()
```

### Ventajas del Repository Pattern

1. **Separación de responsabilidades**
   - Controller: HTTP y validación básica
   - Repository: Lógica de negocio compleja

2. **Reutilización**
   - Mismo repository desde múltiples controladores
   - Lógica centralizada

3. **Testing más fácil**
   - Mock del repository
   - Test de lógica independiente

4. **Transacciones complejas**
   - Múltiples inserts relacionados
   - Rollback automático en errores

### Ejemplo de Uso

```php
// Controller
public function store(Request $request)
{
    if ($request->entrada[2] == 0) {
        return $this->registroEntradaMadera->guardar($request);
    } else {
        return $this->registroEntradaMadera->actualizar($request);
    }
}

// Repository
public function guardar($entrada)
{
    // 1. Guardar entrada principal
    $registroEntrada = $this->guardarEntrada($entrada);
    
    if ($registroEntrada['error']) {
        return response()->json([
            'error' => true, 
            'message' => $registroEntrada['message']
        ]);
    }
    
    // 2. Guardar maderas asociadas
    $registroMaderas = $this->guardarMaderas(
        $entrada, 
        $registroEntrada['id']
    );
    
    if ($registroMaderas['error']) {
        return response()->json([
            'error' => true,
            'message' => $registroMaderas['message']
        ]);
    }
    
    // 3. Retornar éxito con ID
    return response()->json([
        'error' => false,
        'message' => 'Registro guardado correctamente',
        'id' => $registroMaderas['idRegisto']
    ]);
}
```

---

## Mejores Prácticas Identificadas

### ✅ Buenas Prácticas Implementadas

1. **Soft Deletes**
   - Preserva historial
   - Permite restauración
   - Mantiene integridad referencial

2. **Autorización Centralizada**
   - `$this->authorize('admin')`
   - Consistente en todos los controladores

3. **Conversión a Mayúsculas**
   - Estandarización de datos
   - Consistencia en reportes

4. **Validación con FormRequests**
   - Lógica separada del controlador
   - Reutilizable y testeable

5. **Registro de Usuario Creador**
   - Auditoría automática
   - Trazabilidad de cambios

### ⚠️ Áreas de Mejora

1. **Paginación**
   ```php
   // Actual
   $clientes = Cliente::withTrashed()->get();
   
   // Mejorado
   $clientes = Cliente::withTrashed()->paginate(20);
   ```

2. **Eager Loading**
   ```php
   // Actual (N+1 problem)
   $clientes = Cliente::all();
   
   // Mejorado
   $clientes = Cliente::with(['pedidos', 'disenos'])->get();
   ```

3. **Búsqueda y Filtros**
   ```php
   // Agregar
   public function index(Request $request)
   {
       $query = Cliente::query();
       
       if ($request->buscar) {
           $query->where('nombre', 'like', "%{$request->buscar}%")
                 ->orWhere('nit', 'like', "%{$request->buscar}%");
       }
       
       $clientes = $query->withTrashed()->paginate(20);
       return view('...', compact('clientes'));
   }
   ```

4. **Mensajes más Descriptivos**
   ```php
   // Actual
   return back()->with('status', 'Cliente creado con éxito');
   
   // Mejorado
   return back()->with('success', "Cliente {$cliente->nombre} (NIT: {$cliente->nit}) creado exitosamente");
   ```

---

## Tests Unitarios Sugeridos

### ClienteControllerTest

```php
class ClienteControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function admin_puede_crear_cliente()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)
                         ->post('/clientes', [
                             'nit' => '900123456-7',
                             'nombre' => 'Muebles SA',
                             'razon_social' => 'Muebles SA SAS',
                             'direccion' => 'Calle 50',
                             'telefono' => '3001234567',
                             'email' => 'info@muebles.com'
                         ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('clientes', [
            'nit' => '900123456-7',
            'nombre' => 'MUEBLES SA' // En mayúsculas
        ]);
    }
    
    /** @test */
    public function cliente_eliminado_puede_ser_restaurado()
    {
        $cliente = Cliente::factory()->create();
        $cliente->delete();
        
        $this->assertSoftDeleted('clientes', ['id' => $cliente->id]);
        
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)
                         ->put("/restore-cliente/{$cliente->id}");
        
        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'deleted_at' => null
        ]);
    }
}
```

---

**Última actualización:** 30 de Enero, 2026  
**Versión:** 1.0  
**Autor:** Sistema de Documentación Inducolma
