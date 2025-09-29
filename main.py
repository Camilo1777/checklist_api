from fastapi import FastAPI, Depends, HTTPException, Form
from sqlalchemy.orm import Session
from passlib.context import CryptContext
from fastapi.middleware.cors import CORSMiddleware



from database import SessionLocal, engine, Base
from profesor_models import Profesor  # ✅ Importamos directamente el modelo
MAX_LEN = 72
# Crear las tablas en la base de datos (si no existen)
Base.metadata.create_all(bind=engine)

# Inicializamos FastAPI
app = FastAPI()

# ----- CORS -----
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],   # Para pruebas locales
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Configuración para hashear contraseñas
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")


# Dependencia para obtener la sesión de la BD
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

# ---------------------------
# RUTA: Registrar profesor
# ---------------------------
@app.post("/register")
def register(
    cedulaprofesor: int = Form(...),
    nombre: str = Form(...),
    apellido: str = Form(...),
    email: str = Form(...),
    password: str = Form(...),
    db: Session = Depends(get_db)
):
    # 1. Verificar si ya existe un profesor con ese email
    profesor = db.query(Profesor).filter(Profesor.email == email).first()
    if profesor:
        raise HTTPException(status_code=400, detail="El correo ya está registrado")

    # 2. Hashear la contraseña
    hashed_password = pwd_context.hash(password[:72])

    # 3. Crear nuevo profesor
    nuevo_profesor = Profesor(
        cedulaprofesor=cedulaprofesor,
        nombre=nombre,
        apellido=apellido,
        email=email,
        password=hashed_password
    )

    # 4. Guardar en la base de datos
    db.add(nuevo_profesor)
    db.commit()
    db.refresh(nuevo_profesor)

    return {"message": "Profesor registrado exitosamente", "profesor": nuevo_profesor.email}

# ---------------------------
# RUTA: Login
# ---------------------------
@app.post("/login")
def login(
    email: str = Form(...),
    password: str = Form(...),
    db: Session = Depends(get_db)
):
    # 1. Buscar profesor por email
    profesor = db.query(Profesor).filter(Profesor.email == email).first()
    if not profesor:
        raise HTTPException(status_code=400, detail="Correo no registrado")

    # 2. Verificar la contraseña
    if not pwd_context.verify(password[:72], profesor.password):
        raise HTTPException(status_code=400, detail="Contraseña incorrecta")

    # 3. Si todo bien → login exitoso
    return {"message": "Login exitoso", "profesor": profesor.email}

