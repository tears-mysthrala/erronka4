package com.zabalagailetak.hrapp.data.auth

import okhttp3.Interceptor
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.Response
import okhttp3.ResponseBody.Companion.toResponseBody
import java.io.IOException

/**
 * Interceptor that handles API errors and ensures proper error responses.
 * Converts HTML error pages to JSON error responses.
 */
class ErrorHandlingInterceptor : Interceptor {
    
    companion object {
        private const val CONTENT_TYPE_JSON = "application/json"
    }
    
    override fun intercept(chain: Interceptor.Chain): Response {
        val request = chain.request()
        val response = chain.proceed(request)
        
        // If response is successful and is JSON, return as-is
        if (response.isSuccessful) {
            val contentType = response.body?.contentType()?.toString() ?: ""
            if (contentType.contains(CONTENT_TYPE_JSON) || contentType.isEmpty()) {
                return response
            }
            // If content type is not JSON, we might have an issue
        }
        
        // Check if response body is HTML instead of JSON
        val contentType = response.body?.contentType()?.toString() ?: ""
        val bodyString = response.body?.string() ?: ""
        
        return when {
            // If it's HTML, convert to JSON error
            contentType.contains("text/html") || bodyString.trim().startsWith("<") -> {
                createJsonErrorResponse(response, bodyString)
            }
            // If body is empty, create a generic error
            bodyString.isBlank() -> {
                createJsonErrorResponse(response, "Empty response from server")
            }
            // Otherwise, rebuild the response with the original body
            else -> {
                response.newBuilder()
                    .body(bodyString.toResponseBody(response.body?.contentType()))
                    .build()
            }
        }
    }
    
    private fun createJsonErrorResponse(originalResponse: Response, htmlBody: String): Response {
        // Extract meaningful error message from HTTP status
        val errorMessage = when (originalResponse.code) {
            404 -> "API endpoint not found. Please check server configuration."
            500 -> "Internal server error. Please try again later."
            502 -> "Bad gateway. Server may be down."
            503 -> "Service unavailable. Please try again later."
            401 -> "Authentication failed. Please check your credentials."
            403 -> "Access denied. You don't have permission to access this resource."
            else -> "Server error (${originalResponse.code}). Please check your connection."
        }
        
        // Create a JSON error response that Gson can parse
        val jsonError = """
            {
                "success": false,
                "message": "$errorMessage",
                "error_code": ${originalResponse.code}
            }
        """.trimIndent()
        
        return originalResponse.newBuilder()
            .body(jsonError.toResponseBody("application/json".toMediaType()))
            .build()
    }
}

/**
 * Custom exception for API errors with user-friendly messages
 */
class ApiException(
    val code: Int,
    override val message: String,
    val isHtmlError: Boolean = false
) : IOException(message)
