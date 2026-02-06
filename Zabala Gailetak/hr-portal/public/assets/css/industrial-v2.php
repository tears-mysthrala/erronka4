<?php
/**
 * CSS Delivery with Correct MIME Type
 * Workaround for InfinityFree MIME type issues
 * NOW WITH DAY/NIGHT MODE SUPPORT
 */

// Set correct Content-Type header
header('Content-Type: text/css; charset=utf-8');

// Enable caching for 1 year (performance)
header('Cache-Control: public, max-age=31536000, immutable');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

// Output the CSS
?>
/* ========================================
   ZABALA GAILETAK - INDUSTRIAL DESIGN SYSTEM v2
   Portal de Gestión de Recursos Humanos
   Versión: Self-Hosted + Day/Night Mode
   ======================================== */

/* ==================== RESET ==================== */
*, *::before, *::after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* ==================== LIGHT THEME (DEFAULT) ==================== */
:root, [data-theme="light"] {
  --primary: #1D4ED8;
  --primary-dark: #1E3A8A;
  --primary-light: #3B82F6;
  --primary-glow: rgba(29, 78, 216, 0.15);
  --secondary: #F8FAFC;
  --secondary-light: #F1F5F9;
  --secondary-lighter: #E2E8F0;
  --accent: #0EA5E9;
  --accent-light: #38BDF8;
  --accent-glow: rgba(14, 165, 233, 0.12);
  --slate-50: #F8FAFC;
  --slate-100: #F1F5F9;
  --slate-200: #E2E8F0;
  --slate-300: #CBD5E1;
  --slate-400: #94A3B8;
  --slate-500: #64748B;
  --slate-600: #475569;
  --slate-700: #334155;
  --slate-800: #1E293B;
  --slate-900: #0F172A;
  --color-blue: #3B82F6;
  --color-green: #22C55E;
  --color-purple: #A855F7;
  --success: #059669;
  --success-light: #10B981;
  --warning: #D97706;
  --warning-light: #F59E0B;
  --danger: #DC2626;
  --info: #0284C7;
  --bg-body: #F6F8FB;
  --bg-surface: #FFFFFF;
  --bg-card: #FFFFFF;
  --bg-elevated: #F1F5F9;
  --bg-overlay: rgba(15, 23, 42, 0.15);
  --glass-bg: rgba(255, 255, 255, 0.9);
  --glass-border: rgba(15, 23, 42, 0.08);
  --glass-backdrop: blur(10px);
  --text-primary: #0F172A;
  --text-secondary: #334155;
  --text-tertiary: #64748B;
  --text-inverse: #FFFFFF;
  --border-base: rgba(15, 23, 42, 0.08);
  --border-hover: rgba(15, 23, 42, 0.12);
  --border-focus: var(--primary);
  --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.08);
  --shadow-md: 0 8px 18px rgba(15, 23, 42, 0.12);
  --shadow-lg: 0 16px 28px rgba(15, 23, 42, 0.14);
  --shadow-xl: 0 24px 40px rgba(15, 23, 42, 0.16);
  --shadow-glow-primary: 0 0 32px var(--primary-glow);
  --shadow-glow-accent: 0 0 28px var(--accent-glow);
  --space-1: 4px;
  --space-2: 8px;
  --space-3: 12px;
  --space-4: 16px;
  --space-5: 20px;
  --space-6: 24px;
  --space-8: 32px;
  --space-10: 40px;
  --space-12: 48px;
  --space-16: 64px;
  --radius-sm: 6px;
  --radius-md: 10px;
  --radius-lg: 14px;
  --radius-xl: 20px;
  --radius-full: 9999px;
  --font-base: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
  --font-mono: 'SF Mono', 'Monaco', 'Inconsolata', 'Fira Code', 'Fira Mono', 'Roboto Mono', 'Courier New', monospace;
  --text-xs: 0.75rem;
  --text-sm: 0.875rem;
  --text-base: 1rem;
  --text-lg: 1.125rem;
  --text-xl: 1.25rem;
  --text-2xl: 1.5rem;
  --text-3xl: 1.875rem;
  --text-4xl: 2.25rem;
  --text-5xl: 3rem;
  --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
  --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
  --transition-slow: 400ms cubic-bezier(0.4, 0, 0.2, 1);
}

