package com.zabalagailetak.hrapp.presentation.auth

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.google.gson.JsonParseException
import com.zabalagailetak.hrapp.data.api.AuthApiService
import com.zabalagailetak.hrapp.data.auth.RateLimitException
import com.zabalagailetak.hrapp.domain.model.LoginRequest
import com.zabalagailetak.hrapp.domain.model.MfaVerificationRequest
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch
import retrofit2.HttpException
import java.io.IOException
import java.net.ConnectException
import java.net.SocketTimeoutException
import java.net.UnknownHostException
import javax.inject.Inject

/**
 * ViewModel for authentication screens
 */
@HiltViewModel
class AuthViewModel @Inject constructor(
    private val authApi: AuthApiService,
    private val tokenStore: com.zabalagailetak.hrapp.data.auth.TokenStore
) : ViewModel() {

    private val _uiState = MutableStateFlow(AuthUiState())
    val uiState: StateFlow<AuthUiState> = _uiState.asStateFlow()

    /**
     * Login with username and password
     */
    fun login(username: String, password: String) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                val response = authApi.login(LoginRequest(username, password))
                
                if (!response.isSuccessful) {
                    val errorMsg = parseErrorResponse(response.errorBody()?.string())
                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            error = errorMsg
                        )
                    }
                    return@launch
                }
                
                val loginResponse = response.body()
                
                if (loginResponse == null) {
                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            error = "Erantzuna hutsik dago. Saiatu berriro."
                        )
                    }
                    return@launch
                }
                
                if (loginResponse.mfaRequired) {
                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            mfaRequired = true,
                            mfaToken = loginResponse.mfaToken,
                            error = null
                        )
                    }
                } else {
                    // Save token to secure store
                    val token = loginResponse.token
                    if (!token.isNullOrBlank()) {
                        try {
                            tokenStore.saveToken(token)
                            // Persist refresh token if provided by API
                            loginResponse.refreshToken?.let { rf ->
                                if (rf.isNotBlank()) tokenStore.saveRefreshToken(rf)
                            }
                        } catch (_: Exception) {
                            // ignore store errors for now
                        }
                    }

                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            loginSuccess = true,
                            token = token,
                            error = null
                        )
                    }
                }
            } catch (e: Exception) {
                val errorMessage = mapExceptionToMessage(e)
                _uiState.update {
                    it.copy(
                        isLoading = false,
                        error = errorMessage
                    )
                }
            }
        }
    }

    /**
     * Verify MFA code
     */
    fun verifyMfa(code: String) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                val mfaToken = _uiState.value.mfaToken ?: throw Exception("No MFA token")
                
                // Call MFA verification API
                val request = MfaVerificationRequest(
                    mfaToken = mfaToken,
                    code = code
                )
                
                val response = authApi.verifyMfa(request)
                
                if (!response.isSuccessful) {
                    val errorMsg = parseErrorResponse(response.errorBody()?.string())
                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            error = errorMsg ?: "Kode okerra / Código incorrecto"
                        )
                    }
                    return@launch
                }
                
                val loginResponse = response.body()
                
                if (loginResponse == null) {
                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            error = "Erantzuna hutsik dago. Saiatu berriro."
                        )
                    }
                    return@launch
                }
                
                // Save tokens
                val token = loginResponse.token
                if (!token.isNullOrBlank()) {
                    try {
                        tokenStore.saveToken(token)
                        loginResponse.refreshToken?.let { rf ->
                            if (rf.isNotBlank()) tokenStore.saveRefreshToken(rf)
                        }
                    } catch (_: Exception) {
                        // ignore store errors
                    }
                }
                
                _uiState.update {
                    it.copy(
                        isLoading = false,
                        loginSuccess = true,
                        token = token,
                        error = null
                    )
                }
            } catch (e: Exception) {
                val errorMessage = mapExceptionToMessage(e)
                _uiState.update {
                    it.copy(
                        isLoading = false,
                        error = errorMessage
                    )
                }
            }
        }
    }

    /**
     * Clear error
     */
    fun clearError() {
        _uiState.update { it.copy(error = null) }
    }

    /**
     * Reset login state
     */
    fun resetLoginState() {
        _uiState.update {
            it.copy(
                loginSuccess = false,
                mfaRequired = false,
                mfaToken = null
            )
        }
    }

    /**
     * Maps exceptions to user-friendly messages in Basque
     */
    private fun mapExceptionToMessage(e: Exception): String {
        return when (e) {
            is UnknownHostException -> 
                "Ezin da zerbitzaria aurkitu. Egiaztatu zure konexioa."
            is ConnectException -> 
                "Ezin da zerbitzariarekin konektatu. Saiatu berriro geroago."
            is SocketTimeoutException -> 
                "Konexioa denboraz kanpo. Zerbitzaria ez dago erabilgarri."
            is IOException -> 
                "Sare errorea. Egiaztatu zure konexioa."
            is HttpException -> {
                when (e.code()) {
                    404 -> "APIa ez da aurkitu. Zerbitzaria konfiguratzen ari da."
                    429 -> "Eskaera gehiegi. Saiatu berriro minutu batean."
                    500, 502, 503 -> "Zerbitzari errorea. Saiatu berriro geroago."
                    401 -> "Kredentzial okerrak. Egiaztatu zure datuak."
                    403 -> "Sarbidea ukatua."
                    else -> "Errorea zerbitzarian (${e.code()})."
                }
            }
            is RateLimitException ->
                "Eskaera gehiegi. Saiatu berriro minutu batean. / Too many requests. Please try again."
            is JsonParseException -> 
                "Zerbitzariaren erantzuna ez da zuzena. Saiatu berriro geroago."
            else -> {
                // Check if it's a JSON parsing error
                val message = e.message ?: ""
                when {
                    message.contains("BEGIN_OBJECT") || 
                    message.contains("Expected") ||
                    message.contains("JSON") ->
                        "Zerbitzariaren erantzuna ez da zuzena. Kontsultatu administratzailea."
                    message.contains("rate limit", ignoreCase = true) ||
                    message.contains("too many", ignoreCase = true) ->
                        "Eskaera gehiegi. Saiatu berriro minutu batean."
                    else -> "Errorea gertatu da: ${e.message ?: "Saiatu berriro"}"
                }
            }
        }
    }

    /**
     * Parse error response from API
     */
    private fun parseErrorResponse(errorBody: String?): String? {
        if (errorBody.isNullOrBlank()) {
            return null
        }
        
        return try {
            // Try to parse as JSON
            val json = org.json.JSONObject(errorBody)
            when {
                json.has("message") -> json.getString("message")
                json.has("error") -> json.getString("error")
                else -> null
            }
        } catch (e: Exception) {
            // Not JSON, check for HTML error
            if (errorBody.contains("rate limit", ignoreCase = true)) {
                "Eskaera gehiegi. Saiatu berriro minutu batean."
            } else if (errorBody.contains("<html") || errorBody.contains("<!DOCTYPE")) {
                "Zerbitzari errorea. Saiatu berriro geroago."
            } else {
                errorBody.take(100)
            }
        }
    }
}

/**
 * UI State for authentication screens
 */
data class AuthUiState(
    val isLoading: Boolean = false,
    val loginSuccess: Boolean = false,
    val mfaRequired: Boolean = false,
    val mfaToken: String? = null,
    val token: String? = null,
    val error: String? = null
)
