package com.zabalagailetak.hrapp.data.api

import retrofit2.Response
import retrofit2.http.GET

/**
 * API Service for checking app updates
 */
interface UpdateApiService {
    
    /**
     * Get latest version info from server
     */
    @GET("version.json")
    suspend fun getLatestVersion(): Response<VersionInfo>
}

/**
 * Version information response
 */
data class VersionInfo(
    val versionName: String,
    val versionCode: Int,
    val downloadUrl: String? = null,
    val releaseNotes: String? = null,
    val forceUpdate: Boolean = false
)
