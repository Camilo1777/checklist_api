from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker, declarative_base

# ⚠️ Ajusta los datos de conexión según tu MySQL/phpMyAdmin
DATABASE_URL = "mysql+pymysql://root:@localhost:3305/checklist"


# Conexión a la base de datos
engine = create_engine(DATABASE_URL)

# Sesión para consultas
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

# Clase base para los modelos
Base = declarative_base()
