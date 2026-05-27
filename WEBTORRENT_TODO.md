# WEBTORRENT — Plan de implementacion
Proyecto: edustream
Enfoque: generacion de torrent en el NAVEGADOR (cliente JS), no en el servidor (PHP)
Libreria: WebTorrent.js (ya incluida via CDN en show.blade.php)

---

## COMO FUNCIONA (resumen)

1. Admin selecciona video en el form
2. WebTorrent.js en el navegador seedea el archivo y genera un magnetURI
3. El magnetURI se guarda en un campo oculto del form
4. El form envia: video + magnetURI al servidor
5. El servidor guarda ambos en la BD (el video en storage, el magnet en columna torrent)
6. Visitante abre el video → WebTorrent carga el magnetURI → busca peers → P2P activo

NOTA: el admin debe mantener la pestaña abierta para sembrar el archivo.

---

## ARCHIVOS A MODIFICAR

### [ ] 1. resources/views/admin/create.blade.php
OBJETIVO: cuando el admin selecciona el video, WebTorrent.js lo seedea y captura el magnetURI
CAMBIOS:
- Agregar <input type="hidden" name="torrent" id="torrent-magnet"> al form
- Agregar CDN de WebTorrent.js al final de la vista
- Agregar script JS que:
    a) Escucha el evento change del input de archivo
    b) Pasa el archivo a client.seed()
    c) Cuando seed() termina, guarda torrent.magnetURI en el input hidden
    d) Muestra al admin una indicacion visual de que el torrent fue generado
    e) Habilita el boton Subir solo cuando el magnetURI este listo

### [ ] 2. app/Http/Controllers/RecursoController.php — metodo store()
OBJETIVO: aceptar y guardar el magnetURI que llega del form
CAMBIOS:
- Agregar 'torrent' a las reglas de validacion: 'torrent' => 'required|string'
- Agregar 'torrent' => $request->torrent en el Recurso::create([...])
- Sin mas cambios, el modelo ya tiene 'torrent' en $fillable

### [ ] 3. resources/views/recursos/show.blade.php
OBJETIVO: usar el magnetURI directamente en vez de una ruta de archivo .torrent
CAMBIOS:
- Linea actual:  const torrentUrl = '/storage/{{ $recurso->torrent }}';
- Linea nueva:   const torrentUrl = '{{ $recurso->torrent }}';
- Agregar logica de fallback:
    si WebTorrent falla o no encuentra peers en X segundos
    → cargar el video directo desde /storage/{{ $recurso->archivo }}
    → luego intentar seedear ese video para futuros visitantes

---

## DETALLES TECNICOS

magnetURI ejemplo:
magnet:?xt=urn:btih:abc123&dn=mi-video.mp4&tr=wss://tracker.openwebtorrent.com

trackers WebSocket a usar (van dentro del magnetURI automaticamente):
- wss://tracker.openwebtorrent.com
- wss://tracker.btorrent.xyz

columna BD: recursos.torrent (ya existe en el modelo y en $fillable)

---

## ORDEN DE IMPLEMENTACION

[ ] Paso 1 — Modificar show.blade.php (cambio minimo, probar que acepta magnet)
[ ] Paso 2 — Modificar create.blade.php (agregar JS de WebTorrent seed)
[ ] Paso 3 — Modificar RecursoController@store (aceptar campo torrent)
[ ] Paso 4 — Prueba completa: subir video, verificar magnet en BD, abrir en 2 pestanas
[ ] Paso 5 — Agregar fallback en show.blade.php si no hay peers

---

## ADVERTENCIAS

- El admin DEBE mantener la pestana del form abierta para sembrar
- Si nadie siembra, el torrent queda sin peers y el video no carga por P2P
- El fallback del Paso 5 es importante para que el video siempre cargue aunque falle P2P
- WebTorrent.js puede tardar unos segundos en generar el magnetURI segun el tamaño del video
- El boton Subir debe estar deshabilitado hasta que el magnetURI este listo
