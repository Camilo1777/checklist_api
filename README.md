# Checklist API

API para registro y login de profesores usando FastAPI, SQLAlchemy y MySQL.  
Las contraseñas se almacenan hasheadas con bcrypt.

## Requisitos

- Python 3.10+
- MySQL
- pip

## Instalación

1. Clona el repositorio o descarga los archivos.
2. Instala las dependencias:

   ```bash
   pip install -r requirements.txt
   ```

3. Configura la conexión a la base de datos en `database.py`:

   ```python
   DATABASE_URL = "mysql+pymysql://usuario:contraseña@localhost:3305/checklist"
   ```

4. Crea la base de datos `checklist` en MySQL si no existe.

## Uso

1. Ejecuta la API:

   ```bash
   uvicorn main:app --reload
   ```

2. Endpoints disponibles:

   ### Registrar profesor

   **POST /register**

   Campos (form-data):  
   - `cedulaprofesor` (int)
   - `nombre` (str)
   - `apellido` (str)
   - `email` (str)
   - `password` (str)

   **Ejemplo con curl:**
   ```bash
   curl -X POST http://localhost:8000/register \
     -F "cedulaprofesor=123456" \
     -F "nombre=Juan" \
     -F "apellido=Pérez" \
     -F "email=juan@example.com" \
     -F "password=miclave123"
   ```

   ### Login profesor

   **POST /login**

   Campos (form-data):  
   - `email` (str)
   - `password` (str)

   **Ejemplo con curl:**
   ```bash
   curl -X POST http://localhost:8000/login \
     -F "email=juan@example.com" \
     -F "password=miclave123"
   ```

## Notas

- Las contraseñas se hashean automáticamente con bcrypt.
- No insertes usuarios manualmente en la base de datos, usa el endpoint `/register`.
- El modelo de profesor está en `profesor_models.py`.

## Pruebas de conexión

Puedes probar la conexión a la base de datos ejecutando:

```bash
python test_db.py
```




