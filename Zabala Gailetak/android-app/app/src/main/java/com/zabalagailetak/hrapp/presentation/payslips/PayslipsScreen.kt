package com.zabalagailetak.hrapp.presentation.payslips

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
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.hilt.navigation.compose.hiltViewModel
import com.zabalagailetak.hrapp.domain.model.Payslip
import java.text.NumberFormat
import java.util.*

/**
 * Payslips Screen - Display employee payslips with real data
 */
@Composable
fun PayslipsScreen(
    onNavigateToDetail: (Int) -> Unit,
    viewModel: PayslipsViewModel = hiltViewModel()
) {
    val uiState by viewModel.uiState.collectAsState()
    
    LaunchedEffect(Unit) {
        viewModel.loadPayslips()
    }

    PayslipsContent(
        uiState = uiState,
        onRefresh = { viewModel.refresh() },
        onNavigateToDetail = onNavigateToDetail
    )
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun PayslipsContent(
    uiState: PayslipsUiState,
    onRefresh: () -> Unit,
    onNavigateToDetail: (Int) -> Unit
) {
    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Nominak") },
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
        when {
            uiState.isLoading && uiState.payslips.isEmpty() -> {
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
                    onRetry = onRefresh,
                    modifier = Modifier.padding(paddingValues)
                )
            }
            uiState.payslips.isEmpty() -> {
                LazyColumn(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(paddingValues)
                        .padding(horizontal = 20.dp, vertical = 16.dp)
                ) {
                    item {
                        EmptyPayslipsState()
                    }
                }
            }
            else -> {
                LazyColumn(
                    modifier = Modifier
                        .fillMaxSize()
                        .background(MaterialTheme.colorScheme.background)
                        .padding(paddingValues)
                        .padding(horizontal = 20.dp, vertical = 16.dp),
                    verticalArrangement = Arrangement.spacedBy(12.dp)
                ) {
                    // Summary card with latest payslip
                    item {
                        PayslipSummaryCard(latestPayslip = uiState.payslips.firstOrNull())
                    }
                    
                    // Section header
                    item {
                        Text(
                            text = "Nominen historiala",
                            style = MaterialTheme.typography.titleMedium,
                            fontWeight = FontWeight.SemiBold,
                            color = MaterialTheme.colorScheme.onBackground,
                            modifier = Modifier.padding(vertical = 8.dp)
                        )
                    }
                    
                    // Payslips list
                    items(uiState.payslips) { payslip ->
                        PayslipCard(
                            payslip = payslip,
                            onClick = { onNavigateToDetail(payslip.id) }
                        )
                    }
                }
            }
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

/**
 * Summary card showing latest payslip info
 */
@Composable
fun PayslipSummaryCard(latestPayslip: Payslip?) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .height(160.dp),
        shape = RoundedCornerShape(20.dp),
        colors = CardDefaults.cardColors(
            containerColor = Color.Transparent
        )
    ) {
        Box(
            modifier = Modifier
                .fillMaxSize()
                .background(
                    brush = Brush.linearGradient(
                        colors = listOf(
                            Color(0xFF667EEA),
                            Color(0xFF764BA2)
                        ),
                        start = Offset.Zero,
                        end = Offset.Infinite
                    )
                )
                .padding(20.dp)
        ) {
            if (latestPayslip != null) {
                Column(
                    modifier = Modifier.fillMaxSize(),
                    verticalArrangement = Arrangement.SpaceBetween
                ) {
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text(
                            text = "Azken nomina",
                            style = MaterialTheme.typography.titleMedium,
                            color = Color.White.copy(alpha = 0.9f)
                        )
                        
                        Text(
                            text = "${latestPayslip.monthName} ${latestPayslip.year}",
                            style = MaterialTheme.typography.bodyMedium,
                            color = Color.White.copy(alpha = 0.9f)
                        )
                    }
                    
                    Column {
                        Text(
                            text = "Soldata garbia",
                            style = MaterialTheme.typography.bodyMedium,
                            color = Color.White.copy(alpha = 0.8f)
                        )
                        Text(
                            text = formatCurrency(latestPayslip.netSalary),
                            style = MaterialTheme.typography.displaySmall,
                            fontWeight = FontWeight.Bold,
                            color = Color.White
                        )
                    }
                }
            } else {
                // Empty state
                Column(
                    modifier = Modifier.fillMaxSize(),
                    verticalArrangement = Arrangement.Center,
                    horizontalAlignment = Alignment.CenterHorizontally
                ) {
                    Text(
                        text = "Ez dago nominarik",
                        style = MaterialTheme.typography.titleMedium,
                        color = Color.White.copy(alpha = 0.9f)
                    )
                }
            }
        }
    }
}

