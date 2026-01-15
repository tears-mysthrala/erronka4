package com.zabalagailetak.hrapp.presentation.vacation

import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.ArrowBack
import androidx.compose.material.icons.filled.CalendarToday
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import java.time.LocalDate
import java.time.format.DateTimeFormatter
import java.time.temporal.ChronoUnit

/**
 * New Vacation Request Screen
 */
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun NewVacationRequestScreen(
    viewModel: VacationViewModel,
    onNavigateBack: () -> Unit
) {
    var startDate by remember { mutableStateOf(LocalDate.now()) }
    var endDate by remember { mutableStateOf(LocalDate.now()) }
    var notes by remember { mutableStateOf("") }
    var showStartDatePicker by remember { mutableStateOf(false) }
    var showEndDatePicker by remember { mutableStateOf(false) }
    
    val calculatedDays = remember(startDate, endDate) {
        calculateBusinessDays(startDate, endDate)
    }
    
    val uiState by viewModel.uiState.collectAsState()

    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Opor Eskaera Berria") },
                navigationIcon = {
                    IconButton(onClick = onNavigateBack) {
                        Icon(Icons.Default.ArrowBack, "Atzera")
                    }
                },
                colors = TopAppBarDefaults.topAppBarColors(
                    containerColor = MaterialTheme.colorScheme.primary,
                    titleContentColor = Color.White,
                    navigationIconContentColor = Color.White
                )
            )
        }
    ) { paddingValues ->
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(paddingValues)
                .verticalScroll(rememberScrollState())
                .padding(16.dp),
            verticalArrangement = Arrangement.spacedBy(16.dp)
        ) {
            // Info Card
            Card(
                colors = CardDefaults.cardColors(
                    containerColor = Color(0xFFE3F2FD)
                )
            ) {
                Text(
                    text = "Opor eskaerak arduradunak eta gero GIBHek onartu behar dituzte.",
                    modifier = Modifier.padding(16.dp),
                    color = Color(0xFF1565C0)
                )
            }

            // Start Date
            OutlinedCard(
                modifier = Modifier.fillMaxWidth(),
                onClick = { showStartDatePicker = true }
            ) {
                Column(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(16.dp)
                ) {
                    Text(
                        text = "Hasiera Data *",
                        style = MaterialTheme.typography.labelLarge,
                        color = MaterialTheme.colorScheme.primary
                    )
                    Spacer(modifier = Modifier.height(8.dp))
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text(
                            text = formatDateLong(startDate),
                            style = MaterialTheme.typography.bodyLarge
                        )
                        Icon(
                            Icons.Default.CalendarToday,
                            contentDescription = null,
                            tint = MaterialTheme.colorScheme.primary
                        )
                    }
                }
            }

            // End Date
            OutlinedCard(
                modifier = Modifier.fillMaxWidth(),
                onClick = { showEndDatePicker = true }
            ) {
                Column(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(16.dp)
                ) {
                    Text(
                        text = "Amaiera Data *",
                        style = MaterialTheme.typography.labelLarge,
                        color = MaterialTheme.colorScheme.primary
                    )
                    Spacer(modifier = Modifier.height(8.dp))
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text(
                            text = formatDateLong(endDate),
                            style = MaterialTheme.typography.bodyLarge
                        )
                        Icon(
                            Icons.Default.CalendarToday,
                            contentDescription = null,
                            tint = MaterialTheme.colorScheme.primary
                        )
                    }
                }
            }

            // Calculated Days
            if (calculatedDays > 0) {
                Card(
                    colors = CardDefaults.cardColors(
                        containerColor = MaterialTheme.colorScheme.surfaceVariant
                    )
                ) {
                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(16.dp),
                        horizontalAlignment = androidx.compose.ui.Alignment.CenterHorizontally
                    ) {
                        Text(
                            text = "Gutxi gorabeherako egun lanerako",
                            style = MaterialTheme.typography.bodySmall,
                            color = MaterialTheme.colorScheme.onSurfaceVariant
                        )
                        Spacer(modifier = Modifier.height(8.dp))
                        Text(
                            text = calculatedDays.toString(),
                            style = MaterialTheme.typography.displayMedium,
                            fontWeight = FontWeight.Bold,
                            color = MaterialTheme.colorScheme.primary
                        )
                        Spacer(modifier = Modifier.height(4.dp))
                        Text(
                            text = "(Jaiegunak kontuan hartu gabe)",
                            style = MaterialTheme.typography.bodySmall,
                            color = MaterialTheme.colorScheme.onSurfaceVariant
                        )
                    }
                }
            }

            // Notes
            OutlinedTextField(
                value = notes,
                onValueChange = { notes = it },
                label = { Text("Oharrak (aukerakoa)") },
                modifier = Modifier
                    .fillMaxWidth()
                    .height(120.dp),
                maxLines = 5,
                placeholder = { Text("Edozein informazio gehigarri...") }
            )

            Spacer(modifier = Modifier.weight(1f))

            // Buttons
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.spacedBy(12.dp)
            ) {
                OutlinedButton(
                    onClick = onNavigateBack,
                    modifier = Modifier.weight(1f),
                    enabled = !uiState.isLoading
                ) {
                    Text("Ezeztatu")
                }
                
                Button(
                    onClick = {
                        viewModel.createRequest(
                            startDate = startDate.toString(),
                            endDate = endDate.toString(),
                            notes = notes.ifBlank { null }
                        )
                    },
                    modifier = Modifier.weight(1f),
                    enabled = !uiState.isLoading && startDate >= LocalDate.now() && endDate >= startDate
                ) {
                    if (uiState.isLoading) {
                        CircularProgressIndicator(
                            modifier = Modifier.size(20.dp),
                            color = Color.White
                        )
                    } else {
                        Text("Eskaera Bidali")
                    }
                }
            }

            // Success/Error handling
            LaunchedEffect(uiState.createSuccess) {
                if (uiState.createSuccess) {
                    onNavigateBack()
                }
            }

            uiState.error?.let { error ->
                Card(
                    colors = CardDefaults.cardColors(
                        containerColor = MaterialTheme.colorScheme.errorContainer
                    )
                ) {
                    Text(
                        text = error,
                        modifier = Modifier.padding(16.dp),
                        color = MaterialTheme.colorScheme.onErrorContainer
                    )
                }
            }
        }
    }

    // Date Pickers
    if (showStartDatePicker) {
        DatePickerDialog(
            onDismissRequest = { showStartDatePicker = false },
            confirmButton = {
                TextButton(onClick = { showStartDatePicker = false }) {
                    Text("OK")
                }
            }
        ) {
            // Note: DatePicker implementation requires Material3 DatePicker
            // This is a simplified version - actual implementation would use DatePicker composable
            Text("Date picker implementation here")
        }
    }

    if (showEndDatePicker) {
        DatePickerDialog(
            onDismissRequest = { showEndDatePicker = false },
            confirmButton = {
                TextButton(onClick = { showEndDatePicker = false }) {
                    Text("OK")
                }
            }
        ) {
            Text("Date picker implementation here")
        }
    }
}

private fun calculateBusinessDays(start: LocalDate, end: LocalDate): Int {
    if (end < start) return 0
    
    var days = 0
    var current = start
    
    while (!current.isAfter(end)) {
        val dayOfWeek = current.dayOfWeek.value
        if (dayOfWeek != 6 && dayOfWeek != 7) { // Not Saturday or Sunday
            days++
        }
        current = current.plusDays(1)
    }
    
    return days
}

private fun formatDateLong(date: LocalDate): String {
    return date.format(DateTimeFormatter.ofPattern("EEEE, d MMMM yyyy"))
}
