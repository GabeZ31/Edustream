# EduStream - Plataforma Educativa

Proyecto desarrollado en **Laravel** con arquitectura **MVC** completa.

## 🚀 Instalación y Pruebas (Guía para el Revisor)

Este proyecto está configurado para que su revisión sea extremadamente rápida y sencilla mediante el uso de Seeders y Factories de Laravel.

### Pasos para iniciar el proyecto localmente:

1. Clonar o descomprimir el proyecto.
2. Abrir una terminal en la carpeta del proyecto.
3. Instalar las dependencias de PHP:
   ```bash
   composer install
   ```
4. Configurar el archivo de entorno (asegurarse de tener un `.env` válido y la conexión a BD correcta).
5. Ejecutar las migraciones y sembrar la base de datos (¡Este paso crea toda la estructura y datos de prueba!):
   ```bash
   php artisan migrate:fresh --seed
   ```
6. Iniciar el servidor local:
   ```bash
   php artisan serve
   ```

---

## 🔑 Credenciales de Prueba

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
