package com.zabalagailetak.hrapp.domain.model

import androidx.annotation.Keep
import com.google.gson.annotations.SerializedName

/**
 * Payslip model
 */
@Keep
data class Payslip(
    val id: Int,
    @SerializedName("employee_id")
    val employeeId: Int,
    val month: Int,
    val year: Int,
    @SerializedName("gross_salary")
    val grossSalary: Float,
    @SerializedName("net_salary")
    val netSalary: Float,
    val deductions: Float,
    val bonuses: Float? = null,
    @SerializedName("social_security")
    val socialSecurity: Float,
    val irpf: Float,
    val notes: String? = null,
    @SerializedName("file_url")
    val fileUrl: String? = null,
    @SerializedName("created_at")
    val createdAt: String? = null
) {
    val monthName: String
        get() = when (month) {
            1 -> "Urtarrila"
            2 -> "Otsaila"
            3 -> "Martxoa"
            4 -> "Apirila"
            5 -> "Maiatza"
            6 -> "Ekaina"
            7 -> "Uztaila"
            8 -> "Abuztua"
            9 -> "Iraila"
            10 -> "Urria"
            11 -> "Azaroa"
            12 -> "Abendua"
            else -> "Unknown"
        }
}

/**
 * Response for payslips list
 */
@Keep
data class PayslipsResponse(
    val payslips: List<Payslip>
)
