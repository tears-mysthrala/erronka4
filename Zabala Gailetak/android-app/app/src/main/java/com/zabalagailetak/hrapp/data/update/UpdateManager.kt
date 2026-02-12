package com.zabalagailetak.hrapp.data.update

import android.app.DownloadManager
import android.content.BroadcastReceiver
import android.content.Context
import android.content.Intent
import android.content.IntentFilter
import android.net.Uri
import android.os.Build
import android.os.Environment
import androidx.core.content.FileProvider
import com.zabalagailetak.hrapp.BuildConfig
import com.zabalagailetak.hrapp.data.api.UpdateApiService
import com.zabalagailetak.hrapp.data.api.VersionInfo
import dagger.hilt.android.qualifiers.ApplicationContext
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import java.io.File
import javax.inject.Inject
import javax.inject.Singleton

/**
 * Update check result
 */
sealed class UpdateResult {
    data class UpdateAvailable(
        val versionInfo: VersionInfo,
        val currentVersionCode: Int
    ) : UpdateResult()
    
    data object UpToDate : UpdateResult()
    data class Error(val message: String) : UpdateResult()
}

/**
 * Manager for handling app updates
 */
@Singleton
class UpdateManager @Inject constructor(
    @ApplicationContext private val context: Context,
    private val updateApiService: UpdateApiService
) {
    private val _updateState = MutableStateFlow<UpdateResult?>(null)
    val updateState: StateFlow<UpdateResult?> = _updateState.asStateFlow()

    private var downloadId: Long = -1
    private val downloadManager by lazy {
        context.getSystemService(Context.DOWNLOAD_SERVICE) as DownloadManager
    }

    /**
     * Check for available updates
     */
    suspend fun checkForUpdates(): UpdateResult {
        return try {
            val response = updateApiService.getLatestVersion()
            
            if (!response.isSuccessful) {
                val result = UpdateResult.Error("Ezin da eguneraketak egiaztatu")
                _updateState.value = result
                return result
            }
            
            val versionInfo = response.body()
            if (versionInfo == null) {
                val result = UpdateResult.Error("Erantzun hutsia zerbitzaritik")
                _updateState.value = result
                return result
            }
            
            val currentVersionCode = BuildConfig.VERSION_CODE
            
            val result = if (versionInfo.versionCode > currentVersionCode) {
                UpdateResult.UpdateAvailable(versionInfo, currentVersionCode)
            } else {
                UpdateResult.UpToDate
            }
            
            _updateState.value = result
            result
            
        } catch (e: Exception) {
            val result = UpdateResult.Error("Eguneraketak egiaztatzean errorea: ${e.message}")
            _updateState.value = result
            result
        }
    }

    /**
     * Download and install update
     */
    fun downloadUpdate(versionInfo: VersionInfo) {
        val downloadUrl = versionInfo.downloadUrl ?: return
        
        // Create download request
        val request = DownloadManager.Request(Uri.parse(downloadUrl))
            .setTitle("Zabala Gailetak HR - Eguneratzea")
            .setDescription("${versionInfo.versionName} bertsioa deskargatzen...")
            .setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED)
            .setDestinationInExternalPublicDir(
                Environment.DIRECTORY_DOWNLOADS,
                "zabala-gailetak-hrapp-v${versionInfo.versionName}.apk"
            )
            .setAllowedOverMetered(true)
            .setAllowedOverRoaming(true)

        // Enqueue download
        downloadId = downloadManager.enqueue(request)
        
        // Register receiver for download completion
        val receiver = object : BroadcastReceiver() {
            override fun onReceive(context: Context, intent: Intent) {
                val id = intent.getLongExtra(DownloadManager.EXTRA_DOWNLOAD_ID, -1)
                if (id == downloadId) {
                    installUpdate(versionInfo)
                    context.unregisterReceiver(this)
                }
            }
        }
        
        val filter = IntentFilter(DownloadManager.ACTION_DOWNLOAD_COMPLETE)
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            context.registerReceiver(receiver, filter, Context.RECEIVER_EXPORTED)
        } else {
            context.registerReceiver(receiver, filter)
        }
    }

    /**
     * Install downloaded update
     */
    private fun installUpdate(versionInfo: VersionInfo) {
        val file = File(
            Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOWNLOADS),
            "zabala-gailetak-hrapp-v${versionInfo.versionName}.apk"
        )
        
        if (!file.exists()) return
        
        val uri = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.N) {
            FileProvider.getUriForFile(
                context,
                "${context.packageName}.fileprovider",
                file
            )
        } else {
            Uri.fromFile(file)
        }
        
        val intent = Intent(Intent.ACTION_VIEW).apply {
            setDataAndType(uri, "application/vnd.android.package-archive")
            flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_GRANT_READ_URI_PERMISSION
        }
        
        context.startActivity(intent)
    }

    /**
     * Get current app version info
     */
    fun getCurrentVersion(): String {
        return "${BuildConfig.VERSION_NAME} (${BuildConfig.VERSION_CODE})"
    }

    /**
     * Check if this is a fresh install or update
     */
    fun isUpdate(): Boolean {
        val prefs = context.getSharedPreferences("app_updates", Context.MODE_PRIVATE)
        val lastVersionCode = prefs.getInt("last_version_code", 0)
        val currentVersionCode = BuildConfig.VERSION_CODE
        
        // Save current version
        prefs.edit().putInt("last_version_code", currentVersionCode).apply()
        
        return lastVersionCode > 0 && currentVersionCode > lastVersionCode
    }

    /**
     * Clear update state
     */
    fun clearUpdateState() {
        _updateState.value = null
    }
}
