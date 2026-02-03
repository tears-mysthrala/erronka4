# 🎬 Quick Start - Android Previews

## ⚡ 3 pasos para ver los Previews

### Paso 1: Abre Android Studio
Asegúrate de tener el proyecto Android abierto en Android Studio.

### Paso 2: Abre uno de estos archivos
```
app/src/main/java/com/zabalagailetak/hrapp/presentation/
├── auth/LoginScreen.kt
├── dashboard/DashboardScreen.kt
├── documents/DocumentsScreen.kt
├── payslips/PayslipsScreen.kt
├── profile/ProfileScreen.kt
└── vacation/
    ├── VacationDashboardScreen.kt
    └── NewVacationRequestScreen.kt
```

### Paso 3: Haz clic en "Preview"
En la parte superior derecha del editor, verás un botón **"Preview"**.
Haz clic y se abrirá el panel con la visualización.

---

## 🔍 Ubicación del botón Preview

```
┌─────────────────────────────────────────────────────────┐
│  LoginScreen.kt                     [Code] [Preview] ← ← ← HERE!
├─────────────────────────────────────────────────────────┤
│                                                           │
│  package com.zabalagailetak.hrapp.presentation.auth      │
│                                                           │
│  import androidx.compose.material3.*                     │
│  ...                                                      │
│                                                           │
│  @Composable                                             │
│  fun LoginScreen(...) { ... }                            │
│                                                           │
│  @Preview(showBackground = true, name = "Light")       │
│  @Composable                                             │
│  fun LoginPreview() { ... }                              │
│                                                           │
└─────────────────────────────────────────────────────────┘
```

---

## 🎨 Icono de Preview en el margen

También verás un pequeño icono de teléfono 📱 en el margen izquierdo:

```
│  328    @Preview(showBackground = true, name = "Light")      📱
│  329    @Composable
│  330    fun LoginPreview() {
│  331        ZabalaGaileTakHRTheme {
│  332            LoginContent(...)
```

**Haz clic en ese icono** para abrir el preview directamente.

---

## 📱 Opciones del Panel Preview

Una vez abierto el panel:

### Zoom
- **Rueda del ratón**: Zoom in/out
- **Ctrl + Rueda**: Zoom más rápido

### Cambiar variante
Si hay múltiples `@Preview`, aparecerán en una lista:
```
□ LoginPreview - Light
□ LoginPreviewLoading - With Loading
□ LoginPreviewError - With Error
```

### Interacción (limitada)
- Los previews son estáticos
- **No permiten clicks** (usa el emulador para pruebas interactivas)

### Configuración
- **Show wireframe**: Muestra bordes de layouts
- **Show grid**: Muestra cuadrícula
- **Device**: Elige dispositivo para previsualizar

---

## ⚙️ Actualizar Preview

Si los cambios no se ven:

**Opción 1**: Presiona `Ctrl+Alt+B` (Rebuild)
**Opción 2**: Guarda el archivo (Ctrl+S) y espera
**Opción 3**: Reinicia Android Studio

---

## 📊 Panel Split (Código + Preview)

Para ver código y preview lado a lado:

1. Ve a **View** en el menú
2. Click en **Split Editor**
3. Abre el archivo con previews
4. ¡Verás el código a la izquierda y el preview a la derecha!

```
┌─────────────────────────┬─────────────────────────┐
│   LoginScreen.kt        │      Preview            │
│                         │                         │
│ @Preview               │   📱 [Login Screen]     │
│ @Composable            │   ┌─────────────────┐   │
│ fun LoginPreview() {   │   │  Egun on        │   │
│   ZabalaGaileTakHRTheme│   │                 │   │
│   LoginContent(...)    │   │ Email:          │   │
│ }                      │   │ ┌──────────────┐│   │
│                         │   │ │              ││   │
│                         │   │ └──────────────┘│   │
│                         │   │                 │   │
│                         │   │ Password:       │   │
│                         │   │ ┌──────────────┐│   │
│                         │   │ │●●●●●●●●●●●● ││   │
│                         │   │ └──────────────┘│   │
│                         │   │                 │   │
│                         │   │ [    SAIO     ]│   │
│                         │   └─────────────────┘   │
└─────────────────────────┴─────────────────────────┘
```

---

## 🚀 Atajos útiles

| Acción | Atajo (Windows/Linux) | Atajo (Mac) |
|--------|----------------------|------------|
| Rebuild | `Ctrl+Alt+B` | `Cmd+Option+B` |
| Guardar | `Ctrl+S` | `Cmd+S` |
| Split Editor | Menú View | Menú View |
| Buscar | `Ctrl+F` | `Cmd+F` |

---

## 💡 Tips Pro

1. **Desarrolla rápido**: No necesitas ejecutar la app en emulador constantemente
2. **Múltiples variantes**: Cada `@Preview` es un estado diferente
3. **Datos mock**: Ya están incluidos en los previews
4. **Tema automático**: Se aplica automáticamente (ZabalaGaileTakHRTheme)

---

## ❓ Problemas comunes

### "No veo el botón Preview"
- Asegúrate de que el archivo tiene `@Preview`
- El archivo debe tener una función Composable con `@Preview` antes

### "Preview no actualiza"
- Guarda el archivo: `Ctrl+S`
- Rebuild: `Ctrl+Alt+B`
- Espera 2-3 segundos

### "Error de compilación en preview"
- Comprueba que importaste: `androidx.compose.ui.tooling.preview.Preview`
- Verifica que `ZabalaGaileTakHRTheme` existe

---

## 📚 Más información

- [PREVIEWS_GUIDE.md](PREVIEWS_GUIDE.md) - Guía completa
- [PREVIEWS_IMPLEMENTATION_SUMMARY.md](PREVIEWS_IMPLEMENTATION_SUMMARY.md) - Resumen de cambios
- [README.md](README.md) - Info general del proyecto

---

**¡Disfruta desarrollando con Previews! 🎉**

¿Tienes preguntas? Lee la documentación completa en **PREVIEWS_GUIDE.md**
