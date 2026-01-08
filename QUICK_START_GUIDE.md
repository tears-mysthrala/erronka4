# Zabala Gailetak - Quick Start Guide

**Get up and running in 15 minutes!**

---

## 📋 Prerequisites Checklist

Before you begin, ensure you have:

- [ ] **Node.js** 18+ installed
- [ ] **Docker** 20+ and Docker Compose 2.0+
- [ ] **Git** installed
- [ ] 4GB+ RAM available
- [ ] 50GB+ disk space
- [ ] Internet connection

---

## 🚀 5-Minute Quick Start

### 1. Clone Repository

```bash
git clone <repository-url>
cd erronkak
```

### 2. Environment Setup

```bash
cd "Zabala Gailetak"
cp .env.example .env
```

Edit `.env` file with your settings:

```env
NODE_ENV=development
PORT=3000
JWT_SECRET=your-secure-secret-key-here
MONGODB_URI=mongodb://localhost:27017/zabala-gailetak
ALLOWED_ORIGINS=http://localhost:3001,http://localhost:3000
```

### 3. Start All Services

```bash
# Build and start API, Database, Redis, Nginx
docker-compose up -d

# Check services are running
docker-compose ps
```

### 4. Install Web App Dependencies

```bash
cd src/web/app
npm install
```

### 5. Start Applications

**Terminal 1 - API:**
```bash
cd "Zabala Gailetak"
npm run dev
```

**Terminal 2 - Web App:**
```bash
cd "Zabala Gailetak/src/web/app"
npm start
```

**Terminal 3 - Mobile App (Optional):**
```bash
cd "Zabala Gailetak/src/mobile"
npm start
```

### 6. Access Applications

- **API:** http://localhost:3000
- **Web App:** http://localhost:3001
- **Kibana (SIEM):** http://localhost:5601
- **API Health:** http://localhost:3000/api/health

---

## 📱 Application Access

### Web Application

**URL:** http://localhost:3001

**Features:**
- Login with username/password
- MFA verification
- Product catalog
- Order placement
- User dashboard

### Mobile Application

**Setup (Android):**
```bash
cd "Zabala Gailetak/src/mobile"
npm install
npm run android
```

**Setup (iOS - macOS only):**
```bash
cd "Zabala Gailetak/src/mobile"
cd ios && pod install && cd ..
npm run ios
```

### API Endpoints

**Base URL:** http://localhost:3000/api

**Available Endpoints:**
- `POST /auth/register` - Register user
- `POST /auth/login` - Login
- `POST /auth/mfa/verify` - Verify MFA
- `GET /products` - Get products
- `POST /orders` - Create order
- `GET /health` - Health check

---

## 🔐 First Login

### 1. Register New User

```bash
curl -X POST http://localhost:3000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "admin",
    "email": "admin@zabala.com",
    "password": "Admin123!"
  }'
```

**Response:**
```json
{
  "message": "Erabiltzailea ondo sortu da",
  "token": "eyJhbGci...",
  "userId": 1
}
```

### 2. Setup MFA (Optional)

**Via Web App:**
1. Login to web app
2. Go to Dashboard
3. Click "MFA Gaitu"
4. Scan QR code with Google Authenticator

**Via API:**
```bash
curl -X POST http://localhost:3000/api/auth/mfa/setup \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 3. Create Test Order

```bash
curl -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "productId": 1,
    "quantity": 2,
    "customerEmail": "customer@example.com",
    "customerName": "Test Customer"
  }'
```

---

## 🛠️ Development Tools

### API Development

```bash
cd "Zabala Gailetak"

# Start with auto-reload
npm run dev

# Run tests
npm test

# Run tests in watch mode
npm run test:watch

# Lint code
npm run lint

# Security audit
npm run audit
```

### Web App Development

```bash
cd "Zabala Gailetak/src/web/app"

# Start development server
npm start

# Build for production
npm run build

# Lint code
npm run lint
```

### Mobile App Development

```bash
cd "Zabala Gailetak/src/mobile"

# Run on Android
npm run android

# Run on iOS (macOS only)
npm run ios

# Run tests
npm test
```

### Docker Operations

```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f

# Restart services
docker-compose restart

# Rebuild services
docker-compose build
```

---

## 📊 Monitoring

### API Health Check

```bash
curl http://localhost:3000/api/health
```

**Expected Response:**
```json
{
  "status": "healthy",
  "timestamp": "2024-01-08T10:00:00Z"
}
```

### Database Status

```bash
# Check MongoDB
docker exec -it zabala-gailetak-mongodb mongosh

# In MongoDB shell
db.adminCommand("ping")
```

### Redis Status

```bash
# Check Redis
docker exec -it zabala-gailetak-redis redis-cli ping

# Expected: PONG
```

### SIEM (Kibana)

**URL:** http://localhost:5601

**Login:**
- Username: `elastic`
- Password: Check `.env` file

---

## 🧪 Testing

### Run All Tests

```bash
cd "Zabala Gailetak"

# API tests
npm test

# With coverage
npm test -- --coverage
```

### Test Coverage Report

```bash
# Generate coverage report
npm test -- --coverage

# View report
open coverage/lcov-report/index.html
```

### Manual Testing

**Test API:**
```bash
# Get products
curl http://localhost:3000/api/products

