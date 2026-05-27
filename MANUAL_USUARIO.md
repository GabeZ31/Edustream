# Manual de Capacitación y Uso: EduStream

Este manual está diseñado para orientar a los nuevos usuarios de la plataforma **EduStream** según su nivel de acceso (rol). La plataforma cuenta con tres roles diferenciados: **Estudiante**, **Maestro** y **Administrador**.

---

## 📌 Índice
1. [Introducción a EduStream](#1-introducción-a-edustream)
2. [Guía para Estudiantes](#2-guía-para-estudiantes)
3. [Guía para Maestros](#3-guía-para-maestros)
4. [Guía para Administradores](#4-guía-para-administradores)
5. [Buenas Prácticas de Seguridad en la Cuenta](#5-buenas-prácticas-de-seguridad-en-la-cuenta)

---

## 1. Introducción a EduStream

EduStream es una plataforma de streaming educativo y distribución de recursos. Los estudiantes pueden acceder a videos, documentos y PDFs subidos por sus profesores. 

Para mantener la privacidad y organización, el acceso a los contenidos se gestiona a través de **Canales**. Un estudiante solo puede ver los canales a los que se ha inscrito mediante un **código de acceso único** proporcionado por el maestro correspondiente.

---

## 2. Guía para Estudiantes

Como estudiante, tu flujo principal consiste en inscribirte a materias o talleres y consumir los recursos interactivos.

### A. Registro e Inicio de Sesión
1. Entra a la página de [Registro](http://localhost:8000/registro).
2. Llena tu nombre completo, correo electrónico y crea una contraseña segura (mínimo 8 caracteres, conteniendo al menos letras y números).
3. Selecciona el rol **Estudiante** y presiona registrarse.
4. Si ya tienes una cuenta, ingresa desde la sección de [Login](http://localhost:8000/login).

> [!NOTE]
> Por seguridad contra ciberataques, si ingresas tu contraseña incorrectamente 5 veces, el sistema bloqueará tu acceso durante 60 segundos.

### B. Inscripción a Canales (Materias)
Al ingresar por primera vez, tu página de inicio y tu barra lateral estarán vacías. Para unirte a una clase:
1. Pídele al maestro de la materia el **Código de Acceso** de su canal (es un código único de 6 caracteres, por ejemplo: `A3B9X1`).
2. En la barra lateral izquierda (computadora) o en el botón "Canales" (celular), ubica el apartado **Inscribirse por código**.
3. Introduce el código en la casilla de texto y presiona el botón con el icono `+` (o **Unirse** en móvil).
4. El canal se añadirá automáticamente a tu menú lateral y verás los videos y documentos disponibles en tu página de inicio.

### C. Visualización de Contenidos y Participación
1. Haz clic en la tarjeta de cualquier recurso (video, PDF o documento) desde tu muro de inicio.
2. Si es un video, podrás reproducirlo directamente en un reproductor multimedia de alto rendimiento. Si es un PDF o documento, se habilitará un lector/descarga seguro.
3. En la sección inferior, verás la zona de **Discusión**. Escribe tus dudas o aportes en la caja de comentarios y presiona **Publicar comentario**.
4. Puedes eliminar tus propios comentarios si lo requieres, pero no los de tus compañeros.

---

## 3. Guía para Maestros

Los maestros son los creadores y gestores de contenido en la plataforma. Tienen permisos para crear canales, subir recursos y moderar comentarios.

### A. Gestión de Canales (Mis Canales)
1. En la parte superior derecha de la pantalla, despliega tu menú de usuario y selecciona **Mis canales**.
2. **Crear un Canal**:
   * Haz clic en **Crear Nuevo Canal**.
   * Ponle un nombre (ej. *Matemáticas Aplicadas II*) y una breve descripción.
   * Al guardar, el sistema generará automáticamente un **Código de Acceso único**.
3. **Compartir el Código**:
   * En la tabla de tus canales, verás una insignia con el código.
   * Haz clic en los **3 puntitos (Menú de Opciones)** y selecciona **Copiar código**. 
   * Compártelo con tus alumnos por correo o chat para que puedan inscribirse.

### B. Subida de Recursos (Material de Clase)
1. En la barra superior, haz clic en **Subir recurso** (o accede desde el menú offcanvas en móviles).
2. Rellena los datos solicitados:
   * **Título** del recurso.
   * **Descripción** clara del tema.
   * **Canal**: Selecciona a cuál de tus canales pertenece.
   * **Tipo**: Elige si subirás un *Video*, *PDF*, *Documento (Word, PPT, etc.)* u *Otro (ZIP, RAR, etc.)*.
   * **Archivo**: Selecciona el archivo de tu computadora.
   * **Portada**: Elige una de las portadas predeterminadas y elegantes para que tu recurso luzca premium en el catálogo.
3. Presiona **Subir Recurso**.

> [!WARNING]
> El sistema valida estrictamente que el archivo corresponda al tipo que seleccionaste. Si indicas "PDF", el sistema rechazará la subida si intentas subir un archivo de video u otro formato diferente, previniendo fallos y manteniendo ordenado el servidor.

### C. Editar y Borrar Contenido
* **Editar**: Si necesitas actualizar un recurso (por ejemplo, subir una versión corregida de un PDF), ingresa a los detalles del recurso, haz clic en **Editar**, haz los cambios y sube el nuevo archivo. El sistema eliminará automáticamente el archivo viejo del servidor para no desperdiciar espacio en disco.
* **Borrar**: Si eliminas un recurso o un canal entero, aparecerá un **Modal de Confirmación Seguro**.
  * Por seguridad y ergonomía visual, el botón de confirmación ("Sí, proceder") se ubica a la izquierda en color rojo, y el botón seguro de cancelar ("Cancelar") se ubica a la derecha en color azul.
  * Al eliminar un canal, EduStream borrará en cascada tanto los registros como todos los archivos físicos subidos a sus recursos del disco del servidor, previniendo basura informática.

---

## 4. Guía para Administradores

Los administradores tienen control total sobre la plataforma para asegurar el correcto comportamiento de la comunidad y realizar mantenimiento general.

### A. Panel de Control (Dashboard Admin)
1. Accede desde tu menú de perfil en **Dashboard Admin**.
2. **Edición rápida de roles**:
   * En la tabla de usuarios registrados verás el rol de cada uno.
   * Puedes promover a un estudiante a maestro haciendo clic en el menú desplegable del rol. El cambio se guardará automáticamente al instante sin tener que recargar la página.
3. **Eliminar Usuarios**:
   * Puedes dar de baja cuentas inactivas o infractoras haciendo clic en **Eliminar**. Se solicitará confirmación previa mediante el modal de seguridad.

> [!IMPORTANT]
> Por protección de la plataforma, el sistema impide que te elimines a ti mismo o que te quites tu propio rol de administrador. Esto previene que la plataforma se quede huérfana de administradores de forma accidental.

---

## 5. Buenas Prácticas de Seguridad en la Cuenta

EduStream implementa medidas alineadas al estándar de seguridad OWASP para proteger tu información. Te recomendamos complementar estas medidas con las siguientes acciones:

1. **Contraseñas Únicas**: No utilices la misma contraseña que usas en otras redes sociales o correos electrónicos.
2. **Cierre de Sesión Activo**: Si utilizas una computadora pública o compartida, asegúrate siempre de presionar **Cerrar sesión** al finalizar tus clases.
3. **No compartas credenciales**: El sistema detecta intentos anormales de inicio de sesión. Compartir tu cuenta podría causar bloqueos temporales por nuestro módulo de tasa de peticiones (*Rate Limiter*).
4. **Verificación de Códigos**: Los códigos de acceso son sensibles a mayúsculas y únicos. No los publiques en foros abiertos para evitar el ingreso de usuarios ajenos a la clase.
