package com.zabalagailetak.hrapp.presentation.dashboard

import androidx.compose.animation.*
import androidx.compose.animation.core.*
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.LazyRow
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.draw.shadow
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.hilt.navigation.compose.hiltViewModel
import com.zabalagailetak.hrapp.presentation.ui.theme.*
import java.time.LocalDate
import java.time.format.DateTimeFormatter
import java.util.Locale
import android.annotation.TargetApi

/**
 * Dashboard Screen - Main hub with real data from API
 */
@OptIn(ExperimentalMaterial3Api::class)
@TargetApi(26)
@Composable
fun DashboardScreen(
    onNavigateToVacations: () -> Unit,
    onNavigateToPayslips: () -> Unit,
    onNavigateToDocuments: () -> Unit,
    onNavigateToProfile: () -> Unit = {},
    viewModel: DashboardViewModel = hiltViewModel()
) {
    val uiState by viewModel.uiState.collectAsState()
    var greeting by remember { mutableStateOf(getGreeting()) }
    
    LaunchedEffect(Unit) {
        greeting = getGreeting()
    }
    
    // Pull to refresh state
    val pullRefreshState = rememberPullToRefreshState()
    
    Scaffold(
        topBar = {
            TopAppBar(
                title = { },
                colors = TopAppBarDefaults.topAppBarColors(
                    containerColor = Color.Transparent
                ),
                actions = {
                    // Notifications icon (hidden if no notifications)
                    if (uiState.pendingRequests > 0) {
                        IconButton(onClick = { onNavigateToVacations() }) {
                            BadgedBox(
                                badge = {
                                    Badge(
                                        containerColor = ErrorRed,
                                        contentColor = Color.White
                                    ) {
                                        Text(uiState.pendingRequests.toString())
                                    }
                                }
                            ) {
                                Icon(
                                    Icons.Default.Notifications,
                                    contentDescription = "Jakinarazpenak",
                                    tint = TextPrimary
                                )
                            }
                        }
                    }
                    
                    // Profile icon
                    IconButton(onClick = onNavigateToProfile) {
                        Icon(
                            Icons.Default.Person,
                            contentDescription = "Profila",
                            tint = TextPrimary
                        )
                    }
                }
            )
        }
    ) { paddingValues ->
        Box(
            modifier = Modifier
                .fillMaxSize()
                .padding(paddingValues)
        ) {
            LazyColumn(
                modifier = Modifier
                    .fillMaxSize()
                    .background(MaterialTheme.colorScheme.background),
                contentPadding = PaddingValues(bottom = 24.dp)
            ) {
                // Loading indicator
                if (uiState.isLoading) {
                    item {
                        Box(
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(32.dp),
                            contentAlignment = Alignment.Center
                        ) {
                            CircularProgressIndicator()
                        }
                    }
                }
                
                // Error message
                uiState.error?.let { error ->
                    item {
                        Card(
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(20.dp),
                            colors = CardDefaults.cardColors(
                                containerColor = ErrorRed.copy(alpha = 0.1f)
                            )
                        ) {
                            Column(
                                modifier = Modifier.padding(16.dp),
                                horizontalAlignment = Alignment.CenterHorizontally
                            ) {
                                Icon(
                                    Icons.Default.Error,
                                    contentDescription = null,
                                    tint = ErrorRed
                                )
                                Spacer(modifier = Modifier.height(8.dp))
                                Text(
                                    text = error,
                                    color = ErrorRed,
                                    style = MaterialTheme.typography.bodyMedium
                                )
                                Spacer(modifier = Modifier.height(8.dp))
                                Button(onClick = { viewModel.refresh() }) {
                                    Text("Saiatu berriro")
                                }
                            }
                        }
                    }
                }
                
                // Header Section
                item {
                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(horizontal = 20.dp, vertical = 16.dp)
                    ) {
                        Text(
                            text = greeting,
                            style = MaterialTheme.typography.headlineMedium,
                            fontWeight = FontWeight.Bold,
                            color = MaterialTheme.colorScheme.onBackground
                        )
                        
                        Spacer(modifier = Modifier.height(4.dp))
                        
                        // Show user name if available
                        val userName = uiState.employee?.fullName
                        if (userName != null) {
                            Text(
                                text = "Kaixo, $userName",
                                style = MaterialTheme.typography.titleMedium,
                                color = MaterialTheme.colorScheme.primary
                            )
                            Spacer(modifier = Modifier.height(4.dp))
                        }
                        
                        Text(
                            text = LocalDate.now().format(
                                DateTimeFormatter.ofPattern("EEEE, MMMM d, yyyy", Locale("eu"))
                            ),
                            style = MaterialTheme.typography.bodyMedium,
                            color = MaterialTheme.colorScheme.onSurfaceVariant
                        )
                    }
                }
                
                // Quick Actions Section
                item {
                    Column(modifier = Modifier.padding(horizontal = 20.dp, vertical = 8.dp)) {
                        Text(
                            text = "Ekintza azkarrak",
                            style = MaterialTheme.typography.titleMedium,
                            fontWeight = FontWeight.SemiBold,
                            color = MaterialTheme.colorScheme.onBackground,
                            modifier = Modifier.padding(bottom = 12.dp)
                        )
                        
                        LazyRow(
                            horizontalArrangement = Arrangement.spacedBy(12.dp),
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            item {
                                QuickActionCard(
                                    icon = Icons.Default.BeachAccess,
                                    title = "Oporrak",
                                    subtitle = "Eskaera berria",
                                    gradient = Brush.linearGradient(
                                        colors = listOf(Color(0xFF667EEA), Color(0xFF764BA2))
                                    ),
                                    onClick = onNavigateToVacations
                                )
                            }
                            
                            item {
                                QuickActionCard(
                                    icon = Icons.Default.Receipt,
                                    title = "Nominak",
                                    subtitle = "Ikusi nominak",
                                    gradient = Brush.linearGradient(
                                        colors = listOf(Color(0xFFf093fb), Color(0xFFf5576c))
                                    ),
                                    onClick = onNavigateToPayslips
                                )
                            }
                            
                            item {
                                QuickActionCard(
                                    icon = Icons.Default.Folder,
                                    title = "Dokumentuak",
                                    subtitle = "Kontsultatu",
                                    gradient = Brush.linearGradient(
                                        colors = listOf(Color(0xFF4facfe), Color(0xFF00f2fe))
                                    ),
                                    onClick = onNavigateToDocuments
                                )
                            }
                        }
                    }
                }
                
                // Statistics Overview with real data
                item {
                    Column(modifier = Modifier.padding(horizontal = 20.dp, vertical = 16.dp)) {
                        Text(
                            text = "Zure laburpena",
                            style = MaterialTheme.typography.titleMedium,
                            fontWeight = FontWeight.SemiBold,
                            color = MaterialTheme.colorScheme.onBackground,
                            modifier = Modifier.padding(bottom = 12.dp)
                        )
                        
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.spacedBy(12.dp)
                        ) {
                            StatCard(
                                title = "Opor Egunak",
                                value = uiState.vacationBalance?.availableDays?.toString() ?: "-",
                                subtitle = "eskuragarri",
                                icon = Icons.Default.CalendarToday,
                                color = SuccessGreen,
                                modifier = Modifier.weight(1f),
                                onClick = onNavigateToVacations
                            )
                            
                            StatCard(
                                title = "Eskaerak",
                                value = uiState.pendingRequests.toString(),
                                subtitle = "zain",
                                icon = Icons.Default.PendingActions,
                                color = if (uiState.pendingRequests > 0) WarningAmber else SuccessGreen,
                                modifier = Modifier.weight(1f),
                                onClick = onNavigateToVacations
                            )
                        }
                        
                        Spacer(modifier = Modifier.height(12.dp))
                        
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.spacedBy(12.dp)
                        ) {
                            StatCard(
                                title = "Dokumentuak",
                                value = if (uiState.totalDocuments > 0) uiState.totalDocuments.toString() else "-",
                                subtitle = "guztira",
                                icon = Icons.Default.Description,
                                color = AccentPurple,
                                modifier = Modifier.weight(1f),
                                onClick = onNavigateToDocuments
                            )
                            
                            StatCard(
                                title = "Nominak",
                                value = "Ikusi",
                                subtitle = "azkenak",
                                icon = Icons.Default.Receipt,
                                color = InfoBlue,
                                modifier = Modifier.weight(1f),
                                onClick = onNavigateToPayslips
                            )
                        }
                    }
                }
                
                // Recent Activity Section with real data
                item {
                    Column(modifier = Modifier.padding(horizontal = 20.dp, vertical = 16.dp)) {
                        Text(
                            text = "Azken jarduera",
                            style = MaterialTheme.typography.titleMedium,
                            fontWeight = FontWeight.SemiBold,
                            color = MaterialTheme.colorScheme.onBackground,
                            modifier = Modifier.padding(bottom = 12.dp)
                        )
                        
                        if (uiState.recentActivities.isEmpty()) {
                            Card(
                                modifier = Modifier.fillMaxWidth(),
                                colors = CardDefaults.cardColors(
                                    containerColor = MaterialTheme.colorScheme.surface
                                )
                            ) {
                                Text(
                                    text = "Ez dago jarduerarik oraindik",
                                    style = MaterialTheme.typography.bodyMedium,
                                    color = MaterialTheme.colorScheme.onSurfaceVariant,
                                    modifier = Modifier.padding(16.dp)
                                )
                            }
                        } else {
                            uiState.recentActivities.forEach { activity ->
                                val (icon, color) = when (activity.type) {
                                    ActivityType.VACATION_APPROVED -> Icons.Default.CheckCircle to SuccessGreen
                                    ActivityType.VACATION_PENDING -> Icons.Default.HourglassEmpty to WarningAmber
                                    ActivityType.PAYSLIP_AVAILABLE -> Icons.Default.Receipt to InfoBlue
                                    ActivityType.DOCUMENT_UPLOADED -> Icons.Default.Description to AccentPurple
                                    ActivityType.GENERAL -> Icons.Default.Info to TextSecondary
                                }
                                
                                ActivityItem(
                                    icon = icon,
                                    title = activity.title,
                                    time = activity.time,
                                    iconColor = color
                                )
                                Spacer(modifier = Modifier.height(8.dp))
                            }
                        }
                    }
                }
            }
        }
    }
}

