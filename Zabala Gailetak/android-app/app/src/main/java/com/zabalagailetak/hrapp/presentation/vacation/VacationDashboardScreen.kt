package com.zabalagailetak.hrapp.presentation.vacation

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Add
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.zabalagailetak.hrapp.domain.model.VacationBalance
import com.zabalagailetak.hrapp.domain.model.VacationRequest
import com.zabalagailetak.hrapp.domain.model.VacationStatus
import java.time.LocalDate
import java.time.format.DateTimeFormatter

import androidx.compose.ui.tooling.preview.Preview
import com.zabalagailetak.hrapp.presentation.ui.theme.ZabalaGaileTakHRTheme

/**
 * Vacation Dashboard Screen - Shows balance and requests
 */
@Composable
fun VacationDashboardScreen(
    viewModel: VacationViewModel,
    onNavigateToNewRequest: () -> Unit,
    onNavigateToRequestDetail: (Int) -> Unit
) {
    val uiState by viewModel.uiState.collectAsState()
    
    LaunchedEffect(Unit) {
        viewModel.loadDashboard()
    }

    VacationDashboardContent(
        uiState = uiState,
        onNavigateToNewRequest = onNavigateToNewRequest,
        onNavigateToRequestDetail = onNavigateToRequestDetail,
        onCancelRequest = { viewModel.cancelRequest(it) }
    )
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun VacationDashboardContent(
    uiState: VacationUiState,
    onNavigateToNewRequest: () -> Unit,
    onNavigateToRequestDetail: (Int) -> Unit,
    onCancelRequest: (Int) -> Unit
) {
    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Oporrak") },
                colors = TopAppBarDefaults.topAppBarColors(
                    containerColor = MaterialTheme.colorScheme.primary,
                    titleContentColor = Color.White
                )
            )
        },
        floatingActionButton = {
            FloatingActionButton(
                onClick = onNavigateToNewRequest,
                containerColor = MaterialTheme.colorScheme.secondary
            ) {
                Icon(Icons.Default.Add, "Eskaera berria")
            }
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
                Box(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(paddingValues),
                    contentAlignment = Alignment.Center
                ) {
                    Text(
                        text = "Errorea: ${uiState.error}",
                        color = MaterialTheme.colorScheme.error
                    )
                }
            }
            else -> {
                LazyColumn(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(paddingValues)
                        .padding(16.dp),
                    verticalArrangement = Arrangement.spacedBy(16.dp)
                ) {
                    // Balance Card
                    item {
                        uiState.balance?.let { balance ->
                            BalanceCard(balance)
                        }
                    }

                    // Requests Section Header
                    item {
                        Text(
                            text = "Nire Eskaerak",
                            style = MaterialTheme.typography.titleLarge,
                            fontWeight = FontWeight.Bold
                        )
                    }

                    // Requests List
                    if (uiState.requests.isEmpty()) {
                        item {
                            Card(
                                modifier = Modifier.fillMaxWidth(),
                                colors = CardDefaults.cardColors(
                                    containerColor = MaterialTheme.colorScheme.surfaceVariant
                                )
                            ) {
                                Box(
                                    modifier = Modifier
                                        .fillMaxWidth()
                                        .padding(32.dp),
                                    contentAlignment = Alignment.Center
                                ) {
                                    Text("Ez dago eskaera aterikorik")
                                }
                            }
                        }
                    } else {
                        items(uiState.requests) { request ->
                            VacationRequestCard(
                                request = request,
                                onClick = { onNavigateToRequestDetail(request.id!!) },
                                onCancel = { onCancelRequest(request.id!!) }
                            )
                        }
                    }
                }
            }
        }
    }
}

@Preview(showBackground = true)
@Composable
fun VacationDashboardPreview() {
    ZabalaGaileTakHRTheme {
        VacationDashboardContent(
            uiState = VacationUiState(
                balance = VacationBalance(2026, 25, 10, 2, 13),
                requests = listOf(
                    VacationRequest(1, 101, "2026-07-01", "2026-07-15", 15, VacationStatus.APPROVED, "Suge oporrak"),
                    VacationRequest(2, 101, "2026-12-24", "2026-12-31", 5, VacationStatus.PENDING, "Gabonak")
                )
            ),
            onNavigateToNewRequest = {},
            onNavigateToRequestDetail = {},
            onCancelRequest = {}
        )
    }
}