/**
 * Individual payslip card
 */
@Composable
fun PayslipCard(
    payslip: Payslip,
    onClick: () -> Unit
) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .clickable(onClick = onClick),
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
            Surface(
                shape = RoundedCornerShape(12.dp),
                color = MaterialTheme.colorScheme.primary.copy(alpha = 0.15f),
                modifier = Modifier.size(56.dp)
            ) {
                Box(contentAlignment = Alignment.Center) {
                    Icon(
                        imageVector = Icons.Default.Receipt,
                        contentDescription = null,
                        tint = MaterialTheme.colorScheme.primary,
                        modifier = Modifier.size(28.dp)
                    )
                }
            }
            
            Spacer(modifier = Modifier.width(16.dp))
            
            Column(modifier = Modifier.weight(1f)) {
                Text(
                    text = "${payslip.monthName} ${payslip.year}",
                    style = MaterialTheme.typography.titleMedium,
                    fontWeight = FontWeight.SemiBold,
                    color = MaterialTheme.colorScheme.onSurface
                )
                
                Spacer(modifier = Modifier.height(4.dp))
                
                Text(
                    text = formatCurrency(payslip.netSalary),
                    style = MaterialTheme.typography.bodyLarge,
                    fontWeight = FontWeight.Bold,
                    color = Color(0xFF28A745)
                )
            }
            
            Icon(
                imageVector = Icons.Default.ChevronRight,
                contentDescription = null,
                tint = MaterialTheme.colorScheme.onSurfaceVariant
            )
        }
    }
}

/**
 * Empty state when no payslips
 */
@Composable
fun EmptyPayslipsState() {
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
                imageVector = Icons.Default.ReceiptLong,
                contentDescription = null,
                modifier = Modifier.size(64.dp),
                tint = MaterialTheme.colorScheme.onSurfaceVariant
            )
            
            Spacer(modifier = Modifier.height(16.dp))
            
            Text(
                text = "Ez dago nominarik",
                style = MaterialTheme.typography.titleMedium,
                color = MaterialTheme.colorScheme.onSurfaceVariant
            )
            
            Spacer(modifier = Modifier.height(8.dp))
            
            Text(
                text = "Oraindik ez dago nominarik eskuragarri",
                style = MaterialTheme.typography.bodyMedium,
                color = MaterialTheme.colorScheme.onSurfaceVariant.copy(alpha = 0.7f)
            )
        }
    }
}

private fun formatCurrency(amount: Float): String {
    val format = NumberFormat.getCurrencyInstance(Locale("eu", "ES"))
    return format.format(amount)
}

