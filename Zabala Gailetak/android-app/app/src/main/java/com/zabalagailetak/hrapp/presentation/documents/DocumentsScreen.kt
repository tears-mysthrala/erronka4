package com.zabalagailetak.hrapp.presentation.documents

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.hilt.navigation.compose.hiltViewModel
import com.zabalagailetak.hrapp.domain.model.Document
import com.zabalagailetak.hrapp.domain.model.DocumentCategory

/**
 * Documents Screen - Display employee documents with real data
 */
@Composable
fun DocumentsScreen(
    viewModel: DocumentsViewModel = hiltViewModel()
) {
    val uiState by viewModel.uiState.collectAsState()
    
    LaunchedEffect(Unit) {
        viewModel.loadDocuments()
    }

    DocumentsContent(
        uiState = uiState,
        onRefresh = { viewModel.refresh() },
        onSelectTab = { viewModel.selectTab(it) },
        onDownload = { viewModel.downloadDocument(it) }
    )
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun DocumentsContent(
    uiState: DocumentsUiState,
    onRefresh: () -> Unit,
    onSelectTab: (Int) -> Unit,
    onDownload: (Int) -> Unit
) {
    val tabs = listOf("Nire dokumentuak", "Publikoak")
    
    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Dokumentuak") },
                colors = TopAppBarDefaults.topAppBarColors(
                    containerColor = MaterialTheme.colorScheme.primary,
                    titleContentColor = Color.White
                ),
                actions = {
                    IconButton(
                        onClick = onRefresh,
                        enabled = !uiState.isLoading
                    ) {
                        if (uiState.isLoading) {
                            CircularProgressIndicator(
                                modifier = Modifier.size(20.dp),
                                color = Color.White,
                                strokeWidth = 2.dp
                            )
                        } else {
                            Icon(
                                Icons.Default.Refresh,
                                contentDescription = "Freskatu",
                                tint = Color.White
                            )
                        }
                    }
                }
            )
        }
    ) { paddingValues ->
        Column(
            modifier = Modifier
                .fillMaxSize()
                .background(MaterialTheme.colorScheme.background)
                .padding(paddingValues)
        ) {
            // Tab row
            TabRow(
                selectedTabIndex = uiState.selectedTab,
                containerColor = MaterialTheme.colorScheme.surface,
                contentColor = MaterialTheme.colorScheme.primary
            ) {
                tabs.forEachIndexed { index, title ->
                    Tab(
                        selected = uiState.selectedTab == index,
                        onClick = { onSelectTab(index) },
                        text = {
                            Text(
                                text = title,
                                style = MaterialTheme.typography.titleSmall,
                                fontWeight = if (uiState.selectedTab == index) FontWeight.Bold else FontWeight.Normal
                            )
                        }
                    )
                }
            }
            
            // Content
            when {
                uiState.isLoading && (uiState.myDocuments.isEmpty() && uiState.publicDocuments.isEmpty()) -> {
                    Box(
                        modifier = Modifier.fillMaxSize(),
                        contentAlignment = Alignment.Center
                    ) {
                        CircularProgressIndicator()
                    }
                }
                uiState.error != null -> {
                    ErrorState(
                        error = uiState.error!!,
                        onRetry = onRefresh
                    )
                }
                else -> {
                    val documents = if (uiState.selectedTab == 0) uiState.myDocuments else uiState.publicDocuments
                    
                    LazyColumn(
                        modifier = Modifier
                            .fillMaxSize()
                            .padding(horizontal = 20.dp, vertical = 16.dp),
                        verticalArrangement = Arrangement.spacedBy(12.dp)
                    ) {
                        if (documents.isEmpty()) {
                            item {
                                EmptyDocumentsState()
                            }
                        } else {
                            items(documents) { document ->
                                DocumentCard(
                                    document = document,
                                    onDownload = { onDownload(document.id) }
                                )
                            }
                        }
                    }
                }
            }
        }
    }
}

@Composable
fun ErrorState(
    error: String,
    onRetry: () -> Unit
) {
    Box(
        modifier = Modifier.fillMaxSize(),
        contentAlignment = Alignment.Center
    ) {
        Card(
            modifier = Modifier.padding(20.dp),
            shape = RoundedCornerShape(16.dp),
            colors = CardDefaults.cardColors(
                containerColor = MaterialTheme.colorScheme.errorContainer
            )
        ) {
            Column(
                modifier = Modifier.padding(24.dp),
                horizontalAlignment = Alignment.CenterHorizontally
            ) {
                Icon(
                    imageVector = Icons.Default.Error,
                    contentDescription = null,
                    tint = MaterialTheme.colorScheme.error,
                    modifier = Modifier.size(48.dp)
                )
                
                Spacer(modifier = Modifier.height(16.dp))
                
                Text(
                    text = error,
                    style = MaterialTheme.typography.bodyLarge,
                    color = MaterialTheme.colorScheme.onErrorContainer,
                    textAlign = androidx.compose.ui.text.style.TextAlign.Center
                )
                
                Spacer(modifier = Modifier.height(16.dp))
                
                Button(
                    onClick = onRetry,
                    colors = ButtonDefaults.buttonColors(
                        containerColor = MaterialTheme.colorScheme.error
                    )
                ) {
                    Text("Saiatu berriro")
                }
            }
        }
    }
}

