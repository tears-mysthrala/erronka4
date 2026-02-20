package com.zabalagailetak.hrapp.data.auth

import android.content.Context
import android.util.Base64
import androidx.datastore.core.DataStore
import androidx.datastore.preferences.core.Preferences
import androidx.datastore.preferences.core.edit
import androidx.datastore.preferences.core.stringPreferencesKey
import androidx.datastore.preferences.preferencesDataStore
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.withContext
import kotlinx.coroutines.runBlocking
import javax.inject.Inject
import javax.inject.Singleton
import android.util.Log

private val Context.dataStore: DataStore<Preferences> by preferencesDataStore(name = "secure_prefs")

private const val TAG = "TokenStore"

@Singleton
class TokenStore @Inject constructor(private val context: Context) {
    private val KEY_TOKEN_DATA = stringPreferencesKey("key_token_data")
    private val KEY_TOKEN_IV = stringPreferencesKey("key_token_iv")
    private val KEY_REFRESH_DATA = stringPreferencesKey("key_refresh_data")
    private val KEY_REFRESH_IV = stringPreferencesKey("key_refresh_iv")

    suspend fun saveToken(token: String) {
        try {
            val (encrypted, iv) = KeystoreHelper.encrypt(token.toByteArray())
            withContext(Dispatchers.IO) {
                context.dataStore.edit { prefs ->
                    prefs[KEY_TOKEN_DATA] = Base64.encodeToString(encrypted, Base64.NO_WRAP)
                    prefs[KEY_TOKEN_IV] = Base64.encodeToString(iv, Base64.NO_WRAP)
                }
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error saving token: ${e.message}", e)
            throw e
        }
    }

    suspend fun saveRefreshToken(refresh: String) {
        try {
            val (encrypted, iv) = KeystoreHelper.encrypt(refresh.toByteArray())
            withContext(Dispatchers.IO) {
                context.dataStore.edit { prefs ->
                    prefs[KEY_REFRESH_DATA] = Base64.encodeToString(encrypted, Base64.NO_WRAP)
                    prefs[KEY_REFRESH_IV] = Base64.encodeToString(iv, Base64.NO_WRAP)
                }
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error saving refresh token: ${e.message}", e)
            throw e
        }
    }

    suspend fun clearToken() {
        withContext(Dispatchers.IO) {
            context.dataStore.edit { prefs ->
                prefs.remove(KEY_TOKEN_DATA)
                prefs.remove(KEY_TOKEN_IV)
                prefs.remove(KEY_REFRESH_DATA)
                prefs.remove(KEY_REFRESH_IV)
            }
        }
    }

    suspend fun getToken(): String? {
        return try {
            val prefs = context.dataStore.data.first()
            val encryptedStr = prefs[KEY_TOKEN_DATA] ?: return null
            val ivStr = prefs[KEY_TOKEN_IV] ?: return null
            val encrypted = Base64.decode(encryptedStr, Base64.NO_WRAP)
            val iv = Base64.decode(ivStr, Base64.NO_WRAP)
            String(KeystoreHelper.decrypt(encrypted, iv))
        } catch (e: javax.crypto.AEADBadTagException) {
            // Decryption failed - likely due to key change or corrupted data
            Log.w(TAG, "AEADBadTagException: Token decryption failed, clearing tokens: ${e.message}")
            clearToken()
            null
        } catch (e: java.security.GeneralSecurityException) {
            Log.w(TAG, "SecurityException: Token decryption failed, clearing tokens: ${e.message}")
            clearToken()
            null
        } catch (e: Exception) {
            Log.e(TAG, "Unexpected error reading token: ${e.message}", e)
            clearToken()
            null
        }
    }

    suspend fun getRefreshToken(): String? {
        return try {
            val prefs = context.dataStore.data.first()
            val encryptedStr = prefs[KEY_REFRESH_DATA] ?: return null
            val ivStr = prefs[KEY_REFRESH_IV] ?: return null
            val encrypted = Base64.decode(encryptedStr, Base64.NO_WRAP)
            val iv = Base64.decode(ivStr, Base64.NO_WRAP)
            String(KeystoreHelper.decrypt(encrypted, iv))
        } catch (e: javax.crypto.AEADBadTagException) {
            Log.w(TAG, "AEADBadTagException: Refresh token decryption failed, clearing tokens: ${e.message}")
            clearToken()
            null
        } catch (e: java.security.GeneralSecurityException) {
            Log.w(TAG, "SecurityException: Refresh token decryption failed, clearing tokens: ${e.message}")
            clearToken()
            null
        } catch (e: Exception) {
            Log.e(TAG, "Unexpected error reading refresh token: ${e.message}", e)
            clearToken()
            null
        }
    }

    // Blocking helpers for network layer (wrap suspend calls)
    fun getTokenBlocking(): String? = runBlocking { getToken() }
    fun getRefreshTokenBlocking(): String? = runBlocking { getRefreshToken() }
}
