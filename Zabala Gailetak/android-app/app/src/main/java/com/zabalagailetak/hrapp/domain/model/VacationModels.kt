package com.zabalagailetak.hrapp.domain.model

import androidx.annotation.Keep
import com.google.gson.annotations.SerializedName
import java.time.LocalDate

/**
 * Vacation balance model
 */
@Keep
data class VacationBalance(
    @SerializedName("employee_id")
    val employeeId: Int,
    val year: Int,
    @SerializedName("total_days")
    val totalDays: Float,
    @SerializedName("used_days")
    val usedDays: Float,
    @SerializedName("pending_days")
    val pendingDays: Float,
    @SerializedName("available_days")
    val availableDays: Float
)

/**
 * Vacation request model
 */
@Keep
data class VacationRequest(
    val id: Int? = null,
    @SerializedName("employee_id")
    val employeeId: Int,
    @SerializedName("start_date")
    val startDate: String, // Format: YYYY-MM-DD
    @SerializedName("end_date")
    val endDate: String, // Format: YYYY-MM-DD
    @SerializedName("total_days")
    val totalDays: Float? = null,
    val status: VacationStatus = VacationStatus.PENDING,
    val notes: String? = null,
    @SerializedName("manager_approved_at")
    val managerApprovedAt: String? = null,
    @SerializedName("manager_approved_by")
    val managerApprovedBy: Int? = null,
    @SerializedName("hr_approved_at")
    val hrApprovedAt: String? = null,
    @SerializedName("hr_approved_by")
    val hrApprovedBy: Int? = null,
    @SerializedName("rejection_reason")
    val rejectionReason: String? = null,
    @SerializedName("created_at")
    val createdAt: String? = null,
    val employee: EmployeeInfo? = null
)

/**
 * Employee info for vacation requests
 */
@Keep
data class EmployeeInfo(
    val id: Int,
    val name: String,
    val surname: String,
    @SerializedName("department_id")
    val departmentId: Int
)

/**
 * Vacation request status
 */
@Keep
enum class VacationStatus {
    @SerializedName("PENDING")
    PENDING,
    
    @SerializedName("MANAGER_APPROVED")
    MANAGER_APPROVED,
    
    @SerializedName("APPROVED")
    APPROVED,
    
    @SerializedName("REJECTED")
    REJECTED,
    
    @SerializedName("CANCELLED")
    CANCELLED;

    fun toBasqueString(): String = when (this) {
        PENDING -> "Zain"
        MANAGER_APPROVED -> "Arduradunak onartua"
        APPROVED -> "Onartua"
        REJECTED -> "Ukatua"
        CANCELLED -> "Ezeztatua"
    }

    fun getColor(): androidx.compose.ui.graphics.Color = when (this) {
        PENDING -> androidx.compose.ui.graphics.Color(0xFFFFC107)
        MANAGER_APPROVED -> androidx.compose.ui.graphics.Color(0xFF17A2B8)
        APPROVED -> androidx.compose.ui.graphics.Color(0xFF28A745)
        REJECTED -> androidx.compose.ui.graphics.Color(0xFFDC3545)
        CANCELLED -> androidx.compose.ui.graphics.Color(0xFF6C757D)
    }
}

/**
 * Request for creating a vacation
 */
@Keep
data class CreateVacationRequest(
    @SerializedName("start_date")
    val startDate: String,
    @SerializedName("end_date")
    val endDate: String,
    val notes: String? = null
)

/**
 * Response for vacation requests list
 */
@Keep
data class VacationRequestsResponse(
    val requests: List<VacationRequest>
)
