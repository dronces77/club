📑 Documento de lo realizado (versión preliminar)
1. Migraciones y estructura de base de datos
Tablas creadas/ajustadas:
catalogo_institutos → con PRIMARY KEY, UNIQUE KEY (codigo), FOREIGN KEY hacia catalogo_regimenes.
catalogo_regimenes → con FK hacia catalogo_institutos.
catalogo_tramites → catálogo independiente.
catalogo_modalidades → catálogo independiente.
usuarios → con UNIQUE KEY (email).
clientes → tabla principal, con FKs hacia catálogos.
Tablas hijas de clientes: cliente_curps, cliente_rfcs, cliente_nsss, cliente_contactos, cliente_domicilios, cliente_dependientes, cliente_beneficiarios, etc.
Tablas de gestión: contratos, pagos, seguimientos, notas, historial_clientes, archivos, documentos.
Motor y charset: todas las tablas en ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci.
Integridad referencial:
Base anterior: FKs con comportamiento implícito RESTRICT.
Base nueva: FKs siguen en RESTRICT, pero se recomienda: 
CASCADE en hijos directos de clientes (CURP, RFC, NSS, contactos, domicilios).
RESTRICT en catálogos (institutos, régimenes, trámites, modalidades).
SET NULL en relaciones opcionales (asesor, responsable).
________________________________________
2. Seeders implementados
CatalogoInstitutosSeeder → reinicia y pobla IMSS, ISSSTE.
CatalogoRegimenesSeeder → reinicia y pobla Obligatorio, Voluntario (buscando id por codigo).
CatalogoTramitesSeeder → reinicia y pobla Pensión, Jubilación.
CatalogoModalidadesSeeder → reinicia y pobla NA, CV, M10, M40.
UsuariosSeeder → crea usuario administrador y usuario normal.
ClientesSeeder → crea cliente de prueba (Juan Pérez) con CURP, RFC y NSS relacionados.
________________________________________
3. Cambios realizados
Antes: CURP, RFC y NSS estaban dentro de la tabla clientes.
Ahora: se normalizaron en tablas hijas (cliente_curps, cliente_rfcs, cliente_nsss) con UNIQUE KEY.
Antes: IDs de catálogos se asumían fijos (1, 2).
Ahora: se buscan por codigo para mayor robustez.
Antes: seeders usaban truncate y fallaban por FKs.
Ahora: se ajustaron con delete() y orden de borrado en cascada.
Se agregó: lógica de cascada en seeders (borrar primero hijos, luego padres).
________________________________________
4. Pendientes / Recomendaciones
Definir explícitamente ON DELETE y ON UPDATE en todas las FKs según la lógica de negocio.
Agregar índices en campos de búsqueda frecuente (nombre, apellido_paterno, estatus).
Documentar con un diagrama ER comparativo (anterior vs nueva) para visualizar evolución.
Generar migraciones Laravel para todas las tablas nuevas (contratos, pagos, seguimientos, etc.).
Crear seeders adicionales con varios clientes de prueba para validar listados y relaciones en masa.
________________________________________
________________________________________
📑 Bitácora técnica del proyecto ClubPensión
1. Migraciones y estructura de base de datos
•	Tablas principales creadas/ajustadas:
o	catalogo_institutos → PK, UNIQUE en codigo, FK hacia catalogo_regimenes.
o	catalogo_regimenes → FK hacia catalogo_institutos.
o	catalogo_tramites → catálogo independiente.
o	catalogo_modalidades → catálogo independiente.
o	usuarios → PK, UNIQUE en email.
o	clientes → tabla central, con FKs hacia catálogos.
o	Tablas hijas de clientes: cliente_curps, cliente_rfcs, cliente_nsss, cliente_contactos, cliente_domicilios, cliente_dependientes, cliente_beneficiarios, cliente_ocupaciones, cliente_estudios.
o	Tablas de gestión: contratos, pagos, seguimientos, notas, historial_clientes, archivos, documentos.
•	Motor y charset: todas las tablas en ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci.
•	Integridad referencial:
o	Base anterior: FKs con comportamiento implícito RESTRICT.
o	Base nueva: FKs siguen en RESTRICT, pero se recomienda: 
	CASCADE en hijos directos de clientes (CURP, RFC, NSS, contactos, domicilios).
	RESTRICT en catálogos (institutos, régimenes, trámites, modalidades).
	SET NULL en relaciones opcionales (asesor, responsable).
