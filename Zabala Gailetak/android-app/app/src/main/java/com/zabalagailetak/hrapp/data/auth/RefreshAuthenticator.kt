package com.zabalagailetak.hrapp.data.auth

import android.util.Log
import com.zabalagailetak.hrapp.data.api.AuthApiService
import kotlinx.coroutines.runBlocking
import okhttp3.Authenticator
import okhttp3.Request
import okhttp3.Response
import okhttp3.Route
import javax.inject.Inject

/**
 * OkHttp Authenticator that attempts to use the refresh token to obtain a new access token.
 * This implementation is conservative: it performs a synchronous refresh call and updates TokenStore.
 * 
 * Handles token refresh failures gracefully:
 * - If refresh token is expired/invalid, clears session and triggers re-login
 * - Prevents infinite refresh loops by tracking retry count
 */
class RefreshAuthenticator @Inject constructor(
    private val authApi: AuthApiService,
    private val tokenStore: TokenStore,
    private val sessionManager: SessionManager
) : Authenticator {

    companion object {
        private const val TAG = "RefreshAuthenticator"
        private const val MAX_RETRY_COUNT = 1
    }

    override fun authenticate(route: Route?, response: Response): Request? {
        // Avoid infinite loops - check if we've already tried to refresh
        val retryCount = response.request.header("X-Refresh-Retry")?.toIntOrNull() ?: 0
        if (retryCount >= MAX_RETRY_COUNT) {
            Log.w(TAG, "Max refresh retries reached, giving up")
            sessionManager.onSessionExpired()
            return null
        }

        // If no Authorization header was present, can't refresh
        if (response.request.header("Authorization") == null) {
            Log.w(TAG, "No authorization header, cannot refresh")
            return null
        }

        // Get refresh token
        val refresh = tokenStore.getRefreshTokenBlocking()
        if (refresh == null) {
            Log.w(TAG, "No refresh token available")
            sessionManager.onSessionExpired()
            return null
        }

        return try {
            Log.d(TAG, "Attempting token refresh...")
            
            // Call refresh endpoint synchronously via runBlocking
            val refreshResponse = runBlocking {
                val body = mapOf("refresh_token" to refresh)
                authApi.refresh(body)
            }

            // Handle refresh failure
            if (!refreshResponse.isSuccessful) {
                val errorCode = refreshResponse.code()
                Log.w(TAG, "Token refresh failed with code: $errorCode")
                
                // If 401 Unauthorized, the refresh token is expired/invalid
                if (errorCode == 401) {
                    Log.e(TAG, "Refresh token expired or invalid, session expired")
                    sessionManager.onSessionExpired()
                }
                return null
            }

            val newTokens = refreshResponse.body()
            if (newTokens == null) {
                Log.e(TAG, "Refresh response body is null")
                return null
            }

            val newAccess = newTokens.token
            val newRefresh = newTokens.refreshToken

            if (newAccess.isBlank()) {
                Log.e(TAG, "New access token is blank")
                return null
            }

            // Persist tokens
            runBlocking {
                tokenStore.saveToken(newAccess)
                if (!newRefresh.isNullOrBlank()) {
                    tokenStore.saveRefreshToken(newRefresh)
                }
                Log.d(TAG, "Tokens refreshed successfully")
            }

            // Build a new request with updated Authorization header
            // Include retry count header to prevent infinite loops
            response.request.newBuilder()
                .header("Authorization", "Bearer $newAccess")
                .header("X-Refresh-Retry", (retryCount + 1).toString())
                .build()
                
        } catch (e: Exception) {
            Log.e(TAG, "Exception during token refresh: ${e.message}", e)
            null
        }
    }
}
