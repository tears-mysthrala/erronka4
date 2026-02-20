package com.zabalagailetak.hrapp.presentation.documents

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.google.gson.JsonParseException
import com.google.gson.JsonSyntaxException
import com.zabalagailetak.hrapp.data.api.DocumentApiService
import com.zabalagailetak.hrapp.domain.model.Document
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
 * UI State for Documents
 */
data class DocumentsUiState(
    val myDocuments: List<Document> = emptyList(),
    val publicDocuments: List<Document> = emptyList(),
    val isLoading: Boolean = false,
    val error: String? = null,
    val selectedTab: Int = 0
)

/**
 * ViewModel for Documents screen
 */
@HiltViewModel
class DocumentsViewModel @Inject constructor(
    private val documentApi: DocumentApiService
) : ViewModel() {

    private val _uiState = MutableStateFlow(DocumentsUiState())
    val uiState: StateFlow<DocumentsUiState> = _uiState.asStateFlow()

    init {
        loadDocuments()
    }

    /**
     * Load all documents
     */
    fun loadDocuments() {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, error = null) }
            
            try {
                // Load my documents
                val myDocsResponse = documentApi.getMyDocuments()
                val myDocs = if (myDocsResponse.isSuccessful) {
                    myDocsResponse.body()?.documents ?: emptyList()
                } else emptyList()
                
                // Load public documents
                val publicDocsResponse = documentApi.getPublicDocuments()
                val publicDocs = if (publicDocsResponse.isSuccessful) {
                    publicDocsResponse.body()?.documents ?: emptyList()
                } else emptyList()
                
                _uiState.update {
                    it.copy(
                        myDocuments = myDocs,
                        publicDocuments = publicDocs,
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
                    401 -> "Saioa amaitu da."
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
                    else -> e.message ?: "Errorea dokumentuak kargatzean"
                }
            }
        }
    }

    /**
     * Refresh documents
     */
    fun refresh() {
        loadDocuments()
    }

    /**
     * Change selected tab
     */
    fun selectTab(index: Int) {
        _uiState.update { it.copy(selectedTab = index) }
    }

    /**
     * Clear error
     */
    fun clearError() {
        _uiState.update { it.copy(error = null) }
    }

    /**
     * Download document
     */
    fun downloadDocument(documentId: Int) {
        viewModelScope.launch {
            try {
                val response = documentApi.downloadDocument(documentId)
                if (!response.isSuccessful) {
                    _uiState.update {
                        it.copy(error = "Ezin izan da dokumentua deskargatu")
                    }
                }
            } catch (e: Exception) {
                val errorMessage = mapExceptionToMessage(e)
                _uiState.update {
                    it.copy(error = errorMessage)
                }
            }
        }
    }
}