________________________________________
2. Seeders implementados
•	CatalogoInstitutosSeeder → reinicia y pobla IMSS, ISSSTE.
•	CatalogoRegimenesSeeder → reinicia y pobla Obligatorio, Voluntario (buscando id por codigo).
•	CatalogoTramitesSeeder → reinicia y pobla Pensión, Jubilación.
•	CatalogoModalidadesSeeder → reinicia y pobla NA, CV, M10, M40.
•	UsuariosSeeder → crea usuario administrador y usuario normal.
•	ClientesSeeder → crea cliente de prueba (Juan Pérez) con CURP, RFC y NSS relacionados.
________________________________________
3. Cambios realizados
•	Normalización: CURP, RFC y NSS pasaron de estar dentro de clientes a tablas hijas con UNIQUE KEY.
•	IDs de catálogos: ya no se asumen fijos (1, 2), se buscan por codigo.
•	Seeders: ajustados para borrar primero hijos y luego padres, evitando errores de FKs.
•	Usuarios: ahora con validación de existencia (no se duplican).
•	Clientes: ahora con datos personales separados y relaciones más completas.
________________________________________
4. Pendientes / Recomendaciones
•	Definir explícitamente ON DELETE y ON UPDATE en todas las FKs según lógica de negocio.
•	Agregar índices en campos de búsqueda frecuente (nombre, apellido_paterno, estatus).
•	Documentar con un diagrama ER comparativo (anterior vs nueva) para visualizar evolución.
•	Generar migraciones Laravel para todas las tablas nuevas (contratos, pagos, seguimientos, etc.).
•	Crear seeders adicionales con varios clientes de prueba para validar listados y relaciones en masa.
________________________________________

