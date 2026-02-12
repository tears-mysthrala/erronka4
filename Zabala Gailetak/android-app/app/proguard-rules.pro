# ProGuard rules for Zabala Gailetak HR App
# Add project specific ProGuard rules here

# Keep class names for Hilt
-keep class * {
    @dagger.hilt.android.lifecycle.HiltViewModel <fields>;
    @dagger.hilt.android.lifecycle.HiltViewModel <methods>;
}

-keepclassmembers class * {
    @dagger.hilt.android.lifecycle.HiltViewModel *;
}

-keep class dagger.hilt.** { *; }
-keep class javax.inject.** { *; }

# Keep Hilt-generated classes
-keep class * extends dagger.hilt.internal.GeneratedComponent { *; }
-keep class * extends dagger.hilt.internal.GeneratedComponentManager { *; }
-keep class dagger.hilt.android.internal.managers.** { *; }

# Keep Application class
-keep class com.zabalagailetak.hrapp.HrApplication { *; }

# Keep class names for Retrofit models
-keep class com.zabalagailetak.hrapp.domain.model.** { *; }

# Keep class names for Retrofit interfaces
-keep interface com.zabalagailetak.hrapp.data.api.** { *; }

# Keep Retrofit and OkHttp
-keep class retrofit2.** { *; }
-keep class okhttp3.** { *; }
-keep class okio.** { *; }

# Keep Gson
-keep class com.google.gson.** { *; }
-keep class * implements com.google.gson.TypeAdapterFactory
-keep class * implements com.google.gson.JsonSerializer
-keep class * implements com.google.gson.JsonDeserializer

# Keep Kotlin metadata
-keepattributes RuntimeVisibleAnnotations,RuntimeInvisibleAnnotations,RuntimeVisibleParameterAnnotations,RuntimeInvisibleParameterAnnotations,AnnotationDefault
-keepattributes Signature,Exceptions,InnerClasses,EnclosingMethod

# Keep Kotlin coroutines
-keepclassmembernames class kotlinx.** {
    volatile <fields>;
}
-keep class kotlinx.coroutines.** { *; }

# Keep Compose
-keep class androidx.compose.** { *; }

# Keep ViewModel
-keep class * extends androidx.lifecycle.ViewModel { *; }
-keep class * extends androidx.lifecycle.AndroidViewModel { *; }

# Keep Room
-keep class androidx.room.** { *; }
-keep class * extends androidx.room.RoomDatabase { *; }

# Keep DataStore
-keep class androidx.datastore.** { *; }

# Keep Biometric
-keep class androidx.biometric.** { *; }

# Keep Credentials
-keep class androidx.credentials.** { *; }

# Keep Error Handling Interceptor
-keep class com.zabalagailetak.hrapp.data.auth.ErrorHandlingInterceptor { *; }
-keep class com.zabalagailetak.hrapp.data.auth.ApiException { *; }

# Keep Update Manager
-keep class com.zabalagailetak.hrapp.data.update.UpdateManager { *; }
-keep class com.zabalagailetak.hrapp.data.update.UpdateResult { *; }
-keep class com.zabalagailetak.hrapp.data.api.UpdateApiService { *; }
-keep class com.zabalagailetak.hrapp.data.api.VersionInfo { *; }

# Keep Coil
-keep class coil.** { *; }

# Suppress warnings for known issues
-dontwarn com.google.errorprone.annotations.**
-dontwarn javax.annotation.**
-dontwarn kotlin.coroutines.**
-dontwarn kotlinx.coroutines.**
-dontwarn org.bouncycastle.**
-dontwarn org.conscrypt.**
-dontwarn org.openjsse.**

# Keep R classes
-keepclassmembers class **.R$* {
    public static <fields>;
}

# Keep Parcelable
-keep class * implements android.os.Parcelable {
    public static final android.os.Parcelable$Creator *;
}

# Keep Serializable
-keep class * implements java.io.Serializable { *; }

# Keep enums
-keepclassmembers enum * {
    public static **[] values();
    public static ** valueOf(java.lang.String);
}

# Keep Android components
-keep public class * extends android.app.Activity
-keep public class * extends android.app.Application
-keep public class * extends android.app.Service
-keep public class * extends android.content.BroadcastReceiver
-keep public class * extends android.content.ContentProvider
