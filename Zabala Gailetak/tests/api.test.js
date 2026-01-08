const request = require('supertest');
const app = require('../../src/api/app');

describe('API Endpoints', () => {
  describe('GET /', () => {
    it('should return API info', async () => {
      const response = await request(app).get('/');
      expect(response.status).toBe(200);
      expect(response.body).toHaveProperty('message');
      expect(response.body).toHaveProperty('status', 'active');
      expect(response.body).toHaveProperty('security', 'enabled');
    });
  });

  describe('GET /api/health', () => {
    it('should return health status', async () => {
      const response = await request(app).get('/api/health');
      expect(response.status).toBe(200);
      expect(response.body).toHaveProperty('status', 'healthy');
      expect(response.body).toHaveProperty('timestamp');
    });
  });

  describe('GET /api/products', () => {
    it('should return list of products', async () => {
      const response = await request(app).get('/api/products');
      expect(response.status).toBe(200);
      expect(Array.isArray(response.body)).toBe(true);
      expect(response.body.length).toBeGreaterThan(0);
      expect(response.body[0]).toHaveProperty('id');
      expect(response.body[0]).toHaveProperty('name');
      expect(response.body[0]).toHaveProperty('price');
    });
  });

  describe('POST /api/orders', () => {
    it('should create a new order with valid data', async () => {
      const orderData = {
        productId: 1,
        quantity: 2,
        customerEmail: 'test@example.com',
        customerName: 'Test User'
      };

      const response = await request(app)
        .post('/api/orders')
        .send(orderData);
      
      expect(response.status).toBe(201);
      expect(response.body).toHaveProperty('message');
      expect(response.body).toHaveProperty('orderId');
    });

    it('should return 400 for invalid productId', async () => {
      const orderData = {
        productId: -1,
        quantity: 2,
        customerEmail: 'test@example.com',
        customerName: 'Test User'
      };

      const response = await request(app)
        .post('/api/orders')
        .send(orderData);
      
      expect(response.status).toBe(400);
      expect(response.body).toHaveProperty('errors');
    });

    it('should return 400 for invalid email', async () => {
      const orderData = {
        productId: 1,
        quantity: 2,
        customerEmail: 'invalid-email',
        customerName: 'Test User'
      };

      const response = await request(app)
        .post('/api/orders')
        .send(orderData);
      
      expect(response.status).toBe(400);
      expect(response.body).toHaveProperty('errors');
    });

    it('should return 400 for quantity exceeding limit', async () => {
      const orderData = {
        productId: 1,
        quantity: 101,
        customerEmail: 'test@example.com',
        customerName: 'Test User'
      };

      const response = await request(app)
        .post('/api/orders')
        .send(orderData);
      
      expect(response.status).toBe(400);
      expect(response.body).toHaveProperty('errors');
    });
  });

  describe('POST /api/auth/register', () => {
    it('should register a new user', async () => {
      const userData = {
        username: `testuser_${Date.now()}`,
        email: `test${Date.now()}@example.com`,
        password: 'Test123456'
      };

      const response = await request(app)
        .post('/api/auth/register')
        .send(userData);
      
      expect(response.status).toBe(201);
      expect(response.body).toHaveProperty('message');
      expect(response.body).toHaveProperty('token');
      expect(response.body).toHaveProperty('userId');
    });

    it('should return 400 for invalid username', async () => {
      const userData = {
        username: 'ab',
        email: 'test@example.com',
        password: 'Test123456'
      };

      const response = await request(app)
        .post('/api/auth/register')
        .send(userData);
      
      expect(response.status).toBe(400);
      expect(response.body).toHaveProperty('errors');
    });

    it('should return 400 for weak password', async () => {
      const userData = {
        username: 'testuser',
        email: 'test@example.com',
        password: 'weak'
      };

      const response = await request(app)
        .post('/api/auth/register')
        .send(userData);
      
      expect(response.status).toBe(400);
      expect(response.body).toHaveProperty('errors');
    });
  });

  describe('POST /api/auth/login', () => {
    it('should login with valid credentials', async () => {
      const userData = {
        username: 'testuser_login',
        email: 'testlogin@example.com',
        password: 'Test123456'
      };

      await request(app)
        .post('/api/auth/register')
        .send(userData);

      const loginData = {
        username: 'testuser_login',
        password: 'Test123456'
      };

      const response = await request(app)
        .post('/api/auth/login')
        .send(loginData);
      
      expect(response.status).toBe(200);
      expect(response.body).toHaveProperty('token');
      expect(response.body).toHaveProperty('userId');
    });

    it('should return 401 for invalid credentials', async () => {
      const loginData = {
        username: 'nonexistent',
        password: 'wrongpassword'
      };

      const response = await request(app)
        .post('/api/auth/login')
        .send(loginData);
      
      expect(response.status).toBe(401);
      expect(response.body).toHaveProperty('error');
    });
  });
});