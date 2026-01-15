# FASE 3: APLICACIÓN MÓVIL REACT NATIVE
## HR Portal - Zabala Gailetak

---

## 📱 Información General

**Aplicación**: HR Portal Mobile (iOS/Android)  
**Framework**: React Native 0.73.2 con Expo 50.0.0  
**Navegación**: React Navigation 6.1.9 (Native Stack)  
**Gestión Estado**: React Context API  
**Persistencia**: AsyncStorage  
**API Client**: Axios 1.6.5  
**Idioma**: Euskera (Euskara)

---

## 🎯 Objetivo

Crear una aplicación móvil nativa para iOS y Android que permita a los empleados de RRHH gestionar el portal de empleados desde cualquier lugar. La aplicación proporciona acceso completo a las funcionalidades CRUD, visualización de detalles, y seguimiento de auditoría, todo optimizado para dispositivos móviles.

---

## 🏗️ Arquitectura

### Estructura del Proyecto

```
mobile/
├── App.js                              # Entry point + Navigation setup
├── app.json                            # Expo configuration
├── package.json                        # Dependencies
├── babel.config.js                     # Babel configuration
├── .gitignore                          # Git ignore rules
└── src/
    ├── context/
    │   └── AuthContext.js              # Authentication state management
    ├── services/
    │   └── api.js                      # API client (Axios)
    └── screens/
        ├── LoginScreen.js              # Login screen
        ├── EmployeeListScreen.js       # Employee list with FlatList
        ├── EmployeeDetailScreen.js     # Employee details + audit trail
        └── EmployeeFormScreen.js       # Create/Edit employee form
```

### Flujo de Navegación

```
App.js (NavigationContainer)
  └── AuthProvider
      └── AppNavigator (Stack Navigator)
          ├── Login (no auth)
          └── Authenticated Stack
              ├── EmployeeList (main screen)
              ├── EmployeeDetail (from list item tap)
              └── EmployeeForm (create new or edit existing)
```

---

## 📦 Dependencias Principales

```json
{
  "dependencies": {
    "expo": "~50.0.0",
    "react": "18.2.0",
    "react-native": "0.73.2",
    "@react-navigation/native": "^6.1.9",
    "@react-navigation/native-stack": "^6.9.17",
    "react-native-screens": "~3.29.0",
    "react-native-safe-area-context": "4.8.2",
    "@react-native-async-storage/async-storage": "1.21.0",
    "axios": "^1.6.5"
  }
}
```

**Justificación de tecnologías**:
- **Expo**: Managed workflow para desarrollo rápido sin configuración nativa compleja
- **React Navigation**: Estándar de industria para navegación en React Native
- **AsyncStorage**: Persistencia simple de tokens (en producción considerar SecureStore)
- **Axios**: Cliente HTTP con interceptors para manejo automático de tokens

---

## 🔐 Autenticación

### AuthContext (`src/context/AuthContext.js`)

**Funcionalidad**: Gestión global del estado de autenticación

```javascript
// Estado que proporciona
{
  user: null | { id, email, role },
  token: null | string,
  loading: boolean,
  login: (email, password) => Promise,
  logout: () => Promise,
  checkAuth: () => Promise
}
```

**Flujo de autenticación**:

1. **App Startup**: 
   - `useEffect` en AuthContext carga token de AsyncStorage
   - Si existe token, valida con `/auth/me`
   - Setea `user` state si válido

2. **Login**:
   - Usuario ingresa credenciales en `LoginScreen`
   - `login()` llama a `/auth/login`
   - Guarda token en AsyncStorage
   - Setea `user` state
   - Navegación automática a `EmployeeList`

3. **API Requests**:
   - Axios interceptor carga token desde AsyncStorage
   - Añade header: `Authorization: Bearer ${token}`
   - Si respuesta 401, limpia token y redirige a Login

4. **Logout**:
   - Elimina token de AsyncStorage
   - Limpia `user` state
   - Navega a `Login`

**Código clave**:

```javascript
// AsyncStorage usage
await AsyncStorage.setItem('token', data.token);
const token = await AsyncStorage.getItem('token');
await AsyncStorage.removeItem('token');

// Axios interceptor
api.interceptors.request.use(async (config) => {
  const token = await AsyncStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});
```

