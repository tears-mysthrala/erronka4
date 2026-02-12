package com.zabalagailetak.hrapp.presentation.profile

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.zabalagailetak.hrapp.data.api.AuthApiService
import com.zabalagailetak.hrapp.data.api.EmployeeApiService
import com.zabalagailetak.hrapp.data.auth.TokenStore
import com.zabalagailetak.hrapp.domain.model.Employee
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch
import javax.inject.Inject

/**
 * UI State for Profile
 */
data class ProfileUiState(
    val employee: Employee? = null,
    val isLoading: Boolean = false,
    val error: String? = null,
    val logoutSuccess: Boolean = false
)

/**
 * ViewModel for Profile screen
 */
@HiltViewModel
class ProfileViewModel @Inject constructor(
    private val authApi: AuthApiService,
    private val employeeApi: EmployeeApiService,
    private val tokenStore: TokenStore
) : ViewModel() {

    private val _uiState = MutableStateFlow(ProfileUiState())
    val uiState: StateFlow<ProfileUiState> = _uiState.asStateFlow()

    init {
        loadProfile()
    }

    /**
     * Load user profile data
     */
    fun loadProfile() {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                val response = authApi.getCurrentUser()
                
                if (response.isSuccessful) {
                    val employee = response.body()
                    _uiState.update {
                        it.copy(
                            employee = employee,
                            isLoading = false,
                            error = null
                        )
                    }
                } else {
                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            error = "Errorea profila kargatzean"
                        )
                    }
                }
            } catch (e: Exception) {
                _uiState.update {
                    it.copy(
                        isLoading = false,
                        error = e.message ?: "Errorea ezezaguna"
                    )
                }
            }
        }
    }

    /**
     * Refresh profile data
     */
    fun refresh() {
        loadProfile()
    }

    /**
     * Logout user
     */
    fun logout() {
        viewModelScope.launch {
            try {
                // Call logout API
                authApi.logout()
                
                // Clear local tokens
                tokenStore.clearToken()
                
                _uiState.update {
                    it.copy(logoutSuccess = true)
                }
            } catch (e: Exception) {
                // Even if API fails, clear local tokens
                tokenStore.clearToken()
                _uiState.update {
                    it.copy(logoutSuccess = true)
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
     * Get employee full name
     */
    fun getFullName(): String {
        return _uiState.value.employee?.fullName ?: "Langilea"
    }

    /**
     * Get employee email
     */
    fun getEmail(): String {
        return _uiState.value.employee?.email ?: ""
    }

    /**
     * Get employee department
     */
    fun getDepartment(): String {
        return _uiState.value.employee?.let { 
            when (it.departmentId) {
                1 -> "Giza Baliabideak"
                2 -> "IT Saila"
                3 -> "Ekoizpena"
                4 -> "Salmentak"
                5 -> "Finantzak"
                else -> "Saila ez zehaztua"
            }
        } ?: "Saila ez zehaztua"
    }

    /**
     * Get employee role
     */
    fun getRole(): String {
        return _uiState.value.employee?.let {
            when (it.role.uppercase()) {
                "ADMIN" -> "Administratzailea"
                "RRHH_MGR" -> "GB Arduraduna"
                "JEFE_SECCION" -> "Sailburua"
                "EMPLEADO" -> "Langilea"
                "AUDITOR" -> "Auditoria"
                else -> it.role
            }
        } ?: "Langilea"
    }

    /**
     * Get phone number
     */
    fun getPhone(): String {
        return _uiState.value.employee?.phoneNumber ?: "Ez zehaztua"
    }

    /**
     * Get address
     */
    fun getAddress(): String {
        return _uiState.value.employee?.address ?: "Ez zehaztua"
    }

    /**
     * Get hire date
     */
    fun getHireDate(): String {
        return _uiState.value.employee?.hireDate ?: "Ez zehaztua"
    }
}
