package com.zabalagailetak.hrapp.domain.model

import androidx.annotation.Keep
import com.google.gson.annotations.SerializedName

/**
 * Login request
 */
@Keep
data class LoginRequest(
    val username: String,
    val password: String
)

/**
 * Login response
 */
@Keep
data class LoginResponse(
    val token: String,
    @SerializedName("refresh_token")
    val refreshToken: String? = null,
    val message: String,
    @SerializedName("mfa_required")
    val mfaRequired: Boolean = false,
    @SerializedName("mfa_token")
    val mfaToken: String? = null
)

/**
 * MFA verification request
 */
@Keep
data class MfaVerificationRequest(
    @SerializedName("mfa_token")
    val mfaToken: String,
    val code: String
)

/**
 * Employee/User model
 */
@Keep
data class Employee(
    val id: Int,
    val username: String,
    val email: String,
    val name: String,
    val surname: String,
    @SerializedName("department_id")
    val departmentId: Int?,
    val role: String,
    @SerializedName("hire_date")
    val hireDate: String?,
    @SerializedName("phone_number")
    val phoneNumber: String?,
    val address: String?,
    val salary: Float?,
    @SerializedName("mfa_enabled")
    val mfaEnabled: Boolean = false,
    @SerializedName("created_at")
    val createdAt: String? = null
) {
    val fullName: String
        get() = "$name $surname"
}

/**
 * Department model
 */
@Keep
data class Department(
    val id: Int,
    val name: String,
    val description: String?
)
