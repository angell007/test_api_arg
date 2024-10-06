
## Requisitos previos

- PHP >= 8.1
- Composer
- MySQL

## Instalación

1. Clonar el repositorio:
   ```
   git clone https://url-del-repositorio.git
   cd nombre-del-proyecto
   ```

2. Instalar dependencias de PHP:
   ```
   composer install
   ```

3. Copiar el archivo de entorno y configurarlo:
   ```
   cp .env.example .env
   ```
   Edita el archivo `.env` con tu configuración de base de datos y otras configuraciones necesarias.

4. Generar la clave de la aplicación:
   ```
   php artisan key:generate
   ```

5. Ejecutar las migraciones y seeders:
   ```
   php artisan migrate --seed
   ```

6. Generar la clave secreta para JWT:
   ```
   php artisan jwt:secret
   ```

## Configuración de Scribe (para documentación de API)

Para generar la documentación de la API:

php artisan scribe:generate

La documentación estará disponible en `public/docs/index.html`.

## Ejecutar el proyecto

1. Iniciar el servidor de desarrollo:
   ```
   php artisan serve
   ```

2. Visita `http://localhost:8000` en tu navegador.

## Pruebas

Para ejecutar las pruebas:

php artisan test