📋 Checklist Operativo – Proyecto ClubPensión
1. Migraciones
•	[x] Todas las tablas creadas con ENGINE=InnoDB y utf8mb4_unicode_ci.
•	[x] Cada tabla con PRIMARY KEY.
•	[x] Catálogos (institutos, regimenes, tramites, modalidades) con UNIQUE KEY (codigo).
•	[x] usuarios con UNIQUE KEY (email).
•	[x] clientes con FKs hacia catálogos.
•	[x] Tablas hijas (cliente_curps, cliente_rfcs, cliente_nsss) con UNIQUE KEY y FK a clientes.
•	[x] Tablas auxiliares (contactos, domicilios, dependientes, beneficiarios, ocupaciones, estudios).
•	[x] Tablas de gestión (contratos, pagos, seguimientos, notas, historial_clientes, archivos, documentos).
2. Seeders
•	[x] CatalogoInstitutosSeeder → IMSS, ISSSTE.
•	[x] CatalogoRegimenesSeeder → Obligatorio, Voluntario (IDs buscados por codigo).
•	[x] CatalogoTramitesSeeder → Pensión, Jubilación.
•	[x] CatalogoModalidadesSeeder → NA, CV, M10, M40.
•	[x] UsuariosSeeder → admin y usuario normal.
•	[x] ClientesSeeder → cliente de prueba (Juan Pérez) con CURP, RFC y NSS.
3. Cambios realizados
•	[x] Normalización: CURP, RFC, NSS pasaron de estar en clientes a tablas hijas.
•	[x] IDs de catálogos ya no se asumen fijos, se buscan por codigo.
•	[x] Seeders ajustados para borrar primero hijos y luego padres.
•	[x] Usuarios con validación de existencia (no duplicados).
•	[x] Cliente de prueba creado con datos personales separados.
•	📑 Documento de transición – Reconfiguración de código
•	1. Tablas eliminadas / reemplazadas
Base anterior (pension)	Base nueva (clubpension)	Acción en código
bitacora	historial_clientes	Cambiar referencias a bitácora → historial_clientes
clientes_documentos	documentos / archivos	Reemplazar acceso a clientes_documentos por documentos/archivos
clientes_notas	notas	Cambiar referencias a clientes_notas → notas
familiares	cliente_dependientes / cliente_beneficiarios	Dividir lógica: dependientes y beneficiarios
familiares_documentos	documentos	Unificar en documentos (con FK a cliente/beneficiario)
•	________________________________________
•	2. Tablas renombradas / normalizadas
Base anterior (pension)	Base nueva (clubpension)	Acción en código
clientes_curp	cliente_curps	Ajustar plural en modelo y migración
clientes_rfc	cliente_rfcs	Ajustar plural
clientes_nss	cliente_nsss	Ajustar plural
clientes_contacto	cliente_contactos	Ajustar plural
usuarios	usuarios	Se mantiene igual
catalogo_institutos	catalogo_institutos	Se mantiene igual
catalogo_regimenes	catalogo_regimenes	Se mantiene igual
catalogo_tramites	catalogo_tramites	Se mantiene igual
catalogo_modalidades	catalogo_modalidades	Se mantiene igual
•	________________________________________
•	3. Tablas nuevas en clubpension
Tabla nueva	Uso previsto	Acción en código
contratos	Gestión de convenios con clientes	Crear modelos, migraciones y lógica nueva
seguimientos	Trazabilidad de clientes	Reemplaza bitácora dispersa
historial_clientes	Registro de cambios en datos	Sustituye bitácora
archivos	Almacenamiento de archivos asociados	Nuevo módulo
documentos	Gestión documental centralizada	Sustituye clientes_documentos
cliente_domicilios	Normalización de domicilios	Nuevo módulo
cliente_ocupaciones	Normalización de ocupaciones	Nuevo módulo
cliente_estudios	Normalización de estudios	Nuevo módulo
cliente_municipios / cliente_localidades / cliente_estados	Catálogos geográficos	Nuevos módulos
•	________________________________________
•	4. Impacto en el código
•	Modelos Laravel: 
•	Ajustar nombres de tablas hijas (clientes_curp → cliente_curps, etc.).
•	Crear nuevos modelos (Contrato, Pago si se usa, Seguimiento, HistorialCliente, Documento, Archivo).
•	Migrations: 
•	Actualizar FKs según reglas nuevas (RESTRICT, CASCADE, SET NULL).
•	Seeders: 
•	Ajustar para insertar en tablas nuevas (contratos, seguimientos, documentos).
•	Controladores: 
•	Reemplazar lógica que usaba tablas eliminadas (clientes_documentos, clientes_notas, familiares).
•	Vistas / Formularios: 
•	Actualizar formularios para usar nuevas tablas hijas (ej. dependientes, beneficiarios).
•	________________________________________

