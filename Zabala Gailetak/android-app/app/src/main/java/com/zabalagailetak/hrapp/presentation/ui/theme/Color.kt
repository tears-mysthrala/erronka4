package com.zabalagailetak.hrapp.presentation.ui.theme

import androidx.compose.ui.graphics.Color

// ============================================================================
// UNIFIED COLOR PALETTE - Aligned with Web Portal
// Updated: 2026-02-06
// Web Portal Standard: /public/assets/css/industrial-v2.php
// ============================================================================

// Material 3 base colors (legacy, kept for compatibility)
val Purple80 = Color(0xFFD0BCFF)
val PurpleGrey80 = Color(0xFFCCC2DC)
val Pink80 = Color(0xFFEFB8C8)

val Purple40 = Color(0xFF6650a4)
val PurpleGrey40 = Color(0xFF625b71)
val Pink40 = Color(0xFF7D5260)

// ============================================================================
// PRIMARY COLORS (Web Standard)
// ============================================================================

val PrimaryBlue = Color(0xFF1D4ED8)        // Match web --primary (Deep Blue)
val PrimaryBlueLight = Color(0xFF3B82F6)   // Match web --primary-light
val PrimaryBlueDark = Color(0xFF1E3A8A)    // Match web --primary-dark

// ============================================================================
// ACCENT COLORS (Web Standard)
// ============================================================================

val AccentBlue = Color(0xFF0EA5E9)         // Match web --accent (Light Blue)
val AccentBlueLight = Color(0xFF38BDF8)    // Match web --accent-light

// ============================================================================
// SEMANTIC COLORS (Web Standard)
// ============================================================================

val SuccessGreen = Color(0xFF059669)       // Match web --success
val SuccessGreenLight = Color(0xFF10B981)  // Match web --success-light
val WarningAmber = Color(0xFFD97706)       // Match web --warning
val WarningAmberLight = Color(0xFFF59E0B)  // Match web --warning-light
val ErrorRed = Color(0xFFDC2626)           // Match web --danger
val InfoBlue = Color(0xFF0284C7)           // Match web --info

// ============================================================================
// GRADIENT COLORS (Mobile Enhancement - Not in Web)
// ============================================================================
// Keep these for mobile-specific cards and enhancements
// Use sparingly to maintain cross-platform consistency

val GradientStart = Color(0xFF667EEA)      // Purple-blue
val GradientMiddle = Color(0xFF764BA2)     // Purple
val GradientEnd = Color(0xFFF093FB)        // Pink-purple

// ============================================================================
// NEUTRAL COLORS
// ============================================================================

val DarkBackground = Color(0xFF0F172A)     // Deep navy black
val DarkSurface = Color(0xFF1E293B)        // Navy gray
val DarkCard = Color(0xFF334155)           // Lighter navy
val LightGray = Color(0xFFF1F5F9)          // Almost white
val MediumGray = Color(0xFF94A3B8)         // Medium gray

// ============================================================================
// TEXT COLORS
// ============================================================================

val TextPrimary = Color(0xFF0F172A)        // Dark navy for light theme
val TextSecondary = Color(0xFF64748B)      // Gray for light theme
val TextPrimaryDark = Color(0xFFF8FAFC)    // Almost white for dark theme
val TextSecondaryDark = Color(0xFFCBD5E1)  // Light gray for dark theme

// ============================================================================
// SPECIAL EFFECTS
// ============================================================================

val GlassmorphismOverlay = Color(0x1AFFFFFF)   // For glassmorphism effect
val ShadowColor = Color(0x40000000)             // For elevation shadows

// ============================================================================
// DEPRECATED COLORS (Remove in v2.0)
// ============================================================================
// These colors are being phased out for web alignment
// Use the web-standard equivalents instead

@Deprecated(
    message = "Use AccentBlue instead for web portal alignment",
    replaceWith = ReplaceWith("AccentBlue"),
    level = DeprecationLevel.WARNING
)
val SecondaryTeal = Color(0xFF06B6D4)

@Deprecated(
    message = "Use WarningAmberLight instead for web portal alignment",
    replaceWith = ReplaceWith("WarningAmberLight"),
    level = DeprecationLevel.WARNING
)
val AccentOrange = Color(0xFFFF6B35)

@Deprecated(
    message = "Use InfoBlue instead for web portal alignment",
    replaceWith = ReplaceWith("InfoBlue"),
    level = DeprecationLevel.WARNING
)
val AccentPurple = Color(0xFF9333EA)

