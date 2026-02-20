package com.zabalagailetak.hrapp.presentation.vacation

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
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
import androidx.compose.ui.unit.sp
import androidx.hilt.navigation.compose.hiltViewModel
import com.zabalagailetak.hrapp.domain.model.VacationRequest
import com.zabalagailetak.hrapp.domain.model.VacationStatus
import java.time.LocalDate
import java.time.format.DateTimeFormatter
import java.time.temporal.ChronoUnit

/**
 * Vacation Detail Screen - Shows detailed information about a vacation request
 */
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun VacationDetailScreen(
    requestId: Int,
    onNavigateBack: () -> Unit,
    viewModel: VacationViewModel = hiltViewModel()
) {
    val uiState by viewModel.uiState.collectAsState()
    
    // Load request detail when screen opens
    LaunchedEffect(requestId) {
        viewModel.loadRequestDetail(requestId)
    }
    
    val request = uiState.currentRequest
    
    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Opor eskaera xehetasunak") },
                navigationIcon = {
                    IconButton(onClick = onNavigateBack) {
                        Icon(Icons.Default.ArrowBack, contentDescription = "Atzera")
                    }
                },
                colors = TopAppBarDefaults.topAppBarColors(
                    containerColor = MaterialTheme.colorScheme.primary,
                    titleContentColor = Color.White,
                    navigationIconContentColor = Color.White
                ),
                actions = {
                    // Cancel button for pending requests
                    if (request?.status == VacationStatus.PENDING) {
                        IconButton(
                            onClick = { viewModel.cancelRequest(requestId) }
                        ) {
                            Icon(
                                Icons.Default.Cancel,
                                contentDescription = "Ezeztatu eskaera",
                                tint = Color.White
                            )
                        }
                    }
                }
            )
        }
    ) { paddingValues ->
        when {
            uiState.isLoading -> {
                Box(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(paddingValues),
                    contentAlignment = Alignment.Center
                ) {
                    CircularProgressIndicator()
                }
            }
            uiState.error != null -> {
                ErrorState(
                    error = uiState.error!!,
                    onRetry = { viewModel.loadRequestDetail(requestId) },
                    modifier = Modifier.padding(paddingValues)
                )
            }
            request == null -> {
                Box(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(paddingValues),
                    contentAlignment = Alignment.Center
                ) {
                    Text("Ez da eskaerarik aurkitu")
                }
            }
            else -> {
                VacationDetailContent(
                    request = request,
                    modifier = Modifier.padding(paddingValues)
                )
            }
        }
    }
}