/**
 * Payslip Detail Screen
 */
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun PayslipDetailScreen(
    payslipId: Int,
    onNavigateBack: () -> Unit,
    viewModel: PayslipsViewModel = hiltViewModel()
) {
    val uiState by viewModel.uiState.collectAsState()
    
    // Load payslip detail when screen opens
    LaunchedEffect(payslipId) {
        viewModel.loadPayslipDetail(payslipId)
    }
    
    val payslip = uiState.selectedPayslip
    
    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Nomina xehetasunak") },
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
                    if (payslip != null) {
                        IconButton(onClick = { viewModel.downloadPayslip(payslip.id) }) {
                            Icon(
                                Icons.Default.Download,
                                contentDescription = "Deskargatu",
                                tint = Color.White
                            )
                        }
                    }
                }
            )
        }
    ) { paddingValues ->
        if (uiState.isLoading) {
            Box(
                modifier = Modifier
                    .fillMaxSize()
                    .padding(paddingValues),
                contentAlignment = Alignment.Center
            ) {
                CircularProgressIndicator()
            }
        } else if (payslip == null) {
            Box(
                modifier = Modifier
                    .fillMaxSize()
                    .padding(paddingValues),
                contentAlignment = Alignment.Center
            ) {
                Text("Ez da nominarik aurkitu")
            }
        } else {
            LazyColumn(
                modifier = Modifier
                    .fillMaxSize()
                    .background(MaterialTheme.colorScheme.background)
                    .padding(paddingValues)
                    .padding(20.dp),
                verticalArrangement = Arrangement.spacedBy(16.dp)
            ) {
                // Header card
                item {
                    Card(
                        modifier = Modifier.fillMaxWidth(),
                        shape = RoundedCornerShape(20.dp),
                        colors = CardDefaults.cardColors(
                            containerColor = Color.Transparent
                        )
                    ) {
                        Box(
                            modifier = Modifier
                                .fillMaxWidth()
                                .background(
                                    brush = Brush.linearGradient(
                                        colors = listOf(
                                            Color(0xFF667EEA),
                                            Color(0xFF764BA2)
                                        )
                                    )
                                )
                                .padding(24.dp)
                        ) {
                            Column {
                                Text(
                                    text = "${payslip.monthName} ${payslip.year}",
                                    style = MaterialTheme.typography.headlineSmall,
                                    fontWeight = FontWeight.Bold,
                                    color = Color.White
                                )
                                Spacer(modifier = Modifier.height(16.dp))
                                Text(
                                    text = "Soldata garbia",
                                    style = MaterialTheme.typography.bodyMedium,
                                    color = Color.White.copy(alpha = 0.8f)
                                )
                                Text(
                                    text = formatCurrency(payslip.netSalary),
                                    style = MaterialTheme.typography.displayMedium,
                                    fontWeight = FontWeight.Bold,
                                    color = Color.White
                                )
                            }
                        }
                    }
                }
                
                // Details section
                item {
                    PayslipDetailItem(
                        label = "Soldata gordina",
                        value = formatCurrency(payslip.grossSalary),
                        icon = Icons.Default.AttachMoney
                    )
                }
                
                item {
                    PayslipDetailItem(
                        label = "Bonuak",
                        value = formatCurrency(payslip.bonuses ?: 0f),
                        icon = Icons.Default.Star,
                        valueColor = Color(0xFF28A745)
                    )
                }
                
                item {
                    PayslipDetailItem(
                        label = "Gizarte Segurantza",
                        value = "- ${formatCurrency(payslip.socialSecurity)}",
                        icon = Icons.Default.HealthAndSafety,
                        valueColor = MaterialTheme.colorScheme.error
                    )
                }
                
                item {
                    PayslipDetailItem(
                        label = "IRPF",
                        value = "- ${formatCurrency(payslip.irpf)}",
                        icon = Icons.Default.AccountBalance,
                        valueColor = MaterialTheme.colorScheme.error
                    )
                }
                
                item {
                    PayslipDetailItem(
                        label = "Beste kenkariak",
                        value = "- ${formatCurrency(payslip.deductions)}",
                        icon = Icons.Default.Remove,
                        valueColor = MaterialTheme.colorScheme.error
                    )
                }
            }
        }
    }
}

@Composable
fun PayslipDetailItem(
    label: String,
    value: String,
    icon: ImageVector,
    valueColor: Color = MaterialTheme.colorScheme.onSurface
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
            
            Text(
                text = label,
                style = MaterialTheme.typography.bodyLarge,
                color = MaterialTheme.colorScheme.onSurfaceVariant,
                modifier = Modifier.weight(1f)
            )
            
            Text(
                text = value,
                style = MaterialTheme.typography.titleMedium,
                fontWeight = FontWeight.Bold,
                color = valueColor
            )
        }
    }
}