---

## 🌐 API Client

### Configuración (`src/services/api.js`)

**Base URL**: `http://192.168.1.100:8080/api` (configurar con IP local)

**Interceptors**:

1. **Request Interceptor**:
   - Carga token de AsyncStorage (async)
   - Añade header Authorization
   - Logs de debug en desarrollo

2. **Response Interceptor**:
   - Captura errores 401 (Unauthorized)
   - Limpia token de AsyncStorage
   - Permite reintento de login

### Métodos Disponibles

```javascript
// Authentication
login(email, password)          // POST /auth/login
logout()                        // POST /auth/logout
getMe()                         // GET /auth/me

// Employees CRUD
getEmployees(params)            // GET /employees?page=1&limit=20
getEmployee(id)                 // GET /employees/:id
createEmployee(data)            // POST /employees
updateEmployee(id, data)        // PUT /employees/:id
deleteEmployee(id)              // DELETE /employees/:id
restoreEmployee(id)             // POST /employees/:id/restore

// Audit Trail
getEmployeeHistory(id)          // GET /employees/:id/history
```

**Ejemplo de uso**:

```javascript
import api from '../services/api';

// En un componente
const loadEmployees = async () => {
  try {
    const data = await api.getEmployees({ page: 1, limit: 20 });
    setEmployees(data.employees);
  } catch (error) {
    Alert.alert('Error', error.message);
  }
};
```

---

## 📱 Pantallas (Screens)

### 1. LoginScreen (`src/screens/LoginScreen.js`)

**Propósito**: Autenticación de usuarios

**UI Components**:
- `KeyboardAvoidingView`: Ajuste automático del teclado
- 2 `TextInput`: Email y Password
- `TouchableOpacity`: Botón de login
- `ActivityIndicator`: Loading durante login

**Estado Local**:
```javascript
const [email, setEmail] = useState('');
const [password, setPassword] = useState('');
const [loading, setLoading] = useState(false);
```

**Validación**:
- Verifica que ambos campos no estén vacíos
- Muestra `Alert` si faltan datos

**Flujo**:
1. Usuario ingresa credenciales
2. Tap en "Hasi Saioa" → `handleLogin()`
3. Valida campos localmente
4. Llama `api.login(email, password)`
5. Si éxito: `navigation.replace('EmployeeList')`
6. Si error: Muestra Alert con mensaje

**Diseño**:
- Background gradient azul (`#0066cc`)
- Inputs blancos con border radius
- Botón verde (`#28a745`)
- Textos en euskera

**Líneas de código**: ~110 líneas

---

### 2. EmployeeListScreen (`src/screens/EmployeeListScreen.js`)

**Propósito**: Lista scrollable de todos los empleados

**UI Components**:
- `FlatList`: Renderizado optimizado de lista
- `RefreshControl`: Pull-to-refresh
- `TouchableOpacity`: Tap en tarjeta para navegar a detalle
- Floating Action Button (FAB): Crear nuevo empleado

**Estado Local**:
```javascript
const [employees, setEmployees] = useState([]);
const [loading, setLoading] = useState(true);
const [refreshing, setRefreshing] = useState(false);
const [page, setPage] = useState(1);
```

**Funcionalidades**:
1. **Carga inicial**: `useEffect` → `loadEmployees()`
2. **Pull-to-refresh**: Recarga lista desde página 1
3. **Paginación**: Parámetros `page` y `limit=20`
4. **Navegación**: Tap en tarjeta → `EmployeeDetail`
5. **Crear nuevo**: FAB → `EmployeeForm` sin ID
6. **Editar**: Botón en tarjeta → `EmployeeForm` con ID
7. **Eliminar**: Confirmación con Alert → `api.deleteEmployee()`

**Renderizado de Items**:

```javascript
const renderEmployee = ({ item }) => (
  <TouchableOpacity style={styles.card} onPress={...}>
    <Text>{item.first_name} {item.last_name}</Text>
    <Text>{item.position}</Text>
    <Text>{item.email}</Text>
    <View style={styles.actions}>
      <TouchableOpacity onPress={editHandler}>Editatu</TouchableOpacity>
      <TouchableOpacity onPress={deleteHandler}>Ezabatu</TouchableOpacity>
    </View>
  </TouchableOpacity>
);
```

