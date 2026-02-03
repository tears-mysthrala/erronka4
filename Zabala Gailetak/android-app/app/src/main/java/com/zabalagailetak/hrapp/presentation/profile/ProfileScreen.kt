package com.zabalagailetak.hrapp.presentation.profile

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import com.zabalagailetak.hrapp.presentation.ui.theme.*

import androidx.compose.ui.tooling.preview.Preview
import com.zabalagailetak.hrapp.presentation.ui.theme.ZabalaGaileTakHRTheme

/**
 * Profile Screen - User profile and settings
 */
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun ProfileScreen(
    onLogout: () -> Unit
) {
    var showLogoutDialog by remember { mutableStateOf(false) }
    
    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Profila") },
                colors = TopAppBarDefaults.topAppBarColors(
                    containerColor = Color.Transparent
                ),
                actions = {
                    IconButton(onClick = { /* Edit profile */ }) {
                        Icon(
                            Icons.Default.Edit,
                            contentDescription = "Editatu",
                            tint = MaterialTheme.colorScheme.onBackground
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
                .padding(paddingValues),
            contentPadding = PaddingValues(bottom = 24.dp)
        ) {
            // Profile header with gradient
            item {
                Card(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(20.dp),
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
                                    ),
                                    start = Offset.Zero,
                                    end = Offset.Infinite
                                )
                            )
                            .padding(24.dp)
                    ) {
                        Column(
                            horizontalAlignment = Alignment.CenterHorizontally,
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            // Avatar
                            Surface(
                                modifier = Modifier
                                    .size(100.dp)
                                    .clip(CircleShape),
                                color = Color.White.copy(alpha = 0.2f)
                            ) {
                                Box(contentAlignment = Alignment.Center) {
                                    Icon(
                                        imageVector = Icons.Default.Person,
                                        contentDescription = null,
                                        modifier = Modifier.size(56.dp),
                                        tint = Color.White
                                    )
                                }
                            }
                            
                            Spacer(modifier = Modifier.height(16.dp))
                            
                            Text(
                                text = "Jon Doe",
                                style = MaterialTheme.typography.headlineSmall,
                                fontWeight = FontWeight.Bold,
                                color = Color.White
                            )
                            
                            Text(
                                text = "jon.doe@zabalagailetak.com",
                                style = MaterialTheme.typography.bodyMedium,
                                color = Color.White.copy(alpha = 0.9f)
                            )
                            
                            Spacer(modifier = Modifier.height(16.dp))
                            
                            // Job info
                            Row(
                                horizontalArrangement = Arrangement.spacedBy(16.dp)
                            ) {
                                ProfileBadge(
                                    icon = Icons.Default.Work,
                                    text = "Garatzailea"
                                )
                                ProfileBadge(
                                    icon = Icons.Default.Business,
                                    text = "IT Saila"
                                )
                            }
                        }
                    }
                }
            }
            
            // Profile info section
            item {
                Column(
                    modifier = Modifier.padding(horizontal = 20.dp, vertical = 8.dp)
                ) {
                    Text(
                        text = "Informazio pertsonala",
                        style = MaterialTheme.typography.titleMedium,
                        fontWeight = FontWeight.SemiBold,
                        color = MaterialTheme.colorScheme.onBackground,
                        modifier = Modifier.padding(vertical = 8.dp)
                    )
                }
            }
            
            item {
                ProfileInfoCard(
                    icon = Icons.Default.Phone,
                    label = "Telefonoa",
                    value = "+34 123 456 789"
                )
            }
            
            item {
                Spacer(modifier = Modifier.height(8.dp))
            }
            
            item {
                ProfileInfoCard(
                    icon = Icons.Default.LocationOn,
                    label = "Helbidea",
                    value = "Bilbo, Bizkaia"
                )
            }
            
            item {
                Spacer(modifier = Modifier.height(8.dp))
            }
            
            item {
                ProfileInfoCard(
                    icon = Icons.Default.CalendarToday,
                    label = "Kontratazio data",
                    value = "2023-01-15"
                )
            }
            
            // Settings section
            item {
                Column(
                    modifier = Modifier.padding(horizontal = 20.dp, vertical = 16.dp)
                ) {
                    Text(
                        text = "Ezarpenak",
                        style = MaterialTheme.typography.titleMedium,
                        fontWeight = FontWeight.SemiBold,
                        color = MaterialTheme.colorScheme.onBackground,
                        modifier = Modifier.padding(vertical = 8.dp)
                    )
                }
            }
            
            item {
                SettingsOptionCard(
                    icon = Icons.Default.Lock,
                    title = "Segurtasuna",
                    subtitle = "Aldatu pasahitza eta MFA",
                    onClick = { /* Navigate to security settings */ }
                )
            }
            
            item {
                Spacer(modifier = Modifier.height(8.dp))
            }
            
            item {
                SettingsOptionCard(
                    icon = Icons.Default.Notifications,
                    title = "Jakinarazpenak",
                    subtitle = "Kudeatu jakinarazpen hobespenak",
                    onClick = { /* Navigate to notification settings */ }
                )
            }
            
            item {
                Spacer(modifier = Modifier.height(8.dp))
            }
            
            item {
                SettingsOptionCard(
                    icon = Icons.Default.Language,
                    title = "Hizkuntza",
                    subtitle = "Euskara",
                    onClick = { /* Change language */ }
                )
            }
            
            // Logout section
            item {
                Spacer(modifier = Modifier.height(16.dp))
            }
            
            item {
                Card(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(horizontal = 20.dp),
                    shape = RoundedCornerShape(16.dp),
                    colors = CardDefaults.cardColors(
                        containerColor = ErrorRed.copy(alpha = 0.1f)
                    )
                ) {
                    ListItem(
                        headlineContent = {
                            Text(
                                text = "Saioa itxi",
                                fontWeight = FontWeight.SemiBold,
                                color = ErrorRed
                            )
                        },
                        leadingContent = {
                            Icon(
                                imageVector = Icons.Default.Logout,
                                contentDescription = null,
                                tint = ErrorRed
                            )
                        },
                        modifier = Modifier.clickable {
                            showLogoutDialog = true
                        }
                    )
                }
            }
        }
    }
    
    // Logout confirmation dialog
    if (showLogoutDialog) {
        AlertDialog(
            onDismissRequest = { showLogoutDialog = false },
            icon = {
                Icon(
                    Icons.Default.Logout,
                    contentDescription = null,
                    tint = ErrorRed
                )
            },
            title = {
                Text("Saioa itxi")
            },
            text = {
                Text("Ziur zaude saioa itxi nahi duzula?")
            },
            confirmButton = {
                Button(
                    onClick = {
                        showLogoutDialog = false
                        onLogout()
                    },
                    colors = ButtonDefaults.buttonColors(
                        containerColor = ErrorRed
                    )
                ) {
                    Text("Itxi saioa")
                }
            },
            dismissButton = {
                TextButton(onClick = { showLogoutDialog = false }) {
                    Text("Ezeztatu")
                }
            }
        )
    }
}

