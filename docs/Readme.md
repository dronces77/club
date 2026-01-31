Nombre de base de datos "clubpension"
Tabla 1 clientes activos "Clientes"
Campos clientes-activos

id, numerico
NoCliente, alfanumerico, datos posibles (C-xxxxx, activos / P-xxxxx, prospectos / B-xxxxx, bajas / I-xxxxx, imposibles) que cada uno de estos tambien sera una tabla, este numero sera generado por el sistema a partir del numero 1000
Nombre, cadena, campo obligatorio
Apellido Paterno, cadena, campo obligatorio
Apellido Materno, cadena, campo obligatorio
FechaNac, tipo fecha (dd-mm-aaaa), campo opcional
Edad, 2 digitos, numerico, campo opcional
CURP1, 18 digitos, alfanumerico, campo obligatorio
CURP2, 18 digitos, alfanumerico, campo opcional
CURP3, 18 digitos, alfanumerico, campo opcional
CURP4, 18 digitos, alfanumerico, campo opcional
NSS1, 11 digitos, numerico, campo obligatorio
NSS2, 11 digitos, numerico, campo opcional
NSS3, 11 digitos, numerico, campo opcional
NSS4, 11 digitos, numerico, campo opcional
RFC, 13 digitos, alfanumerico, campo obligatorio
RFC2, 13 digitos, alfanumerico, campo opcional
Instituto , datos posibles (IMSS, ISSSTE) obligatorio, lista desplegable
Regimen-IMSS, (son dos valores: 73 o 97) numerico, campo obligatorio, lista desplegable
Regimen-ISSSTE, (son dos valores: 10mo o CI), alfanumerico, campo obligatorio, lista desplegable
Semanas-IMSS, numerico, campo obligatorio
Semanas-ISSSTE, numerico, campo obligatorio
Tramite,  alfanumerico, campo obligatorio, lista desplegable (Invalidez, RT, CEAV, Viudez, Orfandad, Retiro)
Modalidad,  alfanumerico, campo obligatorio, lista desplegable (M10, M40)
PensionDefault, 10 digitos, contabilidad o moneda, campo obligatorio 
PensionNormal, 10 digitos, contabilidad o moneda, campo obligatorio 
Comision, 10 digitos, contabilidad o moneda, campo obligatorio 
Honorarios, 10 digitos, contabilidad o moneda, campo obligatorio 
FechaAlta, tipo fecha (dd-mm-aaaa), campo opcional
FechaBaja, tipo fecha (dd-mm-aaaa), campo opcional
Estatus, cadena, campo obligatorio, lista desplegable (Activo, Suspendido, Terminado, Baja)
TipoCliente, cadena, campo obligatorio, lista desplegable (Cliente, Prospecto, Suspendido, Baja, Imposible)
Celular1, cadena, campo obligatorio
Celular2, cadena, campo opcional
TelCasa, cadena, campo opcional
Correo1, cadena, campo obligatorio
Correo2, cadena, campo opcional
CorreoPersonal, cadena, campo opcional
CuentaImss, cadena, campo opcional (lista de 3 posibles valores: Clientes_A.Correo1, Clientes_A.Correo2, Clientes_A.CorreoP)
PasswordImss, cadena, campo opcional
CuentaIssste, cadena, campo opcional
PasswordIssste, cadena, campo opcional
CuentaInfonavit, cadena, campo opcional (lista de 3 posibles valores: Clientes_A.Correo1, Clientes_A.Correo2, Clientes_A.CorreoP)
PasswordInfonavit, cadena, campo opcional
Referencia, cadena, campo opcional, lista desplegable (Clientes_A.NoCliente) es para llevar una cadena o descendencia de quien me recomendo el cliente
Notas (no se como lo agregues, pero puede tener muchos Notas) tal vez tenga que ser una tabla de puras notas de cada cliente

Documentos, tenemos que agregar un checklist de documentos como:
	INE
	Comprobante de Domicilio
	CURP
	Banco
	Afore
	ActaNacimiento
	ActaDefuncion
	NSS
	RFC


Tabla para familiares, para algunos clientes daremos de alta beneficiarios que tendra que ir cazado el id del cliente con el id del familiar
idFamiliar, numerico
Nombre, cadena, campo obligatorio
Apellido Paterno, cadena, campo obligatorio
Apellido Materno, cadena, campo obligatorio
CURP, 18 digitos, alfanumerico, campo obligatorio
RFC, 13 digitos, alfanumerico, campo opcional
Documentos, tenemos que agregar un checklist de documentos como:
	INE
	Comprobante de Domicilio
	CURP
	Banco
	ActaNacimiento
	ActaMatrimonio
	RFC

si necesitas hacer separacion en varias tablas adelante

No se si necesites crear otros campos para fechas de creacion, eliminacion, actualizacion, datos necesarios para la trazabilidad o logs del sistema

los menus principales seran
Inicio
Dashboard
Operacion
	Activos
	Prospectos
	Suspendidos
	Baja
	Imposible
Catalogos
	Instituto , datos posibles (IMSS, ISSSTE)
	Modalidad,  alfanumerico, campo obligatorio, lista desplegable (M10, M40)
Estadisticas
Configuracion
	Perfil de usuario
	Usuarios
	Permisos
	Roles
	Menu
	Datos de la empresa
	Bitacora

Operacion/Activos
sera un listado de los clientes activos mostrando los siguientes campos
NoCliente, Nombre y Apellidos en un solo campo, celular, NSS, CURP, correo, RFC, FechaNac, edad, instituto, regimen, modalidad, fecha alta, fecha baja, estatus
y en la ultima columna, las acciones de cada cliente como: editar, M10, M40, Semanas, Afore
M10, M40, Semanas, Afore, la accion de cada uan es abrir un link y en esa pagina se insertaran campos, despues lo revisamos

en el boton de editar abriras el cliente con todos sus datos separados en varias pestañas y grupos
Pestaña Cliente
Datos Generales
	Nombre, Apellidos, No Cliente, CURP1, NSS1, RFC, FechaNac, edad

Datos de Contacto
Celular1
Celular2
TelCasa
Correo1
Correo2
CorreoPersonal
CuentaImss
PasswordImss
CuentaIssste
PasswordIssste
CuentaInfonavit
PasswordInfonavit
Referencia

Datos de pension
Estatus
TipoCliente
Instituto
Regimen-IMSS
Regimen-ISSSTE
Semanas-IMSS
Semanas-ISSSTE
Tramite
Modalidad
FechaAlta
FechaBaja
PensionDefault 
PensionNormal


Datos de Contrato
Comision
Honorarios
Contrato

Pestaña Documentos (aqui se agregaran los documentos en pdf y los renombraras "NoCliente_TipoDocumento", ejem, C-1001_INE.pdf, esto lo veremos mucho mas adelante)
Cliente
	INE
	Comprobante de Domicilio
	CURP
	Banco
	Afore
	ActaNacimiento
	ActaDefuncion
	NSS
	RFC

Beneficiario
	INE
	Comprobante de Domicilio
	CURP
	Banco
	ActaNacimiento
	ActaMatrimonio
	RFC
	
	
Pestaña Notas (boton de agregar Notas y guardar nota)
desplegar todas las notas del cliente



el tema de la organazacion de menus, permisos, etc, he visto varias opciones pero lo dejo a tu criterio
https://github.com/julio101290/boilerplate
https://cesarsystems.com.mx/jcposcreator-creador-de-catalogos-php-en-base-en-la-tabla

