/**
 * Quick action card with gradient background
 */
@Composable
fun QuickActionCard(
    icon: ImageVector,
    title: String,
    subtitle: String,
    gradient: Brush,
    onClick: () -> Unit
) {
    var pressed by remember { mutableStateOf(false) }
    val scale by animateFloatAsState(
        targetValue = if (pressed) 0.95f else 1f,
        animationSpec = spring(
            dampingRatio = Spring.DampingRatioMediumBouncy,
            stiffness = Spring.StiffnessLow
        ),
        label = "card_scale"
    )
    
    Card(
        modifier = Modifier
            .width(160.dp)
            .height(120.dp)
            .shadow(
                elevation = 8.dp,
                shape = RoundedCornerShape(20.dp),
                spotColor = ShadowColor
            )
            .clickable(onClick = onClick),
        shape = RoundedCornerShape(20.dp),
        colors = CardDefaults.cardColors(
            containerColor = Color.Transparent
        )
    ) {
        Box(
            modifier = Modifier
                .fillMaxSize()
                .background(brush = gradient)
                .padding(16.dp)
        ) {
            Column(
                modifier = Modifier.fillMaxSize(),
                verticalArrangement = Arrangement.SpaceBetween
            ) {
                Icon(
                    imageVector = icon,
                    contentDescription = null,
                    tint = Color.White,
                    modifier = Modifier.size(32.dp)
                )
                
                Column {
                    Text(
                        text = title,
                        style = MaterialTheme.typography.titleMedium,
                        fontWeight = FontWeight.Bold,
                        color = Color.White
                    )
                    Text(
                        text = subtitle,
                        style = MaterialTheme.typography.bodySmall,
                        color = Color.White.copy(alpha = 0.9f)
                    )
                }
            }
        }
    }
}

