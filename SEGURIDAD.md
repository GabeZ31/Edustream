# Diccionario de Seguridad: EduStream

Este documento contiene un desglose detallado de todas las medidas de seguridad implementadas en la plataforma EduStream. El objetivo es proporcionar una referencia clara para desarrolladores y auditores sobre las vulnerabilidades mitigadas, las tecnologías empleadas, y la ubicación exacta de los controles dentro de la estructura del proyecto.

---

## Índice de Contenidos
1. [Control de Acceso Centralizado (OWASP A01:2021)](#1-control-de-acceso-centralizado-owasp-a012021)
2. [Prevención de Fuerza Bruta y Seguridad en Autenticación (OWASP A07:2021)](#2-prevención-de-fuerza-bruta-y-seguridad-en-autenticación-owasp-a072021)
3. [Prevención de Abuso y Denegación de Servicio (OWASP A04:2021)](#3-prevención-de-abuso-y-denegación-de-servicio-owasp-a042021)
4. [Sanitización de Cargas y Prevención de Inyecciones (OWASP A03:2021)](#4-sanitización-de-cargas-y-prevención-de-inyecciones-owasp-a032021)
5. [Configuración de Seguridad HTTP y Fuga de Datos (OWASP A05:2021)](#5-configuración-de-seguridad-http-y-fuga-de-datos-owasp-a052021)
6. [Prevención de XSS en Componentes Dinámicos (OWASP A03:2021)](#6-prevención-de-xss-en-componentes-dinámicos-owasp-a032021)
7. [Integridad de Datos y Gestión del Almacenamiento](#7-integridad-de-datos-y-gestión-del-almacenamiento)

---

## 1. Control de Acceso Centralizado (OWASP A01:2021)

### A. Middleware de Administración (`EnsureUserIsAdmin`)
* **Amenaza mitigada**: Elevación de privilegios. Evita que usuarios normales (estudiantes o maestros) accedan a rutas críticas de administración (como cambiar roles de usuarios o eliminarlos) modificando la URL del navegador.
* **Solución**: Se intercepta la solicitud entrante y se valida que el usuario logueado tenga explícitamente el rol `'admin'`. Si no es así, es redirigido con un mensaje de acceso denegado.
* **Archivos y líneas clave**:
  * [EnsureUserIsAdmin.php](app/Http/Middleware/EnsureUserIsAdmin.php#L17-L24) (Líneas 17-24): Implementación del middleware.
  * [web.php](routes/web.php#L37-L41) (Líneas 37-41): Agrupación y protección de las rutas administrativas.
* **Código de referencia**:
  ```php
  // EnsureUserIsAdmin.php
  if (auth()->check() && auth()->user()->rol === 'admin') {
      return $next($request);
  }
  return redirect('/')->with('mensaje', 'Acceso denegado. Se requieren permisos de administrador.');
  ```

### B. Autorización mediante Gates (Políticas)
* **Amenaza mitigada**: Referencia directa insegura a objetos (IDOR). Evita que un maestro intente modificar o eliminar un canal o recurso que pertenece a otro maestro cambiando el ID en el formulario o en la URL.
* **Solución**: Definición de Gates centralizadas en el proveedor de servicios que comprueban si el usuario es dueño del recurso o cuenta con privilegios de administrador antes de proceder.
* **Archivos y líneas clave**:
  * [AppServiceProvider.php](app/Providers/AppServiceProvider.php#L26-L41) (Líneas 26-41): Registro y definición de las Gates `create-content`, `manage-canal` y `manage-recurso`.
  * [CanalController.php](app/Http/Controllers/CanalController.php) (Líneas 13, 29, 38, 65, 74, 93): Uso de las Gates mediante `Gate::denies(...)` para proteger el CRUD de canales.
  * [RecursoController.php](app/Http/Controllers/RecursoController.php) (Líneas 82, 97, 102, 115, 124, 153): Validación de las Gates para el CRUD de recursos.
* **Código de referencia**:
  ```php
  // AppServiceProvider.php
  Gate::define('manage-canal', function (User $user, Canal $canal) {
      return $user->rol === 'admin' || 
             ($user->rol === 'maestro' && $canal->maestro_id === $user->id);
  });
  ```

### C. Bloqueo de Acceso Directo por URL a Recursos sin Suscripción
* **Amenaza mitigada**: Fuga de propiedad intelectual y evasión del sistema de inscripciones. Si un alumno conoce el ID directo de un recurso de video/PDF, podría escribir `edustream.test/recursos/{id}` y visualizarlo sin estar suscrito al canal correspondiente.
* **Solución**: El controlador intercepta las solicitudes de visualización y valida en la tabla de `inscripcions` si el estudiante/maestro se encuentra inscrito en el canal del recurso solicitado. Si no está inscrito, deniega el acceso.
* **Archivos y líneas clave**:
  * [RecursoController.php](app/Http/Controllers/RecursoController.php#L56-L79) (Líneas 56-79): Validación de suscripción en el método `show`.
* **Código de referencia**:
  ```php
  // RecursoController.php
  $esSuscrito = \App\Models\Inscripcion::where('user_id', $user->id)
                                       ->where('canal_id', $recurso->canal_id)
                                       ->exists();
  if (!$esSuscrito) {
      return redirect('/')->with('mensaje', 'No estás inscrito en el canal de este recurso.');
  }
  ```

---

## 2. Prevención de Fuerza Bruta y Seguridad en Autenticación (OWASP A07:2021)

### A. Límite de Intentos (Rate Limiting) en Inicio de Sesión
* **Amenaza mitigada**: Ataques de fuerza bruta y de diccionario de contraseñas. Un atacante automatizado podría adivinar contraseñas infinitamente hasta romper la seguridad de una cuenta.
* **Solución**: Integración del componente `RateLimiter` de Laravel en el proceso de login. Se bloquea temporalmente al usuario por 60 segundos si ingresa credenciales erróneas más de 5 veces consecutivas basadas en su correo electrónico e IP.
* **Archivos y líneas clave**:
  * [AuthController.php](app/Http/Controllers/AuthController.php#L22-L42) (Líneas 22-42): Implementación de la clave del throttle (`throttleKey`), validación del límite, penalización y limpieza del contador al autenticarse con éxito.
* **Código de referencia**:
  ```php
  // AuthController.php
  if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
      $seconds = RateLimiter::availableIn($throttleKey);
      Log::warning("Intento de login bloqueado por fuerza bruta para: " . $request->email);
      return back()->withErrors(['email' => "Demasiados intentos... intente en {$seconds} segundos."]);
  }
  ```

### B. Auditoría de Intentos Fallidos (Logging)
* **Amenaza mitigada**: Falta de visibilidad de ataques activos. Sin logs, un ataque de fuerza bruta exitoso pasaría desapercibido por los administradores.
* **Solución**: Registro automático de advertencias (`Log::warning` y `Log::notice`) en los archivos de log del sistema cada vez que se bloquea una IP o se introduce una contraseña incorrecta.
* **Archivos y líneas clave**:
  * [AuthController.php](app/Http/Controllers/AuthController.php#L26) y [Línea 39](app/Http/Controllers/AuthController.php#L39): Invocación del servicio de Log con detalles de IP y correo.
* **Código de referencia**:
  ```php
  Log::warning("Intento de login bloqueado por fuerza bruta para: " . $request->email . " desde IP: " . $request->ip());
  ```

### C. Requisitos de Complejidad en Contraseñas
* **Amenaza mitigada**: Creación de cuentas con contraseñas débiles o triviales (ej. `12345678`), vulnerables a ataques de diccionario sencillos.
* **Solución**: Validación del campo contraseña al registrarse, requiriendo un mínimo de 8 caracteres con la obligación de contener al menos una letra y un número.
* **Archivos y líneas clave**:
  * [RegistroRequest.php](app/Http/Requests/RegistroRequest.php#L19-L23) (Líneas 19-23): Aplicación de la regla nativa de Laravel `Password::min(8)->letters()->numbers()`.

---

## 3. Prevención de Abuso y Denegación de Servicio (OWASP A04:2021)

### A. Limitación de Peticiones en el Registro de Usuarios (Throttle)
* **Amenaza mitigada**: Creación masiva automatizada de usuarios (Spam/Bots) que inunde la base de datos y sature el almacenamiento o CPU del servidor (Denegación de Servicio a nivel de aplicación).
* **Solución**: Aplicación de un middleware limitador de peticiones directo en la ruta POST de registro (`throttle:5,10`), permitiendo un máximo de 5 peticiones cada 10 minutos por dirección IP.
* **Archivos y líneas clave**:
  * [web.php](routes/web.php#L55) (Línea 55): Middleware `throttle:5,10` adjunto a la ruta `registro.post`.
* **Código de referencia**:
  ```php
  Route::post('/registro', [AuthController::class, 'registro'])->middleware('throttle:5,10')->name('registro.post');
  ```

---

## 4. Sanitización de Cargas y Prevención de Inyecciones (OWASP A03:2021)

### A. Validación Estricta de Archivos Subidos
* **Amenaza mitigada**: Carga de archivos maliciosos (un script `.php` camuflado) que al ejecutarse en el servidor le dé control remoto al atacante (Remote Code Execution - RCE).
* **Solución**: Reglas de validación dinámica basadas en el tipo de recurso seleccionado. Si el usuario indica que es un video, solo se permite subir archivos `.mp4` o `.webm`. Si es un PDF, solo se permite `.pdf`. Se restringe el tamaño a 500 MB máximos para mitigar saturación de almacenamiento.
* **Archivos y líneas clave**:
  * [RecursoRequest.php](app/Http/Requests/RecursoRequest.php#L24-L35) (Líneas 24-35): Lógica condicional de validación por MIME types y extensiones seguras.
* **Código de referencia**:
  ```php
  $tipo = $this->input('tipo');
  if ($tipo === 'video') {
      $rules['archivo'] = 'required|file|max:512000|mimes:mp4,webm';
  } elseif ($tipo === 'pdf') {
      $rules['archivo'] = 'required|file|max:512000|mimes:pdf';
  }
  ```

---

## 5. Configuración de Seguridad HTTP y Fuga de Datos (OWASP A05:2021)

### A. Middleware de Cabeceras de Seguridad (`SecureHeaders`)
* **Amenaza mitigada**:
  * **Clickjacking**: Un atacante podría incrustar EduStream en un `<iframe>` invisible en un sitio de terceros para engañar al usuario y capturar sus clics.
  * **MIME Sniffing**: El navegador del usuario podría intentar interpretar archivos multimedia como código ejecutable si no se declara la cabecera adecuada.
* **Solución**: Un middleware global inyecta cabeceras HTTP restrictivas en cada respuesta enviada por el servidor web.
* **Archivos y líneas clave**:
  * [SecureHeaders.php](app/Http/Middleware/SecureHeaders.php#L21-L26) (Líneas 21-26): Cabeceras `X-Frame-Options`, `X-Content-Type-Options` y `Referrer-Policy`.
* **Código de referencia**:
  ```php
  // SecureHeaders.php
  $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
  $response->headers->set('X-Content-Type-Options', 'nosniff');
  $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
  ```

### B. Desactivación de Autocompletado Sensible
* **Amenaza mitigada**: Fuga de datos confidenciales por lectura del historial de autocompletado en navegadores compartidos o comprometidos.
* **Solución**: Declaración explícita de atributos `autocomplete="new-password"` y `autocomplete="current-password"` en las contraseñas, e inhabilitación de autocompletado en el ingreso de códigos del canal.
* **Archivos y líneas clave**:
  * [login.blade.php](resources/views/auth/login.blade.php): Formularios de ingreso.
  * [registro.blade.php](resources/views/auth/registro.blade.php): Formularios de registro de usuario.
  * [base.blade.php](resources/views/layouts/base.blade.php#L74) (Líneas 74 y 122): Atributo `autocomplete="off"` para los inputs de código de acceso.

---

## 6. Prevención de XSS en Componentes Dinámicos (OWASP A03:2021)

### A. Sanitización de Textos Dinámicos en Modales
* **Amenaza mitigada**: Inyección Cross-Site Scripting basada en DOM (DOM-based XSS). Si un formulario contiene un mensaje personalizado con código HTML o scripts, este podría ejecutarse cuando JavaScript dibuje dinámicamente el contenido del modal de confirmación.
* **Solución**: Uso de la propiedad `.textContent` de JavaScript para renderizar el texto en el modal. Esta propiedad anula la interpretación HTML y escribe todo input estrictamente como texto plano.
* **Archivos y líneas clave**:
  * [base.blade.php](resources/views/layouts/base.blade.php#L335) (Línea 335): Inserción del mensaje al modal.
* **Código de referencia**:
  ```javascript
  // base.blade.php
  var modalMsg = document.getElementById('confirmModalMessage');
  if (modalMsg) modalMsg.textContent = msg; // Seguro contra inyección XSS
  ```

---

## 7. Integridad de Datos y Gestión del Almacenamiento

### A. Limpieza de Archivos Huérfanos al Modificar o Eliminar Contenido
* **Amenaza mitigada**: Denegación de servicio por agotamiento de espacio en disco en el servidor web.
* **Solución**: Implementación de rutinas de borrado físico del archivo adjunto (`Storage::disk('public')->delete(...)`) cuando un recurso se actualiza (eliminando el archivo reemplazado) o cuando se borra un canal completo (barriendo y eliminando todos los archivos de sus recursos).
* **Archivos y líneas clave**:
  * [CanalController.php](app/Http/Controllers/CanalController.php#L97-L102) (Líneas 97-102): Limpieza física de todos los archivos del canal antes de borrar el registro de la base de datos.
  * [RecursoController.php](app/Http/Controllers/RecursoController.php#L139-L142) y [Líneas 157-159](app/Http/Controllers/RecursoController.php#L157-L159): Limpieza física al actualizar o eliminar un recurso individual.
* **Código de referencia**:
  ```php
  // CanalController.php (Eliminación en cascada en almacenamiento físico)
  foreach ($canale->recursos as $recurso) {
      if ($recurso->archivo && \Illuminate\Support\Facades\Storage::disk('public')->exists($recurso->archivo)) {
          \Illuminate\Support\Facades\Storage::disk('public')->delete($recurso->archivo);
      }
  }
  ```
