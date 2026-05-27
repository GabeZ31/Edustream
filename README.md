# EduStream - Plataforma Educativa

Proyecto desarrollado en **Laravel** con arquitectura **MVC** completa.

##  Instalación y Pruebas (Guía para el Revisor)

Este proyecto está configurado para que su revisión sea extremadamente rápida y sencilla mediante el uso de Seeders y Factories de Laravel.

### Pasos para iniciar el proyecto localmente:

1. Clonar o descomprimir el proyecto.
2. Abrir una terminal en la carpeta del proyecto.
3. Instalar las dependencias de PHP:
   ```bash
   composer install
   ```
4. Crear y configurar el archivo de entorno:
   - Duplicar el archivo de ejemplo: `cp .env.example .env` (o `copy .env.example .env` en Windows).
   - Generar la llave de la aplicación:
     ```bash
     php artisan key:generate
     ```
   - Configurar la conexión a la base de datos en el archivo `.env` con tus credenciales locales.
5. Ejecutar las migraciones y sembrar la base de datos (¡Este paso crea la estructura y los usuarios de prueba!):
   ```bash
   php artisan migrate --seed
   ```
6. Crear el enlace simbólico para que los archivos multimedia subidos (videos, PDFs) sean accesibles públicamente:
   ```bash
   php artisan storage:link
   ```
7. Iniciar el servidor local:
   ```bash
   php artisan serve
   ```

---

##  Credenciales de Prueba

Al correr el comando `--seed`, el sistema automáticamente generó toda una red de canales y recursos. Para probar los distintos niveles de permisos y vistas, puedes usar cualquiera de estos usuarios fijos:

### 1. Administrador (Control Total)
- **Correo:** `ladypure31@gmail.com`
- **Contraseña:** `password123`
- *Permisos:* Ver Dashboard Admin, cambiar roles de usuarios, eliminar usuarios, subir/editar/borrar recursos de cualquier persona.

### 2. Maestro (Gestión de Canales Propios)
- **Correo:** `maestro1@edustream.com`
- **Contraseña:** `password123`
- *Permisos:* Crear, editar y borrar sus propios canales. Subir recursos y asignarlos a sus canales. Borrar sus propios recursos.

### 3. Estudiante (Visualización)
- **Correo:** `alumno1@edustream.com`
- **Contraseña:** `password123`
- *Permisos:* Navegar, buscar canales y visualizar recursos. No puede acceder a paneles de administración.

> **Nota:** El sistema también generó otros maestros y estudiantes con canales y recursos aleatorios para que la página de inicio (Grid) se vea poblada y realísticamente distribuida.