/* ==================== DARK THEME ==================== */
[data-theme="dark"] {
  --primary: #1D4ED8;
  --primary-dark: #1E3A8A;
  --primary-light: #3B82F6;
  --primary-glow: rgba(29, 78, 216, 0.2);
  --secondary: #18181B;
  --secondary-light: #27272A;
  --secondary-lighter: #3F3F46;
  --accent: #0EA5E9;
  --accent-light: #38BDF8;
  --accent-glow: rgba(14, 165, 233, 0.18);
  --success: #059669;
  --success-light: #10B981;
  --warning: #D97706;
  --warning-light: #F59E0B;
  --danger: #DC2626;
  --info: #0284C7;
  --bg-body: #0F0F11;
  --bg-surface: #18181B;
  --bg-card: #1C1C1F;
  --bg-elevated: #27272A;
  --bg-overlay: rgba(0, 0, 0, 0.75);
  --glass-bg: rgba(24, 24, 27, 0.85);
  --glass-border: rgba(255, 255, 255, 0.08);
  --glass-backdrop: blur(16px);
  --text-primary: #FAFAFA;
  --text-secondary: #A1A1AA;
  --text-tertiary: #71717A;
  --text-inverse: #18181B;
  --border-base: rgba(255, 255, 255, 0.08);
  --border-hover: rgba(255, 255, 255, 0.12);
  --border-focus: var(--primary);
  --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.5);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.6);
  --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.7);
  --shadow-xl: 0 16px 40px rgba(0, 0, 0, 0.8);
  --shadow-glow-primary: 0 0 32px var(--primary-glow);
  --shadow-glow-accent: 0 0 28px var(--accent-glow);
}

/* ==================== BASE STYLES ==================== */
html {
  font-family: var(--font-base);
  font-size: 16px;
  line-height: 1.5;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  transition: background-color 0.3s ease, color 0.3s ease;
}

body {
  background: var(--bg-body);
  color: var(--text-primary);
  min-height: 100vh;
  overflow-x: hidden;
}

a { color: var(--primary-light); text-decoration: none; transition: color var(--transition-fast); }
a:hover { color: var(--primary); }

/* ==================== THEME TOGGLE ==================== */
.theme-toggle {
  position: relative;
  width: 52px;
  height: 28px;
  background: var(--bg-elevated);
  border: 1px solid var(--border-base);
  border-radius: var(--radius-full);
  cursor: pointer;
  transition: all var(--transition-base);
  padding: 0;
  overflow: hidden;
}

.theme-toggle:hover {
  border-color: var(--border-hover);
  transform: scale(1.05);
}

