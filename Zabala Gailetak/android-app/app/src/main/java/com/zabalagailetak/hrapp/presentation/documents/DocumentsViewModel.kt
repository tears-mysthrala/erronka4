package com.zabalagailetak.hrapp.presentation.documents

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.zabalagailetak.hrapp.data.api.DocumentApiService
import com.zabalagailetak.hrapp.domain.model.Document
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch
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
                _uiState.update {
                    it.copy(
                        isLoading = false,
                        error = e.message ?: "Errorea dokumentuak kargatzean"
                    )
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
                _uiState.update {
                    it.copy(error = "Errorea deskargatzean: ${e.message}")
                }
            }
        }
    }
}
