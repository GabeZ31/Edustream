# CAMBIOS DE ESTILO — edustream
Proyecto: c:/xampp/htdocs/edustream
Referencia de estilo: c:/xampp/htdocs/L21380363/BDatos
Tipo de cambios: SOLO SINTAXIS/FORMATO. Cero impacto en diseño o funcionalidad.

---

## REGLAS DE ESTILO APLICADAS (estilo de Cesar L21380363)

1. Llave de apertura en misma linea de clase y metodo: `class Foo{` y `public function bar(){`
2. Propiedades del modelo sin indentacion extra (al nivel del cuerpo de clase, no del bloque)
3. `use` de controllers en routes/web.php mezclados con las rutas, no todos al inicio
4. Comentarios de seccion en MAYUSCULAS: `// AQUI VAN LAS RUTAS`
5. Comentarios Blade cortos: `{{-- Titulo --}}` sin acentos
6. `return view()` con array en una sola linea cuando los params caben: `['key' => $val, 'key2' => $val2]`
7. Sin DocBlocks (bloques /** */ eliminados)
8. Texto en strings PHP/Blade sin tildes ni acentos (informal, estilo del autor)
9. Minimas lineas en blanco entre bloques de codigo

---

## ARCHIVOS MODIFICADOS

### routes/web.php
ANTES: todos los `use` al inicio del archivo
DESPUES: `use App\Http\Controllers\RecursoController;` colocado junto a sus rutas
ANTES: sin comentarios de seccion
DESPUES: `// INICIO`, `// VER EL CONTENIDO`, `// ADMIN Y SUBIDA`

### app/Http/Controllers/RecursoController.php
ANTES: `class RecursoController extends Controller\n{`  (llave en linea nueva)
DESPUES: `class RecursoController extends Controller{`  (llave en misma linea)
ANTES: metodos con llave en linea nueva `public function index()\n    {`
DESPUES: `public function index(){`
ANTES: `return view('recursos.index', [\n    'recursos' => ...,\n    'canales' => ...,\n]);`
DESPUES: `return view('recursos.index', ['recursos' => $recursos, 'canales' => $canales]);`
NOTA: el metodo show() antes tenia llave desalineada respecto a la clase, ahora es consistente

### app/Models/Canal.php
ANTES: propiedades con 4 espacios de indentacion dentro de la clase
DESPUES: propiedades sin indentacion (al ras del cuerpo)
ANTES: `public function recursos()\n    {`
DESPUES: `public function recursos(){`
ANTES: comentario `// Un canal tiene MUCHOS -- recursos --` (ya estaba en español, conservado)

### app/Models/Recurso.php
ANTES: propiedades con 4 espacios de indentacion
DESPUES: sin indentacion extra
ANTES: `public function canal()\n    {`
DESPUES: `public function canal(){`

### app/Models/User.php
ANTES: DocBlocks con @var y @return eliminados
DESPUES: modelo limpio, solo use traits y propiedades

### resources/views/layouts/base.blade.php
ANTES: codigo duplicado (el bloque bottom-nav y el script de alerta aparecian DOS veces, y habia dos `</body></html>`)
DESPUES: duplicados eliminados (esto corrigio un bug real del archivo original)
ANTES: comentarios Blade con tildes `{{-- Navbar desde partial --}}`
DESPUES: sin tildes `{{-- Contenido --}}`
ANTES: `@if(session('mensaje'))` desalineado fuera del bloque
DESPUES: alineado con 4 espacios como el resto

### resources/views/partials/navbar.blade.php
ANTES: sin cambios de funcionalidad
DESPUES: indentacion consistente, comentario `{{-- Links solo en desktop --}}` y `{{-- Buscador --}}` conservados

### resources/views/recursos/index.blade.php
ANTES: `No hay recursos todavía.` (con tilde)
DESPUES: `No hay recursos todavia.` (sin tilde, estilo del autor)
Estructura y logica: identica

### resources/views/recursos/show.blade.php
ANTES: tildes en comentarios y texto: `métricas`, `está`, `Más del canal`
DESPUES: sin tildes: `metricas`, `esta`, `Mas del canal`
ANTES: comentarios Blade con guion largo `{{-- Columna lateral — recursos --}}`
DESPUES: guion simple `{{-- Columna lateral - recursos --}}`
Logica JS (WebTorrent): identica, sin cambios

