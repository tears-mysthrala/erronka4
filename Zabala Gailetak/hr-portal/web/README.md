# Zabala Gailetak - Web Interface

Interfaz web en React para el portal de RRHH.

## 🚀 Desarrollo

```bash
# Instalar dependencias
npm install

# Iniciar servidor desarrollo (puerto 3001)
npm run dev

# Compilar para producción
npm run build

# Vista previa de producción
npm run preview

# Linting
npm run lint
npm run lint:fix
```

## 🏗️ Estructura

```
web/
├── src/
│   ├── context/
│   │   └── AuthContext.jsx    # Context de autenticación
│   ├── pages/
│   │   ├── LoginPage.jsx      # Página de login
│   │   ├── EmployeeList.jsx   # Lista de empleados
│   │   ├── EmployeeForm.jsx   # Crear/editar empleado
│   │   └── EmployeeDetail.jsx # Detalle + historial
│   ├── services/
│   │   └── api.js             # Cliente API
│   ├── App.jsx                # Componente raíz
│   └── main.jsx               # Entry point
├── index.html
├── vite.config.js
└── package.json
```

## 📋 Funcionalidades

- ✅ Login con JWT
- ✅ Lista de empleados con paginación
- ✅ Crear nuevo empleado
- ✅ Editar empleado existente
- ✅ Ver detalle de empleado
- ✅ Historial de cambios (Audit Trail)
- ✅ Soft delete y restauración
- ✅ Validación de formularios
- ✅ Textos en euskera

## 🔌 API Backend

La app se conecta al backend PHP en `http://localhost:8080/api`

Configurar variable de entorno si es necesario:
```bash
VITE_API_URL=http://localhost:8080/api
```

## 🎨 Tecnologías

- React 18
- React Router v6
- Styled Components
- Axios
- Vite
