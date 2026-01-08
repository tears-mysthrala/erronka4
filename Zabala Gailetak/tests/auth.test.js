const { 
  register, 
  login, 
  setupMFA, 
  verifyMFA, 
  disableMFA,
  authMiddleware,
  users 
} = require('../../src/api/middleware/auth');
const bcrypt = require('bcryptjs');

describe('Authentication Service', () => {
  beforeEach(() => {
    users.length = 0;
  });

  describe('register', () => {
    const mockReq = {
      body: {
        username: 'testuser',
        email: 'test@example.com',
        password: 'Test123456'
      }
    };

    const mockRes = {
      status: jest.fn().mockReturnThis(),
      json: jest.fn()
    };

    it('should register a new user', async () => {
      await register(mockReq, mockRes);
      
      expect(mockRes.status).toHaveBeenCalledWith(201);
      expect(mockRes.json).toHaveBeenCalledWith(
        expect.objectContaining({
          message: 'Erabiltzailea ondo sortu da',
          userId: expect.any(Number)
        })
      );
      expect(users.length).toBe(1);
    });

    it('should hash password', async () => {
      await register(mockReq, mockRes);
      
      const user = users[0];
      const isMatch = await bcrypt.compare('Test123456', user.password);
      expect(isMatch).toBe(true);
      expect(user.password).not.toBe('Test123456');
    });

    it('should reject duplicate username', async () => {
      await register(mockReq, mockRes);
      
      const newReq = {
        body: {
          username: 'testuser',
          email: 'different@example.com',
          password: 'Test123456'
        }
      };

      await register(newReq, mockRes);
      
      expect(mockRes.status).toHaveBeenCalledWith(400);
      expect(mockRes.json).toHaveBeenCalledWith(
        expect.objectContaining({
          error: 'Erabiltzailea jada existitzen da'
        })
      );
    });
  });

  describe('login', () => {
    const mockUser = {
      id: 1,
      username: 'testuser',
      email: 'test@example.com',
      password: '',
      mfaEnabled: false
    };

    beforeEach(async () => {
      mockUser.password = await bcrypt.hash('Test123456', 10);
      users.push(mockUser);
    });

    const mockRes = {
      status: jest.fn().mockReturnThis(),
      json: jest.fn()
    };

    it('should login with valid credentials', async () => {
      const mockReq = {
        body: {
          username: 'testuser',
          password: 'Test123456'
        }
      };

      await login(mockReq, mockRes);
      
      expect(mockRes.json).toHaveBeenCalledWith(
        expect.objectContaining({
          token: expect.any(String),
          userId: 1
        })
      );
    });

    it('should return 401 for invalid password', async () => {
      const mockReq = {
        body: {
          username: 'testuser',
          password: 'wrongpassword'
        }
      };

      await login(mockReq, mockRes);
      
      expect(mockRes.status).toHaveBeenCalledWith(401);
      expect(mockRes.json).toHaveBeenCalledWith(
        expect.objectContaining({
          error: expect.any(String)
        })
      );
    });

    it('should require MFA if enabled', async () => {
      mockUser.mfaEnabled = true;

      const mockReq = {
        body: {
          username: 'testuser',
          password: 'Test123456'
        }
      };

      await login(mockReq, mockRes);
      
      expect(mockRes.json).toHaveBeenCalledWith(
        expect.objectContaining({
          requiresMFA: true,
          userId: 1
        })
      );
    });
  });

  describe('authMiddleware', () => {
    it('should pass with valid token', () => {
      const mockReq = {
        header: jest.fn().mockReturnValue('Bearer valid_token_here')
      };
      const mockRes = {
        status: jest.fn().mockReturnThis(),
        json: jest.fn()
      };
      const mockNext = jest.fn();

      authMiddleware(mockReq, mockRes, mockNext);
      
      expect(mockNext).toHaveBeenCalled();
    });

    it('should reject without token', () => {
      const mockReq = {
        header: jest.fn().mockReturnValue(null)
      };
      const mockRes = {
        status: jest.fn().mockReturnThis(),
        json: jest.fn()
      };
      const mockNext = jest.fn();

      authMiddleware(mockReq, mockRes, mockNext);
      
      expect(mockRes.status).toHaveBeenCalledWith(401);
      expect(mockNext).not.toHaveBeenCalled();
    });
  });
});