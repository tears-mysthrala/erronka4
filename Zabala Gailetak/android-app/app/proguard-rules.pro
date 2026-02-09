# ProGuard rules for Zabala Gailetak HR App
# Add project specific ProGuard rules here

# Keep class names for Hilt
-keepclassmembers class * {
    @dagger.hilt.android.lifecycle.HiltViewModel *;
}

# Keep class names for Retrofit models
-keep class com.zabalagailetak.hrapp.domain.model.** { *; }

# Keep class names for Retrofit interfaces
-keep interface com.zabalagailetak.hrapp.data.api.** { *; }

# Keep Kotlin metadata
-keepattributes RuntimeVisibleAnnotations,RuntimeInvisibleAnnotations,RuntimeVisibleParameterAnnotations,RuntimeInvisibleParameterAnnotations,AnnotationDefault

# Keep Kotlin coroutines
-keepclassmembernames class kotlinx.** {
    volatile <fields>;
}

# Keep Compose
-keep class androidx.compose.** { *; }

# Keep ViewModel
-keep class * extends androidx.lifecycle.ViewModel { *; }

# Suppress warnings for known issues
-dontwarn com.google.errorprone.annotations.**
-dontwarn javax.annotation.**
