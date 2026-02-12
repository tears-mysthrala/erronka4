package com.zabalagailetak.hrapp.presentation.payslips

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.zabalagailetak.hrapp.data.api.PayslipApiService
import com.zabalagailetak.hrapp.domain.model.Payslip
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch
import javax.inject.Inject

/**
 * UI State for Payslips
 */
data class PayslipsUiState(
    val payslips: List<Payslip> = emptyList(),
    val selectedPayslip: Payslip? = null,
    val isLoading: Boolean = false,
    val error: String? = null
)

/**
 * ViewModel for Payslips screen
 */
@HiltViewModel
class PayslipsViewModel @Inject constructor(
    private val payslipApi: PayslipApiService
) : ViewModel() {

    private val _uiState = MutableStateFlow(PayslipsUiState())
    val uiState: StateFlow<PayslipsUiState> = _uiState.asStateFlow()

    init {
        loadPayslips()
    }

    /**
     * Load all payslips
     */
    fun loadPayslips() {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                val response = payslipApi.getMyPayslips()
                
                if (response.isSuccessful) {
                    val payslips = response.body()?.payslips ?: emptyList()
                    _uiState.update {
                        it.copy(
                            payslips = payslips,
                            isLoading = false,
                            error = null
                        )
                    }
                } else {
                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            error = "Errorea nominak kargatzean"
                        )
                    }
                }
            } catch (e: Exception) {
                _uiState.update {
                    it.copy(
                        isLoading = false,
                        error = e.message ?: "Errorea nominak kargatzean"
                    )
                }
            }
        }
    }

    /**
     * Refresh payslips
     */
    fun refresh() {
        loadPayslips()
    }

    /**
     * Load payslip detail
     */
    fun loadPayslipDetail(payslipId: Int) {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                val response = payslipApi.getPayslipById(payslipId)
                
                if (response.isSuccessful) {
                    val payslip = response.body()
                    _uiState.update {
                        it.copy(
                            selectedPayslip = payslip,
                            isLoading = false,
                            error = null
                        )
                    }
                } else {
                    _uiState.update {
                        it.copy(
                            isLoading = false,
                            error = "Errorea nominaren xehetasunak kargatzean"
                        )
                    }
                }
            } catch (e: Exception) {
                _uiState.update {
                    it.copy(
                        isLoading = false,
                        error = e.message ?: "Errorea nominaren xehetasunak kargatzean"
                    )
                }
            }
        }
    }

    /**
     * Clear selected payslip
     */
    fun clearSelectedPayslip() {
        _uiState.update { it.copy(selectedPayslip = null) }
    }

    /**
     * Clear error
     */
    fun clearError() {
        _uiState.update { it.copy(error = null) }
    }

    /**
     * Download payslip
     */
    fun downloadPayslip(payslipId: Int) {
        viewModelScope.launch {
            try {
                val response = payslipApi.downloadPayslip(payslipId)
                if (!response.isSuccessful) {
                    _uiState.update {
                        it.copy(error = "Ezin izan da nomina deskargatu")
                    }
                }
            } catch (e: Exception) {
                _uiState.update {
                    it.copy(error = "Errorea deskargatzean: ${e.message}")
                }
            }
        }
    }

    /**
     * Get latest payslip
     */
    fun getLatestPayslip(): Payslip? {
        return _uiState.value.payslips.firstOrNull()
    }
}
