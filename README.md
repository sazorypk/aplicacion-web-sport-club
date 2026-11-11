# Sport Club

**Estudiante:** Brandon Ospina

**Proyecto:** aplicación web académica de prueba para gestionar un club deportivo.

Sport Club permite consultar planes y sedes, crear una cuenta, administrar el perfil, seleccionar disciplinas, gestionar membresías y registrar pagos simulados. Incluye un panel administrativo para gestionar planes, miembros y solicitudes relacionadas con datos personales. Los pagos no corresponden a transacciones reales.

- Aplicación: [sportclub.elnexusdigital.com](https://sportclub.elnexusdigital.com/)
- Repositorio: [sazorypk/aplicacion-web-sport-club](https://github.com/sazorypk/aplicacion-web-sport-club)
- Contacto de privacidad: privacidad@correo.sportclub.elnexusdigital.com

## Tecnologías y arquitectura

- PHP 8.3, Laravel 13 y Composer.
- Blade, Vite, JavaScript, Alpine.js y estilos CSS.
- MySQL en producción; SQLite está configurado como alternativa local.
- Railway para alojamiento y base de datos.
- Resend para correo transaccional; Better Stack para monitoreo de disponibilidad; Backblaze B2 para archivos privados mediante S3; Sentry para errores.
- Pest para pruebas automatizadas y Laravel Pint para formato de PHP.

Las rutas web están en `routes/`, los controladores y modelos en `app/`, las vistas en `resources/views/`, las migraciones y seeders en `database/`, y las pruebas en `tests/`. Las solicitudes llegan a Laravel, que consulta la base de datos y utiliza los servicios externos según la operación.

## Base de datos

Relaciones principales:

| Tabla | Función y relaciones |
| --- | --- |
| `users` | Cuentas, rol, datos de perfil, ruta de la foto y registro de consentimiento. Un usuario puede tener membresías y solicitudes de eliminación. |
| `plans` y `sedes` | Catálogo de planes y sedes usados por las membresías. |
| `memberships` | Vincula un usuario con un plan y una sede; registra disciplinas, precio, estado y fechas. |
| `payments` | Registra pagos simulados asociados a una membresía. |
| `account_deletion_requests` | Guarda la solicitud, su estado y las fechas del proceso, vinculadas a la cuenta. |

Laravel también utiliza tablas para sesiones, recuperación de contraseña, caché y trabajos, según la configuración del entorno. Las migraciones en `database/migrations` son la referencia para el esquema completo.

## Ejecución local

Requisitos: PHP 8.3 con las extensiones necesarias para Laravel y SQLite, Composer, Node.js y npm. Para MySQL también se necesita su extensión de PHP y un servidor disponible.

1. Clona el repositorio y entra en la carpeta:

   ```bash
   git clone https://github.com/sazorypk/aplicacion-web-sport-club.git
   cd aplicacion-web-sport-club
   ```

2. Instala las dependencias y crea el archivo de entorno:

   ```bash
   composer install
   php -r "file_exists('.env') || copy('.env.example', '.env');"
   php artisan key:generate
   npm install
   npm run build
   ```

3. Para usar SQLite, conserva `DB_CONNECTION=sqlite` en `.env` y crea el archivo de base de datos si todavía no existe:

   ```bash
   php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
   php artisan migrate --seed
   ```

   Para MySQL, configura primero las variables `DB_*` y crea una base de datos vacía. El seeder principal incorpora planes y sedes de ejemplo; no crea una cuenta administrativa.

4. Inicia el servidor y visita la dirección que indique el comando:

   ```bash
   php artisan serve
   ```

   Durante el desarrollo de recursos de interfaz puedes ejecutar `npm run dev` en otra terminal. Con `MAIL_MAILER=log`, los correos se registran localmente y no se envían. La carga de fotos usa el disco S3 y requiere configurar un servicio compatible.

Para ejecutar las pruebas:

```bash
php artisan test --compact
```

## Variables de entorno

Copia `.env.example` como punto de partida y configura las variables según el entorno. No subas `.env`, contraseñas ni claves al repositorio. Las credenciales de producción se administran en Railway.

| Variables | Uso |
| --- | --- |
| `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL` | Nombre, entorno, clave de cifrado, depuración y URL. En producción usa `APP_DEBUG=false` y HTTPS. |
| `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Conexión a la base de datos; las variables necesarias dependen del motor. |
| `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION` | Control de sesiones, caché y cola. |
| `MAIL_MAILER`, `RESEND_API_KEY`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` | Transporte, clave y remitente del correo. Para Resend, configura `MAIL_MAILER=resend` y un remitente de un dominio verificado. |
| `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET`, `AWS_ENDPOINT`, `AWS_USE_PATH_STYLE_ENDPOINT` | Credenciales y conexión del disco S3 con Backblaze B2. `AWS_URL` es opcional. |
| `SENTRY_LARAVEL_DSN` | DSN del proyecto de Sentry; vacío desactiva el envío de eventos. |

Better Stack se configura desde su panel externo, por lo que el monitor de disponibilidad no necesita una variable en Laravel. Algunas variables de servicios no aparecen en `.env.example`; agrégalas al entorno que las necesite.

## Integraciones

### Resend

Laravel utiliza el transporte de Resend para correo transaccional, incluido el restablecimiento de contraseña. El dominio del remitente debe estar verificado en Resend y la clave debe mantenerse en una variable de entorno. El buzón de contacto de privacidad recibe mensajes en Resend.

### Better Stack

Un monitor de disponibilidad consulta la URL pública de la aplicación. La configuración del intervalo, los destinatarios y las alertas se administra en Better Stack.

### Backblaze B2

El disco `s3` de Laravel utiliza el bucket privado de B2. Las fotos de perfil se suben al prefijo `profile-photos/` y se entregan a través de una ruta autenticada, sin depender exclusivamente del disco local del servidor. Para ese prefijo se configuró la eliminación de versiones ocultas después de un día; al quitar una foto, su eliminación física puede no ser inmediata.

### Sentry

El SDK de Sentry para Laravel registra excepciones para su revisión en el panel del servicio. Se configura mediante `SENTRY_LARAVEL_DSN`. El comando `php artisan sentry:test` genera un evento de prueba en el entorno donde se ejecute.

## Privacidad y solicitudes de cuenta

La [política de privacidad](https://sportclub.elnexusdigital.com/privacidad) identifica al responsable y al canal de contacto. El registro solicita autorización y guarda la fecha y la versión de la política aceptada.

Desde su perfil, una persona autenticada puede solicitar la eliminación de su cuenta confirmando su contraseña. Un administrador revisa la solicitud y puede confirmar el procesamiento con su propia contraseña. La operación sustituye los identificadores directos, desactiva el acceso, retira la foto y cancela las membresías activas o pendientes; las membresías y pagos simulados se conservan para fines de prueba.

Los datos conservados podrían permitir una identificación indirecta al combinarse con otra información. Correos, copias y registros de proveedores se revisan por separado; el proceso no los elimina automáticamente. La aplicación es un proyecto académico: utiliza datos de prueba y evita información sensible.

## Despliegue

El servicio de producción está conectado a la rama `master` de GitHub en Railway. Su configuración actual ejecuta `php artisan migrate --seed --force` antes de activar una versión. Las variables y los servicios externos se configuran en sus respectivos paneles; no forman parte del repositorio.
