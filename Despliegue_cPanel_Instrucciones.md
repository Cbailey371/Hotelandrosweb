# Guía Definitiva de Despliegue en cPanel para cbailey

Dado que este proyecto usa **Laravel 12** + **Vite** y será alojado en un **cPanel** donde no tienes acceso por consola (SSH), he preparado todo el código para que el despliegue sea lo más automatizado posible.

## Paso 1: Preparación del Archivo ZIP 📦
1. Acabo de correr el comando para compilar todos los estilos e imágenes nativas (`npm run build`).
2. También vacié la caché interna del sistema (`php artisan optimize:clear`) para evitar conflictos de rutas con la computadora local.
3. Ahora, lo único que tienes que hacer en tu Mac es seleccionar **TODAS** las carpetas de este proyecto (`HotelandrosWEB`) y comprimirlas en un archivo `.zip`.
   > ⚠️ **IMPORTANTE:** Por cuestiones de peso y orden, **EXCLUYE** estas carpetas antes de comprimir:
   > - `node_modules` (ya compilamos todo en `public/build`, no la necesitas).
   > - `tests`
   > - `.git` (si tienes Git configurado).

## Paso 2: Subida al cPanel ☁️
1. Inicia sesión en tu cPanel y abre el **Administrador de Archivos (File Manager)**.
2. Crea una carpeta llamada `hotelandros` en la carpeta **raíz** de tu cPanel (es decir, **antes** o al mismo nivel de tu carpeta `public_html`).
3. Sube tu archivo `.zip` dentro de esta nueva carpeta `hotelandros` y usa la herramienta de "Extraer" o "Extract" para descomprimirlo todo allí mismo.

## Paso 3: Configurando la carpeta Pública (`public_html`) 🌐
En Laravel, la carpeta `public` es la única que debe tener acceso a internet. Para lograr esto en cPanel:
1. Dentro de tu carpeta `hotelandros` en el cPanel, entra al directorio `public`.
2. Selecciona todos los archivos que hay allí (incluyendo `.htaccess` y `index.php`).
3. **Pega/Mueve** todos esos archivos dentro de tu carpeta `public_html` (o la carpeta dominio principal).
4. **Edita el archivo `index.php`** que ahora está en tu `public_html`:
   - Busca las siguientes dos líneas (líneas 34 y 47 aprox.):
     ```php
     require __DIR__.'/../vendor/autoload.php';
     $app = require_once __DIR__.'/../bootstrap/app.php';
     ```
   - Modifícalas indicándole el "nuevo" lugar secreto donde guardaste el núcleo, es decir, tu carpeta `hotelandros`:
     ```php
     require __DIR__.'/../hotelandros/vendor/autoload.php';
     $app = require_once __DIR__.'/../hotelandros/bootstrap/app.php';
     ```

## Paso 4: Configuración de Base de Datos y Accesos 🗄️
1. En el cPanel, ve a **Bases de datos MySQL®** y crea una base de datos y un usuario nuevo. Otórgale todos los privilegios.
2. Usando el Administrador de Archivos de cPanel, ve a la carpeta privada `hotelandros` y edita el archivo oculto `.env`.
   *(Asegúrate de tener habilitada la vista de "Archivos Ocultos / Show Hidden Files" en las Settings de cPanel).*
3. Modifica tus credenciales a algo como esto:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://tudominio.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_de_tu_bd
   DB_USERNAME=tu_usuario_bd
   DB_PASSWORD=tu_contraseña_bd
   ```

## Paso 5: Truco Automático de Migraciones e Imágenes (Magia sin SSH) 🪄
Como no tienes acceso por SSH para correr los comandos mágicos de Artisan, **he modificado tu código fuente** agregando dos enlaces secretos en tu web para instalar la base de datos y enlazar las imágenes en un clic.

**Una vez que el `.env` y el `index.php` estén configurados:**

1. **Crear las Tablas en tu BD:**
   - Abre tu navegador y simplemente ve a: `https://tudominio.com/cpanel-setup/migrate`
   -  Espera un par de segundos y verás un mensaje de "Migraciones ejecutadas exitosamente". Esto acaba de construir toda la arquitectura MySQL en tu cPanel.
2. **Revelar las Imágenes (Storage Link):**
   - Asegúrate de limpiar y corregir cualquier enlace roto por defecto abriendo esta pestaña: `https://tudominio.com/cpanel-setup/storage-link`
   - Esto creará el atajo entre tu Storage interno y el internet, arreglando la subida de imágenes, fotos y PDF's del sistema.

> 🚨 **PASO FINAL DE SEGURIDAD:** 
> Una vez que todo funcione en vivo, **debes borrar** estas dos funciones auxiliares buscando tu archivo `routes/web.php` (dentro de tu carpeta interna `/hotelandros/routes/web.php`) y eliminando las últimas 20 líneas de código que contienen `/cpanel-setup`. Esto evitará que cualquier curioso rompa tu base de datos en el futuro.

¡Y eso es todo! El sistema estará vivo.