**Diseño**:
- Tarjetas blancas con sombra
- Badge de estado (Aktiboa/Inaktiboa)
- Botones inline (Editatu, Ezabatu)
- FAB verde en esquina inferior derecha

**Líneas de código**: ~200 líneas

---

### 3. EmployeeDetailScreen (`src/screens/EmployeeDetailScreen.js`)

**Propósito**: Vista detallada de un empleado + audit trail

**UI Components**:
- `ScrollView`: Contenedor scrollable
- Secciones con `View`:
  - Header (nombre + badge)
  - Información Básica
  - Fechas Importantes
  - Acciones (Editatu, Ezabatu/Berreskuratu)
  - Historia de Cambios

**Estado Local**:
```javascript
const [employee, setEmployee] = useState(null);
const [history, setHistory] = useState([]);
const [loading, setLoading] = useState(true);
```

**Datos Mostrados**:

**Información Básica**:
- Langile Zenbakia (Employee Number)
- Posta Elektronikoa (Email)
- Kargua (Position)
- Departamentua (Department)
- Telefonoa (Phone) - opcional

**Fechas**:
- Kontratu Data (Hire Date)
- Jaiotze Data (Date of Birth)
- Sortze Data (Created At)
- Azken Eguneraketa (Updated At)

**Historia de Cambios**:
```javascript
history.map(entry => (
  <View key={entry.id}>
    <Text>{getActionText(entry.action)}</Text> // Sortua, Eguneratua, etc.
    <Text>{entry.user_email}</Text>
    <Text>{new Date(entry.created_at).toLocaleString('eu-ES')}</Text>
    {entry.changes && renderChanges(entry.changes)}
  </View>
))
```

**Acciones**:
- **Editatu**: Navega a `EmployeeForm` con ID
- **Ezabatu**: Soft delete con confirmación Alert
- **Berreskuratu**: Restaurar empleado eliminado (si `active = false`)

**Diseño**:
- Header grande con nombre y badge
- Secciones con títulos bold
- Info en filas (label izq, value der)
- Timeline visual para historia
- Colores diferenciados por acción

**Líneas de código**: ~310 líneas

---

### 4. EmployeeFormScreen (`src/screens/EmployeeFormScreen.js`)

**Propósito**: Crear nuevo empleado o editar existente

**UI Components**:
- `KeyboardAvoidingView`: Manejo del teclado
- `ScrollView`: Formulario scrollable
- 10 `TextInput`: Campos del formulario
- Validación inline con mensajes de error
- Botones: Utzi (Cancelar) y Sortu/Eguneratu (Submit)

**Estado Local**:
```javascript
const [formData, setFormData] = useState({
  employee_number: '',
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  date_of_birth: '',
  hire_date: '',
  position: '',
  department: '',
  salary: ''
});
const [errors, setErrors] = useState({});
const [loading, setLoading] = useState(false);
const [saving, setSaving] = useState(false);
```

**Modos**:
1. **Crear**: `route.params` vacío → Formulario limpio
2. **Editar**: `route.params.id` → Carga datos con `api.getEmployee(id)`

**Validación**:

```javascript
const validateForm = () => {
  const newErrors = {};
  
  if (!formData.employee_number.trim()) {
    newErrors.employee_number = 'Langile zenbakia beharrezkoa da';
  }
  if (!formData.email.match(/\S+@\S+\.\S+/)) {
    newErrors.email = 'Email formatu baliogabea';
  }
  // ... más validaciones
  
  setErrors(newErrors);
  return Object.keys(newErrors).length === 0;
};
```

**Campos Requeridos** (marcados con *):
- Langile Zenbakia
- Izena (First Name)
- Abizena (Last Name)
- Posta Elektronikoa
- Kargua
- Departamentua
- Kontratu Data

**Campos Opcionales**:
- Telefonoa
- Jaiotze Data
- Soldata

