package com.zabalagailetak.hrapp.presentation

import android.content.BroadcastReceiver
import android.content.Context
import android.content.Intent
import android.content.IntentFilter
import android.os.Bundle
import android.widget.Toast
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Surface
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.lifecycle.lifecycleScope
import com.zabalagailetak.hrapp.data.auth.SessionManager
import com.zabalagailetak.hrapp.data.auth.TokenStore
import com.zabalagailetak.hrapp.presentation.navigation.AppNavigation
import com.zabalagailetak.hrapp.presentation.navigation.Screen
import com.zabalagailetak.hrapp.presentation.ui.theme.ZabalaGaileTakHRTheme
import dagger.hilt.android.AndroidEntryPoint
import kotlinx.coroutines.launch
import javax.inject.Inject

/**
 * Main Activity for Zabala Gailetak HR App
 * Entry point for the Android application
 */
@AndroidEntryPoint
class MainActivity : ComponentActivity() {

    @Inject
    lateinit var sessionManager: SessionManager

    @Inject
    lateinit var tokenStore: TokenStore

    private var sessionExpiredReceiver: BroadcastReceiver? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()

        // Register broadcast receiver for session expired events
        registerSessionExpiredReceiver()

        // Collect session expired flow
        lifecycleScope.launch {
            sessionManager.sessionExpiredFlow.collect {
                showSessionExpiredMessage()
            }
        }

        setContent {
            ZabalaGaileTakHRTheme {
                Surface(
                    modifier = Modifier.fillMaxSize(),
                    color = MaterialTheme.colorScheme.background
                ) {
                    // Determine start destination based on authentication state
                    var startDestination by remember { mutableStateOf<String?>(null) }
                    
                    LaunchedEffect(Unit) {
                        // Check if user has a valid token
                        val token = tokenStore.getToken()
                        startDestination = if (token != null) {
                            Screen.Dashboard.route
                        } else {
                            Screen.Login.route
                        }
                    }
                    
                    // Only show navigation when start destination is determined
                    startDestination?.let { destination ->
                        AppNavigation(
                            startDestination = destination,
                            onSessionExpired = {
                                showSessionExpiredMessage()
                            }
                        )
                    }
                }
            }
        }
    }

    override fun onDestroy() {
        super.onDestroy()
        // Unregister broadcast receiver
        sessionExpiredReceiver?.let {
            unregisterReceiver(it)
        }
    }

    /**
     * Register broadcast receiver to handle session expired events from any part of the app
     */
    private fun registerSessionExpiredReceiver() {
        sessionExpiredReceiver = object : BroadcastReceiver() {
            override fun onReceive(context: Context?, intent: Intent?) {
                if (intent?.action == SessionManager.ACTION_SESSION_EXPIRED) {
                    showSessionExpiredMessage()
                }
            }
        }

        registerReceiver(
            sessionExpiredReceiver,
            IntentFilter(SessionManager.ACTION_SESSION_EXPIRED),
            Context.RECEIVER_NOT_EXPORTED
        )
    }

    /**
     * Show session expired toast message
     */
    private fun showSessionExpiredMessage() {
        Toast.makeText(
            this,
            "Saioa amaitu da. Logeatu berriro. / Sesión expirada. Inicie sesión de nuevo.",
            Toast.LENGTH_LONG
        ).show()
    }
}
