package com.zabalagailetak.hrapp.domain.model

import androidx.annotation.Keep
import com.google.gson.annotations.SerializedName

/**
 * Document model
 */
@Keep
data class Document(
    val id: Int,
    @SerializedName("employee_id")
    val employeeId: Int?,
    val title: String,
    val description: String?,
    val category: DocumentCategory,
    @SerializedName("file_url")
    val fileUrl: String,
    @SerializedName("file_size")
    val fileSize: Long? = null,
    @SerializedName("file_type")
    val fileType: String? = null,
    @SerializedName("is_public")
    val isPublic: Boolean = false,
    @SerializedName("uploaded_by")
    val uploadedBy: Int?,
    @SerializedName("created_at")
    val createdAt: String? = null
)

/**
 * Document category
 */
@Keep
enum class DocumentCategory {
    @SerializedName("CONTRACT")
    CONTRACT,
    
    @SerializedName("PAYSLIP")
    PAYSLIP,
    
    @SerializedName("CERTIFICATE")
    CERTIFICATE,
    
    @SerializedName("POLICY")
    POLICY,
    
    @SerializedName("OTHER")
    OTHER;

    fun toBasqueString(): String = when (this) {
        CONTRACT -> "Kontratua"
        PAYSLIP -> "Nomina"
        CERTIFICATE -> "Ziurtagiria"
        POLICY -> "Politika"
        OTHER -> "Beste"
    }
}

/**
 * Response for documents list
 */
@Keep
data class DocumentsResponse(
    val documents: List<Document>
)
