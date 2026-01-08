const mongoose = require('mongoose');

const auditLogSchema = new mongoose.Schema({
  action: {
    type: String,
    required: [true, 'Action is required'],
    trim: true,
    index: true,
    enum: [
      // Authentication actions
      'auth.login.success',
      'auth.login.failed',
      'auth.logout',
      'auth.register',
      'auth.password.change',
      'auth.password.reset',
      'auth.mfa.enabled',
      'auth.mfa.disabled',
      'auth.mfa.verified',
      'auth.account.locked',
      
      // User management
      'user.create',
      'user.update',
      'user.delete',
      'user.role.change',
      
      // Product management
      'product.create',
      'product.update',
      'product.delete',
      'product.stock.update',
      
      // Order management
      'order.create',
      'order.update',
      'order.status.change',
      'order.cancel',
      'order.refund',
      
      // Security events
      'security.access.denied',
      'security.sql.injection.attempt',
      'security.xss.attempt',
      'security.rate.limit.exceeded',
      'security.invalid.token',
      'security.suspicious.activity',
      
      // System events
      'system.start',
      'system.stop',
      'system.error',
      'system.config.change',
      
      // Data access
      'data.read',
      'data.export',
      'data.import',
      
      // Compliance
      'gdpr.data.request',
      'gdpr.data.delete',
      'gdpr.consent.given',
      'gdpr.consent.withdrawn'
    ]
  },
  severity: {
    type: String,
    required: true,
    enum: ['low', 'medium', 'high', 'critical'],
    default: 'low',
    index: true
  },
  user: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: false, // Some events may not have a user (e.g., system events)
    index: true
  },
  username: {
    type: String,
    trim: true
  },
  ipAddress: {
    type: String,
    trim: true,
    index: true
  },
  userAgent: {
    type: String,
    trim: true
  },
  resource: {
    type: String,
    trim: true,
    index: true // e.g., 'User', 'Product', 'Order'
  },
  resourceId: {
    type: String,
    trim: true,
    index: true
  },
  method: {
    type: String,
    enum: ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD'],
    uppercase: true
  },
  endpoint: {
    type: String,
    trim: true
  },
  statusCode: {
    type: Number,
    min: 100,
    max: 599
  },
  message: {
    type: String,
    trim: true,
    maxlength: [1000, 'Message cannot exceed 1000 characters']
  },
  details: {
    type: mongoose.Schema.Types.Mixed, // Store additional contextual information
    default: {}
  },
  oldValue: {
    type: mongoose.Schema.Types.Mixed // For update actions, store previous value
  },
  newValue: {
    type: mongoose.Schema.Types.Mixed // For update actions, store new value
  },
  success: {
    type: Boolean,
    required: true,
    default: true,
    index: true
  },
  errorMessage: {
    type: String,
    trim: true
  },
  sessionId: {
    type: String,
    trim: true,
    index: true
  },
  requestId: {
    type: String,
    trim: true,
    index: true
  },
  duration: {
    type: Number, // Request duration in milliseconds
    min: 0
  },
  geoLocation: {
    country: String,
    city: String,
    latitude: Number,
    longitude: Number
  },
  timestamp: {
    type: Date,
    default: Date.now,
    required: true,
    index: true
  }
}, {
  timestamps: false // We're using custom timestamp field
});

// Compound indexes for common queries
auditLogSchema.index({ action: 1, timestamp: -1 });
auditLogSchema.index({ user: 1, timestamp: -1 });
auditLogSchema.index({ severity: 1, timestamp: -1 });
auditLogSchema.index({ ipAddress: 1, timestamp: -1 });
auditLogSchema.index({ success: 1, severity: 1, timestamp: -1 });
auditLogSchema.index({ timestamp: -1 }); // For time-based queries

// TTL index to automatically delete old audit logs after 2 years
auditLogSchema.index({ timestamp: 1 }, { expireAfterSeconds: 63072000 }); // 730 days

// Static method to create audit log entry
auditLogSchema.statics.log = async function(logData) {
  try {
    const auditLog = new this(logData);
    await auditLog.save();
    return auditLog;
  } catch (error) {
    console.error('Failed to create audit log:', error);
    // Don't throw error to prevent application failure due to logging issues
    return null;
  }
};

// Static method to query logs by date range
auditLogSchema.statics.findByDateRange = function(startDate, endDate, filters = {}) {
  const query = {
    timestamp: {
      $gte: startDate,
      $lte: endDate
    },
    ...filters
  };
  return this.find(query).sort({ timestamp: -1 });
};

// Static method to find failed login attempts
auditLogSchema.statics.findFailedLogins = function(ipAddress, since) {
  return this.find({
    action: 'auth.login.failed',
    ipAddress: ipAddress,
    timestamp: { $gte: since }
  }).sort({ timestamp: -1 });
};

// Static method to find suspicious activities
auditLogSchema.statics.findSuspiciousActivities = function(since) {
  return this.find({
    $or: [
      { severity: { $in: ['high', 'critical'] } },
      { action: { $regex: /^security\./ } },
      { success: false, statusCode: { $in: [401, 403, 429] } }
    ],
    timestamp: { $gte: since }
  }).sort({ timestamp: -1 });
};

// Static method to generate security report
auditLogSchema.statics.generateSecurityReport = async function(startDate, endDate) {
  const report = {
    totalEvents: 0,
    authenticationEvents: 0,
    failedLogins: 0,
    successfulLogins: 0,
    securityEvents: 0,
    criticalEvents: 0,
    topUsers: [],
    topIPs: [],
    topActions: []
  };
  
  const dateFilter = {
    timestamp: { $gte: startDate, $lte: endDate }
  };
  
  // Total events
  report.totalEvents = await this.countDocuments(dateFilter);
  
  // Authentication events
  report.authenticationEvents = await this.countDocuments({
    ...dateFilter,
    action: { $regex: /^auth\./ }
  });
  
  // Failed logins
  report.failedLogins = await this.countDocuments({
    ...dateFilter,
    action: 'auth.login.failed'
  });
  
  // Successful logins
  report.successfulLogins = await this.countDocuments({
    ...dateFilter,
    action: 'auth.login.success'
  });
  
  // Security events
  report.securityEvents = await this.countDocuments({
    ...dateFilter,
    action: { $regex: /^security\./ }
  });
  
  // Critical events
  report.criticalEvents = await this.countDocuments({
    ...dateFilter,
    severity: 'critical'
  });
  
  return report;
};

// Method to anonymize user data (for GDPR compliance)
auditLogSchema.methods.anonymize = async function() {
  this.user = null;
  this.username = 'ANONYMIZED';
  this.ipAddress = '0.0.0.0';
  this.userAgent = 'ANONYMIZED';
  this.details = {};
  return await this.save();
};

const AuditLog = mongoose.model('AuditLog', auditLogSchema);

module.exports = AuditLog;
