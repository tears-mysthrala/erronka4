const mongoose = require('mongoose');
require('dotenv').config();

/**
 * Database Configuration for Zabala Gailetak
 * 
 * Implements secure MongoDB connection with:
 * - Connection pooling
 * - Automatic reconnection
 * - Error handling
 * - Connection monitoring
 * - Security best practices
 */

const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://localhost:27017/zabala-gailetak';
const NODE_ENV = process.env.NODE_ENV || 'development';

// Connection options
const options = {
  // Connection pool settings
  maxPoolSize: parseInt(process.env.MONGODB_POOL_SIZE) || 10,
  minPoolSize: 2,
  
  // Timeout settings
  serverSelectionTimeoutMS: 5000, // 5 seconds
  socketTimeoutMS: 45000, // 45 seconds
  
  // Automatic reconnection
  retryWrites: true,
  retryReads: true,
  
  // Application name for monitoring
  appName: 'zabala-gailetak-api',
  
  // Use new URL parser
  useNewUrlParser: true,
  useUnifiedTopology: true
};

// Add authentication if credentials are provided
if (process.env.MONGODB_USER && process.env.MONGODB_PASSWORD) {
  options.auth = {
    username: process.env.MONGODB_USER,
    password: process.env.MONGODB_PASSWORD
  };
  options.authSource = process.env.MONGODB_AUTH_SOURCE || 'admin';
}

// Enable debug mode in development
if (NODE_ENV === 'development') {
  mongoose.set('debug', true);
}

/**
 * Connect to MongoDB
 */
const connectDatabase = async () => {
  try {
    console.log(`[Database] Connecting to MongoDB...`);
    console.log(`[Database] Environment: ${NODE_ENV}`);
    
    await mongoose.connect(MONGODB_URI, options);
    
    console.log(`[Database] ✓ Successfully connected to MongoDB`);
    console.log(`[Database] Database: ${mongoose.connection.name}`);
    console.log(`[Database] Host: ${mongoose.connection.host}`);
    
    // Set up event listeners
    setupEventListeners();
    
    return mongoose.connection;
  } catch (error) {
    console.error('[Database] ✗ Failed to connect to MongoDB:', error.message);
    
    if (error.name === 'MongoServerError' && error.code === 18) {
      console.error('[Database] Authentication failed. Please check MONGODB_USER and MONGODB_PASSWORD');
    }
    
    // In production, exit the process if we can't connect to the database
    if (NODE_ENV === 'production') {
      console.error('[Database] Exiting process due to database connection failure');
      process.exit(1);
    }
    
    throw error;
  }
};

/**
 * Disconnect from MongoDB
 */
const disconnectDatabase = async () => {
  try {
    await mongoose.disconnect();
    console.log('[Database] ✓ Disconnected from MongoDB');
  } catch (error) {
    console.error('[Database] ✗ Error disconnecting from MongoDB:', error.message);
    throw error;
  }
};

/**
 * Set up event listeners for connection monitoring
 */
const setupEventListeners = () => {
  const db = mongoose.connection;
  
  db.on('error', (error) => {
    console.error('[Database] Connection error:', error.message);
  });
  
  db.on('disconnected', () => {
    console.warn('[Database] Disconnected from MongoDB');
    
    // Attempt to reconnect in development
    if (NODE_ENV === 'development') {
      console.log('[Database] Attempting to reconnect...');
    }
  });
  
  db.on('reconnected', () => {
    console.log('[Database] ✓ Reconnected to MongoDB');
  });
  
  db.on('close', () => {
    console.log('[Database] Connection closed');
  });
};

/**
 * Check database connection health
 */
const checkConnection = () => {
  const state = mongoose.connection.readyState;
  const states = {
    0: 'disconnected',
    1: 'connected',
    2: 'connecting',
    3: 'disconnecting'
  };
  
  return {
    state: states[state] || 'unknown',
    isConnected: state === 1,
    host: mongoose.connection.host,
    name: mongoose.connection.name
  };
};

/**
 * Get database statistics
 */
const getDatabaseStats = async () => {
  try {
    if (mongoose.connection.readyState !== 1) {
      return { error: 'Not connected to database' };
    }
    
    const admin = mongoose.connection.db.admin();
    const dbStats = await mongoose.connection.db.stats();
    
    return {
      database: mongoose.connection.name,
      collections: dbStats.collections,
      dataSize: (dbStats.dataSize / 1024 / 1024).toFixed(2) + ' MB',
      storageSize: (dbStats.storageSize / 1024 / 1024).toFixed(2) + ' MB',
      indexes: dbStats.indexes,
      indexSize: (dbStats.indexSize / 1024 / 1024).toFixed(2) + ' MB',
      documents: dbStats.objects
    };
  } catch (error) {
    console.error('[Database] Error getting stats:', error.message);
    return { error: error.message };
  }
};

/**
 * Create initial indexes for all models
 */
const createIndexes = async () => {
  try {
    console.log('[Database] Creating indexes...');
    
    const models = mongoose.modelNames();
    for (const modelName of models) {
      const model = mongoose.model(modelName);
      await model.createIndexes();
      console.log(`[Database] ✓ Created indexes for ${modelName}`);
    }
    
    console.log('[Database] ✓ All indexes created successfully');
  } catch (error) {
    console.error('[Database] ✗ Error creating indexes:', error.message);
    throw error;
  }
};

/**
 * Drop database (use with caution - only for development)
 */
const dropDatabase = async () => {
  if (NODE_ENV === 'production') {
    throw new Error('Cannot drop database in production environment');
  }
  
  try {
    console.warn('[Database] WARNING: Dropping database...');
    await mongoose.connection.dropDatabase();
    console.log('[Database] ✓ Database dropped');
  } catch (error) {
    console.error('[Database] ✗ Error dropping database:', error.message);
    throw error;
  }
};

/**
 * Graceful shutdown handler
 */
const gracefulShutdown = async (signal) => {
  console.log(`[Database] ${signal} received. Closing database connection...`);
  
  try {
    await disconnectDatabase();
    console.log('[Database] ✓ Database connection closed gracefully');
    process.exit(0);
  } catch (error) {
    console.error('[Database] ✗ Error during graceful shutdown:', error.message);
    process.exit(1);
  }
};

// Handle process termination signals
process.on('SIGINT', () => gracefulShutdown('SIGINT'));
process.on('SIGTERM', () => gracefulShutdown('SIGTERM'));

// Handle uncaught exceptions
process.on('uncaughtException', (error) => {
  console.error('[Database] Uncaught exception:', error);
  gracefulShutdown('UNCAUGHT_EXCEPTION');
});

// Handle unhandled promise rejections
process.on('unhandledRejection', (reason, promise) => {
  console.error('[Database] Unhandled rejection at:', promise, 'reason:', reason);
  gracefulShutdown('UNHANDLED_REJECTION');
});

module.exports = {
  connectDatabase,
  disconnectDatabase,
  checkConnection,
  getDatabaseStats,
  createIndexes,
  dropDatabase,
  mongoose
};