@Composable
fun ProfileBadge(
    icon: ImageVector,
    text: String
) {
    Surface(
        shape = RoundedCornerShape(12.dp),
        color = Color.White.copy(alpha = 0.2f)
    ) {
        Row(
            modifier = Modifier.padding(horizontal = 12.dp, vertical = 8.dp),
            verticalAlignment = Alignment.CenterVertically,
            horizontalArrangement = Arrangement.spacedBy(6.dp)
        ) {
            Icon(
                imageVector = icon,
                contentDescription = null,
                tint = Color.White,
                modifier = Modifier.size(16.dp)
            )
            Text(
                text = text,
                style = MaterialTheme.typography.bodySmall,
                color = Color.White
            )
        }
    }
}

@Composable
fun ProfileInfoCard(
    icon: ImageVector,
    label: String,
    value: String
) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 20.dp),
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
            Icon(
                imageVector = icon,
                contentDescription = null,
                tint = PrimaryBlue,
                modifier = Modifier.size(24.dp)
            )
            
            Spacer(modifier = Modifier.width(16.dp))
            
            Column {
                Text(
                    text = label,
                    style = MaterialTheme.typography.bodySmall,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
                Text(
                    text = value,
                    style = MaterialTheme.typography.bodyLarge,
                    fontWeight = FontWeight.Medium,
                    color = MaterialTheme.colorScheme.onSurface
                )
            }
        }
    }
}

@Composable
fun SettingsOptionCard(
    icon: ImageVector,
    title: String,
    subtitle: String,
    onClick: () -> Unit
) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 20.dp)
            .clickable(onClick = onClick),
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(
            containerColor = MaterialTheme.colorScheme.surface
        ),
        elevation = CardDefaults.cardElevation(defaultElevation = 1.dp)
    ) {
        ListItem(
            headlineContent = {
                Text(
                    text = title,
                    fontWeight = FontWeight.Medium
                )
            },
            supportingContent = {
                Text(
                    text = subtitle,
                    style = MaterialTheme.typography.bodySmall
                )
            },
            leadingContent = {
                Icon(
                    imageVector = icon,
                    contentDescription = null,
                    tint = PrimaryBlue
                )
            },
            trailingContent = {
                Icon(
                    imageVector = Icons.Default.ChevronRight,
                    contentDescription = null,
                    tint = MaterialTheme.colorScheme.onSurfaceVariant
                )
            }
        )
    }
}

@Preview(showBackground = true)
@Composable
fun ProfileScreenPreview() {
    ZabalaGaileTakHRTheme {
        ProfileScreen(onLogout = {})
    }
}
