# Web Application Implementation - Complete Guide

## Overview

Zabala Gailetak web application is a modern, secure React-based e-commerce platform built with the same security standards as the mobile app.

## Architecture

```
Web App (React)
    ↓ HTTPS + JWT + MFA
Backend API (Express)
    ↓
Database (MongoDB)
    ↓
SIEM (ELK Stack)
```

## Features Implemented

### 1. Authentication & Security
- Login with username/password
- MFA (TOTP) support using Speakeasy
- JWT token-based authentication
- Secure cookie handling (HttpOnly, SameSite=Strict)
- CSRF protection
- XSS prevention with DOMPurify
- Input sanitization

### 2. Core Functionality
- Product catalog with pricing
- Order creation with validation
- User dashboard with statistics
- MFA enable/disable functionality
- Responsive design (mobile-friendly)

### 3. Security Features
- Content Security Policy (CSP)
- Helmet.js security headers
- Rate limiting on API
- Input validation (express-validator)
- SQL injection prevention
- XSS protection
- CORS configuration
- HTTPS enforcement

## Project Structure

```
src/web/
├── app/
│   ├── pages/
│   │   ├── Login.js           # Login page with gradient design
│   │   ├── MFA.js            # MFA verification page
│   │   ├── Products.js        # Product catalog
│   │   ├── Order.js          # Order creation
│   │   └── Dashboard.js      # User dashboard
│   ├── context/
│   │   └── AuthContext.js    # Authentication state management
│   ├── services/
│   │   └── api.js           # API client with interceptors
│   ├── styles/
│   │   └── global.css       # Global styles
│   ├── index.js             # App entry point
│   └── package.json         # Web app dependencies
└── index.html              # HTML template
```

## Installation

### 1. Install Dependencies

```bash
cd "Zabala Gailetak/src/web/app"
npm install
```

Or install from root:

```bash
cd "Zabala Gailetak"
npm install
```

### 2. Environment Configuration

Create `.env` file in the root:

```env
REACT_APP_API_URL=http://localhost:3000/api
REACT_APP_MFA_ENABLED=true
```

### 3. Development Server

```bash
npm run web:start
```

The app will be available at `http://localhost:3001`

### 4. Production Build

```bash
npm run web:build
```

Output: `dist/web/bundle.[hash].js`

## Usage Guide

### Login Flow

1. Navigate to `/login`
2. Enter username and password
3. If MFA is enabled, redirected to `/mfa`
4. Enter 6-digit TOTP code
5. Redirected to dashboard

### Product Browsing

1. Navigate to `/products` after login
2. Browse available products
3. View pricing and descriptions
4. Click "Eskaera Egin" to order

### Creating Orders

1. Select a product from catalog
2. Fill in order form:
   - Quantity (1-10)
   - Full name
   - Email address
   - Shipping address
3. Review total price
4. Submit order
5. Receive order confirmation

### Dashboard Features

- View statistics (products, orders, revenue)
- Quick actions (products, orders, profile)
- MFA management (enable/disable)
- User information

## Security Implementation

### 1. API Client Security

```javascript
// Auto-injects JWT token
// Handles 401 errors
// CSRF token injection
// Input sanitization

apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  config.headers['X-CSRF-Token'] = getCsrfToken();
  return config;
});
```

### 2. Input Sanitization

```javascript
// DOMPurify for XSS prevention
// Strips malicious code
// Preserves safe HTML

const sanitizeInput = (data) => {
  if (typeof data === 'string') {
    return DOMPurify.sanitize(data.trim());
  }
  // ... object handling
};
```

### 3. Auth Context Security

```javascript
// Secure token storage
// Auto-logout on 401
// Cookie management
// Token expiration handling

const login = async (username, password) => {
  const response = await apiService.login(username, password);
  if (response.token) {
    Cookies.set('auth_token', response.token, { 
      secure: true, 
      sameSite: 'strict',
      expires: 1
    });
  }
};
```

## Styling Architecture

