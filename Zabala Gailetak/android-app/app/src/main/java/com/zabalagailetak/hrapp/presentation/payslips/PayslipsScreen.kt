package com.zabalagailetak.hrapp.presentation.payslips

import androidx.compose.animation.*
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
import com.zabalagailetak.hrapp.domain.model.Payslip
import com.zabalagailetak.hrapp.presentation.ui.theme.*
import java.text.NumberFormat
import java.util.*

import androidx.compose.ui.tooling.preview.Preview
import com.zabalagailetak.hrapp.presentation.ui.theme.ZabalaGaileTakHRTheme

/**
 * Payslips Screen - Display employee payslips
 */
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun PayslipsScreen(
    onNavigateToDetail: (Int) -> Unit
) {
    // Mock data for demonstration
    val payslips = remember {
        listOf(
            Payslip(1, 101, 12, 2025, 3500f, 2800f, 500f, 200f, 300f, 200f, null, null, null),
            Payslip(2, 101, 11, 2025, 3500f, 2800f, 500f, null, 300f, 200f, null, null, null),
            Payslip(3, 101, 10, 2025, 3500f, 2800f, 500f, null, 300f, 200f, null, null, null)
        )
    }
    
    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Nominak") },
                colors = TopAppBarDefaults.topAppBarColors(
                    containerColor = PrimaryBlue,
                    titleContentColor = Color.White
                )
            )
        }
    ) { paddingValues ->
        LazyColumn(
            modifier = Modifier
                .fillMaxSize()
                .background(MaterialTheme.colorScheme.background)
                .padding(paddingValues)
                .padding(horizontal = 20.dp, vertical = 16.dp),
            verticalArrangement = Arrangement.spacedBy(12.dp)
        ) {
            // Summary card
            item {
                PayslipSummaryCard(latestPayslip = payslips.firstOrNull())
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
            items(payslips) { payslip ->
                PayslipCard(
                    payslip = payslip,
                    onClick = { onNavigateToDetail(payslip.id) }
                )
            }
        }
    }
}

@Preview(showBackground = true)
@Composable
fun PayslipsScreenPreview() {
    ZabalaGaileTakHRTheme {
        PayslipsScreen({})
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
                color = SecondaryTeal.copy(alpha = 0.15f),
                modifier = Modifier.size(56.dp)
            ) {
                Box(contentAlignment = Alignment.Center) {
                    Icon(
                        imageVector = Icons.Default.Receipt,
                        contentDescription = null,
                        tint = SecondaryTeal,
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
                    color = SuccessGreen
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
 * Payslip detail screen
 */
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun PayslipDetailScreen(
    payslipId: Int,
    onNavigateBack: () -> Unit
) {
    // Mock data
    val payslip = remember {
        Payslip(payslipId, 101, 12, 2025, 3500f, 2800f, 500f, 200f, 300f, 200f, null, null, null)
    }
    
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
                    containerColor = PrimaryBlue,
                    titleContentColor = Color.White,
                    navigationIconContentColor = Color.White
                ),
                actions = {
                    IconButton(onClick = { /* Download PDF */ }) {
                        Icon(
                            Icons.Default.Download,
                            contentDescription = "Deskargatu",
                            tint = Color.White
                        )
                    }
                }
            )
        }
    ) { paddingValues ->
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
                    valueColor = SuccessGreen
                )
            }
            
            item {
                PayslipDetailItem(
                    label = "Gizarte Segurantza",
                    value = "- ${formatCurrency(payslip.socialSecurity)}",
                    icon = Icons.Default.HealthAndSafety,
                    valueColor = ErrorRed
                )
            }
            
            item {
                PayslipDetailItem(
                    label = "IRPF",
                    value = "- ${formatCurrency(payslip.irpf)}",
                    icon = Icons.Default.AccountBalance,
                    valueColor = ErrorRed
                )
            }
            
            item {
                PayslipDetailItem(
                    label = "Beste kenkariak",
                    value = "- ${formatCurrency(payslip.deductions)}",
                    icon = Icons.Default.Remove,
                    valueColor = ErrorRed
                )
            }
        }
    }
}

@Preview(showBackground = true)
@Composable
fun PayslipDetailScreenPreview() {
    ZabalaGaileTakHRTheme {
        PayslipDetailScreen(1, {})
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
                tint = PrimaryBlue,
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

private fun formatCurrency(amount: Float): String {
    val format = NumberFormat.getCurrencyInstance(Locale("eu", "ES"))
    return format.format(amount)
}

@Preview(showBackground = true, name = "Default")
@Composable
fun PayslipsScreenPreview() {
    ZabalaGaileTakHRTheme {
        PayslipsScreen({})
    }
}

@Preview(showBackground = true, name = "Empty State")
@Composable
fun PayslipsScreenEmptyPreview() {
    ZabalaGaileTakHRTheme {
        Box(
            modifier = Modifier
                .background(MaterialTheme.colorScheme.background)
                .fillMaxSize(),
            contentAlignment = Alignment.Center
        ) {
            Text("Ez dago nominik")
        }
    }
}