.theme-toggle::before {
  content: '';
  position: absolute;
  top: 3px;
  left: 3px;
  width: 20px;
  height: 20px;
  background: var(--primary);
  border-radius: var(--radius-full);
  transition: transform var(--transition-base);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

[data-theme="dark"] .theme-toggle::before {
  transform: translateX(24px);
}

.theme-icon-light,
.theme-icon-dark {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  font-size: 12px;
  transition: opacity var(--transition-fast);
}

.theme-icon-light {
  left: 8px;
  color: var(--warning-light);
  opacity: 0.3;
}

.theme-icon-dark {
  right: 8px;
  color: var(--info);
  opacity: 1;
}

[data-theme="dark"] .theme-icon-light {
  opacity: 1;
}

[data-theme="dark"] .theme-icon-dark {
  opacity: 0.3;
}

/* ==================== NAVBAR INDUSTRIAL ==================== */
.navbar-industrial {
  position: sticky;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  background: var(--glass-bg);
  backdrop-filter: var(--glass-backdrop);
  -webkit-backdrop-filter: var(--glass-backdrop);
  border-bottom: 1px solid var(--glass-border);
  box-shadow: var(--shadow-md);
  padding: var(--space-4) var(--space-6);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.navbar-industrial > div {
  width: 100%;
  gap: var(--space-8);
}

.navbar-brand {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  font-size: var(--text-xl);
  font-weight: 700;
  color: var(--text-primary);
  text-decoration: none;
  transition: transform var(--transition-base);
}

.navbar-brand:hover {
  transform: scale(1.02);
  color: var(--text-primary);
}

.brand-logo {
  font-size: var(--text-2xl);
  color: var(--primary);
}

.brand-text { font-weight: 800; }
.brand-subtitle {
  font-size: var(--text-sm);
  font-weight: 400;
  color: var(--text-tertiary);
  margin-left: var(--space-2);
}

.navbar-nav {
  display: flex;
  gap: var(--space-2);
  list-style: none;
  align-items: center;
}

.nav-links-industrial {
  flex: 1;
  justify-content: center;
}

.nav-link-industrial {
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-sm);
  font-size: var(--text-sm);
  font-weight: 600;
  color: var(--text-secondary);
  text-decoration: none;
  transition: color var(--transition-fast), border-color var(--transition-fast), background var(--transition-fast);
  position: relative;
  border-bottom: 2px solid transparent;
}

.nav-link-industrial:hover {
  color: var(--text-primary);
  border-bottom-color: var(--border-hover);
  background: rgba(29, 78, 216, 0.08);
}

.nav-link-industrial.active {
  color: var(--primary);
  border-bottom-color: var(--primary);
  background: rgba(14, 165, 233, 0.12);
}

.nav-link-industrial i { margin-right: var(--space-2); }

.user-profile {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-2) var(--space-5);
  background: var(--bg-elevated);
  border: 1px solid var(--border-base);
  border-radius: var(--radius-xl);
  transition: all var(--transition-base);
  text-decoration: none;
  color: inherit;
  box-shadow: var(--shadow-sm);
  min-height: 44px;
  cursor: pointer;
}

.user-profile:hover {
  border-color: var(--border-hover);
  transform: scale(1.02);
  background: rgba(29, 78, 216, 0.06);
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: var(--radius-full);
  background: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: var(--text-sm);
  border: 2px solid rgba(255, 255, 255, 0.7);
}

.user-info { display: flex; flex-direction: column; }
.user-name { font-size: var(--text-sm); font-weight: 700; color: var(--text-primary); line-height: 1.2; }
.user-role { font-size: var(--text-xs); color: var(--text-tertiary); line-height: 1.2; }

/* ==================== BUTTONS INDUSTRIAL ==================== */
.btn-industrial {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-2);
  padding: var(--space-3) var(--space-5);
  border: none;
  border-radius: var(--radius-md);
  font-size: var(--text-sm);
  font-weight: 600;
  cursor: pointer;
  transition: all var(--transition-fast);
  text-decoration: none;
  position: relative;
  overflow: hidden;
}

.btn-industrial:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.btn-industrial:active {
  transform: translateY(0);
}

.btn-primary-industrial {
  background: var(--primary);
  color: white;
  box-shadow: var(--shadow-md);
}

.btn-primary-industrial:hover {
  box-shadow: var(--shadow-lg);
  color: white;
}

.btn-secondary-industrial {
  background: var(--bg-elevated);
  color: var(--text-primary);
  border: 1px solid var(--border-base);
}

.btn-secondary-industrial:hover {
  border-color: var(--border-hover);
  background: var(--bg-card);
}

.btn-ghost-industrial {
  background: transparent;
  color: var(--text-secondary);
  border: 1px solid transparent;
}

.btn-ghost-industrial:hover {
  background: var(--bg-elevated);
  color: var(--text-primary);
  border-color: var(--border-base);
}

.btn-success-industrial {
  background: var(--success);
  color: white;
}

.btn-danger-industrial {
  background: var(--danger);
  color: white;
}

.btn-sm {
  padding: var(--space-2) var(--space-3);
  font-size: var(--text-xs);
}

/* ==================== DASHBOARD LAYOUT ==================== */
.dashboard-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: var(--space-6);
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: var(--space-8);
  padding-bottom: var(--space-4);
  border-bottom: 2px solid var(--border-base);
}

.page-title {
  font-size: var(--text-3xl);
  font-weight: 800;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  gap: var(--space-3);
}

