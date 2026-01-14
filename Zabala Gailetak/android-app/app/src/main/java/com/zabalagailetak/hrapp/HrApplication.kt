package com.zabalagailetak.hrapp

import android.app.Application
import dagger.hilt.android.HiltAndroidApp

/**
 * Application class for Zabala Gailetak HR App
 * 
 * This class initializes Hilt for dependency injection
 */
@HiltAndroidApp
class HrApplication : Application() {
    
    override fun onCreate() {
        super.onCreate()
        // Initialize any application-wide components here
    }
}
