# Sistema de Atenciones de la Sub Unidad de Servicio Social - UNAP

Este es un sistema **backend (API)** desarrollado en Laravel 11, que gestiona las atenciones realizadas en la Sub Unidad de Servicio Social de la Universidad Nacional del Altiplano (UNAP). El sistema permite a los usuarios gestionar, registrar y realizar el seguimiento de las atenciones brindadas por el servicio social.

## Características

- Gestión de usuarios y roles (administradores, trabajadores sociales, entre otros).
- Registro de atenciones brindadas por el personal de servicio social.
- Seguimiento de casos y atenciones sociales.
- Búsqueda y filtrado de atenciones.
- Generación de reportes estadísticos sobre atenciones realizadas.
- Seguridad robusta a través de autenticación y autorización.
  
## Requisitos del Sistema

- **PHP >= 8.2** con las siguientes extensiones:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
- **Composer** para gestionar dependencias de PHP.
- **Base de datos**: MySQL.
- **Servidor web**: Apache o Nginx.

## Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local o servidor:

1. **Copia la carpeta completa del proyecto** en el servidor o tu máquina local.

2. **Configura el servidor web** para apuntar al directorio `public/` como el punto de entrada del proyecto:
    - En **Apache**, asegúrate de configurar el archivo `DocumentRoot` para que apunte a `path-a-tu-proyecto/public`.
    - En **Nginx**, configura la directiva `root` para que apunte a `path-a-tu-proyecto/public`.

3. **Configura el archivo `.env`**:
   - Renombra el archivo `.env.example` a `.env`.
   - Configura los detalles de conexión a la base de datos, correo y otros servicios necesarios.

4. **Instala las dependencias de PHP**:
    ```bash
    composer install
    ```

5. **Genera la clave de la aplicación**:
    ```bash
    php artisan key:generate
    ```

6. **Ejecuta las migraciones** (para crear las tablas en la base de datos):
    ```bash
    php artisan migrate --seed
    ```

## Licencia

Este proyecto está bajo una licencia de uso restringido. Solo puede ser modificado por el personal autorizado de la **Sub Unidad de Servicio Social de la Universidad Nacional del Altiplano (UNAP)**
Para más detalles, consulte el archivo [LICENSE](LICENSE).

---

**Universidad Nacional del Altiplano (UNAP) - Sub Unidad de Servicio Social**