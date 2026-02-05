/**
 * CSRF Protection Helper
 * Automatically adds CSRF tokens to all fetch requests
 */

// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

if (!csrfToken) {
    console.warn('[CSRF] No CSRF token found in meta tag');
}

// Store original fetch
const originalFetch = window.fetch;

/**
 * Wrapper for fetch with automatic CSRF token injection
 */
function fetchWithCSRF(url, options = {}) {
    // Only add CSRF token for non-GET requests
    const method = (options.method || 'GET').toUpperCase();
    
    if (method !== 'GET' && method !== 'HEAD' && csrfToken) {
        options.headers = options.headers || {};
        
        // Handle Headers object or plain object
        if (options.headers instanceof Headers) {
            options.headers.set('X-CSRF-Token', csrfToken);
        } else {
            options.headers['X-CSRF-Token'] = csrfToken;
        }
    }
    
    return originalFetch(url, options);
}

// Override global fetch
window.fetch = fetchWithCSRF;

// Export for manual usage
window.csrfToken = csrfToken;

console.log('[CSRF] Protection initialized');
