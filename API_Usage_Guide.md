## Guía de Uso de la API REST de Productos

Esta guía proporciona instrucciones completas para configurar su entorno, la base de datos, y probar la API de productos utilizando Postman.

### 1. Configuración del Servidor Web

Asegúrese de que su servidor web (Apache, Nginx, XAMPP, WAMP, etc.) esté configurado para servir el directorio `youtube-primer-api-php/public`. Por ejemplo, si está usando Apache o Nginx, configuraría un host virtual para que apunte a este directorio. Si está usando XAMPP/WAMP, puede colocar la carpeta `youtube-primer-api-php` dentro de su directorio `htdocs` o `www` y acceder a ella a través de `http://localhost/youtube-primer-api-php/public`.

### 2. Configuración de la Base de Datos

Asegúrese de tener una base de datos MySQL llamada `prueba69` y un usuario `nuevo_usuario` con la contraseña `contraseña_segura` con los permisos adecuados para acceder a esta base de datos. La tabla `productos` debe crearse utilizando el siguiente script SQL:

```sql
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL,
    producto VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL,
    descripcion TEXT NULL
);
```

### 3. Endpoints de la API

Todas las solicitudes a la API deben dirigirse al archivo `index.php` dentro del directorio `public`.

**URL de Ejemplo:** `http://localhost/youtube-primer-api-php/public/index.php`

### 4. Pruebas con Postman

#### A. Crear Producto (Solicitud POST)

*   **URL:** `http://localhost/youtube-primer-api-php/public/index.php`
*   **Método:** `POST`
*   **Encabezados:**
    *   `Content-Type: application/json`
*   **Cuerpo (JSON crudo):**
    ```json
    {
        "codigo": "P001",
        "producto": "Laptop Gamer",
        "precio": 1200.00,
        "cantidad": 5,
        "descripcion": "Una potente laptop para juegos."
    }
    ```
    *   `descripcion` es opcional.
*   **Respuesta Esperada (201 Created):**
    ```json
    {"message": "Producto creado exitosamente."}
    ```

#### B. Obtener Productos (Solicitud GET)

*   **URL:** `http://localhost/youtube-primer-api-php/public/index.php`
*   **Método:** `GET`
*   **Encabezados:** No son necesarios.
*   **Cuerpo:** Vacío.
*   **Respuesta Esperada (200 OK):**
    ```json
    {
        "registros": [
            {
                "id": "1",
                "codigo": "P001",
                "producto": "Laptop Gamer",
                "precio": "1200.00",
                "cantidad": "5",
                "descripcion": "Una potente laptop para juegos."
            }
            // Más productos si están disponibles
        ]
    }
    ```
    *   Si no se encuentran productos, recibirá un estado 404 Not Found.

#### C. Actualizar Producto (Solicitud PUT)

*   **URL:** `http://localhost/youtube-primer-api-php/public/index.php`
*   **Método:** `PUT`
*   **Encabezados:**
    *   `Content-Type: application/json`
*   **Cuerpo (JSON crudo):**
    ```json
    {
        "id": 1,
        "precio": 1150.00,
        "cantidad": 4
    }
    ```
    *   Debe proporcionar el `id` del producto a actualizar.
    *   Puede actualizar uno o más campos (`codigo`, `producto`, `precio`, `cantidad`, `descripcion`).
*   **Respuesta Esperada (200 OK):**
    ```json
    {"message": "Producto actualizado exitosamente."}
    ```