**Flujo de Submit**:
1. Usuario completa formulario
2. Tap en "Sortu/Eguneratu"
3. `validateForm()` → Si falla, muestra errores
4. Si pasa:
   - Modo crear: `api.createEmployee(formData)`
   - Modo editar: `api.updateEmployee(id, formData)`
5. Si éxito: Alert "Ondo!" + `navigation.goBack()`
6. Si error backend: Muestra errores de servidor en campos

**Manejo de Errores Backend**:

```javascript
catch (error) {
  if (error.response?.data?.errors) {
    // Errores de validación del backend
    setErrors(error.response.data.errors);
  } else {
    // Error genérico
    Alert.alert('Errorea', error.response?.data?.error || 'Error al guardar');
  }
}
```

**Diseño**:
- Secciones agrupadas: Oinarrizko, Datak, Lanaren Xehetasunak
- Inputs con borde que cambia a rojo si error
- Mensajes de error bajo cada input
- Teclados específicos:
  - `email-address` para email
  - `phone-pad` para teléfono
  - `decimal-pad` para salario
- Botones de acción en fila (Utzi, Sortu/Eguneratu)

**Líneas de código**: ~400 líneas

---

## 🎨 Sistema de Estilos

### Paleta de Colores

```javascript
const colors = {
  primary: '#0066cc',      // Azul principal (headers, links)
  success: '#28a745',      // Verde (botones crear, FAB, badge activo)
  danger: '#dc3545',       // Rojo (botones eliminar)
  warning: '#ffc107',      // Amarillo (alertas)
  background: '#f5f5f5',   // Gris claro (fondo general)
  card: '#ffffff',         // Blanco (tarjetas)
  text: '#333333',         // Texto principal
  textLight: '#666666',    // Texto secundario
  border: '#dddddd',       // Bordes
  inactive: '#6c757d'      // Gris (badge inactivo)
};
```

### Tipografía

```javascript
const typography = {
  h1: { fontSize: 24, fontWeight: 'bold' },      // Títulos principales
  h2: { fontSize: 18, fontWeight: 'bold' },      // Subtítulos
  body: { fontSize: 16, fontWeight: 'normal' },  // Texto normal
  label: { fontSize: 14, fontWeight: '500' },    // Labels de formulario
  caption: { fontSize: 12, fontWeight: 'normal' } // Notas pequeñas
};
```

### Componentes Reutilizables (Estilos)

**Card**:
```javascript
card: {
  backgroundColor: '#fff',
  borderRadius: 8,
  padding: 15,
  marginBottom: 15,
  shadowColor: '#000',
  shadowOffset: { width: 0, height: 2 },
  shadowOpacity: 0.1,
  shadowRadius: 4,
  elevation: 3,  // Android shadow
}
```

**Badge**:
```javascript
badge: {
  paddingHorizontal: 10,
  paddingVertical: 4,
  borderRadius: 12,
}
badgeActive: { backgroundColor: '#28a745' }
badgeInactive: { backgroundColor: '#6c757d' }
```

**Button**:
```javascript
button: {
  paddingVertical: 15,
  borderRadius: 8,
  alignItems: 'center',
}
buttonPrimary: { backgroundColor: '#0066cc' }
buttonDanger: { backgroundColor: '#dc3545' }
buttonDisabled: { opacity: 0.6 }
```

**Input**:
```javascript
input: {
  backgroundColor: '#fff',
  borderWidth: 1,
  borderColor: '#ddd',
  borderRadius: 8,
  padding: 12,
  fontSize: 16,
}
inputError: { borderColor: '#dc3545' }
```

---

## 🔄 Flujos de Usuario

### Flujo 1: Login

```
1. Usuario abre app
2. AuthContext verifica AsyncStorage → No hay token
3. Muestra LoginScreen
4. Usuario ingresa: email "admin@zabala.eus", password "admin123"
5. Tap "Hasi Saioa"
6. api.login() → Backend valida
7. Token guardado en AsyncStorage
8. AuthContext setea user state
9. Navegación automática → EmployeeListScreen
```

### Flujo 2: Ver Lista y Detalles

