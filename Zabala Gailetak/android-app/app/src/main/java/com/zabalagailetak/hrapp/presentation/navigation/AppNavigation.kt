package com.zabalagailetak.hrapp.presentation.navigation

import androidx.compose.animation.*
import androidx.compose.animation.core.tween
import androidx.compose.foundation.layout.padding
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.unit.dp
import androidx.hilt.navigation.compose.hiltViewModel
import androidx.navigation.NavDestination.Companion.hierarchy
import androidx.navigation.NavGraph.Companion.findStartDestination
import androidx.navigation.NavHostController
import androidx.navigation.NavType
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.currentBackStackEntryAsState
import androidx.navigation.compose.rememberNavController
import androidx.navigation.navArgument
import com.zabalagailetak.hrapp.presentation.auth.LoginScreen
import com.zabalagailetak.hrapp.presentation.auth.MfaVerificationScreen
import com.zabalagailetak.hrapp.presentation.dashboard.DashboardScreen
import com.zabalagailetak.hrapp.presentation.documents.DocumentsScreen
import com.zabalagailetak.hrapp.presentation.payslips.PayslipsScreen
import com.zabalagailetak.hrapp.presentation.payslips.PayslipDetailScreen
import com.zabalagailetak.hrapp.presentation.profile.ProfileScreen
import com.zabalagailetak.hrapp.presentation.vacation.VacationDashboardScreen
import com.zabalagailetak.hrapp.presentation.vacation.NewVacationRequestScreen
import com.zabalagailetak.hrapp.presentation.vacation.VacationDetailScreen

import androidx.compose.ui.tooling.preview.Preview
import com.zabalagailetak.hrapp.presentation.ui.theme.ZabalaGaileTakHRTheme

/**
 * Main navigation host for the app
 */
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun AppNavigation(
    startDestination: String = Screen.Login.route,
    onSessionExpired: () -> Unit = {}
) {
    val navController = rememberNavController()
    var showBottomBar by remember { mutableStateOf(false) }
    
    // Handle session expired - navigate to login
    LaunchedEffect(onSessionExpired) {
        // This will be triggered when session expires
        // The actual navigation is handled by observing the navController
    }
    
    // Observe navigation to show/hide bottom bar
    val navBackStackEntry by navController.currentBackStackEntryAsState()
    
    LaunchedEffect(navBackStackEntry) {
        showBottomBar = when (navBackStackEntry?.destination?.route) {
            Screen.Login.route, Screen.MfaVerification.route -> false
            else -> true
        }
    }
    
    Scaffold(
        bottomBar = {
            if (showBottomBar) {
                BottomNavigationBar(navController = navController)
            }
        }
    ) { paddingValues ->
        NavHost(
            navController = navController,
            startDestination = startDestination,
            modifier = Modifier.padding(paddingValues)
        ) {
            // Auth screens
            composable(
                route = Screen.Login.route,
                enterTransition = { fadeIn(animationSpec = tween(300)) },
                exitTransition = { fadeOut(animationSpec = tween(300)) }
            ) {
                LoginScreen(
                    onLoginSuccess = {
                        navController.navigate(Screen.Dashboard.route) {
                            popUpTo(Screen.Login.route) { inclusive = true }
                        }
                    },
                    onMfaRequired = {
                        navController.navigate(Screen.MfaVerification.route)
                    }
                )
            }
            
            composable(route = Screen.MfaVerification.route) {
                MfaVerificationScreen(
                    onVerificationSuccess = {
                        navController.navigate(Screen.Dashboard.route) {
                            popUpTo(Screen.Login.route) { inclusive = true }
                        }
                    },
                    onCancel = {
                        navController.popBackStack()
                    }
                )
            }
            
            // Main app screens
            composable(
                route = Screen.Dashboard.route,
                enterTransition = { slideInHorizontally(initialOffsetX = { it }) + fadeIn() },
                exitTransition = { slideOutHorizontally(targetOffsetX = { -it }) + fadeOut() }
            ) {
                DashboardScreen(
                    onNavigateToVacations = { navController.navigate(Screen.Vacations.route) },
                    onNavigateToPayslips = { navController.navigate(Screen.Payslips.route) },
                    onNavigateToDocuments = { navController.navigate(Screen.Documents.route) }
                )
            }
            
            composable(route = Screen.Vacations.route) {
                VacationDashboardScreen(
                    viewModel = hiltViewModel(),
                    onNavigateToNewRequest = { navController.navigate(Screen.NewVacationRequest.route) },
                    onNavigateToRequestDetail = { requestId ->
                        navController.navigate(Screen.VacationDetail.createRoute(requestId))
                    }
                )
            }
            
            composable(route = Screen.NewVacationRequest.route) {
                NewVacationRequestScreen(
                    viewModel = hiltViewModel(),
                    onNavigateBack = { navController.popBackStack() }
                )
            }
            
            composable(
                route = Screen.VacationDetail.route,
                arguments = listOf(navArgument("requestId") { type = NavType.IntType })
            ) { backStackEntry ->
                val requestId = backStackEntry.arguments?.getInt("requestId") ?: 0
                VacationDetailScreen(
                    requestId = requestId,
                    onNavigateBack = { navController.popBackStack() }
                )
            }
            
            composable(route = Screen.Payslips.route) {
                PayslipsScreen(
                    onNavigateToDetail = { payslipId ->
                        navController.navigate(Screen.PayslipDetail.createRoute(payslipId))
                    }
                )
            }
            
            composable(
                route = Screen.PayslipDetail.route,
                arguments = listOf(navArgument("payslipId") { type = NavType.IntType })
            ) { backStackEntry ->
                val payslipId = backStackEntry.arguments?.getInt("payslipId") ?: 0
                PayslipDetailScreen(
                    payslipId = payslipId,
                    onNavigateBack = { navController.popBackStack() }
                )
            }
            
            composable(route = Screen.Documents.route) {
                DocumentsScreen()
            }
            
            composable(route = Screen.Profile.route) {
                ProfileScreen(
                    onLogout = {
                        navController.navigate(Screen.Login.route) {
                            popUpTo(0) { inclusive = true }
                        }
                    }
                )
            }
        }
    }
}