.page-title i {
  color: var(--primary);
}

.page-subtitle {
  font-size: var(--text-sm);
  color: var(--text-tertiary);
  margin-top: var(--space-1);
}

.page-actions {
  display: flex;
  gap: var(--space-3);
  align-items: center;
}

/* ==================== LOGIN ==================== */
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: var(--space-6);
  background: var(--bg-body);
}

.login-card {
  width: 100%;
  max-width: 460px;
  background: var(--bg-card);
  border: 1px solid var(--border-base);
  border-radius: var(--radius-xl);
  padding: var(--space-10);
  box-shadow: var(--shadow-xl);
}

.login-header {
  text-align: center;
  margin-bottom: var(--space-8);
}

.login-logo {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 72px;
  height: 72px;
  background: var(--primary);
  border-radius: var(--radius-lg);
  margin-bottom: var(--space-4);
  box-shadow: var(--shadow-md);
}

.login-logo i {
  font-size: 30px;
  color: white;
}

.login-title {
  font-size: var(--text-3xl);
  font-weight: 800;
  margin-bottom: var(--space-2);
  color: var(--text-primary);
}

.login-subtitle {
  font-size: var(--text-sm);
  color: var(--text-tertiary);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-weight: 600;
}

/* ==================== STATS GRID ==================== */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: var(--space-6);
  margin-bottom: var(--space-8);
}

.stat-card-industrial {
  background: var(--bg-card);
  border: 1px solid var(--border-base);
  border-radius: var(--radius-lg);
  padding: var(--space-6);
  display: flex;
  align-items: center;
  gap: var(--space-4);
  transition: all var(--transition-base);
  position: relative;
  overflow: hidden;
}

.stat-card-industrial:hover {
  border-color: var(--border-hover);
  transform: translateY(-4px);
  box-shadow: var(--shadow-lg);
}

.stat-card-industrial::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: var(--primary);
  opacity: 0;
  transition: opacity var(--transition-base);
}

.stat-card-industrial:hover::before {
  opacity: 1;
}

.stat-icon-wrapper {
  width: 60px;
  height: 60px;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: var(--text-2xl);
  flex-shrink: 0;
}

.stat-details {
  flex: 1;
}

.stat-label {
  font-size: var(--text-sm);
  color: var(--text-tertiary);
  margin-bottom: var(--space-1);
  font-weight: 500;
}

.stat-value {
  font-size: var(--text-4xl);
  font-weight: 800;
  color: var(--text-primary);
  line-height: 1;
  margin-bottom: var(--space-2);
}

.stat-trend {
  font-size: var(--text-xs);
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: var(--space-1);
}

.stat-trend-positive { color: var(--success-light); }
.stat-trend-negative { color: var(--danger); }
.stat-trend-neutral { color: var(--text-tertiary); }

/* ==================== WIDGET CARDS ==================== */
.widget-card-industrial {
  background: var(--bg-card);
  border: 1px solid var(--border-base);
  border-radius: var(--radius-lg);
  overflow: hidden;
  transition: all var(--transition-base);
}

.widget-card-industrial:hover {
  border-color: var(--border-hover);
  box-shadow: var(--shadow-md);
}

