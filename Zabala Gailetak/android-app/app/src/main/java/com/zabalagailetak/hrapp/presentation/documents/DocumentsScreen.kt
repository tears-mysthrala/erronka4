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
import com.zabalagailetak.hrapp.domain.model.Document
import com.zabalagailetak.hrapp.domain.model.DocumentCategory
import com.zabalagailetak.hrapp.presentation.ui.theme.*

import androidx.compose.ui.tooling.preview.Preview
import com.zabalagailetak.hrapp.presentation.ui.theme.ZabalaGaileTakHRTheme

/**
 * Documents Screen - Display employee documents
 */
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun DocumentsScreen() {
    var selectedTab by remember { mutableStateOf(0) }
    val tabs = listOf("Nire dokumentuak", "Publikoak")
    
    // Mock data
    val myDocuments = remember {
        listOf(
            Document(1, 101, "Lan kontratua 2025", "Kontratu berritu berria", DocumentCategory.CONTRACT, "/docs/contract.pdf", null, "pdf", false, 1, null),
            Document(2, 101, "Nomina - Abendua 2025", "Azken nomina", DocumentCategory.PAYSLIP, "/docs/payslip.pdf", null, "pdf", false, 1, null),
            Document(3, 101, "Lan ziurtagiria", "Ziurtagiri orokorra", DocumentCategory.CERTIFICATE, "/docs/cert.pdf", null, "pdf", false, 1, null)
        )
    }
    
    val publicDocuments = remember {
        listOf(
            Document(4, null, "Segurtasun politika", "Enpresaren segurtasun politika", DocumentCategory.POLICY, "/docs/security_policy.pdf", null, "pdf", true, 1, null),
            Document(5, null, "Barneko arautegia", "Enpresaren arautegia", DocumentCategory.POLICY, "/docs/regulations.pdf", null, "pdf", true, 1, null)
        )
    }
    
    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Dokumentuak") },
                colors = TopAppBarDefaults.topAppBarColors(
                    containerColor = PrimaryBlue,
                    titleContentColor = Color.White
                )
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
                selectedTabIndex = selectedTab,
                containerColor = MaterialTheme.colorScheme.surface,
                contentColor = PrimaryBlue
            ) {
                tabs.forEachIndexed { index, title ->
                    Tab(
                        selected = selectedTab == index,
                        onClick = { selectedTab = index },
                        text = {
                            Text(
                                text = title,
                                style = MaterialTheme.typography.titleSmall,
                                fontWeight = if (selectedTab == index) FontWeight.Bold else FontWeight.Normal
                            )
                        }
                    )
                }
            }
            
            // Content
            LazyColumn(
                modifier = Modifier
                    .fillMaxSize()
                    .padding(horizontal = 20.dp, vertical = 16.dp),
                verticalArrangement = Arrangement.spacedBy(12.dp)
            ) {
                val documents = if (selectedTab == 0) myDocuments else publicDocuments
                
                items(documents) { document ->
                    DocumentCard(document = document)
                }
                
                if (documents.isEmpty()) {
                    item {
                        EmptyDocumentsState()
                    }
                }
            }
        }
    }
}

@Preview(showBackground = true)
@Composable
fun DocumentsScreenPreview() {
    ZabalaGaileTakHRTheme {
        DocumentsScreen()
    }
}

/**
 * Document card
 */
@Composable
fun DocumentCard(document: Document) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .clickable { /* Download or open document */ },
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
            
            IconButton(onClick = { /* Download */ }) {
                Icon(
                    imageVector = Icons.Default.Download,
                    contentDescription = "Deskargatu",
                    tint = PrimaryBlue
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

@Preview(showBackground = true, name = "Light")
@Composable
fun DocumentsScreenPreview() {
    ZabalaGaileTakHRTheme {
        val mockDocuments = listOf(
            Document(1, 101, DocumentCategory.CONTRACT, "Kontratua", "2024-01-15", "contract.pdf"),
            Document(2, 101, DocumentCategory.PAYSLIP, "Nomin 2024-01", "2024-01-31", "payslip.pdf"),
            Document(3, 101, DocumentCategory.CERTIFICATE, "Laneko sertifikatua", "2024-02-01", "certificate.pdf")
        )
        
        Box(
            modifier = Modifier
                .background(MaterialTheme.colorScheme.background)
                .fillMaxSize()
        ) {
            LazyColumn(
                modifier = Modifier
                    .fillMaxSize()
                    .padding(16.dp),
                verticalArrangement = Arrangement.spacedBy(12.dp)
            ) {
                items(mockDocuments) { document ->
                    DocumentCard(document = document, onDownload = {})
                }
            }
        }
    }
}
