package com.zabalagailetak.hrapp.presentation.dashboard

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.zabalagailetak.hrapp.data.api.AuthApiService
import com.zabalagailetak.hrapp.data.api.EmployeeApiService
import com.zabalagailetak.hrapp.data.api.VacationApiService
import com.zabalagailetak.hrapp.data.api.DocumentApiService
import com.zabalagailetak.hrapp.domain.model.Employee
import com.zabalagailetak.hrapp.domain.model.VacationBalance
import com.zabalagailetak.hrapp.domain.model.VacationRequest
import com.zabalagailetak.hrapp.domain.model.VacationStatus
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch
import javax.inject.Inject

/**
 * UI State for Dashboard
 */
data class DashboardUiState(
    val employee: Employee? = null,
    val vacationBalance: VacationBalance? = null,
    val pendingRequests: Int = 0,
    val totalDocuments: Int = 0,
    val recentActivities: List<ActivityItem> = emptyList(),
    val isLoading: Boolean = false,
    val error: String? = null
)

/**
 * Activity item for dashboard
 */
data class ActivityItem(
    val id: String,
    val title: String,
    val time: String,
    val type: ActivityType
)

enum class ActivityType {
    VACATION_APPROVED,
    VACATION_PENDING,
    PAYSLIP_AVAILABLE,
    DOCUMENT_UPLOADED,
    GENERAL
}

/**
 * ViewModel for Dashboard screen
 */
@HiltViewModel
class DashboardViewModel @Inject constructor(
    private val authApi: AuthApiService,
    private val employeeApi: EmployeeApiService,
    private val vacationApi: VacationApiService,
    private val documentApi: DocumentApiService
) : ViewModel() {

    private val _uiState = MutableStateFlow(DashboardUiState())
    val uiState: StateFlow<DashboardUiState> = _uiState.asStateFlow()

    init {
        loadDashboardData()
    }

    /**
     * Load all dashboard data from APIs
     */
    fun loadDashboardData() {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                // Load user data
                val employeeResult = authApi.getCurrentUser()
                val employee = if (employeeResult.isSuccessful) {
                    employeeResult.body()
                } else null
                
                // Load vacation balance
                val balanceResult = vacationApi.getBalance()
                val balance = if (balanceResult.isSuccessful) {
                    balanceResult.body()
                } else null
                
                // Load vacation requests to count pending
                val requestsResult = vacationApi.getMyRequests()
                val requests = if (requestsResult.isSuccessful) {
                    requestsResult.body()?.requests ?: emptyList()
                } else emptyList()
                
                val pendingCount = requests.count { it.status == VacationStatus.PENDING }
                
                // Load documents count
                val docsResult = documentApi.getMyDocuments()
                val docsCount = if (docsResult.isSuccessful) {
                    docsResult.body()?.documents?.size ?: 0
                } else 0
                
                // Build activity list from real data
                val activities = buildActivityList(requests, docsCount)
                
                _uiState.update {
                    it.copy(
                        employee = employee,
                        vacationBalance = balance,
                        pendingRequests = pendingCount,
                        totalDocuments = docsCount,
                        recentActivities = activities,
                        isLoading = false,
                        error = null
                    )
                }
            } catch (e: Exception) {
                _uiState.update {
                    it.copy(
                        isLoading = false,
                        error = e.message ?: "Errorea datuak kargatzean"
                    )
                }
            }
        }
    }

    /**
     * Refresh dashboard data (pull to refresh)
     */
    fun refresh() {
        loadDashboardData()
    }

    /**
     * Clear error state
     */
    fun clearError() {
        _uiState.update { it.copy(error = null) }
    }

    /**
     * Build activity list from real data
     */
    private fun buildActivityList(
        requests: List<VacationRequest>,
        docsCount: Int
    ): List<ActivityItem> {
        val activities = mutableListOf<ActivityItem>()
        
        // Add pending vacation requests
        requests.filter { it.status == VacationStatus.PENDING }.take(2).forEachIndexed { index, request ->
            activities.add(
                ActivityItem(
                    id = "vac_pending_$index",
                    title = "Opor eskaera zain: ${request.startDate}",
                    time = request.createdAt ?: "Orain dela gutxi",
                    type = ActivityType.VACATION_PENDING
                )
            )
        }
        
        // Add approved vacation requests
        requests.filter { it.status == VacationStatus.APPROVED }.take(1).forEachIndexed { index, request ->
            activities.add(
                ActivityItem(
                    id = "vac_approved_$index",
                    title = "Opor eskaera onartua",
                    time = request.hrApprovedAt ?: request.managerApprovedAt ?: request.createdAt ?: "Orain dela gutxi",
                    type = ActivityType.VACATION_APPROVED
                )
            )
        }
        
        // Add document info if there are documents
        if (docsCount > 0) {
            activities.add(
                ActivityItem(
                    id = "docs",
                    title = "$docsCount dokumentu eskuragarri",
                    time = "",
                    type = ActivityType.DOCUMENT_UPLOADED
                )
            )
        }
        
        return activities
    }

    /**
     * Get available vacation days
     */
    fun getAvailableDays(): Int {
        return _uiState.value.vacationBalance?.availableDays?.toInt() ?: 0
    }
}
