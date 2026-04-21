# Bolsa de Empleo UNIPAZ — Guía de Instalación

## Requisitos del servidor
- PHP >= 8.2
- Composer >= 2.x
- MySQL >= 8.0 (o MariaDB >= 10.5)
- Node.js >= 18 (solo para desarrollo)
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

---

## 1. Clonar / descomprimir el proyecto

```bash
# Si tienes el ZIP
unzip bolsa-empleo-unipaz.zip
cd bolsa-empleo-unipaz
```

---

## 2. Instalar dependencias PHP

```bash
composer install
```

> **Paquetes instalados automáticamente:**
> - `laravel/framework ^11`
> - `laravel/socialite ^5` — Login con Google
> - `laravel/sanctum ^4`

---

## 3. Configurar el archivo .env

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con tus datos:

```env
# Base de datos
DB_DATABASE=bolsa_empleo_unipaz
DB_USERNAME=root
DB_PASSWORD=tu_contraseña

# Correo (recomendado: Gmail con App Password o servidor SMTP institucional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=bolsaempleo@unipaz.edu.co
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx    # App Password de Google
MAIL_FROM_ADDRESS=bolsaempleo@unipaz.edu.co

# Google OAuth
GOOGLE_CLIENT_ID=xxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=xxxxx
GOOGLE_REDIRECT_URI=https://tu-dominio.com/auth/google/callback

# Colas (para notificaciones por correo)
QUEUE_CONNECTION=database
```

---

## 4. Crear la base de datos MySQL

```sql
CREATE DATABASE bolsa_empleo_unipaz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## 5. Ejecutar migraciones y datos de prueba

```bash
php artisan migrate
php artisan db:seed
```

**Credenciales de acceso de prueba:**
| Rol         | Correo                          | Contraseña    |
|-------------|----------------------------------|---------------|
| Administrador | admin@unipaz.edu.co            | *(Ver .env)* |
| Empresa     | info@tecnosoluciones.com         | *(Ver .env)* |
| Estudiante  | *(Login con Google @unipaz.edu.co)* | —          |

---

## 6. Configurar almacenamiento

```bash
php artisan storage:link
```

Esto crea el enlace simbólico para acceder a CVs y logos subidos.

---

## 7. Configurar Google OAuth

### Pasos en Google Cloud Console:
1. Ir a https://console.cloud.google.com/
2. Crear un nuevo proyecto: **"Bolsa Empleo UNIPAZ"**
3. Ir a **APIs y servicios → Credenciales**
4. Clic en **Crear credenciales → ID de cliente de OAuth**
5. Tipo de aplicación: **Aplicación web**
6. Agregar URI de redireccionamiento autorizado:
   - Desarrollo: `http://localhost:8000/auth/google/callback`
   - Producción: `https://tu-dominio.com/auth/google/callback`
7. Copiar **Client ID** y **Client Secret** al `.env`

### Restricción de dominio (importante):
En el controlador `GoogleController.php` ya está configurado que solo se permite el correo `@unipaz.edu.co`:

```php
if (!str_ends_with($googleUser->getEmail(), '@unipaz.edu.co')) {
    return redirect()->route('login')
        ->with('error', 'Solo se permite el ingreso con correo @unipaz.edu.co');
}
```

---

## 8. Configurar colas para notificaciones

```bash
# Crear tabla de colas
php artisan queue:table
php artisan migrate

# Iniciar worker (desarrollo)
php artisan queue:work

# Para producción, usar Supervisor:
# /etc/supervisor/conf.d/bolsa-empleo-queue.conf
```

**Configuración de Supervisor (producción):**
```ini
[program:bolsa-empleo-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/bolsa-empleo/artisan queue:work database --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/bolsa-empleo/storage/logs/queue.log
```

---

## 9. Iniciar servidor de desarrollo

```bash
php artisan serve
```

Visita: **http://localhost:8000**

---

## Estructura del sistema

### Roles de usuario
| Rol | Descripción |
|-----|-------------|
| `admin` | Administrador UNIPAZ: aprueba empresas, gestiona usuarios, ve reportes |
| `company` | Empresario: registra empresa, publica vacantes, gestiona postulaciones |
| `student` | Estudiante: busca vacantes, se postula con Google OAuth |

### Flujo del sistema
```
1. Estudiante → Inicia sesión con Google @unipaz.edu.co
2. Empresa → Se registra → Admin aprueba → Publica vacantes
3. Estudiante → Busca y se postula → Empresa revisa y actualiza estado
4. Notificaciones por correo en cada paso del proceso
```

### Base de datos — Tablas principales
| Tabla | Descripción |
|-------|-------------|
| `users` | Todos los usuarios (admin, empresa, estudiante) |
| `student_profiles` | Perfil extendido del estudiante (programa, CV, etc.) |
| `companies` | Perfil de empresa con estado de aprobación |
| `job_postings` | Vacantes publicadas por las empresas |
| `applications` | Postulaciones de estudiantes a vacantes |
| `notifications` | Notificaciones del sistema |

---

## Despliegue en producción

### Configuraciones importantes:
```bash
# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Variables de entorno producción
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bolsaempleo.unipaz.edu.co
```

### Permisos de carpetas:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Soporte

Para soporte técnico, contactar al área de Sistemas de UNIPAZ.

**Desarrollado para:** Universidad de Paz (UNIPAZ) — Barrancabermeja, Colombia
