# Guía de Inicio Rápido - Zabala Gailetak HR Portal

## ⚡ Setup en 5 minutos

### 1. Prerequisitos

Asegúrate de tener instalado:

- ✅ Docker Desktop (Windows/Mac) o Docker Engine (Linux)
- ✅ Git

### 2. Clonar y Configurar

```bash
# Clonar repositorio
git clone <repository-url> erronka4
cd erronka4/"Zabala Gailetak"

# Configurar backend
cd hr-portal
cp .env.example .env

# Editar las siguientes variables (opcional para desarrollo)
# DB_PASSWORD=tu_password_seguro
# JWT_SECRET=tu_secret_key_aleatorio_largo
```

### 3. Iniciar Servicios

```bash
# Volver al directorio de Zabala Gailetak
cd ..

# Iniciar todos los servicios
docker-compose -f docker-compose.hrportal.yml up -d

# Ver logs
docker-compose -f docker-compose.hrportal.yml logs -f
```

### 4. Ejecutar Migraciones

```bash
cd "Zabala Gailetak/hr-portal"
chmod +x scripts/migrate.sh
./scripts/migrate.sh
```

### 5. Acceder a la Aplicación

- **Web Portal**: http://localhost:8080
- **API Health Check**: http://localhost:8080/api/health

**Usuario por defecto**:
- Email: `admin@zabalagailetak.com`
- Password: `Admin123!`

## 🎯 Siguiente Pasos

1. Cambiar password del usuario admin
2. Crear usuarios de prueba
3. Explorar la API: http://localhost:8080/api/employees
4. Ver documentación completa: [README.md](README.md)

## 🐛 Troubleshooting

### Puerto 8080 ya está en uso

```bash
# Cambiar el puerto en docker-compose.hrportal.yml
ports:
  - "8081:80"  # Cambiar 8080 por 8081
```

### Error de conexión a PostgreSQL

```bash
# Verificar que el contenedor está ejecutándose
docker-compose -f docker-compose.hrportal.yml ps

# Ver logs de PostgreSQL
docker-compose -f docker-compose.hrportal.yml logs postgres
```

### Permisos denegados en scripts

```bash
chmod +x hr-portal/scripts/*.sh
```

## 📱 Setup Android App

```bash
cd "Zabala Gailetak/android-app"

# Abrir en Android Studio
# File -> Open -> Seleccionar carpeta android-app
# Esperar a que Gradle sync complete
# Run app (Shift+F10)
```

## 🆘 Ayuda

Ver documentación completa o contactar:
- IT Support: it@zabalagailetak.com
- Documentación: [DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md)