### resources/views/admin/create.blade.php
ANTES: labels con tilde: `Título`, `Descripción`, `Más`
DESPUES: sin tilde: `Titulo`, `Descripcion`
ANTES: comentario `{{-- Errores de validación --}}`
DESPUES: `{{-- Errores de validacion --}}`
Validacion, rutas y logica del form: identicas

### resources/views/components/card-recurso.blade.php
ANTES: indentacion con espacios inconsistente
DESPUES: 4 espacios consistentes en todo el componente
Logica de @if/@elseif para tipos: identica

---

## IMPACTO TECNICO

riesgo_real: ninguno
advertencias_posibles:
- Intelephense (VSCode) puede marcar warning por `use` mezclado en routes/web.php. Es estetico, no error.
- Texto sin tildes es gramaticalmente informal pero no afecta ejecucion

bug_corregido: base.blade.php tenia body/html duplicados con bottom-nav repetido. Fue eliminado.

---

## RECOMENDACIONES PARA CLAUDE

### CONTEXTO DEL AUTOR
- Nombre: Cesar, estudiante de desarrollo web (nivel intermedio-basico)
- Proyecto de referencia de su estilo: c:/xampp/htdocs/L21380363/BDatos
- El autor aprende haciendo. Su codigo funciona aunque no siga PSR-12.
- No corregir su estilo automaticamente. Respetarlo y replicarlo.

### AL GENERAR CODIGO NUEVO para este proyecto, SIEMPRE usar:
- Llaves en misma linea: `class Foo{` y `public function bar(){`
- Propiedades de modelo sin indentacion extra dentro de la clase
- `return view('vista', ['var' => $valor])` en una linea si caben los parametros
- Comentarios de seccion en MAYUSCULAS: `// AQUI VA X`
- Comentarios Blade sin tildes y cortos: `{{-- info --}}`
- Sin DocBlocks (no generar bloques /** */)
- Minimas lineas en blanco (maximo 1 entre bloques)
- Strings en vistas sin tildes: `"catalogo"`, `"descripcion"`, `"titulo"`

### AL GENERAR CODIGO NUEVO para este proyecto, NUNCA usar:
- Llave en linea nueva: NO hacer `class Foo\n{`
- DocBlocks: NO hacer `/** @var list<string> */`
- Multiples lineas en blanco consecutivas
- `use` statements todos al inicio si se puede colocar junto a su grupo de rutas
- PSR-12 estricto (indentacion de propiedades dentro de clase)

### SOBRE EL DISEÑO
- El CSS esta en public/css/edustream.css. NO modificarlo salvo que se pida.
- El layout base esta en resources/views/layouts/base.blade.php
- El navbar esta en resources/views/partials/navbar.blade.php
- Componente de tarjeta: resources/views/components/card-recurso.blade.php
- Cualquier cambio visual debe mantener la estetica actual (Bootstrap 5 + clases badge-tipo + colores #0C447C y #378ADD)

### SOBRE LA ARQUITECTURA
- Framework: Laravel (PHP)
- Entorno: XAMPP local, Windows
- Modelos: Canal (canals), Recurso (recursos), User
- Relacion: Canal hasMany Recurso / Recurso belongsTo Canal
- Almacenamiento de archivos: storage/app/public/recursos (disco 'public')
- WebTorrent activo para videos (cliente P2P en el navegador)
- Rutas definidas: GET /, GET /recursos/{id}, GET /admin/recursos/create, POST /admin/recursos

### ADVERTENCIA DE INTERPRETACION
- Si ves codigo sin indentacion en los modelos, ES INTENCIONAL (estilo del autor)
- Si ves `use` mezclado en routes/web.php, ES INTENCIONAL
- Si ves strings sin tildes, ES INTENCIONAL
- No "corregir" estos patrones a menos que el autor lo pida explicitamente
- El bug del base.blade.php (codigo duplicado) ya fue corregido en esta sesion

### FLUJO DE TRABAJO DEL AUTOR
- Desarrolla de forma incremental, primero rutas simples luego las compleja
- Prefiere comentar codigo en vez de borrarlo cuando prueba cosas
- Usa @component con @slot (estilo antiguo de Blade) ademas de <x-componente> (estilo nuevo)
- Mezcla ambos estilos de componente segun el archivo, no forzar uno solo
- Mantener una sintaxys entendible y adecuada a su estilo y nivel, no forzar un profesionalismo en syntaxis aunque si se busque en funcionalidad

