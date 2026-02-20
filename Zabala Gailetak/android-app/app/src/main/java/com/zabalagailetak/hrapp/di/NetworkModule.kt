package com.zabalagailetak.hrapp.di

import android.content.Context
import com.google.gson.Gson
import com.google.gson.GsonBuilder
import com.zabalagailetak.hrapp.BuildConfig
import com.zabalagailetak.hrapp.data.api.*
import com.zabalagailetak.hrapp.data.update.UpdateManager
import com.zabalagailetak.hrapp.data.auth.AuthInterceptor
import com.zabalagailetak.hrapp.data.auth.ErrorHandlingInterceptor
import com.zabalagailetak.hrapp.data.auth.TokenStore
import dagger.hilt.android.qualifiers.ApplicationContext
import javax.inject.Named
import dagger.Module
import dagger.Provides
import dagger.hilt.InstallIn
import dagger.hilt.components.SingletonComponent
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import okhttp3.ConnectionSpec
import java.util.Collections
import java.util.concurrent.TimeUnit
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import javax.inject.Singleton

@Module
@InstallIn(SingletonComponent::class)
object NetworkModule {

    @Provides
    @Singleton
    fun provideGson(): Gson {
        return GsonBuilder()
            .setLenient()
            .create()
    }

    @Provides
    @Singleton
    fun provideLoggingInterceptor(): HttpLoggingInterceptor {
        return HttpLoggingInterceptor().apply {
            level = HttpLoggingInterceptor.Level.BODY
        }
    }

    @Provides
    @Singleton
    fun provideTokenStore(@ApplicationContext context: Context): TokenStore = TokenStore(context)

    @Provides
    @Singleton
    fun provideAuthInterceptor(tokenStore: TokenStore): AuthInterceptor = AuthInterceptor(tokenStore)

    @Provides
    @Singleton
    fun provideErrorHandlingInterceptor(): ErrorHandlingInterceptor = ErrorHandlingInterceptor()

    // Auth OkHttp client (no authenticator, only logging) used for refresh calls
    @Provides
    @Singleton
    @Named("authClient")
    fun provideAuthOkHttpClient(
        loggingInterceptor: HttpLoggingInterceptor,
        errorHandlingInterceptor: ErrorHandlingInterceptor
    ): OkHttpClient {
        return OkHttpClient.Builder()
            .addInterceptor(errorHandlingInterceptor)
            .addInterceptor(loggingInterceptor)
            .connectTimeout(30, TimeUnit.SECONDS)
            .readTimeout(30, TimeUnit.SECONDS)
            .writeTimeout(30, TimeUnit.SECONDS)
            .retryOnConnectionFailure(true)
            .build()
    }

    @Provides
    @Singleton
    @Named("authRetrofit")
    fun provideAuthRetrofit(@Named("authClient") okHttpClient: OkHttpClient, gson: Gson): Retrofit {
        return Retrofit.Builder()
            .baseUrl(BuildConfig.API_BASE_URL)
            .client(okHttpClient)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build()
    }

    // Provide the RefreshAuthenticator using a Retrofit instance that does NOT depend on the main API client,
    // to avoid cyclic injection. Create an AuthApiService from the authRetrofit here.
    @Provides
    @Singleton
    fun provideRefreshAuthenticator(
        @Named("authRetrofit") authRetrofit: Retrofit,
        tokenStore: TokenStore,
        sessionManager: com.zabalagailetak.hrapp.data.auth.SessionManager
    ): com.zabalagailetak.hrapp.data.auth.RefreshAuthenticator {
        val authApi = authRetrofit.create(AuthApiService::class.java)
        return com.zabalagailetak.hrapp.data.auth.RefreshAuthenticator(authApi, tokenStore, sessionManager)
    }

    // API OkHttp client (has AuthInterceptor and Authenticator)
    @Provides
    @Singleton
    @Named("apiClient")
    fun provideApiOkHttpClient(
        loggingInterceptor: HttpLoggingInterceptor,
        authInterceptor: AuthInterceptor,
        errorHandlingInterceptor: ErrorHandlingInterceptor,
        refreshAuthenticator: com.zabalagailetak.hrapp.data.auth.RefreshAuthenticator
    ): OkHttpClient {
        return OkHttpClient.Builder()
            .authenticator(refreshAuthenticator)
            .addInterceptor(errorHandlingInterceptor)
            .addInterceptor(authInterceptor)
            .addInterceptor(loggingInterceptor)
            .connectTimeout(30, TimeUnit.SECONDS)
            .readTimeout(30, TimeUnit.SECONDS)
            .writeTimeout(30, TimeUnit.SECONDS)
            .retryOnConnectionFailure(true)
            .build()
    }

    @Provides
    @Singleton
    @Named("apiRetrofit")
    fun provideApiRetrofit(@Named("apiClient") okHttpClient: OkHttpClient, gson: Gson): Retrofit {
        return Retrofit.Builder()
            .baseUrl(BuildConfig.API_BASE_URL)
            .client(okHttpClient)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build()
    }

    @Provides
    @Singleton
    fun provideVacationApiService(@Named("apiRetrofit") retrofit: Retrofit): VacationApiService {
        return retrofit.create(VacationApiService::class.java)
    }

    @Provides
    @Singleton
    fun provideAuthApiService(@Named("apiRetrofit") retrofit: Retrofit): AuthApiService {
        return retrofit.create(AuthApiService::class.java)
    }

    @Provides
    @Singleton
    fun provideEmployeeApiService(@Named("apiRetrofit") retrofit: Retrofit): EmployeeApiService {
        return retrofit.create(EmployeeApiService::class.java)
    }

    @Provides
    @Singleton
    fun providePayslipApiService(@Named("apiRetrofit") retrofit: Retrofit): PayslipApiService {
        return retrofit.create(PayslipApiService::class.java)
    }

    @Provides
    @Singleton
    fun provideDocumentApiService(@Named("apiRetrofit") retrofit: Retrofit): DocumentApiService {
        return retrofit.create(DocumentApiService::class.java)
    }

    @Provides
    @Singleton
    fun provideUpdateApiService(@Named("authRetrofit") retrofit: Retrofit): UpdateApiService {
        return retrofit.create(UpdateApiService::class.java)
    }
}
