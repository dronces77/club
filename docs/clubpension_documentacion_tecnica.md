---
# 📄 Documentación Técnica - Proyecto Club Pension

## 1. Contexto General del Proyecto
**Nombre del proyecto:** Club Pension (CRM de clientes y prospectos)

**Propósito:**
- Gestionar prospectos y clientes en un sistema de asesorías de pensiones.
- Funcionalidades principales:
  - Captura de prospectos
  - Conversión de prospectos a clientes
  - Gestión de datos auxiliares (CURP, NSS, contactos)
  - Control de números de cliente CP-X

**Usuarios:**
- Administradores y asesores de pensiones.

**Tecnologías:**
- Laravel (PHP)
- MySQL
- Blade templates
- Eloquent ORM

---
## 2. Base de Datos
### 2.1 Tabla `prospectos`
| Campo | Tipo | Función |
|-------|------|---------|
| id | bigint | PK prospecto |
| nombre, apellido_paterno, apellido_materno | varchar | Datos del prospecto |
| curp, nss, celular | varchar | Datos personales y contacto |
| convertido | boolean | 0 = prospecto, 1 = convertido |
| cliente_id | bigint | FK al cliente generado |
| fecha_creacion | timestamp | Fecha de registro |
| notas | text | Observaciones |

### 2.2 Tabla `clientes`
| Campo | Tipo | Función |
|-------|------|---------|
| id | bigint | PK cliente |
| no_cliente | varchar | CP-X secuencial |
| tipo_cliente | varchar(1) | 'P' = prospecto, 'C' = cliente |
| nombre, apellido_paterno, apellido_materno | varchar | Datos personales |
| estatus | varchar | activo/inactivo |
| prospecto_id | int | Referencia al prospecto original |
| creado_por, actualizado_por | bigint | Usuario responsable |

### 2.3 Tablas auxiliares
**cliente_curps, cliente_nsss, cliente_contactos**
- Almacenan datos específicos de clientes
- Campos principales:
| Campo | Tipo | Función |
|-------|------|---------|
| cliente_id | bigint | FK a clientes |
| curp / nss / valor | varchar | Valor del dato |
| es_principal | tinyint(1) | 1 = principal, 0 = secundario |

### 2.4 Catálogos
**catalogo_estatus_prospectos**
- Campos: `id, nombre, activo, orden`
- Función: filtrar prospectos por estatus

---
## 3. Modelos y Relaciones
### 3.1 Prospecto
- Relación: `estatus() → CatalogoEstatusProspecto`
- Función principal: capturar y convertir prospectos a clientes
- Validaciones: CURP único, NSS único, celular obligatorio

### 3.2 Cliente
- Relaciones:
  - `curps()` → ClienteCurp
  - `nss()` → ClienteNss
  - `contactos()` → ClienteContacto
  - `creadoPor()`, `actualizadoPor()` → Usuario
- Funciones:
  - `generarNumeroCliente()` → CP-X secuencial seguro
  - `scopeProspectos()`, `scopeClientes()`
- Reglas:
  - Tipo cliente: 'P' o 'C'
  - Edad calculada por fecha_nacimiento

### 3.3 Modelos auxiliares
- `ClienteCurp`, `ClienteNss`, `ClienteContacto`
  - Guardan datos específicos
  - `es_principal = 1` determina el dato principal
  - Regla: un solo dato principal por tipo

---
## 4. Controladores
### 4.1 ProspectoController
Funciones principales:
1. `index()` → Listado con filtro por estatus
2. `create()` → Formulario de prospecto
3. `store()` → Guarda prospecto con validación
4. `updateEstatus()` → Cambia estatus de prospecto
5. `convertir($id)` → Convierte prospecto a cliente
   - Bloquea prospecto y calcula CP-X con `lockForUpdate`
   - Crea registro en `clientes`
   - Crea datos auxiliares (CURP, NSS, Contacto principal)
   - Marca prospecto como convertido
   - Transaction `DB::beginTransaction()` y rollback en error

### 4.2 ClienteController
- Funciones: mostrar, editar clientes
- Vista respeta regla de principal/segundario para CURP/NSS/Contacto

---
## 5. Lógica de Negocio
1. **Conversión:** solo un CP-X, un solo principal por dato auxiliar, estatus activo
2. **Datos auxiliares:** `es_principal` determina dato principal
3. **Fechas:** `fecha_creacion`, `fecha_contrato`, edad calculada
4. **Validaciones:** CURP único, NSS único, celular obligatorio
5. **Transacciones:** asegura atomicidad y consistencia de datos

---
## 6. Vistas
- `prospectos/`: index, create, edit
- `clientes/`: _form, create, edit, index, show
- Layout general: `layouts/app.blade.php`
- Reglas principales en vista: mostrar datos principales según `es_principal`

---
## 7. Variables y Campos Clave
| Variable | Tipo | Función |
|----------|------|---------|
| tipo_cliente | varchar(1) | 'P' = prospecto, 'C' = cliente |
| convertido | boolean | Prospecto convertido |
| no_cliente | varchar(50) | CP-X generado secuencial |
| es_principal | tinyint(1) | Dato principal en tabla auxiliar |
| cliente_id | bigint | FK a tabla clientes |
| valor | varchar | Valor del contacto |

---
## 8. Seguridad y Concurrencia
- `lockForUpdate()` durante conversión
- Transaction `DB::beginTransaction()` y `rollBack()` en error
- Prevención de duplicados y conversiones múltiples

---
## 9. Flujo de Conversión
```mermaid
flowchart TD
A[Usuario accede a /prospectos/{id}/convertir] --> B[Lock prospecto]
B --> C[Calcular CP-X seguro]
C --> D[Crear registro en clientes]
D --> E[Crear datos auxiliares]
E --> F[Marcar prospecto como convertido]
F --> G[Commit transaction]
G --> H[Redirigir a clientes.show]
```
- Datos principales: CURP/NSS/contacto con `es_principal = 1`

---
## 10. Notas Finales
- Código listo para multiusuario sin duplicar CP-X
- Datos auxiliares correctamente asignados como principales
- Vista respeta regla principal/segundario
- Arquitectura mantiene separación prospecto/cliente/datos auxiliares
- Diseño, variables y funciones intactas, solo se corrigió la conversión y asignación de principal

---
**Fin de la documentación técnica.**

