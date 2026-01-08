const express = require('express');
const helmet = require('helmet');
const cors = require('cors');
const rateLimit = require('express-rate-limit');
const mongoSanitize = require('express-mongo-sanitize');
const xss = require('xss-clean');
const hpp = require('hpp');
const compression = require('compression');
const morgan = require('morgan');
const { body, validationResult } = require('express-validator');
const { register, login, setupMFA, verifyMFA, disableMFA, authMiddleware } = require('./middleware/auth');
require('dotenv').config();

const app = express();
const port = process.env.PORT || 3000;

app.use(helmet({
  contentSecurityPolicy: process.env.HELMET_CONTENT_SECURITY_POLICY === 'true',
  hsts: {
    maxAge: process.env.HELMET_HSTS_MAX_AGE || 31536000,
    includeSubDomains: true,
    preload: true
  }
}));

const corsOptions = {
  origin: process.env.ALLOWED_ORIGINS ? process.env.ALLOWED_ORIGINS.split(',') : '*',
  credentials: true,
  optionsSuccessStatus: 200
};
app.use(cors(corsOptions));

const limiter = rateLimit({
  windowMs: parseInt(process.env.RATE_LIMIT_WINDOW_MS) || 900000,
  max: parseInt(process.env.RATE_LIMIT_MAX_REQUESTS) || 100,
  message: 'Eskari gehiegi jaso dira IP honetatik, mesedez saiatu berriro geroago.'
});
app.use('/api/', limiter);

app.use(express.json({ limit: '10kb' }));
app.use(express.urlencoded({ extended: true, limit: '10kb' }));

app.use(mongoSanitize());

app.use(xss());

app.use(hpp());

app.use(compression());

if (process.env.NODE_ENV !== 'production') {
  app.use(morgan('dev'));
}

app.get('/', (req, res) => {
  res.json({ 
    message: 'Zabala Gailetak API - Bertsioa 1.0',
    status: 'active',
    security: 'enabled'
  });
});

app.get('/api/health', (req, res) => {
  res.json({ status: 'healthy', timestamp: new Date().toISOString() });
});

app.post('/api/auth/register', [
  body('username').trim().isLength({ min: 3, max: 30 }).withMessage('Username must be between 3 and 30 characters'),
  body('email').isEmail().normalizeEmail().withMessage('Valid email is required'),
  body('password').isLength({ min: 8 }).withMessage('Password must be at least 8 characters')
], (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ errors: errors.array() });
  }
  register(req, res);
});

app.post('/api/auth/login', [
  body('username').trim().notEmpty().withMessage('Username is required'),
  body('password').notEmpty().withMessage('Password is required')
], (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ errors: errors.array() });
  }
  login(req, res);
});

app.post('/api/auth/mfa/setup', authMiddleware, setupMFA);

app.post('/api/auth/mfa/verify', authMiddleware, [
  body('token').trim().isLength({ min: 6, max: 6 }).withMessage('Token must be 6 digits')
], (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ errors: errors.array() });
  }
  verifyMFA(req, res);
});

app.post('/api/auth/mfa/disable', authMiddleware, disableMFA);

app.get('/api/products', (req, res) => {
  res.json([
    { id: 1, name: 'Gaileta Tradizionalak', price: 2.50 },
    { id: 2, name: 'Txokolatezko Gailetak', price: 3.00 },
    { id: 3, name: 'Zereal Gailetak', price: 2.80 }
  ]);
});

app.post('/api/orders', [
  body('productId').isInt({ min: 1 }).withMessage('Product ID must be a positive integer'),
  body('quantity').isInt({ min: 1, max: 100 }).withMessage('Quantity must be between 1 and 100'),
  body('customerEmail').isEmail().normalizeEmail().withMessage('Valid email is required'),
  body('customerName').trim().isLength({ min: 2, max: 100 }).withMessage('Name must be between 2 and 100 characters')
], (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ errors: errors.array() });
  }

  console.log('Eskaera berria jaso da:', req.body);
  res.status(201).json({ 
    message: 'Eskaera ondo jaso da', 
    orderId: Math.floor(Math.random() * 1000) 
  });
});

app.use((req, res) => {
  res.status(404).json({ error: 'Bidea ez da aurkitu' });
});

app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(err.status || 500).json({ 
    error: process.env.NODE_ENV === 'production' ? 'Zerbitzariaren errorea' : err.message 
  });
});

app.listen(port, () => {
  console.log(`Zabala Gailetak API entzuten: http://localhost:${port}`);
  console.log(`Segurtasun neurriak gaituta: Helmet, CORS, Rate Limiting, Sanitization`);
});