@Composable
fun DocumentCard(
    document: Document,
    onDownload: () -> Unit
) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .clickable { /* Open document */ },
        shape = RoundedCornerShape(16.dp),
        colors = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surface
        ),
        elevation = CardDefaults.cardElevation(defaultElevation = 2.dp)
    ) {
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            // Document icon based on type
            Surface(
                shape = RoundedCornerShape(12.dp),
                color = getDocumentColor(document.category).copy(alpha = 0.15f),
                modifier = Modifier.size(56.dp)
            ) {
                Box(contentAlignment = Alignment.Center) {
                    Icon(
                        imageVector = getDocumentIcon(document.category),
                        contentDescription = null,
                        tint = getDocumentColor(document.category),
                        modifier = Modifier.size(28.dp)
                    )
                }
            }
            
            Spacer(modifier = Modifier.width(16.dp))
            
            Column(modifier = Modifier.weight(1f)) {
                Text(
                    text = document.title,
                    style = MaterialTheme.typography.titleMedium,
                    fontWeight = FontWeight.SemiBold,
                    color = MaterialTheme.colorScheme.onSurface
                )
                
                Spacer(modifier = Modifier.height(4.dp))
                
                Row(
                    verticalAlignment = Alignment.CenterVertically,
                    horizontalArrangement = Arrangement.spacedBy(8.dp)
                ) {
                    Surface(
                        shape = RoundedCornerShape(6.dp),
                        color = getDocumentColor(document.category).copy(alpha = 0.15f)
                    ) {
                        Text(
                            text = document.category.toBasqueString(),
                            style = MaterialTheme.typography.bodySmall,
                            color = getDocumentColor(document.category),
                            modifier = Modifier.padding(horizontal = 8.dp, vertical = 4.dp)
                        )
                    }
                    
                    Text(
                        text = document.fileType?.uppercase() ?: "PDF",
                        style = MaterialTheme.typography.bodySmall,
                        color = MaterialTheme.colorScheme.onSurfaceVariant
                    )
                }
            }
            
            IconButton(onClick = onDownload) {
                Icon(
                    imageVector = Icons.Default.Download,
                    contentDescription = "Deskargatu",
                    tint = MaterialTheme.colorScheme.primary
                )
            }
        }
    }
}

/**
 * Empty state when no documents
 */
@Composable
fun EmptyDocumentsState() {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(vertical = 32.dp),
        shape = RoundedCornerShape(16.dp),
        colors = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surfaceVariant
        )
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(48.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Icon(
                imageVector = Icons.Default.FolderOpen,
                contentDescription = null,
                modifier = Modifier.size(64.dp),
                tint = MaterialTheme.colorScheme.onSurfaceVariant
            )
            
            Spacer(modifier = Modifier.height(16.dp))
            
            Text(
                text = "Ez dago dokumenturik",
                style = MaterialTheme.typography.titleMedium,
                color = MaterialTheme.colorScheme.onSurfaceVariant
            )
        }
    }
}

private fun getDocumentIcon(category: DocumentCategory): ImageVector {
    return when (category) {
        DocumentCategory.CONTRACT -> Icons.Default.Description
        DocumentCategory.PAYSLIP -> Icons.Default.Receipt
        DocumentCategory.CERTIFICATE -> Icons.Default.Verified
        DocumentCategory.POLICY -> Icons.Default.Policy
        DocumentCategory.OTHER -> Icons.Default.InsertDriveFile
    }
}

private fun getDocumentColor(category: DocumentCategory): Color {
    return when (category) {
        DocumentCategory.CONTRACT -> Color(0xFF667EEA)
        DocumentCategory.PAYSLIP -> Color(0xFF06B6D4)
        DocumentCategory.CERTIFICATE -> Color(0xFF10B981)
        DocumentCategory.POLICY -> Color(0xFF9333EA)
        DocumentCategory.OTHER -> Color(0xFF6B7280)
    }
}

private fun DocumentCategory.toBasqueString(): String {
    return when (this) {
        DocumentCategory.CONTRACT -> "Kontratua"
        DocumentCategory.PAYSLIP -> "Nomina"
        DocumentCategory.CERTIFICATE -> "Ziurtagiria"
        DocumentCategory.POLICY -> "Politika"
        DocumentCategory.OTHER -> "Bestelakoa"
    }
}