.widget-header {
  padding: var(--space-5) var(--space-6);
  border-bottom: 1px solid var(--border-base);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.widget-title {
  font-size: var(--text-lg);
  font-weight: 700;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  gap: var(--space-2);
}

.widget-title i {
  color: var(--primary);
}

.widget-body {
  padding: var(--space-6);
}

/* ==================== TABLES INDUSTRIAL ==================== */
.table-container-industrial {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.table-industrial {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.table-industrial thead {
  background: var(--bg-elevated);
  border-bottom: 2px solid var(--border-base);
}

.table-industrial th {
  padding: var(--space-4) var(--space-5);
  font-size: var(--text-xs);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--text-secondary);
  text-align: left;
  white-space: nowrap;
}

.table-industrial th i {
  margin-right: var(--space-2);
  color: var(--primary);
}

.table-industrial tbody tr {
  border-bottom: 1px solid var(--border-base);
  transition: background var(--transition-fast);
}

.table-industrial tbody tr:hover {
  background: var(--bg-elevated);
}

.table-industrial td {
  padding: var(--space-4) var(--space-5);
  font-size: var(--text-sm);
  color: var(--text-primary);
}

.table-user {
  display: flex;
  align-items: center;
  gap: var(--space-3);
}

.table-avatar {
  width: 32px;
  height: 32px;
  border-radius: var(--radius-full);
  background: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: var(--text-xs);
  flex-shrink: 0;
}

.table-user-name {
  font-weight: 600;
  color: var(--text-primary);
}

.table-link {
  color: var(--text-secondary);
  text-decoration: none;
  transition: color var(--transition-fast);
}

.table-link:hover {
  color: var(--primary);
}

.table-badge {
  display: inline-flex;
  align-items: center;
  gap: var(--space-1);
  padding: var(--space-1) var(--space-3);
  border-radius: var(--radius-sm);
  font-size: var(--text-xs);
  font-weight: 600;
  white-space: nowrap;
}

.table-badge-primary {
  background: rgba(29, 78, 216, 0.12);
  color: var(--primary);
}

.table-badge-secondary {
  background: var(--bg-elevated);
  color: var(--text-secondary);
}

.table-badge-success {
  background: rgba(5, 150, 105, 0.1);
  color: var(--success-light);
}

.table-badge-danger {
  background: rgba(220, 38, 38, 0.1);
  color: var(--danger);
}

.table-badge-warning {
  background: rgba(217, 119, 6, 0.1);
  color: var(--warning-light);
}

.table-badge-accent {
  background: rgba(234, 88, 12, 0.1);
  color: var(--accent-light);
}

.table-actions {
  display: flex;
  gap: var(--space-2);
  align-items: center;
}

.table-action {
  width: 32px;
  height: 32px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius-sm);
  background: transparent;
  border: 1px solid var(--border-base);
  color: var(--text-secondary);
  cursor: pointer;
  transition: all var(--transition-fast);
  text-decoration: none;
}

.table-action:hover {
  background: var(--bg-elevated);
  border-color: var(--border-hover);
  color: var(--text-primary);
  transform: translateY(-2px);
}

.table-action-danger:hover {
  background: rgba(220, 38, 38, 0.1);
  border-color: var(--danger);
  color: var(--danger);
}

/* ==================== ALERTS ==================== */
.alert-industrial {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-md);
  border-left: 4px solid;
  margin-bottom: var(--space-6);
  display: flex;
  align-items: center;
  gap: var(--space-3);
  font-size: var(--text-sm);
}

.alert-success-industrial {
  background: rgba(5, 150, 105, 0.1);
  border-color: var(--success);
  color: var(--success-light);
}

.alert-warning-industrial {
  background: rgba(217, 119, 6, 0.1);
  border-color: var(--warning);
  color: var(--warning-light);
}

.alert-danger-industrial {
  background: rgba(220, 38, 38, 0.1);
  border-color: var(--danger);
  color: var(--danger);
}

