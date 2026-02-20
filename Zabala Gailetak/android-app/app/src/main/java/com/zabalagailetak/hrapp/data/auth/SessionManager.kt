package com.zabalagailetak.hrapp.data.auth

import android.content.Context
import android.content.Intent
import android.util.Log
import dagger.hilt.android.qualifiers.ApplicationContext
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.SupervisorJob
import kotlinx.coroutines.flow.MutableSharedFlow
import kotlinx.coroutines.flow.SharedFlow
import kotlinx.coroutines.flow.asSharedFlow
import kotlinx.coroutines.launch
import javax.inject.Inject
import javax.inject.Singleton

/**
 * SessionManager handles global session state and broadcasts session events.
 * This is used to handle token expiration, session invalidation, and logout across the app.
 */
@Singleton
class SessionManager @Inject constructor(
    @ApplicationContext private val context: Context,
    private val tokenStore: TokenStore
) {
    private val scope = CoroutineScope(SupervisorJob() + Dispatchers.Main)
    
    companion object {
        private const val TAG = "SessionManager"
        const val ACTION_SESSION_EXPIRED = "com.zabalagailetak.hrapp.ACTION_SESSION_EXPIRED"
    }
    
    /**
     * Flow that emits when the session has expired and user needs to re-login
     */
    private val _sessionExpiredFlow = MutableSharedFlow<Unit>(extraBufferCapacity = 1)
    val sessionExpiredFlow: SharedFlow<Unit> = _sessionExpiredFlow.asSharedFlow()
    
    /**
     * Called when the refresh token fails, indicating the session is no longer valid.
     * Clears all tokens and broadcasts a session expired event.
     */
    fun onSessionExpired() {
        Log.w(TAG, "Session expired - clearing tokens and broadcasting event")
        
        scope.launch {
            // Clear all stored tokens
            tokenStore.clearToken()
            
            // Emit to flow for observers
            _sessionExpiredFlow.emit(Unit)
            
            // Broadcast intent for activities that aren't observing the flow
            val intent = Intent(ACTION_SESSION_EXPIRED).apply {
                setPackage(context.packageName)
            }
            context.sendBroadcast(intent)
        }
    }
    
    /**
     * Forces a logout by clearing tokens
     */
    fun logout() {
        Log.d(TAG, "Logout - clearing tokens")
        scope.launch {
            tokenStore.clearToken()
        }
    }
}
