package com.zabalagailetak.hrapp.presentation.update

import androidx.compose.foundation.layout.*
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.SystemUpdate
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import com.zabalagailetak.hrapp.data.api.VersionInfo

/**
 * Dialog shown when an update is available
 */
@Composable
fun UpdateAvailableDialog(
    versionInfo: VersionInfo,
    currentVersionCode: Int,
    onDismiss: () -> Unit,
    onDownload: () -> Unit
) {
    AlertDialog(
        onDismissRequest = onDismiss,
        icon = {
            Icon(
                imageVector = Icons.Default.SystemUpdate,
                contentDescription = null,
                tint = MaterialTheme.colorScheme.primary
            )
        },
        title = {
            Text(text = "Eguneratzea eskuragarri")
        },
        text = {
            Column(
                modifier = Modifier.fillMaxWidth(),
                horizontalAlignment = Alignment.CenterHorizontally
            ) {
                Text(
                    text = "Bertsio berria eskuragarri dago!",
                    style = MaterialTheme.typography.bodyLarge,
                    textAlign = TextAlign.Center
                )
                
                Spacer(modifier = Modifier.height(16.dp))
                
                // Version info card
                Card(
                    modifier = Modifier.fillMaxWidth(),
                    colors = CardDefaults.cardColors(
                        containerColor = MaterialTheme.colorScheme.primaryContainer
                    )
                ) {
                    Column(
                        modifier = Modifier.padding(16.dp),
                        horizontalAlignment = Alignment.CenterHorizontally
                    ) {
                        Text(
                            text = "Bertsio berria:",
                            style = MaterialTheme.typography.labelMedium
                        )
                        Text(
                            text = "${versionInfo.versionName} (#${versionInfo.versionCode})",
                            style = MaterialTheme.typography.titleMedium,
                            color = MaterialTheme.colorScheme.onPrimaryContainer
                        )
                        
                        Spacer(modifier = Modifier.height(8.dp))
                        
                        Text(
                            text = "Uneko bertsioa: (#$currentVersionCode)",
                            style = MaterialTheme.typography.bodySmall
                        )
                    }
                }
                
                Spacer(modifier = Modifier.height(16.dp))
                
                // Release notes if available
                if (!versionInfo.releaseNotes.isNullOrBlank()) {
                    Text(
                        text = "Berrikuntzak:",
                        style = MaterialTheme.typography.labelMedium,
                        modifier = Modifier.align(Alignment.Start)
                    )
                    Text(
                        text = versionInfo.releaseNotes,
                        style = MaterialTheme.typography.bodySmall,
                        modifier = Modifier.align(Alignment.Start)
                    )
                    Spacer(modifier = Modifier.height(16.dp))
                }
                
                // Warning for forced updates
                if (versionInfo.forceUpdate) {
                    Card(
                        modifier = Modifier.fillMaxWidth(),
                        colors = CardDefaults.cardColors(
                            containerColor = MaterialTheme.colorScheme.errorContainer
                        )
                    ) {
                        Text(
                            text = "Eguneratze hau beharrezkoa da aplikazioa erabiltzeko.",
                            style = MaterialTheme.typography.bodySmall,
                            color = MaterialTheme.colorScheme.onErrorContainer,
                            modifier = Modifier.padding(12.dp),
                            textAlign = TextAlign.Center
                        )
                    }
                }
            }
        },
        confirmButton = {
            Button(
                onClick = onDownload,
                modifier = Modifier.fillMaxWidth()
            ) {
                Text("Deskargatu eta instalatu")
            }
        },
        dismissButton = {
            if (!versionInfo.forceUpdate) {
                TextButton(onClick = onDismiss) {
                    Text("Geroago")
                }
            }
        }
    )
}

/**
 * Shows update check result as a snackbar
 */
@Composable
fun UpdateSnackbar(
    result: com.zabalagailetak.hrapp.data.update.UpdateResult,
    onDismiss: () -> Unit
) {
    val message = when (result) {
        is com.zabalagailetak.hrapp.data.update.UpdateResult.UpdateAvailable -> 
            "Bertsio berria eskuragarri: ${result.versionInfo.versionName}"
        is com.zabalagailetak.hrapp.data.update.UpdateResult.UpToDate -> 
            "Aplikazioa eguneratuta dago"
        is com.zabalagailetak.hrapp.data.update.UpdateResult.Error -> 
            result.message
    }
    
    val actionLabel = if (result is com.zabalagailetak.hrapp.data.update.UpdateResult.UpdateAvailable) "Ikusi" else null
    
    Snackbar(
        action = {
            if (actionLabel != null) {
                TextButton(onClick = onDismiss) {
                    Text(actionLabel)
                }
            }
        },
        modifier = Modifier.padding(16.dp)
    ) {
        Text(message)
    }
}

/**
 * Version info chip for display in settings/profile
 */
@Composable
fun VersionInfoChip(
    version: String,
    onClick: () -> Unit = {}
) {
    AssistChip(
        onClick = onClick,
        label = { Text("Bertsioa: $version") },
        modifier = Modifier.padding(horizontal = 4.dp)
    )
}
