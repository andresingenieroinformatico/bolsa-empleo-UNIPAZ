# Reporte de Mejoras y Auditoría — Bolsa de Empleo UNIPAZ

Este documento detalla las mejoras realizadas en el sistema, actuando como QA para encontrar fallas y como Desarrollador Senior para solucionarlas.

## 1. Seguridad e Integridad de Datos

| Mejora | Ubicación | Descripción del Fallo / Oportunidad | Acción Realizada |
| :--- | :--- | :--- | :--- |
| **Protección de Vacantes** | `routes/web.php` | Las empresas pendientes de aprobación podían acceder a rutas de creación de vacantes si conocían la URL. | Se aplicó el middleware `company.approved` a todo el grupo de rutas de recursos de vacantes. |
| **Limitación de Tasa (Rate Limiting)** | `routes/web.php` | Las rutas de login y registro no tenían protección contra ataques de fuerza bruta. | Se añadió el middleware `throttle:10,1` a todas las rutas de autenticación de invitados. |
| **Limpieza de Controladores** | `JobPostingController.php` | El controlador tenía lógica manual de validación de aprobación, lo que duplicaba código y era propenso a errores. | Se eliminaron los checks manuales `isApproved()`, delegando la responsabilidad al middleware de rutas. |

## 2. Experiencia de Usuario y Diseño (Aesthetics)

| Mejora | Ubicación | Lo que estaba mal | Mejora Aplicada |
| :--- | :--- | :--- | :--- |
| **Sistema de Diseño Premium** | `resources/css/unipaz-premium.css` | Los estilos estaban dispersos y eran básicos (Bootstrap puro). | Se creó un archivo de CSS dedicado con variables modernas, efectos de Glassmorphism y sombras premium. |
| **Animaciones Dinámicas** | `home.blade.php` & `app.blade.php` | La interfaz se sentía estática y poco profesional al cargar. | Se implementaron animaciones de entrada (`fadeInUp`, `fadeInScale`) en secciones clave y en el layout global. |
| **Efectos de Interacción** | `home.blade.php` | Las tarjetas de vacantes no tenían feedback visual al pasar el mouse. | Se añadieron clases `premium-hover` que proporcionan elevación y suavidad en la interacción. |
| **Optimización de Assets** | `home.blade.php` | Uso de lógica Blade en atributos `style` causaba errores de linting CSS. | Se migraron los retrasos de animación a atributos `data-delay` procesados por JS, eliminando errores de validación. |
| **Corrección de Tipado** | `routes/web.php` | El analizador estático no reconocía correctamente el método `user()` en el helper `auth()`. | Se cambió a `request()->user()` para mejorar la compatibilidad con herramientas de análisis estático (linting). |
| **Jerarquía de Capas (Z-Index)** | `unipaz-premium.css` | Los menús desplegables del perfil quedaban ocultos detrás de elementos pegajosos (sticky) como el panel de postulación. | Se forzó un sistema estricto de capas (`z-index`) garantizando la máxima prioridad para la navbar y los menús dropdown. |
| **Validación Lógica de CV** | `JobController.php` | El sistema obligaba a subir un archivo PDF incluso si el estudiante ya tenía su CV en el perfil. | Se ajustó la validación para que sea opcional si ya existe un CV previo, evitando errores de formulario redundantes. |
| **Visibilidad de Funciones** | `app.blade.php` | Empresas pendientes de aprobación veían botones de "Publicar vacante" que terminaban en error 403. | Se ocultó el botón de publicación en la navbar para empresas que aún no han sido verificadas por el administrador. |
| **Optimización de Flujo** | `home.blade.php` | Los botones de la Home eran confusos y añadían clics innecesarios para el acceso de estudiantes. | Se redirigió el botón de Estudiante directamente a Google OAuth y se unificaron etiquetas para una jerarquía visual clara. |
| **Acceso a Documentos (CV)** | `views/company/*` | Las empresas recibían error 404 al intentar abrir los PDFs de los candidatos debido a inconsistencias con `Storage::url()`. | Se unificaron los enlaces usando `asset('storage/...')` y se verificó la configuración de discos para garantizar el acceso en producción. |
| **Transparencia y Legalidad** | `privacy.blade.php` | La plataforma no contaba con términos legales ni políticas de tratamiento de datos personales para la bolsa de empleo. | Se redactó e integró una página de "Políticas de Privacidad" adaptada a la Ley 1581 (Colombia) y accesible desde el pie de página. |

## 3. Arquitectura y Roles

| Mejora | Ubicación | Descripción |
| :--- | :--- | :--- |
| **Consistencia de Middleware** | `routes/web.php` | Se centralizó el uso de middleware para evitar que usuarios con roles incorrectos o estados pendientes acedan a funciones críticas. |

## 4. Despliegue en Producción y Autenticación (Google OAuth)

| Mejora | Ubicación | Descripción |
| :--- | :--- | :--- |
| **Proxies de Confianza** | `bootstrap/app.php` | Laravel no gestionaba correctamente el HTTPS en entornos de proxy inverso (como Railway), rompiendo sesiones seguras. | Se configuró `$middleware->trustProxies(at: '*')` para soportar la terminación HTTPS en el servidor de despliegue. |
| **Autenticación sin estado (Stateless)** | `GoogleController.php` | Socialite fallaba con error 400 (`Missing code`) o expiración de tokens al volver de Google. | Se implementó el modo `stateless()`, añadiendo captura de excepciones detallada y manejo explícito de la cancelación por parte del usuario. |
| **Prevensión de Bloqueos (Rate Limiting)** | `routes/web.php` | El límite estricto de intentos provocaba bloqueos rápidos al hacer pruebas intensivas de QA (Error 429). | Se ajustó el límite temporal de `throttle` a 60 peticiones por minuto. |

## 5. Datos Iniciales e Idempotencia (Seeders)

| Mejora | Ubicación | Descripción |
| :--- | :--- | :--- |
| **Idempotencia en Siembra de Datos** | `DatabaseSeeder.php` | Volver a correr el comando `db:seed` causaba que la BD se rompiera por violación de restricciones (Unique Constraint). | Se migró la creación de usuarios, perfiles y empresas a `updateOrCreate()`, evitando conflictos si los datos ya existen. |
| **Sincronización de Contraseñas** | `DatabaseSeeder.php` | Había una discrepancia entre las contraseñas reales generadas (`secret`) y las impresas en consola (`Admin2024*`). | Se sincronizaron las contraseñas generadas a los valores correctos de prueba. |
| **Sintaxis en Vite** | `vite.config.js` | El IDE reportaba múltiples errores de sintaxis TypeScript/JS en la configuración de Vite. | Se cerraron y encapsularon correctamente los métodos y plugins de Vite (`laravel-vite-plugin`). |

---

### Notas del Desarrollador Senior:
- Se ha mantenido la identidad institucional de UNIPAZ (Azul #273475 y Verde #00963F).
- El sistema ahora es más robusto ante intentos de acceso no autorizados por parte de empresas no verificadas.
- La estética general ha subido de nivel, pasando de un MVP a una plataforma con sensación premium y moderna.
**Fecha:** 2026-04-27
