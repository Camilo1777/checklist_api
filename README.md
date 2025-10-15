# Checklist API

API REST sencilla en PHP para el manejo de profesores y autenticación JWT.

Descripción
- Proyecto pequeño pensado para usarse con una app Flutter que consume endpoints de autenticación (`auth/register.php`, `auth/login.php`).

Requisitos
- PHP 7.4+ (o PHP 8.x)
- MySQL / MariaDB
- XAMPP (opcional, recomendado para desarrollo local)
- Composer

Instalación rápida
1. Clona o copia este repositorio en tu servidor (ej. `c:\xampp\htdocs\checklist_api`).
2. Edita `config/database.php` y ajusta las credenciales de la base de datos si es necesario.
3. Ajusta `config/secret.php` si quieres cambiar la clave secreta o tiempos del token.
4. Si no has subido la carpeta `vendor/`, ejecuta en la raíz del proyecto:

```powershell
# En PowerShell
composer install
```

Uso
- Endpoints principales:
  - `POST /auth/register.php` — Registrar profesor.
  - `POST /auth/login.php` — Login y obtención de token JWT.
  - `GET /auth/validate_token.php` — Validar token (Authorization: Bearer <token>).

Ejemplo (JSON) para registro:

```json
{
  "idprofesor": "12345",
  "nombre": "Juan",
  "apellido": "Pérez",
  "email": "juan@example.com",
  "password": "secret123"
}
```

Ejemplo (PowerShell) para login:

```powershell
Invoke-RestMethod -Uri "http://localhost/checklist_api/auth/login.php" -Method Post -Body (@{ email = 'juan@example.com'; password = 'secret123' } | ConvertTo-Json) -ContentType 'application/json'
```

Notas
- El proyecto usa `firebase/php-jwt` para generación y verificación de tokens.
- Las contraseñas se almacenan con `password_hash` (bcrypt).
- Ajusta los tiempos de expiración del token en `config/secret.php` según tus necesidades.

Siguientes pasos recomendados
- Proteger endpoints adicionales con validación del token (ej. usando `validate_token.php`).
- Añadir migraciones/SQL de ejemplo para la tabla `profesor`.
- Añadir pruebas unitarias y/o Postman collection.

Despliegue en Railway
1. Crea un proyecto en Railway y añade un plugin MySQL.
2. En Variables (Environment), añade las variables mostradas en `.env.example` y pega las credenciales que te da Railway (DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_PORT). Añade `SECRET_KEY` también.
3. En Railway, conecta tu repositorio GitHub o sube el código.
4. Usa el `Dockerfile` incluido o deja que Railway detecte el proyecto; asegura que `composer install` se ejecute en build.
5. Ejecuta el SQL de creación de la tabla `profesor` (ver más abajo).

SQL ejemplo para la tabla `profesor`:

```
CREATE TABLE profesor (
  idprofesor VARCHAR(100) PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100),
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Notas de seguridad
- No subas `.env` con credenciales. Usa las Variables de entorno del servicio en Railway.
- Cambia `SECRET_KEY` por una cadena larga y segura en producción.

Licencia
- Proyecto sin licencia explícita (añadir una `LICENSE` si quieres públicarlo).
