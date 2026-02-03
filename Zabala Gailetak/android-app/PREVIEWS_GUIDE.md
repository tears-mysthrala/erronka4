# 📱 Android Studio Preview Guide

## ¿Qué son los Previews?

Los **Previews** en Jetpack Compose te permiten visualizar tus componentes en tiempo de diseño sin necesidad de ejecutar la aplicación. Es una forma rápida y eficiente de desarrollar y probar la UI.

## 📍 Dónde están los Previews

Hemos configurado previews en los siguientes pantallas:

### 🔐 Authentication
- **LoginScreen.kt**
  - `LoginPreview` - Estado normal
  - `LoginPreviewLoading` - Estado de carga
  - `LoginPreviewError` - Con mensaje de error

### 📊 Dashboard
- **DashboardScreen.kt**
  - `DashboardScreenPreview` - Vista principal del dashboard

### 📄 Documents
- **DocumentsScreen.kt**
  - `DocumentsScreenPreview` - Lista de documentos

### 💰 Payslips (Nóminas)
- **PayslipsScreen.kt**
  - `PayslipsScreenPreview` - Vista predeterminada
  - `PayslipsScreenEmptyPreview` - Estado vacío

### 👤 Profile
- **ProfileScreen.kt**
  - `ProfileScreenPreview` - Pantalla de perfil

### 🏖️ Vacation (Vacaciones)
- **VacationDashboardScreen.kt**
  - `VacationDashboardScreenPreview` - Dashboard de vacaciones

- **NewVacationRequestScreen.kt**
  - `NewVacationRequestScreenPreview` - Formulario de solicitud
  - `NewVacationRequestScreenErrorPreview` - Formulario con error

## 🚀 Cómo usar los Previews en Android Studio

### Opción 1: Panel Preview integrado
1. Abre cualquier archivo `.kt` que contenga `@Preview`
2. Haz clic en el botón **Preview** en la parte derecha del editor
3. Se abrirá un panel con la visualización del componente
4. Los cambios en el código se reflejan en tiempo real

### Opción 2: Gutter Icons
1. Busca el icono de **vista previa** (pequeño teléfono) en el margen izquierdo
2. Haz clic en él para abrir la vista previa en el panel

### Opción 3: Split View
1. Ve a **View** > **Split Editor** en el menú superior
2. Abre el archivo `.kt` con previews
3. Verás el código a la izquierda y la vista previa a la derecha

## 🎨 Características de los Previews

- ✅ Múltiples variantes (estados diferentes)
- ✅ Fondo de pantalla habilitado para mejor visualización
- ✅ Tema configurado correctamente (ZabalaGaileTakHRTheme)
- ✅ Datos simulados (mock data) para pruebas
- ✅ Nombres descriptivos para identificar cada variante

## ⚙️ Configuración del tema

Todos los previews utilizan el tema **ZabalaGaileTakHRTheme**, que incluye:
- Colores personalizados de Zabala Gailetak
- Tipografía coherente
- Tema oscuro/claro automático según la configuración del dispositivo

## 📱 Tipos de Preview

### Preview Simple
```kotlin
@Preview(showBackground = true)
@Composable
fun MyComponentPreview() {
    ZabalaGaileTakHRTheme {
        MyComponent()
    }
}
```

### Preview Múltiple (variantes)
```kotlin
@Preview(showBackground = true, name = "Light")
@Composable
fun MyComponentLightPreview() { ... }

@Preview(showBackground = true, name = "Dark")
@Composable
fun MyComponentDarkPreview() { ... }
```

## 🔍 Tips útiles

1. **Actualizar previews**: Si no ves cambios, presiona `Ctrl+Alt+B` (o `Cmd+Option+B` en Mac) para reconstruir
2. **Zoom**: Usa la rueda del ratón para hacer zoom en la vista previa
3. **Inspeccionar**: Pasa el cursor sobre elementos para ver detalles de padding, tamaño, etc.
4. **Interactividad limitada**: Los previews son estáticos, no permiten clicks (usa el emulador para pruebas interactivas)

## 🚨 Solución de problemas

### El preview no aparece
- Asegúrate de que el archivo está guardado
- Reconstruye el proyecto: **Build** > **Rebuild Project**
- Comprueba que la función tiene `@Preview` y `@Composable`

### Error de compilación
- Verifica que importaste `androidx.compose.ui.tooling.preview.Preview`
- Comprueba que el tema `ZabalaGaileTakHRTheme` existe

### Preview muy lento
- Desactiva la actualización en tiempo real en las opciones del panel
- Reduce la complejidad del componente para pruebas rápidas

## 📚 Referencias

- [Android Compose Preview Documentation](https://developer.android.com/jetpack/compose/tooling/previews)
- [Jetpack Compose Best Practices](https://developer.android.com/jetpack/compose/hands-on)

---

**¡Disfruta del desarrollo rápido con Previews! 🎉**