/**
 * Stat card with clean design
 */
@Composable
fun StatCard(
    title: String,
    value: String,
    subtitle: String,
    icon: ImageVector,
    color: Color,
    modifier: Modifier = Modifier,
    onClick: (() -> Unit)? = null
) {
    Card(
        modifier = modifier
            .height(110.dp)
            .then(if (onClick != null) Modifier.clickable(onClick = onClick) else Modifier),
        shape = RoundedCornerShape(16.dp),
        colors = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surface
        ),
        elevation = CardDefaults.cardElevation(defaultElevation = 2.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(16.dp),
            verticalArrangement = Arrangement.SpaceBetween
        ) {
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.Top
            ) {
                Text(
                    text = title,
                    style = MaterialTheme.typography.bodyMedium,
                    color = MaterialTheme.colorScheme.onSurfaceVariant,
                    modifier = Modifier.weight(1f)
                )
                
                Surface(
                    shape = CircleShape,
                    color = color.copy(alpha = 0.15f),
                    modifier = Modifier.size(32.dp)
                ) {
                    Box(contentAlignment = Alignment.Center) {
                        Icon(
                            imageVector = icon,
                            contentDescription = null,
                            tint = color,
                            modifier = Modifier.size(18.dp)
                        )
                    }
                }
            }
            
            Column {
                Text(
                    text = value,
                    style = MaterialTheme.typography.headlineMedium,
                    fontWeight = FontWeight.Bold,
                    color = MaterialTheme.colorScheme.onSurface
                )
                Text(
                    text = subtitle,
                    style = MaterialTheme.typography.bodySmall,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
            }
        }
    }
}

/**
 * Activity item with icon
 */
@Composable
fun ActivityItem(
    icon: ImageVector,
    title: String,
    time: String,
    iconColor: Color
) {
    Card(
        modifier = Modifier.fillMaxWidth(),
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surface
        ),
        elevation = CardDefaults.cardElevation(defaultElevation = 1.dp)
    ) {
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            Surface(
                shape = CircleShape,
                color = iconColor.copy(alpha = 0.15f),
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
                    style = MaterialTheme.typography.bodyMedium,
                    fontWeight = FontWeight.Medium,
                    color = MaterialTheme.colorScheme.onSurface
                )
                if (time.isNotBlank()) {
                    Text(
                        text = time,
                        style = MaterialTheme.typography.bodySmall,
                        color = MaterialTheme.colorScheme.onSurfaceVariant
                    )
                }
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
 * Get time-based greeting in Basque
 */
private fun getGreeting(): String {
    val hour = java.util.Calendar.getInstance().get(java.util.Calendar.HOUR_OF_DAY)
    return when (hour) {
        in 0..5 -> "Gau on"
        in 6..12 -> "Egun on"
        in 13..20 -> "Arratsalde on"
        else -> "Gau on"
    }
}
