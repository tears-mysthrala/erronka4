package com.zabalagailetak.hrapp.presentation.auth

import androidx.compose.animation.*
import androidx.compose.animation.core.*
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.KeyboardActions
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.scale
import androidx.compose.ui.focus.FocusRequester
import androidx.compose.ui.focus.focusRequester
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.ImeAction
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.hilt.navigation.compose.hiltViewModel
import com.zabalagailetak.hrapp.presentation.ui.theme.*

/**
 * MFA Verification Screen
 * Handles TOTP (Time-based One-Time Password) verification
 */
@Composable
fun MfaVerificationScreen(
    viewModel: AuthViewModel = hiltViewModel(),
    onVerificationSuccess: () -> Unit,
    onCancel: () -> Unit
) {
    val uiState by viewModel.uiState.collectAsState()
    
    // Handle successful verification
    LaunchedEffect(uiState.loginSuccess) {
        if (uiState.loginSuccess) {
            onVerificationSuccess()
            viewModel.resetLoginState()
        }
    }
    
    MfaVerificationContent(
        uiState = uiState,
        onVerify = { code -> viewModel.verifyMfa(code) },
        onCancel = onCancel,
        onClearError = { viewModel.clearError() }
    )
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun MfaVerificationContent(
    uiState: AuthUiState,
    onVerify: (String) -> Unit,
    onCancel: () -> Unit,
    onClearError: () -> Unit
) {
    var code by remember { mutableStateOf("") }
    val focusRequester = remember { FocusRequester() }
    
    // Auto-focus the input field when screen loads
    LaunchedEffect(Unit) {
        focusRequester.requestFocus()
    }
    
    // Clear error when user starts typing
    LaunchedEffect(code) {
        if (uiState.error != null && code.isNotEmpty()) {
            onClearError()
        }
    }
    
    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(
                brush = Brush.linearGradient(
                    colors = listOf(GradientStart, GradientMiddle, GradientEnd)
                )
            )
    ) {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(24.dp),
            horizontalAlignment = Alignment.CenterHorizontally,
            verticalArrangement = Arrangement.Center
        ) {
            // Icon and title
            Column(
                horizontalAlignment = Alignment.CenterHorizontally,
                modifier = Modifier.padding(bottom = 32.dp)
            ) {
                Surface(
                    modifier = Modifier
                        .size(80.dp)
                        .scale(
                            animateFloatAsState(
                                targetValue = if (uiState.isLoading) 0.9f else 1f,
                                animationSpec = spring(
                                    dampingRatio = Spring.DampingRatioMediumBouncy,
                                    stiffness = Spring.StiffnessLow
                                ),
                                label = "icon_scale"
                            ).value
                        ),
                    shape = RoundedCornerShape(20.dp),
                    color = Color.White.copy(alpha = 0.2f),
                    shadowElevation = 8.dp
                ) {
                    Box(contentAlignment = Alignment.Center) {
                        Icon(
                            imageVector = Icons.Default.Security,
                            contentDescription = "Security",
                            modifier = Modifier.size(48.dp),
                            tint = Color.White
                        )
                    }
                }
                
                Spacer(modifier = Modifier.height(24.dp))
                
                Text(
                    text = "Segurtasun Egiaztapena",
                    style = MaterialTheme.typography.headlineSmall,
                    fontWeight = FontWeight.Bold,
                    color = Color.White,
                    textAlign = TextAlign.Center
                )
                
                Text(
                    text = "Verificación de seguridad",
                    style = MaterialTheme.typography.titleMedium,
                    color = Color.White.copy(alpha = 0.9f),
                    textAlign = TextAlign.Center
                )
                
                Spacer(modifier = Modifier.height(16.dp))
                
                Text(
                    text = "Sartu zure autentikazio aplikaziotik lortutako 6 digituko kodea",
                    style = MaterialTheme.typography.bodyLarge,
                    color = Color.White.copy(alpha = 0.8f),
                    textAlign = TextAlign.Center
                )
                
                Text(
                    text = "Ingrese el código de 6 dígitos de su aplicación de autenticación",
                    style = MaterialTheme.typography.bodyMedium,
                    color = Color.White.copy(alpha = 0.7f),
                    textAlign = TextAlign.Center
                )
            }
            
            // MFA Code input card
            Card(
                modifier = Modifier
                    .fillMaxWidth()
                    .animateContentSize(),
                shape = RoundedCornerShape(24.dp),
                colors = CardDefaults.cardColors(
                    containerColor = Color.White.copy(alpha = 0.15f)
                ),
                elevation = CardDefaults.cardElevation(defaultElevation = 0.dp)
            ) {
                Column(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(28.dp),
                    horizontalAlignment = Alignment.CenterHorizontally
                ) {
                    // Code input field
                    OutlinedTextField(
                        value = code,
                        onValueChange = { newValue ->
                            // Only allow 6 digits
                            if (newValue.length <= 6 && newValue.all { it.isDigit() }) {
                                code = newValue
                            }
                        },
                        label = { Text("MFA Kodea / Código MFA", color = Color.White.copy(alpha = 0.9f)) },
                        leadingIcon = {
                            Icon(
                                Icons.Default.VpnKey,
                                contentDescription = null,
                                tint = Color.White
                            )
                        },
                        modifier = Modifier
                            .fillMaxWidth()
                            .focusRequester(focusRequester),
                        colors = OutlinedTextFieldDefaults.colors(
                            focusedTextColor = Color.White,
                            unfocusedTextColor = Color.White,
                            focusedBorderColor = Color.White,
                            unfocusedBorderColor = Color.White.copy(alpha = 0.5f),
                            cursorColor = Color.White
                        ),
                        shape = RoundedCornerShape(16.dp),
                        singleLine = true,
                        keyboardOptions = KeyboardOptions(
                            keyboardType = KeyboardType.Number,
                            imeAction = ImeAction.Done
                        ),
                        keyboardActions = KeyboardActions(
                            onDone = {
                                if (code.length == 6) {
                                    onVerify(code)
                                }
                            }
                        ),
                        textStyle = MaterialTheme.typography.headlineMedium.copy(
                            textAlign = TextAlign.Center,
                            fontWeight = FontWeight.Bold,
                            letterSpacing = 8.dp
                        )
                    )
                    
                    // Digit indicators
                    Row(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(top = 16.dp),
                        horizontalArrangement = Arrangement.SpaceEvenly
                    ) {
                        repeat(6) { index ->
                            Box(
                                modifier = Modifier
                                    .size(12.dp)
                                    .background(
                                        color = if (index < code.length) 
                                            Color.White 
                                        else 
                                            Color.White.copy(alpha = 0.3f),
                                        shape = RoundedCornerShape(6.dp)
                                    )
                            )
                        }
                    }
                    
                    Spacer(modifier = Modifier.height(28.dp))
                    
                    // Verify button
                    Button(
                        onClick = { onVerify(code) },
                        modifier = Modifier
                            .fillMaxWidth()
                            .height(56.dp),
                        shape = RoundedCornerShape(16.dp),
                        colors = ButtonDefaults.buttonColors(
                            containerColor = Color.White,
                            contentColor = PrimaryBlue
                        ),
                        enabled = !uiState.isLoading && code.length == 6
                    ) {
                        if (uiState.isLoading) {
                            CircularProgressIndicator(
                                modifier = Modifier.size(24.dp),
                                color = PrimaryBlue
                            )
                        } else {
                            Text(
                                text = "Berretsi / Confirmar",
                                style = MaterialTheme.typography.titleMedium,
                                fontWeight = FontWeight.Bold
                            )
                        }
                    }
                    
                    Spacer(modifier = Modifier.height(12.dp))
                    
                    // Cancel button
                    OutlinedButton(
                        onClick = onCancel,
                        modifier = Modifier
                            .fillMaxWidth()
                            .height(48.dp),
                        shape = RoundedCornerShape(16.dp),
                        colors = ButtonDefaults.outlinedButtonColors(
                            contentColor = Color.White
                        ),
                        enabled = !uiState.isLoading
                    ) {
                        Text(
                            text = "Utzi / Cancelar",
                            style = MaterialTheme.typography.titleMedium
                        )
                    }
                    
                    // Error message
                    AnimatedVisibility(
                        visible = uiState.error != null,
                        enter = fadeIn() + expandVertically(),
                        exit = fadeOut() + shrinkVertically()
                    ) {
                        Card(
                            modifier = Modifier
                                .fillMaxWidth()
                                .padding(top = 16.dp),
                            colors = CardDefaults.cardColors(
                                containerColor = ErrorRed.copy(alpha = 0.9f)
                            ),
                            shape = RoundedCornerShape(12.dp)
                        ) {
                            Row(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .padding(12.dp),
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Icon(
                                    Icons.Default.Error,
                                    contentDescription = null,
                                    tint = Color.White,
                                    modifier = Modifier.size(20.dp)
                                )
                                Spacer(modifier = Modifier.width(8.dp))
                                Text(
                                    text = uiState.error ?: "",
                                    color = Color.White,
                                    style = MaterialTheme.typography.bodyMedium
                                )
                            }
                        }
                    }
                }
            }
            
            Spacer(modifier = Modifier.height(24.dp))
            
            // Help text
            TextButton(onClick = { /* TODO: MFA Help */ }) {
                Text(
                    text = "Laguntza behar duzu? / ¿Necesitas ayuda?",
                    color = Color.White.copy(alpha = 0.8f),
                    style = MaterialTheme.typography.bodyMedium
                )
            }
        }
    }
}