```
1. EmployeeListScreen carga
2. useEffect → api.getEmployees({ page: 1, limit: 20 })
3. FlatList renderiza 20 empleados
4. Usuario hace pull-to-refresh → Recarga página 1
5. Usuario tap en empleado "Mikel Garcia"
6. navigation.navigate('EmployeeDetail', { id: 5 })
7. EmployeeDetailScreen carga:
   - api.getEmployee(5)
   - api.getEmployeeHistory(5)
8. Muestra información + timeline de cambios
```

### Flujo 3: Crear Nuevo Empleado

```
1. Usuario en EmployeeListScreen
2. Tap en FAB (botón verde +)
3. navigation.navigate('EmployeeForm') → Sin ID
4. EmployeeFormScreen en modo "crear"
5. Usuario completa formulario:
   - Employee Number: EMP015
   - Nombre: Ane
   - Apellido: Lopez
   - Email: ane@zabala.eus
   - Etc.
6. Tap "Sortu"
7. validateForm() → Pasa
8. api.createEmployee(formData)
9. Backend responde 201 Created
10. Alert "Ondo! Langilea sortu da"
11. navigation.goBack() → Vuelve a lista
12. Lista se recarga automáticamente (useEffect)
```

### Flujo 4: Editar Empleado

```
1. Usuario en EmployeeDetailScreen (Mikel Garcia)
2. Tap "Editatu"
3. navigation.navigate('EmployeeForm', { id: 5 })
4. EmployeeFormScreen:
   - Detecta id en route.params
   - Modo "editar"
   - api.getEmployee(5) → Carga datos actuales
   - Pre-llena formulario
5. Usuario cambia:
   - Position: "Senior Developer" → "Lead Developer"
   - Salary: "50000" → "60000"
6. Tap "Eguneratu"
7. api.updateEmployee(5, formData)
8. Backend registra cambios en audit_logs
9. Alert "Ondo! Langilea eguneratu da"
10. Vuelve a EmployeeDetailScreen → Datos actualizados
```

### Flujo 5: Eliminar y Restaurar

```
1. Usuario en EmployeeDetailScreen
2. Tap "Ezabatu"
3. Alert de confirmación: "Ziur zaude?"
4. Usuario confirma
5. api.deleteEmployee(5)
6. Backend: soft delete (active = false)
7. navigation.goBack() → Lista
8. Empleado ya no aparece en lista (filtro active = true)

// Para restaurar:
1. Usuario accede directamente a detail (por URL o búsqueda)
2. Badge muestra "Inaktiboa"
3. Botón "Berreskuratu" visible
4. Tap → api.restoreEmployee(5)
5. Backend: active = true
6. Alert "Ondo! Langilea berreskuratu da"
7. Empleado vuelve a aparecer en lista
```

---

## 🧪 Testing

### Configuración de Testing (Pendiente)

Para testing en React Native, se recomienda:

```bash
npm install --save-dev @testing-library/react-native jest
```

### Ejemplo de Test (LoginScreen)

```javascript
import { render, fireEvent, waitFor } from '@testing-library/react-native';
import LoginScreen from '../src/screens/LoginScreen';

describe('LoginScreen', () => {
  it('should show error if fields are empty', () => {
    const { getByText } = render(<LoginScreen />);
    const loginButton = getByText('Hasi Saioa');
    
    fireEvent.press(loginButton);
    
    expect(getByText('Eremu guztiak bete behar dira')).toBeTruthy();
  });

  it('should call login API on valid submission', async () => {
    const { getByPlaceholderText, getByText } = render(<LoginScreen />);
    
    fireEvent.changeText(getByPlaceholderText('Email'), 'admin@zabala.eus');
    fireEvent.changeText(getByPlaceholderText('Pasahitza'), 'admin123');
    fireEvent.press(getByText('Hasi Saioa'));
    
    await waitFor(() => {
      expect(mockApiLogin).toHaveBeenCalledWith('admin@zabala.eus', 'admin123');
    });
  });
});
```

---

## 📊 Estadísticas de Código

### Resumen por Archivo