/**
 * Bottom navigation bar with modern design
 */
@Composable
fun BottomNavigationBar(navController: NavHostController) {
    val items = getBottomNavItems()
    val navBackStackEntry by navController.currentBackStackEntryAsState()
    val currentDestination = navBackStackEntry?.destination
    
    NavigationBar(
        containerColor = MaterialTheme.colorScheme.surface,
        tonalElevation = 8.dp
    ) {
        items.forEach { item ->
            val selected = currentDestination?.hierarchy?.any { it.route == item.route } == true
            
            NavigationBarItem(
                icon = {
                    Icon(
                        imageVector = getIconForRoute(item.icon),
                        contentDescription = item.title
                    )
                },
                label = { Text(item.title) },
                selected = selected,
                onClick = {
                    navController.navigate(item.route) {
                        // Pop up to Dashboard (the start of bottom nav) to maintain proper back stack
                        popUpTo(Screen.Dashboard.route) {
                            saveState = true
                            inclusive = false
                        }
                        launchSingleTop = true
                        restoreState = true
                    }
                },
                colors = NavigationBarItemDefaults.colors(
                    selectedIconColor = MaterialTheme.colorScheme.onSecondaryContainer,
                    selectedTextColor = MaterialTheme.colorScheme.onSecondaryContainer,
                    indicatorColor = MaterialTheme.colorScheme.secondaryContainer,
                    unselectedIconColor = MaterialTheme.colorScheme.onSurfaceVariant,
                    unselectedTextColor = MaterialTheme.colorScheme.onSurfaceVariant
                )
            )
        }
    }
}

@Preview
@Composable
fun BottomNavigationBarPreview() {
    ZabalaGaileTakHRTheme {
        BottomNavigationBar(navController = rememberNavController())
    }
}

/**
 * Get Material Icon for navigation item
 */
private fun getIconForRoute(icon: String): ImageVector {
    return when (icon) {
        "home" -> Icons.Default.Home
        "beach_access" -> Icons.Default.BeachAccess
        "receipt_long" -> Icons.Default.Receipt
        "folder" -> Icons.Default.Folder
        "person" -> Icons.Default.Person
        else -> Icons.Default.Home
    }
}
