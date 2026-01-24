# Guía de Instalación: Hotel Andros WEB en GoDaddy cPanel

Esta guía detalla los pasos necesarios para desplegar la aplicación en un entorno de producción utilizando cPanel y el repositorio oficial.

## 📋 Requisitos Previos
- Acceso a cPanel en GoDaddy.
- PHP 8.2 o superior habilitado.
- Extensiones PHP activas: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`.
- Composer instalado en el servidor (usualmente disponible vía SSH).

---

## 🚀 Pasos de Despliegue

### 1. Clonar el Repositorio
Accede vía SSH a tu servidor y posiciónate en el directorio donde deseas instalar la app (fuera de `public_html` por seguridad es recomendado):
```bash
git clone https://github.com/Cbailey371/Hotelandrosweb.git hotelandros
cd hotelandros
```

### 2. Instalación de Dependencias
Ejecuta la instalación de Composer optimizada para producción:
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Configuración de Entorno
Copia el archivo de ejemplo y genera la llave de la aplicación:
```bash
cp .env.example .env
php artisan key:generate
```
> [!IMPORTANT]
> Edita el archivo `.env` con las credenciales de tu base de datos de cPanel, configuración de correo y `APP_DEBUG=false`.

### 4. Base de Datos
Crea una base de datos y un usuario en cPanel, asígnalos con todos los permisos y luego ejecuta:
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 5. Configuración de Carpeta Pública
En cPanel de GoDaddy, lo más común es mover los archivos de la carpeta `public/` de Laravel directamente a `public_html`. Asegúrate de actualizar el archivo `public_html/index.php` para que las rutas a `vendor/autoload.php` y `bootstrap/app.php` apunten a la ubicación correcta de la carpeta raíz del proyecto.

### 6. Permisos de Directorios
Asegura que Laravel pueda escribir en los directorios necesarios:
```bash
chmod -R 775 storage bootstrap/cache
```

### 7. Optimización de Laravel
Ejecuta los comandos de caché para mejorar el rendimiento:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🛠️ Mantenimiento y Actualizaciones
Para actualizar la aplicación en el futuro:
```bash
git pull origin main
composer install --no-dev
php artisan migrate --force
php artisan optimize
```