/* ==================== BADGES ==================== */
.badge-industrial {
  display: inline-flex;
  align-items: center;
  gap: var(--space-1);
  padding: var(--space-2) var(--space-3);
  border-radius: var(--radius-full);
  font-size: var(--text-xs);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.badge-success-industrial {
  background: rgba(5, 150, 105, 0.15);
  color: var(--success-light);
}

.badge-warning-industrial {
  background: rgba(217, 119, 6, 0.15);
  color: var(--warning-light);
}

.badge-danger-industrial {
  background: rgba(220, 38, 38, 0.15);
  color: var(--danger);
}

.badge-primary-industrial {
  background: rgba(185, 28, 28, 0.15);
  color: var(--primary-light);
}

/* ==================== ANIMATIONS ==================== */
@keyframes fadeInScale {
  from {
    opacity: 0;
    transform: scale(0.9) translateY(20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

@keyframes scanline {
  0%, 100% { transform: translateX(-100%); }
  50% { transform: translateX(100%); }
}

.animate-fade-in { animation: fadeInScale 0.5s ease-out forwards; }
.animate-delay-1 { animation-delay: 0.1s; }
.animate-delay-2 { animation-delay: 0.2s; }
.animate-delay-3 { animation-delay: 0.3s; }
.animate-delay-4 { animation-delay: 0.4s; }

/* ==================== RESPONSIVE ==================== */
@media (max-width: 768px) {
  .navbar-industrial {
    flex-direction: column;
    gap: var(--space-4);
    padding: var(--space-4);
  }
  
  .navbar-nav {
    flex-direction: column;
    width: 100%;
  }
  
  .nav-link-industrial {
    width: 100%;
    text-align: center;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .page-header {
    flex-direction: column;
    align-items: flex-start;
    gap: var(--space-4);
  }
  
  .page-actions {
    width: 100%;
    flex-wrap: wrap;
  }
  
  .table-container-industrial {
    margin: 0 calc(var(--space-6) * -1);
  }
}

/* ==================== SCROLLBAR ==================== */
::-webkit-scrollbar {
  width: 12px;
  height: 12px;
}

::-webkit-scrollbar-track {
  background: var(--bg-surface);
}

::-webkit-scrollbar-thumb {
  background: var(--bg-elevated);
  border-radius: var(--radius-full);
  border: 2px solid var(--bg-surface);
}

::-webkit-scrollbar-thumb:hover {
  background: var(--secondary-lighter);
}

/* ==================== UTILITIES ==================== */
.text-center { text-align: center; }
.text-right { text-align: right; }
.mt-1 { margin-top: var(--space-1); }
.mt-2 { margin-top: var(--space-2); }
.mt-3 { margin-top: var(--space-3); }
.mt-4 { margin-top: var(--space-4); }
.mt-6 { margin-top: var(--space-6); }
.mb-1 { margin-bottom: var(--space-1); }
.mb-2 { margin-bottom: var(--space-2); }
.mb-3 { margin-bottom: var(--space-3); }
.mb-4 { margin-bottom: var(--space-4); }
.mb-6 { margin-bottom: var(--space-6); }
.p-0 { padding: 0; }
.px-4 { padding-left: var(--space-4); padding-right: var(--space-4); }
.py-4 { padding-top: var(--space-4); padding-bottom: var(--space-4); }

/* ==================== DASHBOARD SPECIFIC ==================== */
.dashboard-header {
  margin-bottom: var(--space-8);
}

.dashboard-title {
  font-size: var(--text-4xl);
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: var(--space-2);
}

.dashboard-subtitle {
  font-size: var(--text-base);
  color: var(--text-tertiary);
}

.stat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: var(--space-3);
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: var(--radius-md);
  background: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: var(--text-xl);
}

.stat-badge {
  padding: var(--space-1) var(--space-3);
  background: var(--bg-elevated);
  border-radius: var(--radius-sm);
  font-size: var(--text-xs);
  font-weight: 700;
  color: var(--text-tertiary);
  letter-spacing: 0.05em;
}

.stat-link {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  margin-top: var(--space-4);
  font-size: var(--text-sm);
  color: var(--primary);
  text-decoration: none;
  font-weight: 600;
  transition: gap var(--transition-fast);
}

.stat-link:hover {
  gap: var(--space-3);
  color: var(--primary-light);
}

.widget-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: var(--space-6);
  margin-top: var(--space-8);
}

.widget-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.widget-list-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: var(--space-4);
  border-bottom: 1px solid var(--border-base);
  transition: background var(--transition-fast);
}

.widget-list-item:last-child {
  border-bottom: none;
}

.widget-list-item:hover {
  background: var(--bg-elevated);
}

.item-user {
  display: flex;
  align-items: center;
  gap: var(--space-3);
}

.item-avatar {
  width: 40px;
  height: 40px;
  border-radius: var(--radius-full);
  background: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: var(--text-sm);
  flex-shrink: 0;
}

.item-details {
  display: flex;
  flex-direction: column;
}

.item-name {
  font-size: var(--text-sm);
  font-weight: 600;
  color: var(--text-primary);
}

.item-meta {
  font-size: var(--text-xs);
  color: var(--text-tertiary);
  display: flex;
  align-items: center;
  gap: var(--space-1);
}

.item-badge {
  padding: var(--space-2) var(--space-3);
  background: var(--bg-elevated);
  border-radius: var(--radius-sm);
  font-size: var(--text-xs);
  font-weight: 600;
  color: var(--text-secondary);
  white-space: nowrap;
}

.empty-state {
  text-align: center;
  padding: var(--space-10) var(--space-4);
  color: var(--text-tertiary);
}

.empty-state i {
  font-size: var(--text-5xl);
  margin-bottom: var(--space-4);
  opacity: 0.3;
}

.empty-state p {
  font-size: var(--text-sm);
  margin: 0;
}

/* ==================== NAVBAR FIX ==================== */
.navbar-brand-industrial {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  font-size: var(--text-xl);
  font-weight: 700;
  color: var(--text-primary);
  text-decoration: none;
  transition: transform var(--transition-base);
}

.navbar-brand-industrial:hover {
  transform: scale(1.02);
  color: var(--text-primary);
}

.brand-icon {
  font-size: var(--text-2xl);
  color: var(--primary);
}

.nav-links-industrial {
  display: flex;
  gap: var(--space-6);
  list-style: none;
  align-items: center;
  margin: 0;
  padding: 0;
}

/* ==================== FORMS INDUSTRIAL ==================== */
.form-card-industrial {
  background: var(--bg-card);
  border: 1px solid var(--border-base);
  border-radius: var(--radius-lg);
  padding: var(--space-8);
  max-width: 800px;
  margin: 0 auto;
}

.form-title-industrial {
  font-size: var(--text-3xl);
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: var(--space-2);
}

.form-subtitle-industrial {
  font-size: var(--text-sm);
  color: var(--text-tertiary);
  margin-bottom: var(--space-6);
}

.info-box-industrial {
  background: rgba(59, 130, 246, 0.1);
  border-left: 4px solid var(--color-blue);
  padding: var(--space-4);
  border-radius: var(--radius-md);
  margin-bottom: var(--space-6);
}

.info-box-industrial p {
  margin: 0;
  font-size: var(--text-sm);
  color: var(--text-secondary);
  line-height: 1.6;
}

.info-box-industrial p + p {
  margin-top: var(--space-2);
}

.form-group-industrial {
  margin-bottom: var(--space-5);
}

.form-label-industrial {
  display: block;
  font-size: var(--text-sm);
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: var(--space-2);
}

.form-input-industrial,
.form-textarea-industrial,
.form-select-industrial {
  width: 100%;
  padding: var(--space-3) var(--space-4);
  background: var(--bg-surface);
  border: 1px solid var(--border-base);
  border-radius: var(--radius-md);
  font-size: var(--text-base);
  color: var(--text-primary);
  transition: all var(--transition-fast);
  font-family: var(--font-base);
}

.form-input-industrial:focus,
.form-textarea-industrial:focus,
.form-select-industrial:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1);
}

.form-textarea-industrial {
  resize: vertical;
  min-height: 120px;
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: var(--space-4);
}

.filter-item label {
  display: block;
  font-size: var(--text-sm);
  font-weight: 600;
  color: var(--text-secondary);
  margin-bottom: var(--space-2);
}

.table-empty {
  text-align: center;
  color: var(--text-tertiary);
  padding: var(--space-4);
}

.calculated-days-industrial {
  background: var(--bg-elevated);
  padding: var(--space-5);
  border-radius: var(--radius-md);
  text-align: center;
  margin: var(--space-6) 0;
  border: 2px solid var(--border-base);
}

.days-label-industrial {
  font-size: var(--text-sm);
  color: var(--text-tertiary);
  margin-bottom: var(--space-2);
  font-weight: 500;
}

.days-value-industrial {
  font-size: var(--text-5xl);
  font-weight: 800;
  color: var(--primary);
}

.days-note-industrial {
  font-size: var(--text-xs);
  color: var(--text-tertiary);
  margin-top: var(--space-2);
}

.form-actions-industrial {
  display: flex;
  gap: var(--space-3);
  margin-top: var(--space-8);
}

.form-actions-industrial .btn-industrial {
  flex: 1;
}
