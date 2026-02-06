Perfecto, Daniel. Te voy a armar un documento técnico completo y ordenado sobre todo lo que hemos trabajado en tu proyecto Club Pension, basado en todo lo que ya hicimos y los últimos ajustes que implementamos. Este documento servirá para referencia futura o para continuar el proyecto en otro chat o equipo. Lo haré muy detallado, incluyendo archivos, tablas, variables, funciones, lógica de negocio y reglas.

?? Documento Técnico – Proyecto Club Pension

1?? Contexto General del Proyecto
Nombre del proyecto: Club Pension (CRM de clientes y prospectos)
Propósito:
Administrar prospectos y clientes en un sistema de asesorías de pensiones, con funcionalidades de:
* Captura de prospectos
* Conversión de prospectos a clientes
* Gestión de datos auxiliares (CURP, NSS, contactos)
* Reglas de negocio sobre cliente/prospecto
* Control de números de cliente CP-X (secuencial)
* Garantía de integridad y concurrencia (nivel banco)
Usuarios principales:
* Administradores / asesores de pensiones
Tecnologías:
* Laravel (PHP 10+)
* MySQL
* Blade templates
* Eloquent ORM
* Bootstrap / HTML para vistas

2?? Base de Datos – Tablas Clave
2.1 Tabla prospectos
* Almacena los prospectos antes de ser clientes.
* Campos principales:
id, nombre, apellido_paterno, apellido_materno, curp, nss, celular, convertido, estatus_prospecto_id, cliente_id, fecha_creacion, notas
* Lógica de negocio:
o convertido: 0 = prospecto, 1 = ya convertido a cliente
o cliente_id: FK al cliente generado al convertir

2.2 Tabla clientes
* Almacena todos los clientes definitivos.
* Campos principales:
id, no_cliente, tipo_cliente, nombre, apellido_paterno, apellido_materno, fecha_contrato, estatus, prospecto_id, ...
* Lógica de negocio:
o tipo_cliente: 'P' = prospecto, 'C' = cliente
o no_cliente: CP-X generado secuencialmente
o estatus: activo/inactivo
o prospecto_id: referencia al prospecto original
* Relaciones:
o Con tablas auxiliares: cliente_curps, cliente_nsss, cliente_contactos
o Con usuario: creado_por, actualizado_por

2.3 Tabla cliente_curps
* Campos:
id, cliente_id, curp, es_principal (1 = principal), created_at, updated_at
* Función: almacenar CURPs asociados a un cliente
* Regla de negocio: el CURP con es_principal = 1 se muestra como principal en la vista

2.4 Tabla cliente_nsss
* Campos:
id, cliente_id, nss, es_principal (1 = principal), created_at, updated_at
* Función: almacenar NSS asociados a un cliente
* Regla de negocio: el NSS con es_principal = 1 se muestra como principal en la vista

2.5 Tabla cliente_contactos
* Campos:
id, cliente_id, tipo (celular, email, etc.), valor, es_principal, created_at, updated_at
* Función: almacenar contactos asociados a un cliente
* Regla de negocio: es_principal = 1 determina el contacto principal

2.6 Tabla catalogo_estatus_prospectos
* Catálogo de estatus de prospectos
* Campos principales: id, nombre, activo, orden
* Función: filtrar prospectos por estatus

3?? Modelos y Relaciones Eloquent
3.1 Modelo Prospecto
* Ubicación: app/Models/Prospecto.php
* Relaciones:
o estatus() ? CatalogoEstatusProspecto
* Funciones principales:
o Guardar prospectos
o Convertir a cliente (se comunica con ClienteController indirectamente)
* Validaciones:
o CURP único
o NSS único
o Celular requerido

3.2 Modelo Cliente
* Ubicación: app/Models/Cliente.php
* Relaciones:
o curps() ? ClienteCurp
o nss() ? ClienteNss
o contactos() ? ClienteContacto
o instituto(), regimen(), tramite()
o creadoPor(), actualizadoPor() ? Usuario
* Funciones importantes:
o generarNumeroCliente() ? Genera CP-X secuencial de manera segura (lockForUpdate)
o scopeProspectos(), scopeClientes(), scopePorTipo()
* Boot method:
o Asigna tipo_cliente = P por defecto
o Calcula edad automáticamente si hay fecha_nacimiento
o Al actualizar:
* Si cambia a cliente, asigna estatus = activo, genera no_cliente si no hay, asigna fecha_contrato si falta
* Calcula edad si cambia fecha_nacimiento
* Limpia estatus si deja de ser cliente

