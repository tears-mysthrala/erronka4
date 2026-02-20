package com.zabalagailetak.hrapp.presentation.vacation

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.google.gson.JsonParseException
import com.google.gson.JsonSyntaxException
import com.zabalagailetak.hrapp.data.api.VacationApiService
import com.zabalagailetak.hrapp.domain.model.*
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
 * ViewModel for vacation management
 */
@HiltViewModel
class VacationViewModel @Inject constructor(
    private val vacationApi: VacationApiService
) : ViewModel() {

    private val _uiState = MutableStateFlow(VacationUiState())
    val uiState: StateFlow<VacationUiState> = _uiState.asStateFlow()

    /**
     * Load dashboard data (balance + requests)
     */
    fun loadDashboard() {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                // Load balance
                val balanceResponse = vacationApi.getBalance()
                if (!balanceResponse.isSuccessful) {
                    throw Exception("Error loading balance: ${balanceResponse.code()}")
                }
                val balance = balanceResponse.body()
                
                // Load requests
                val requestsResponse = vacationApi.getMyRequests()
                if (!requestsResponse.isSuccessful) {
                    throw Exception("Error loading requests: ${requestsResponse.code()}")
                }
                val requests = requestsResponse.body()?.requests ?: emptyList()
                
                _uiState.update {
                    it.copy(
                        balance = balance,
                        requests = requests,
                        isLoading = false,
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
     * Create new vacation request
     */
    fun createRequest(startDate: String, endDate: String, notes: String?) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                val request = CreateVacationRequest(
                    startDate = startDate,
                    endDate = endDate,
                    notes = notes
                )
                
                val response = vacationApi.createRequest(request)
                if (!response.isSuccessful) {
                    val errorBody = response.errorBody()?.string()
                    throw Exception(errorBody ?: "Error creating request: ${response.code()}")
                }
                
                _uiState.update {
                    it.copy(
                        isLoading = false,
                        createSuccess = true,
                        error = null
                    )
                }
                
                // Reload dashboard after successful creation
                loadDashboard()
            } catch (e: Exception) {
                val errorMessage = mapExceptionToMessage(e)
                _uiState.update {
                    it.copy(
                        isLoading = false,
                        createSuccess = false,
                        error = errorMessage
                    )
                }
            }
        }
    }

    /**
     * Cancel a vacation request
     */
    fun cancelRequest(requestId: Int) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                val response = vacationApi.cancelRequest(requestId)
                if (!response.isSuccessful) {
                    throw Exception("Error cancelling request: ${response.code()}")
                }
                
                // Reload dashboard after successful cancellation
                loadDashboard()
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
     * Load single request details
     */
    fun loadRequestDetail(requestId: Int) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                val response = vacationApi.getRequestById(requestId)
                if (!response.isSuccessful) {
                    throw Exception("Error loading request: ${response.code()}")
                }
                
                _uiState.update {
                    it.copy(
                        currentRequest = response.body(),
                        isLoading = false,
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
     * Maps exceptions to user-friendly messages in Basque
     */
    private fun mapExceptionToMessage(e: Exception): String {
        return when (e) {
            is UnknownHostException -> 
                "Ezin da zerbitzaria aurkitu. Egiaztatu zure konexioa."
            is ConnectException -> 
                "Ezin da zerbitzariarekin konektatu."
            is SocketTimeoutException -> 
                "Konexioa denboraz kanpo."
            is IOException -> 
                "Sare errorea."
            is HttpException -> {
                when (e.code()) {
                    404 -> "APIa ez da aurkitu."
                    500, 502, 503 -> "Zerbitzari errorea."
                    401 -> "Saioa amaitu da. Logeatu berriro."
                    403 -> "Sarbidea ukatua."
                    else -> "Errorea (${e.code()})."
                }
            }
            is JsonSyntaxException, is JsonParseException -> 
                "Zerbitzariaren erantzuna ez da zuzena."
            else -> {
                val message = e.message ?: ""
                when {
                    message.contains("BEGIN_OBJECT") || 
                    message.contains("JSON") ||
                    message.contains("Json") ->
                        "Zerbitzariaren erantzuna ez da zuzena."
                    else -> e.message ?: "Errorea gertatu da"
                }
            }
        }
    }

    /**
     * Reset create success flag
     */
    fun resetCreateSuccess() {
        _uiState.update { it.copy(createSuccess = false) }
    }

    /**
     * Clear error
     */
    fun clearError() {
        _uiState.update { it.copy(error = null) }
    }
}

/**
 * UI State for vacation screens
 */
data class VacationUiState(
    val balance: VacationBalance? = null,
    val requests: List<VacationRequest> = emptyList(),
    val currentRequest: VacationRequest? = null,
    val isLoading: Boolean = false,
    val createSuccess: Boolean = false,
    val error: String? = null
)