@Composable
fun VacationDetailContent(
    request: VacationRequest,
    modifier: Modifier = Modifier
) {
    Column(
        modifier = modifier
            .fillMaxSize()
            .verticalScroll(rememberScrollState())
            .padding(20.dp),
        verticalArrangement = Arrangement.spacedBy(16.dp)
    ) {
        // Status Header Card
        StatusHeaderCard(request = request)
        
        // Date Details
        DetailSection(title = "Datak") {
            DateDetailItem(
                icon = Icons.Default.CalendarToday,
                label = "Hasiera data",
                value = formatFullDate(request.startDate)
            )
            
            DateDetailItem(
                icon = Icons.Default.Event,
                label = "Amaiera data",
                value = formatFullDate(request.endDate)
            )
            
            DateDetailItem(
                icon = Icons.Default.Timelapse,
                label = "Iraupena",
                value = "${request.totalDays ?: calculateDays(request.startDate, request.endDate)} egun"
            )
        }
        
        // Notes Section
        if (!request.notes.isNullOrBlank()) {
            DetailSection(title = "Oharrak") {
                Card(
                    modifier = Modifier.fillMaxWidth(),
                    shape = RoundedCornerShape(12.dp),
                    colors = CardDefaults.cardColors(
                        containerColor = MaterialTheme.colorScheme.surfaceVariant
                    )
                ) {
                    Text(
                        text = request.notes,
                        modifier = Modifier.padding(16.dp),
                        style = MaterialTheme.typography.bodyMedium,
                        color = MaterialTheme.colorScheme.onSurfaceVariant
                    )
                }
            }
        }
        
        // Approval Timeline
        if (request.status != VacationStatus.PENDING && request.status != VacationStatus.CANCELLED) {
            DetailSection(title = "Onarpen ibilbidea") {
                ApprovalTimeline(request = request)
            }
        }
        
        // Rejection Reason
        if (!request.rejectionReason.isNullOrBlank()) {
            DetailSection(title = "Uztailera ez uzteko arrazoia") {
                Card(
                    modifier = Modifier.fillMaxWidth(),
                    shape = RoundedCornerShape(12.dp),
                    colors = CardDefaults.cardColors(
                        containerColor = Color(0xFFFFEBEE)
                    )
                ) {
                    Row(
                        modifier = Modifier.padding(16.dp),
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Icon(
                            imageVector = Icons.Default.Warning,
                            contentDescription = null,
                            tint = Color(0xFFC62828)
                        )
                        Spacer(modifier = Modifier.width(12.dp))
                        Text(
                            text = request.rejectionReason,
                            style = MaterialTheme.typography.bodyMedium,
                            color = Color(0xFFC62828),
                            modifier = Modifier.weight(1f)
                        )
                    }
                }
            }
        }
        
        // Request Info Footer
        Card(
            modifier = Modifier.fillMaxWidth(),
            shape = RoundedCornerShape(12.dp),
            colors = CardDefaults.cardColors(
                containerColor = MaterialTheme.colorScheme.surface
            )
        ) {
            Column(
                modifier = Modifier.padding(16.dp),
                verticalArrangement = Arrangement.spacedBy(8.dp)
            ) {
                Text(
                    text = "Eskaeraren informazioa",
                    style = MaterialTheme.typography.titleSmall,
                    fontWeight = FontWeight.SemiBold,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
                
                request.createdAt?.let { createdAt ->
                    Text(
                        text = "Eskaera data: ${formatDateTime(createdAt)}",
                        style = MaterialTheme.typography.bodySmall,
                        color = MaterialTheme.colorScheme.onSurfaceVariant
                    )
                }
                
                Text(
                    text = "Eskaera zenbakia: #${request.id}",
                    style = MaterialTheme.typography.bodySmall,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
            }
        }
    }
}

@Composable
fun StatusHeaderCard(request: VacationRequest) {
    val statusColor = request.status.getColor()
    
    Card(
        modifier = Modifier.fillMaxWidth(),
        shape = RoundedCornerShape(16.dp),
        colors = CardDefaults.cardColors(
            containerColor = statusColor.copy(alpha = 0.1f)
        )
    ) {
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .padding(20.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            Surface(
                shape = RoundedCornerShape(12.dp),
                color = statusColor,
                modifier = Modifier.size(56.dp)
            ) {
                Box(contentAlignment = Alignment.Center) {
                    Icon(
                        imageVector = when (request.status) {
                            VacationStatus.PENDING -> Icons.Default.Schedule
                            VacationStatus.MANAGER_APPROVED -> Icons.Default.CheckCircle
                            VacationStatus.APPROVED -> Icons.Default.DoneAll
                            VacationStatus.REJECTED -> Icons.Default.Cancel
                            VacationStatus.CANCELLED -> Icons.Default.Block
                        },
                        contentDescription = null,
                        tint = Color.White,
                        modifier = Modifier.size(28.dp)
                    )
                }
            }
            
            Spacer(modifier = Modifier.width(16.dp))
            
            Column(modifier = Modifier.weight(1f)) {
                Text(
                    text = request.status.toBasqueString(),
                    style = MaterialTheme.typography.titleLarge,
                    fontWeight = FontWeight.Bold,
                    color = statusColor
                )
                
                Spacer(modifier = Modifier.height(4.dp))
                
                Text(
                    text = when (request.status) {
                        VacationStatus.PENDING -> "Zure eskaera berrikusteko zain dago"
                        VacationStatus.MANAGER_APPROVED -> "Arduradunak onartu du, HRren onarpenaren zain"
                        VacationStatus.APPROVED -> "Opor eskaera onartua izan da"
                        VacationStatus.REJECTED -> "Opor eskaera ukatua izan da"
                        VacationStatus.CANCELLED -> "Opor eskaera ezeztatua izan da"
                    },
                    style = MaterialTheme.typography.bodyMedium,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
            }
        }
    }
}

@Composable
fun DetailSection(
    title: String,
    content: @Composable ColumnScope.() -> Unit
) {
    Column(
        modifier = Modifier.fillMaxWidth(),
        verticalArrangement = Arrangement.spacedBy(12.dp)
    ) {
        Text(
            text = title,
            style = MaterialTheme.typography.titleMedium,
            fontWeight = FontWeight.Bold,
            color = MaterialTheme.colorScheme.onBackground
        )
        content()
    }
}

@Composable
fun DateDetailItem(
    icon: ImageVector,
    label: String,
    value: String
) {
    Card(
        modifier = Modifier.fillMaxWidth(),
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surface
        )
    ) {
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            Icon(
                imageVector = icon,
                contentDescription = null,
                tint = MaterialTheme.colorScheme.primary,
                modifier = Modifier.size(24.dp)
            )
            
            Spacer(modifier = Modifier.width(12.dp))
            
            Column(modifier = Modifier.weight(1f)) {
                Text(
                    text = label,
                    style = MaterialTheme.typography.bodyMedium,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
                Text(
                    text = value,
                    style = MaterialTheme.typography.titleMedium,
                    fontWeight = FontWeight.SemiBold,
                    color = MaterialTheme.colorScheme.onSurface
                )
            }
        }
    }
}

@Composable
fun ApprovalTimeline(request: VacationRequest) {
    Column(
        modifier = Modifier.fillMaxWidth(),
        verticalArrangement = Arrangement.spacedBy(8.dp)
    ) {
        // Manager approval step
        TimelineItem(
            icon = Icons.Default.Person,
            title = "Sailburuaren berrikuspena",
            status = when {
                request.managerApprovedAt != null -> TimelineStatus.COMPLETED
                request.status == VacationStatus.REJECTED && request.managerApprovedAt == null -> TimelineStatus.REJECTED
                else -> TimelineStatus.PENDING
            },
            timestamp = request.managerApprovedAt,
            approver = request.employee?.let { "${it.name} ${it.surname}" }
        )
        
        // Connector line
        if (request.status != VacationStatus.REJECTED || request.managerApprovedAt != null) {
            Box(
                modifier = Modifier
                    .padding(start = 19.dp)
                    .width(2.dp)
                    .height(24.dp)
                    .background(
                        if (request.managerApprovedAt != null) 
                            Color(0xFF28A745).copy(alpha = 0.5f)
                        else 
                            MaterialTheme.colorScheme.onSurfaceVariant.copy(alpha = 0.3f)
                    )
            )
        }
        
        // HR approval step
        TimelineItem(
            icon = Icons.Default.Business,
            title = "HRren berrikuspena",
            status = when {
                request.hrApprovedAt != null -> TimelineStatus.COMPLETED
                request.status == VacationStatus.MANAGER_APPROVED -> TimelineStatus.PENDING
                request.status == VacationStatus.REJECTED && request.managerApprovedAt != null -> TimelineStatus.REJECTED
                else -> TimelineStatus.WAITING
            },
            timestamp = request.hrApprovedAt,
            approver = request.hrApprovedBy?.let { "HR #$it" }
        )
    }
}

enum class TimelineStatus {
    COMPLETED, PENDING, REJECTED, WAITING
}

@Composable
fun TimelineItem(
    icon: ImageVector,
    title: String,
    status: TimelineStatus,
    timestamp: String?,
    approver: String?
) {
    val (iconColor, bgColor, statusText) = when (status) {
        TimelineStatus.COMPLETED -> Triple(
            Color(0xFF28A745),
            Color(0xFF28A745).copy(alpha = 0.1f),
            "Onartua"
        )
        TimelineStatus.PENDING -> Triple(
            MaterialTheme.colorScheme.primary,
            MaterialTheme.colorScheme.primary.copy(alpha = 0.1f),
            "Zain"
        )
        TimelineStatus.REJECTED -> Triple(
            Color(0xFFDC3545),
            Color(0xFFDC3545).copy(alpha = 0.1f),
            "Ukatua"
        )
        TimelineStatus.WAITING -> Triple(
            MaterialTheme.colorScheme.onSurfaceVariant,
            MaterialTheme.colorScheme.surfaceVariant,
            "Itxaroten"
        )
    }
    
    Row(
        modifier = Modifier.fillMaxWidth(),
        verticalAlignment = Alignment.CenterVertically
    ) {
        Surface(
            shape = RoundedCornerShape(10.dp),
            color = bgColor,
            modifier = Modifier.size(40.dp)
        ) {
            Box(contentAlignment = Alignment.Center) {
                Icon(
                    imageVector = icon,
                    contentDescription = null,
                    tint = iconColor,
                    modifier = Modifier.size(20.dp)
                )
            }
        }
        
        Spacer(modifier = Modifier.width(12.dp))
        
        Column(modifier = Modifier.weight(1f)) {
            Text(
                text = title,
                style = MaterialTheme.typography.bodyLarge,
                fontWeight = FontWeight.Medium,
                color = MaterialTheme.colorScheme.onSurface
            )
            
            if (timestamp != null) {
                Text(
                    text = formatDateTime(timestamp),
                    style = MaterialTheme.typography.bodySmall,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
            }
            
            approver?.let {
                Text(
                    text = "Nork: $it",
                    style = MaterialTheme.typography.bodySmall,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
            }
        }
        
        Surface(
            shape = RoundedCornerShape(8.dp),
            color = bgColor
        ) {
            Text(
                text = statusText,
                modifier = Modifier.padding(horizontal = 12.dp, vertical = 6.dp),
                style = MaterialTheme.typography.labelMedium,
                fontWeight = FontWeight.SemiBold,
                color = iconColor
            )
        }
    }
}

@Composable
fun ErrorState(
    error: String,
    onRetry: () -> Unit,
    modifier: Modifier = Modifier
) {
    Box(
        modifier = modifier.fillMaxSize(),
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

// Helper functions
private fun formatFullDate(dateString: String): String {
    return try {
        val date = LocalDate.parse(dateString)
        date.format(DateTimeFormatter.ofPattern("yyyyko MMMM'ren' d'a'", java.util.Locale("eu")))
    } catch (e: Exception) {
        dateString
    }
}

private fun formatDateTime(dateTimeString: String): String {
    return try {
        // Try parsing as LocalDateTime first
        val dateTime = java.time.LocalDateTime.parse(dateTimeString.replace(" ", "T"))
        dateTime.format(DateTimeFormatter.ofPattern("yyyy/MM/dd HH:mm", java.util.Locale("eu")))
    } catch (e: Exception) {
        try {
            // Fall back to date only
            val date = LocalDate.parse(dateTimeString.substring(0, 10))
            date.format(DateTimeFormatter.ofPattern("yyyy/MM/dd", java.util.Locale("eu")))
        } catch (e: Exception) {
            dateTimeString
        }
    }
}

private fun calculateDays(startDate: String, endDate: String): Long {
    return try {
        val start = LocalDate.parse(startDate)
        val end = LocalDate.parse(endDate)
        ChronoUnit.DAYS.between(start, end) + 1
    } catch (e: Exception) {
        0
    }
}