📑 Documento de transición – Reconfiguración de código
1. Tablas eliminadas / reemplazadas
Base anterior (pension)	Base nueva (clubpension)	Acción en código	Archivos Laravel afectados
bitacora	historial_clientes	Cambiar referencias a bitácora → historial_clientes	HistorialCliente.php (modelo), HistorialClienteController.php, migración
clientes_documentos	documentos / archivos	Reemplazar acceso a clientes_documentos por documentos/archivos	Documento.php, Archivo.php, controladores asociados
clientes_notas	notas	Cambiar referencias a clientes_notas → notas	Nota.php, NotaController.php
familiares	cliente_dependientes / cliente_beneficiarios	Dividir lógica: dependientes y beneficiarios	ClienteDependiente.php, ClienteBeneficiario.php, controladores
familiares_documentos	documentos	Unificar en documentos (con FK a cliente/beneficiario)	Documento.php, migración
________________________________________
2. Tablas renombradas / normalizadas
Base anterior (pension)	Base nueva (clubpension)	Acción en código	Archivos Laravel afectados
clientes_curp	cliente_curps	Ajustar plural en modelo y migración	ClienteCurp.php, migración
clientes_rfc	cliente_rfcs	Ajustar plural	ClienteRfc.php, migración
clientes_nss	cliente_nsss	Ajustar plural	ClienteNss.php, migración
clientes_contacto	cliente_contactos	Ajustar plural	ClienteContacto.php, migración
usuarios	usuarios	Se mantiene igual	Usuario.php, controladores
catalogo_institutos	catalogo_institutos	Se mantiene igual	CatalogoInstituto.php
catalogo_regimenes	catalogo_regimenes	Se mantiene igual	CatalogoRegimen.php
catalogo_tramites	catalogo_tramites	Se mantiene igual	CatalogoTramite.php
catalogo_modalidades	catalogo_modalidades	Se mantiene igual	CatalogoModalidad.php
________________________________________
3. Tablas nuevas en clubpension
Tabla nueva	Uso previsto	Acción en código	Archivos Laravel afectados
contratos	Gestión de convenios con clientes	Crear modelos, migraciones y lógica nueva	Contrato.php, ContratoController.php, migración
seguimientos	Trazabilidad de clientes	Reemplaza bitácora dispersa	Seguimiento.php, SeguimientoController.php
historial_clientes	Registro de cambios en datos	Sustituye bitácora	HistorialCliente.php, migración
archivos	Almacenamiento de archivos asociados	Nuevo módulo	Archivo.php, ArchivoController.php
documentos	Gestión documental centralizada	Sustituye clientes_documentos	Documento.php, DocumentoController.php
cliente_domicilios	Normalización de domicilios	Nuevo módulo	ClienteDomicilio.php, migración
cliente_ocupaciones	Normalización de ocupaciones	Nuevo módulo	ClienteOcupacion.php, migración
cliente_estudios	Normalización de estudios	Nuevo módulo	ClienteEstudio.php, migración
cliente_municipios / cliente_localidades / cliente_estados	Catálogos geográficos	Nuevos módulos	ClienteMunicipio.php, ClienteLocalidad.php, ClienteEstado.php
________________________________________
4. Impacto en el código
•	Modelos Laravel: crear/ajustar según tablas nuevas y renombradas.
•	Migraciones: actualizar FKs y nombres de tablas.
•	Seeders: insertar datos en catálogos y tablas nuevas.
•	Controladores: modificar lógica que usaba tablas eliminadas.
•	Vistas/Formularios: actualizar formularios para dependientes, beneficiarios, documentos, contratos.
________________________________________
✅ Resultado
Este documento funciona como mapa de transición:
•	Te dice qué tablas ya no existen y por cuáles se sustituyen.
•	Qué tablas cambiaron de nombre (singular → plural).
•	Qué tablas son nuevas y requieren código adicional.
•	Qué archivos de Laravel se ven afectados (modelos, controladores, migraciones, seeders).
•	📑 Documento de transición – Reconfiguración completa del sistema ClubPensión
•	1. Comparativo de Tablas
•	Tablas eliminadas o reemplazadas
Base anterior (pension)	Nueva (clubpension)	Acción
bitacora	historial_clientes	Sustituir auditoría
clientes_documentos	documentos, archivos	Centralizar gestión documental
clientes_notas	notas	Unificar notas
familiares	cliente_dependientes, cliente_beneficiarios	Dividir lógica
familiares_documentos	documentos	Unificar
•	Tablas renombradas / normalizadas
Anterior	Nueva	Acción
clientes_curp	cliente_curps	Ajustar plural
clientes_rfc	cliente_rfcs	Ajustar plural
clientes_nss	cliente_nsss	Ajustar plural
clientes_contacto	cliente_contactos	Ajustar plural
•	Tablas nuevas
Tabla	Uso
contratos	Convenios con clientes
seguimientos	Trazabilidad
historial_clientes	Registro de cambios
archivos	Almacenamiento
documentos	Gestión documental
cliente_domicilios	Normalización
cliente_ocupaciones	Normalización
cliente_estudios	Normalización
cliente_municipios, cliente_localidades, cliente_estados	Catálogos geográficos
•	________________________________________