| Archivo | Líneas | Componentes | Funciones | Estilos |
|---------|--------|-------------|-----------|---------|
| App.js | 85 | 2 | 1 | 1 |
| AuthContext.js | 95 | 1 | 3 | 0 |
| api.js | 95 | 0 | 11 | 0 |
| LoginScreen.js | 110 | 1 | 1 | 1 |
| EmployeeListScreen.js | 200 | 1 | 3 | 1 |
| EmployeeDetailScreen.js | 310 | 1 | 5 | 1 |
| EmployeeFormScreen.js | 400 | 1 | 4 | 1 |

**Total**: ~1,300 líneas de código JavaScript

### Distribución

- **Screens**: 1,020 líneas (78%)
- **Services**: 95 líneas (7%)
- **Context**: 95 líneas (7%)
- **Navigation**: 85 líneas (7%)

### Componentes React Native Usados

- View: ~80 instancias
- Text: ~120 instancias
- TextInput: 12 instancias
- TouchableOpacity: ~30 instancias
- ScrollView: 4 instancias
- FlatList: 1 instancia
- ActivityIndicator: 6 instancias
- Alert: ~15 llamadas
- KeyboardAvoidingView: 2 instancias

---

## 🚀 Despliegue

### Desarrollo Local

```bash
# Terminal 1: Backend API
cd api
php -S localhost:8080 -t public

# Terminal 2: Mobile App
cd mobile
npm start
```

### Build para Producción

#### Android (APK)

```bash
# Usando EAS Build (Expo Application Services)
npm install -g eas-cli
eas login
eas build --platform android --profile production
```

**Configurar `eas.json`**:
```json
{
  "build": {
    "production": {
      "android": {
        "buildType": "apk",
        "gradleCommand": ":app:assembleRelease"
      }
    }
  }
}
```

#### iOS (IPA)

```bash
eas build --platform ios --profile production
```

**Requisitos**:
- Apple Developer Account ($99/año)
- Provisioning Profile configurado
- Bundle ID: `com.zabalagailetak.hrportal`

### Variables de Entorno

Para producción, configurar en `app.config.js`:

```javascript
export default {
  expo: {
    extra: {
      apiUrl: process.env.API_URL || 'https://api.zabalagailetak.eus/api',
    }
  }
};
```

Usar en código:
```javascript
import Constants from 'expo-constants';
const API_BASE_URL = Constants.expoConfig.extra.apiUrl;
```

---

## 🔒 Consideraciones de Seguridad

### Almacenamiento de Tokens

**Actual** (AsyncStorage):
```javascript
await AsyncStorage.setItem('token', jwt);
```

**Recomendado para Producción** (SecureStore):
```javascript
import * as SecureStore from 'expo-secure-store';
await SecureStore.setItemAsync('token', jwt);
```

**Ventajas de SecureStore**:
- Encriptación en iOS Keychain
- Encriptación en Android Keystore
- No accesible desde backup del dispositivo

### HTTPS en Producción

**Desarrollo**: `http://192.168.1.100:8080` (OK)  
**Producción**: `https://api.zabalagailetak.eus` (OBLIGATORIO)

Configurar certificado SSL/TLS en backend.

### Validación de Certificados

Prevenir Man-in-the-Middle attacks:

```javascript
import { Platform } from 'react-native';

if (Platform.OS === 'android') {
  // Configurar certificate pinning
}
```

### Sanitización de Inputs

**Actual**: Validación solo en cliente y backend  
**Mejora**: Usar librerías como `validator` para sanitizar antes de enviar:

```javascript
import validator from 'validator';

const sanitizedEmail = validator.normalizeEmail(email);
const sanitizedPhone = validator.escape(phone);
```

---

## 📝 Mejoras Futuras

### Funcionalidad

1. **Búsqueda y Filtros**:
   - Buscar empleados por nombre, email, departamento
   - Filtros por estado (activo/inactivo)
   - Ordenamiento (nombre, fecha de contratación)

2. **Paginación Infinite Scroll**:
   - Cargar más empleados al llegar al final de FlatList
   - Implementar `onEndReached` prop

3. **Caché Offline**:
   - Persistir lista de empleados en AsyncStorage
   - Mostrar caché mientras carga datos nuevos
   - React Query o SWR para gestión de caché