@Composable
fun BalanceCard(balance: VacationBalance) {
    Card(
        modifier = Modifier.fillMaxWidth(),
        colors = CardDefaults.cardColors(
            containerColor = Color(0xFF667EEA)
        ),
        shape = RoundedCornerShape(12.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(20.dp)
        ) {
            Text(
                text = "Opor Balantzea ${balance.year}",
                color = Color.White,
                fontSize = 18.sp,
                fontWeight = FontWeight.Bold
            )
            
            Spacer(modifier = Modifier.height(16.dp))
            
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.spacedBy(12.dp)
            ) {
                BalanceItem(
                    label = "Guztira",
                    value = balance.totalDays.toString(),
                    modifier = Modifier.weight(1f)
                )
                BalanceItem(
                    label = "Erabilita",
                    value = balance.usedDays.toString(),
                    modifier = Modifier.weight(1f)
                )
            }
            
            Spacer(modifier = Modifier.height(12.dp))
            
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.spacedBy(12.dp)
            ) {
                BalanceItem(
                    label = "Zain",
                    value = balance.pendingDays.toString(),
                    modifier = Modifier.weight(1f)
                )
                BalanceItem(
                    label = "Eskuragarri",
                    value = balance.availableDays.toString(),
                    valueColor = Color(0xFF2ECC71),
                    modifier = Modifier.weight(1f)
                )
            }
        }
    }
}

@Composable
fun BalanceItem(
    label: String,
    value: String,
    valueColor: Color = Color.White,
    modifier: Modifier = Modifier
) {
    Card(
        modifier = modifier,
        colors = CardDefaults.cardColors(
            containerColor = Color.White.copy(alpha = 0.2f)
        ),
        shape = RoundedCornerShape(8.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Text(
                text = label,
                color = Color.White.copy(alpha = 0.9f),
                fontSize = 12.sp
            )
            Spacer(modifier = Modifier.height(4.dp))
            Text(
                text = value,
                color = valueColor,
                fontSize = 28.sp,
                fontWeight = FontWeight.Bold
            )
        }
    }
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun VacationRequestCard(
    request: VacationRequest,
    onClick: () -> Unit,
    onCancel: () -> Unit
) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .clickable(onClick = onClick),
        elevation = CardDefaults.cardElevation(defaultElevation = 2.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp)
        ) {
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                // Dates
                Row(verticalAlignment = Alignment.CenterVertically) {
                    Text(
                        text = formatDate(request.startDate),
                        fontSize = 16.sp,
                        fontWeight = FontWeight.SemiBold
                    )
                    Text(
                        text = " → ",
                        fontSize = 14.sp,
                        color = MaterialTheme.colorScheme.onSurfaceVariant
                    )
                    Text(
                        text = formatDate(request.endDate),
                        fontSize = 16.sp,
                        fontWeight = FontWeight.SemiBold
                    )
                }
                
                // Status Badge
                Surface(
                    color = request.status.getColor(),
                    shape = RoundedCornerShape(12.dp)
                ) {
                    Text(
                        text = request.status.toBasqueString(),
                        modifier = Modifier.padding(horizontal = 12.dp, vertical = 4.dp),
                        color = Color.White,
                        fontSize = 12.sp,
                        fontWeight = FontWeight.SemiBold
                    )
                }
            }
            
            Spacer(modifier = Modifier.height(8.dp))
            
            Text(
                text = "${request.totalDays ?: 0} egun",
                fontSize = 14.sp,
                color = MaterialTheme.colorScheme.onSurfaceVariant
            )
            
            request.notes?.let { notes ->
                Spacer(modifier = Modifier.height(4.dp))
                Text(
                    text = notes,
                    fontSize = 14.sp,
                    color = MaterialTheme.colorScheme.onSurfaceVariant,
                    style = MaterialTheme.typography.bodyMedium,
                    maxLines = 2
                )
            }
            
            // Cancel button for pending requests
            if (request.status == VacationStatus.PENDING) {
                Spacer(modifier = Modifier.height(12.dp))
                TextButton(
                    onClick = onCancel,
                    colors = ButtonDefaults.textButtonColors(
                        contentColor = MaterialTheme.colorScheme.error
                    )
                ) {
                    Text("Ezeztatu")
                }
            }
        }
    }
}

private fun formatDate(dateString: String): String {
    return try {
        val date = LocalDate.parse(dateString)
        date.format(DateTimeFormatter.ofPattern("MMM d"))
    } catch (e: Exception) {
        dateString
    }
}

@Preview(showBackground = true, name = "Light")
@Composable
fun VacationDashboardScreenPreview() {
    ZabalaGaileTakHRTheme {
        // Mock state
        val mockVacationBalance = VacationBalance(
            totalDays = 30,
            usedDays = 10,
            remainingDays = 20,
            pendingDays = 5
        )
        
        val mockRequests = listOf(
            VacationRequest(
                id = 1,
                employeeId = 101,
                startDate = "2024-03-01",
                endDate = "2024-03-10",
                daysRequested = 10,
                reason = "Udareetara joatea",
                status = VacationStatus.APPROVED,
                createdAt = "2024-02-01"
            ),
            VacationRequest(
                id = 2,
                employeeId = 101,
                startDate = "2024-04-15",
                endDate = "2024-04-20",
                daysRequested = 5,
                reason = "Ostera",
                status = VacationStatus.PENDING,
                createdAt = "2024-02-10"
            )
        )
        
        // Display simplified version
        Box(
            modifier = Modifier
                .fillMaxSize()
                .background(MaterialTheme.colorScheme.background)
                .padding(16.dp)
        ) {
            Text("Ostalagunaren iruzkina: $mockVacationBalance")
        }
    }
}