### Styled Components

```javascript
// Component-scoped styles
// Theme variables
// Responsive design
// Hover states

const Button = styled.button`
  padding: 12px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
  }
`;
```

## Webpack Configuration

### Production Optimizations

- Code splitting (vendors, common chunks)
- Minification
- Source maps
- Bundle analysis
- CSP injection
- Compression

### Development Features

- Hot Module Replacement (HMR)
- Source maps
- CORS headers
- History API fallback
- Fast refresh

## Testing

### Unit Tests

```bash
cd src/web/app
npm test
```

### E2E Tests

```bash
# Requires Cypress or Playwright setup
npm run test:e2e
```

## Deployment

### 1. Build for Production

```bash
npm run web:build
```

### 2. Serve Static Files

With Nginx:

```nginx
server {
    listen 443 ssl;
    server_name web.zabala-gailetak.com;
    
    ssl_certificate /etc/ssl/cert.pem;
    ssl_certificate_key /etc/ssl/key.pem;
    
    root /var/www/zabala-gailetak/dist/web;
    index index.html;
    
    location / {
        try_files $uri $uri/ /index.html;
    }
    
    location /api {
        proxy_pass http://api:3000;
    }
}
```

### 3. Docker Deployment

```dockerfile
FROM node:18-alpine AS builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run web:build

FROM nginx:alpine
COPY --from=builder /app/dist/web /usr/share/nginx/html
COPY nginx/web.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
```

## Performance Optimization

### 1. Bundle Size

- Code splitting by route
- Tree shaking
- Dead code elimination
- Vendor chunking

### 2. Caching

- Browser caching (Cache-Control headers)
- Service Worker (PWA support)
- CDN distribution
- Static asset optimization

### 3. Loading Performance

- Lazy loading routes
- Image optimization
- Font optimization
- Minification

## Browser Compatibility

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Accessibility

- ARIA labels
- Keyboard navigation
- Screen reader support
- Color contrast compliance (WCAG AA)
- Focus indicators

## Monitoring & Analytics

### 1. Error Tracking

```javascript
// Log errors to API
window.addEventListener('error', (event) => {
  apiService.logError({
    message: event.message,
    stack: event.error?.stack,
    url: window.location.href
  });
});
```

### 2. Performance Monitoring

```javascript
// Page load times
// API response times
// User interaction tracking
```

## Maintenance

### Regular Updates

- Weekly dependency checks
- Monthly security patches
- Quarterly feature updates
- Annual major version upgrades

### Backup & Recovery

- Database backups (daily)
- Application backups (weekly)
- Disaster recovery testing (monthly)

## Troubleshooting

### Common Issues

1. **Login fails**: Check API connection, verify JWT secret
2. **MFA not working**: Verify time sync, check TOTP app
3. **Build fails**: Clear node_modules, reinstall dependencies
4. **Styles not loading**: Check webpack configuration, verify CSS loaders

### Debug Mode

```javascript
// Enable debug logging
localStorage.setItem('debug', 'zabala-gailetak:*');
```

## Security Checklist

- [x] HTTPS enabled
- [x] CSP headers configured
- [x] XSS protection (DOMPurify)
- [x] CSRF tokens
- [x] Secure cookies
- [x] Input validation
- [x] Rate limiting
- [x] MFA implemented
- [x] JWT authentication
- [x] Password hashing
- [x] Error handling
- [x] Logging

## Future Enhancements

- PWA (Progressive Web App)
- WebSocket real-time updates
- Payment gateway integration
- Advanced search & filtering
- User reviews & ratings
- Order tracking
- Wishlist functionality
- Multi-language support
- Dark mode theme
- Analytics dashboard

## Support

For issues or questions:
- Documentation: `IMPLEMENTATION_SUMMARY.md`
- API Docs: `src/api/middleware/auth.js`
- Security SOP: `security/web_hardening_sop.md`
- CI/CD: `.github/workflows/ci-cd.yml`

## License

ISC