4. **Push Notifications**:
   - Notificar cuando se crea/edita un empleado
   - Expo Notifications API

5. **Biometría**:
   - Login con Face ID / Touch ID
   - `expo-local-authentication`

6. **Dark Mode**:
   - Detectar tema del sistema
   - Alternar entre tema claro/oscuro

7. **Multi-idioma**:
   - Soporte para Euskera, Español, Inglés
   - `i18next` o `react-i18next`

### UI/UX

1. **Animaciones**:
   - Transiciones suaves entre pantallas
   - `react-native-reanimated`

2. **Skeleton Loaders**:
   - Placeholders mientras carga
   - `react-native-skeleton-placeholder`

3. **Swipe Actions**:
   - Swipe para eliminar en lista
   - `react-native-swipeable-item`

4. **Bottom Sheet**:
   - Confirmaciones en bottom sheet
   - `@gorhom/bottom-sheet`

### Técnicas

1. **TypeScript**:
   - Migrar a TypeScript para type safety
   - Definir interfaces para Employee, User, etc.

2. **State Management**:
   - Considerar Redux si crece complejidad
   - O Zustand para algo más ligero

3. **Code Splitting**:
   - Lazy loading de pantallas
   - React.lazy() y Suspense

4. **Testing**:
   - Unit tests con Jest
   - E2E tests con Detox
   - Coverage objetivo: 80%

5. **CI/CD**:
   - GitHub Actions para builds automáticos
   - EAS Build en cada push a main
   - Deploy automático a TestFlight/Play Store Beta

---

## 📚 Recursos y Referencias

### Documentación Oficial

- **React Native**: https://reactnative.dev/docs/getting-started
- **Expo**: https://docs.expo.dev/
- **React Navigation**: https://reactnavigation.org/docs/getting-started
- **AsyncStorage**: https://react-native-async-storage.github.io/async-storage/

### Guías de Estilo

- **React Native Best Practices**: https://github.com/jondot/awesome-react-native
- **Expo Best Practices**: https://docs.expo.dev/guides/best-practices/

### Comunidad

- **React Native Community**: https://github.com/react-native-community
- **Expo Forums**: https://forums.expo.dev/

---

## 🤝 Contribución

### Flujo de Desarrollo

1. Crear branch desde `main`:
   ```bash
   git checkout -b feature/nueva-funcionalidad
   ```

2. Desarrollar y probar localmente:
   ```bash
   npm start
   # Probar en simulador/dispositivo
   ```

3. Lint antes de commit:
   ```bash
   npm run lint
   ```

4. Commit con mensaje descriptivo:
   ```bash
   git commit -m "feat: añadir búsqueda de empleados"
   ```

5. Push y crear Pull Request:
   ```bash
   git push origin feature/nueva-funcionalidad
   ```

### Convenciones de Commits

Seguir [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` - Nueva funcionalidad
- `fix:` - Corrección de bug
- `docs:` - Cambios en documentación
- `style:` - Cambios de formato (no afectan código)
- `refactor:` - Refactorización de código
- `test:` - Añadir o corregir tests
- `chore:` - Cambios en build o dependencias

---

## 📞 Soporte

**Equipo de Desarrollo**: Zabala Gailetak IT Team  
**Email**: dev@zabalagailetak.eus  
**Documentación Interna**: Confluence / Wiki interno

---

## 📄 Licencia

Uso interno - Zabala Gailetak  
Todos los derechos reservados © 2024

---

## 📌 Notas Finales

Esta aplicación móvil complementa el portal web de RRHH, permitiendo gestión completa desde dispositivos móviles. La arquitectura está diseñada para:

- **Escalabilidad**: Fácil añadir nuevas pantallas y funcionalidades
- **Mantenibilidad**: Código limpio y bien estructurado
- **Seguridad**: Autenticación JWT, validación de inputs, manejo de errores
- **UX**: Diseño intuitivo en euskera, feedback visual inmediato

La aplicación está lista para producción con las consideraciones de seguridad mencionadas (SecureStore, HTTPS, etc.).

---

**Versión**: 1.0.0  
**Fecha**: Enero 2024  
**Autor**: Zabala Gailetak IT Team