3.3 Modelos auxiliares
* ClienteCurp, ClienteNss, ClienteContacto
o Guardan datos específicos de cada cliente
o es_principal define qué dato se muestra como principal
o ClienteContacto tiene tipo y valor
* Regla de negocio: solo un campo de cada tipo puede ser principal

4?? Controladores
4.1 ProspectoController
* Ubicación: app/Http/Controllers/ProspectoController.php
* Funciones principales:
1. index() ? Listado de prospectos con filtro por estatus
2. create() ? Vista de captura de prospecto
3. store() ? Guarda prospecto validando CURP/NSS/celular
4. updateEstatus() ? Cambia estatus de prospecto
5. convertir($id) ? Conversión a cliente
* Bloquea la fila de prospecto (lockForUpdate)
* Genera CP-X de manera segura (lockForUpdate en clientes)
* Crea cliente en clientes
* Crea datos auxiliares:
* cliente_curps con es_principal = 1
* cliente_nsss con es_principal = 1
* cliente_contactos con tipo = celular, valor = prospecto.celular, es_principal = 1
* Marca prospecto como convertido = 1 y guarda cliente_id
* Transaction (DB::beginTransaction()) y rollback en error
Reglas de negocio implementadas en conversión:
* Solo un CP-X por cliente
* No se permite convertir prospecto ya convertido
* Datos auxiliares se marcan como principales al crear
* CURP/NSS/Contacto principal visible en vista cliente

4.2 ClienteController
* Ubicación: app/Http/Controllers/ClienteController.php
* Funciones:
o Mostrar cliente (show) ? incluye datos principales de CURP/NSS/contacto
o Editar cliente (edit) ? permite modificar cliente y datos auxiliares
* Lógica de validación es_principal para mostrar campo principal

5?? Lógica de negocio y reglas
1. Prospecto ? Cliente
o Solo se puede convertir 1 vez
o Generación de CP-X secuencial, segura, concurrente
o Datos principales: CURP/NSS/celular marcados como es_principal = 1
o Se asigna estatus = activo, fecha_contrato si no existe
2. Datos auxiliares
o es_principal determina qué dato se muestra como principal
o Solo un registro por tipo puede tener es_principal = 1 por cliente
3. Fechas
o fecha_creacion prospecto ? se guarda automáticamente
o fecha_contrato cliente ? asignada si no existe
o edad ? calculada automáticamente a partir de fecha_nacimiento
4. Validaciones
o CURP único
o NSS único
o Celular obligatorio
5. Transacciones
o convertir() usa DB::beginTransaction() para garantizar atomicidad
o lockForUpdate() asegura que CP-X sea único en concurrencia alta

6?? Vistas
* prospectos/:
o index.blade.php ? listado con filtros
o create.blade.php ? formulario nuevo prospecto
o edit.blade.php ? editar prospecto
* clientes/:
o _form.blade.php ? formulario cliente
o create.blade.php ? crear cliente
o edit.blade.php ? editar cliente
o show.blade.php ? ver cliente, CURP/NSS/Contacto principal visible
o index.blade.php ? listado de clientes
* layouts/app.blade.php ? layout general

7?? Variables y campos clave
Variable / CampoTipoFuncióntipo_clientevarchar(1)'P' = prospecto, 'C' = clienteconvertidobooleanProspecto convertido a clienteno_clientevarchar(50)CP-X generado secuencialmentees_principaltinyint(1)Define si CURP/NSS/contacto es principalcliente_idbigintFK a tabla clientesvalorvarcharValor del contacto (celular, email)
8?? Seguridad y concurrencia
* lockForUpdate() en prospectos y clientes durante conversión
* Transaction DB::beginTransaction() y rollBack() en error
* Validaciones para evitar duplicados y conversiones múltiples

9?? Resumen de flujo de conversión
1. Usuario accede a /prospectos/{id}/convertir
2. ProspectoController@convertir inicia transacción
3. Bloquea prospecto y calcula CP-X seguro
4. Crea registro en clientes
5. Crea registros auxiliares:
o cliente_curps (CURP principal)
o cliente_nsss (NSS principal)
o cliente_contactos (celular principal)
6. Marca prospecto como convertido = 1
7. Commit de la transacción
8. Redirige a clientes.show con mensaje de éxito

?? Notas finales
* Código listo para multiusuario simultáneo sin riesgo de duplicar CP-X
* Datos auxiliares correctamente marcados como principales (es_principal = 1)
* Vista de cliente respeta regla de principal/segundario
* Arquitectura mantiene separación prospecto/cliente/datos auxiliares
* Diseño, variables y funciones no fueron alteradas, solo corregimos la conversión y el principal