# Login
curl -X POST http://localhost:3000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"Admin123!"}'
```

**Test Web App:**
1. Navigate to http://localhost:3001
2. Login with credentials
3. Browse products
4. Create order
5. Check dashboard

---

## 🔧 Troubleshooting

### Common Issues

#### Issue 1: Port Already in Use

**Error:** `Error: listen EADDRINUSE: address already in use :::3000`

**Solution:**
```bash
# Find process using port 3000
lsof -i :3000

# Kill process
kill -9 <PID>

# Or change port in .env
PORT=3001
```

#### Issue 2: MongoDB Connection Failed

**Error:** `MongoNetworkError: failed to connect to server`

**Solution:**
```bash
# Check MongoDB is running
docker-compose ps

# Restart MongoDB
docker-compose restart mongodb

# Check logs
docker-compose logs mongodb
```

#### Issue 3: Docker Build Fails

**Error:** Build fails with dependency errors

**Solution:**
```bash
# Clear Docker cache
docker system prune -a

# Rebuild without cache
docker-compose build --no-cache

# Pull fresh images
docker-compose pull
```

#### Issue 4: Web App Won't Start

**Error:** `Module not found` or similar

**Solution:**
```bash
cd "Zabala Gailetak/src/web/app"

# Clear node_modules
rm -rf node_modules package-lock.json

# Reinstall
npm install

# Clear cache
npm start -- --reset-cache
```

### Getting Help

1. **Check Logs:**
   ```bash
   docker-compose logs -f
   ```

2. **Verify Services:**
   ```bash
   docker-compose ps
   ```

3. **Check Documentation:**
   - `PROJECT_DOCUMENTATION.md` - Complete documentation
   - `API_DOCUMENTATION.md` - API reference
   - `WEB_APP_GUIDE.md` - Web app guide
   - `MOBILE_APP_GUIDE.md` - Mobile app guide

4. **Review SOPs:**
   - Check security SOPs in `security/` directory
   - Review deployment SOPs

---

## 📚 Next Steps

### Learn More

1. **Read Documentation:**
   - Start with `PROJECT_DOCUMENTATION.md`
   - Review `API_DOCUMENTATION.md`
   - Check app-specific guides

2. **Explore Features:**
   - Test MFA setup
   - Create sample orders
   - Review SIEM dashboard
   - Test security features

3. **Customize:**
   - Modify products
   - Adjust security settings
   - Configure rate limits
   - Set up CI/CD

### Development Workflow

```bash
# 1. Create feature branch
git checkout -b feature/my-feature

# 2. Make changes
# Edit files...

# 3. Test changes
npm test

# 4. Commit changes
git add .
git commit -m "feat: add my feature"

# 5. Push to remote
git push origin feature/my-feature

# 6. Create Pull Request
# CI/CD will run automatically
```

---

## 🎯 Configuration Checklist

After initial setup, configure these for production:

### Security

- [ ] Change JWT_SECRET
- [ ] Update MONGODB_URI
- [ ] Configure ALLOWED_ORIGINS
- [ ] Setup SSL/TLS certificates
- [ ] Enable MFA for all users
- [ ] Configure rate limits
- [ ] Setup SIEM alerts

### Performance

- [ ] Configure Redis caching
- [ ] Setup CDN for static assets
- [ ] Enable compression
- [ ] Configure load balancing
- [ ] Setup database indexes

### Monitoring

- [ ] Configure health checks
- [ ] Setup log aggregation
- [ ] Configure alerts
- [ ] Setup uptime monitoring
- [ ] Configure backup notifications

---

## 📞 Support

### Documentation

- **Project Docs:** `PROJECT_DOCUMENTATION.md`
- **API Docs:** `API_DOCUMENTATION.md`
- **Web App:** `WEB_APP_GUIDE.md`
- **Mobile App:** `MOBILE_APP_GUIDE.md`
- **Deployment:** `IMPLEMENTATION_SUMMARY.md`

### SOPs

- **Web Security:** `security/web_hardening_sop.md`
- **Mobile Security:** `security/mobile_security_sop.md`
- **Network:** `infrastructure/network/network_segmentation_sop.md`
- **Honeypot:** `security/honeypot/honeypot_implementation_sop.md`
- **Incident Response:** `security/incidents/sop_incident_response.md`

### External Resources

- **React Native:** https://reactnative.dev
- **React:** https://react.dev
- **Express.js:** https://expressjs.com
- **MongoDB:** https://www.mongodb.com/docs
- **OWASP:** https://owasp.org

---

## ✅ Verification Checklist

After setup, verify:

- [ ] API running on http://localhost:3000
- [ ] Web app running on http://localhost:3001
- [ ] MongoDB running and accessible
- [ ] Redis running and accessible
- [ ] Can register new user
- [ ] Can login with credentials
- [ ] Can view products
- [ ] Can create order
- [ ] API health check returns 200
- [ ] Docker services all up
- [ ] Kibana accessible at http://localhost:5601

---

## 🚀 Ready to Go!

You now have a fully functional Zabala Gailetak cybersecurity system running locally!

**What's Next?**
1. Explore the web app and mobile app
2. Test the security features
3. Review the SIEM dashboard
4. Read the full documentation
5. Start customizing for your needs

**Happy Developing!** 🎉

---

**Quick Start Guide Version:** 1.0  
**Last Updated:** 2024-01-08  
**For:** Zabala Gailetak Project Team