from sqlalchemy import Column, Integer, String
from database import Base

class Profesor(Base):
    __tablename__ = "profesor"

    cedulaprofesor = Column(Integer, primary_key=True, index=True)
    nombre = Column(String(100), nullable=True)
    apellido = Column(String(100), nullable=True)
    email = Column(String(100), unique=True, nullable=False)
    password = Column(String(100), nullable=False)
