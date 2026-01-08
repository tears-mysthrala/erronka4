const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const speakeasy = require('speakeasy');
const QRCode = require('qrcode');

const users = [];

const generateToken = (userId) => {
  return jwt.sign({ userId }, process.env.JWT_SECRET, {
    expiresIn: process.env.JWT_EXPIRES_IN || '1h'
  });
};

const register = async (req, res) => {
  try {
    const { username, email, password } = req.body;
    
    const existingUser = users.find(u => u.username === username || u.email === email);
    if (existingUser) {
      return res.status(400).json({ error: 'Erabiltzailea jada existitzen da' });
    }

    const hashedPassword = await bcrypt.hash(password, 10);
    const user = {
      id: users.length + 1,
      username,
      email,
      password: hashedPassword,
      mfaEnabled: false,
      mfaSecret: null
    };

    users.push(user);
    const token = generateToken(user.id);

    res.status(201).json({ 
      message: 'Erabiltzailea ondo sortu da',
      token,
      userId: user.id
    });
  } catch (error) {
    res.status(500).json({ error: 'Errorea erregistratzerakoan' });
  }
};

const login = async (req, res) => {
  try {
    const { username, password } = req.body;
    
    const user = users.find(u => u.username === username);
    if (!user) {
      return res.status(401).json({ error: 'Erabiltzailea edo pasahitza okerra' });
    }

    const isValidPassword = await bcrypt.compare(password, user.password);
    if (!isValidPassword) {
      return res.status(401).json({ error: 'Erabiltzailea edo pasahitza okerra' });
    }

    if (user.mfaEnabled) {
      return res.json({ 
        requiresMFA: true, 
        userId: user.id,
        message: 'MFA kodea behar da'
      });
    }

    const token = generateToken(user.id);
    res.json({ token, userId: user.id });
  } catch (error) {
    res.status(500).json({ error: 'Errorea saioa hastean' });
  }
};

const setupMFA = async (req, res) => {
  try {
    const { userId } = req;
    const user = users.find(u => u.id === userId);
    
    if (!user) {
      return res.status(404).json({ error: 'Erabiltzailea ez da aurkitu' });
    }

    if (user.mfaEnabled) {
      return res.status(400).json({ error: 'MFA jada gaituta dago' });
    }

    const secret = speakeasy.generateSecret({
      name: `ZabalaGailetak (${user.username})`,
      issuer: process.env.MFA_ISSUER || 'ZabalaGailetak'
    });

    user.mfaSecret = secret.base32;
    
    const qrCodeUrl = await QRCode.toDataURL(secret.otpauth_url);

    res.json({
      secret: secret.base32,
      qrCode: qrCodeUrl,
      message: 'Eskaneatu QR kodea autentikatzaile aplikazioarekin'
    });
  } catch (error) {
    res.status(500).json({ error: 'Errorea MFA konfiguratzean' });
  }
};

const verifyMFA = async (req, res) => {
  try {
    const { userId } = req;
    const { token } = req.body;
    
    const user = users.find(u => u.id === userId);
    if (!user || !user.mfaSecret) {
      return res.status(404).json({ error: 'MFA ez dago konfiguratuta' });
    }

    const verified = speakeasy.totp.verify({
      secret: user.mfaSecret,
      encoding: 'base32',
      token
    });

    if (verified) {
      if (!user.mfaEnabled) {
        user.mfaEnabled = true;
        return res.json({ 
          message: 'MFA ondo gaitu da',
          enabled: true
        });
      }
      
      const authToken = generateToken(user.id);
      res.json({ token: authToken, message: 'MFA balidazioa arrakastatsua' });
    } else {
      res.status(401).json({ error: 'MFA kodea baliogabea' });
    }
  } catch (error) {
    res.status(500).json({ error: 'Errorea MFA balidatzean' });
  }
};

const disableMFA = async (req, res) => {
  try {
    const { userId } = req;
    const user = users.find(u => u.id === userId);
    
    if (!user) {
      return res.status(404).json({ error: 'Erabiltzailea ez da aurkitu' });
    }

    user.mfaEnabled = false;
    user.mfaSecret = null;

    res.json({ message: 'MFA desgaitu da' });
  } catch (error) {
    res.status(500).json({ error: 'Errorea MFA desgaitzean' });
  }
};

const authMiddleware = (req, res, next) => {
  const token = req.header('Authorization')?.replace('Bearer ', '');
  
  if (!token) {
    return res.status(401).json({ error: 'Sarbidea ukatua: tokenik ez' });
  }

  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    req.userId = decoded.userId;
    next();
  } catch (error) {
    res.status(401).json({ error: 'Token baliogabea' });
  }
};

module.exports = {
  register,
  login,
  setupMFA,
  verifyMFA,
  disableMFA,
  authMiddleware,
  users
};