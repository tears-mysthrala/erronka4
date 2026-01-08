# Zabala Gailetak API Documentation

**Version:** 1.0  
**Base URL:** `https://api.zabala-gailetak.com/api`  
**Authentication:** JWT Bearer Token + MFA

---

## Table of Contents

1. [Authentication](#1-authentication)
2. [Products](#2-products)
3. [Orders](#3-orders)
4. [System](#4-system)
5. [Error Codes](#5-error-codes)
6. [Rate Limiting](#6-rate-limiting)
7. [Security](#7-security)

---

## 1. Authentication

### 1.1 Register New User

Creates a new user account.

**Endpoint:** `POST /auth/register`  
**Authentication:** None required  
**Rate Limit:** 5 requests / 15 minutes

**Request Body:**

```json
{
  "username": "johndoe",
  "email": "john@example.com",
  "password": "SecurePass123!"
}
```

**Request Validation:**

| Field | Type | Required | Validation |
|-------|------|----------|-------------|
| username | string | Yes | 3-30 characters, alphanumeric |
| email | string | Yes | Valid email format |
| password | string | Yes | Minimum 8 characters |

**Success Response (201):**

```json
{
  "message": "Erabiltzailea ondo sortu da",
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "userId": 1
}
```

**Error Response (400):**

```json
{
  "errors": [
    {
      "msg": "Username must be between 3 and 30 characters",
      "param": "username",
      "location": "body"
    }
  ]
}
```

**Error Response (409):**

```json
{
  "error": "Erabiltzailea jada existitzen da"
}
```

---

### 1.2 Login

Authenticates a user and returns a JWT token.

**Endpoint:** `POST /auth/login`  
**Authentication:** None required  
**Rate Limit:** 5 requests / 15 minutes

**Request Body:**

```json
{
  "username": "johndoe",
  "password": "SecurePass123!"
}
```

**Success Response with MFA (200):**

```json
{
  "requiresMFA": true,
  "userId": 1,
  "message": "MFA kodea behar da"
}
```

**Success Response without MFA (200):**

```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "userId": 1
}
```

**Error Response (401):**

```json
{
  "error": "Erabiltzailea edo pasahitza okerra"
}
```

---

### 1.3 Setup MFA

Initiates MFA setup for authenticated user.

**Endpoint:** `POST /auth/mfa/setup`  
**Authentication:** Required (Bearer Token)  
**Rate Limit:** 3 requests / hour

**Request Headers:**

```http
Authorization: Bearer <JWT_TOKEN>
```

**Success Response (200):**

```json
{
  "secret": "JBSWY3DPEHPK3PXP",
  "qrCode": "data:image/png;base64,iVBORw0KGgo...",
  "message": "Eskaneatu QR kodea autentikatzaile aplikazioarekin"
}
```

**Error Response (400):**

```json
{
  "error": "MFA jada gaituta dago"
}
```

---

### 1.4 Verify MFA

Verifies MFA TOTP code and completes authentication.

**Endpoint:** `POST /auth/mfa/verify`  
**Authentication:** Required (Bearer Token)  
**Rate Limit:** 10 requests / 15 minutes

**Request Headers:**

```http
Authorization: Bearer <JWT_TOKEN>
```

**Request Body:**

```json
{
  "token": "123456"
}
```

**Request Validation:**

| Field | Type | Required | Validation |
|-------|------|----------|-------------|
| token | string | Yes | 6 digits, numeric |

**Success Response (200):**

```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "message": "MFA balidazioa arrakastatsua"
}
```

**Error Response (401):**

```json
{
  "error": "MFA kodea baliogabea"
}
```

---

### 1.5 Disable MFA

Disables MFA for authenticated user.

**Endpoint:** `POST /auth/mfa/disable`  
**Authentication:** Required (Bearer Token)  
**Rate Limit:** 1 request / hour

**Request Headers:**

```http
Authorization: Bearer <JWT_TOKEN>
```

**Success Response (200):**

```json
{
  "message": "MFA desgaitu da"
}
```

---

## 2. Products

### 2.1 Get All Products

Retrieves list of all available products.

**Endpoint:** `GET /products`  
**Authentication:** Optional  
**Rate Limit:** 50 requests / 15 minutes

**Success Response (200):**

```json
[
  {
    "id": 1,
    "name": "Gaileta Tradizionalak",
    "price": 2.50,
    "description": "Betiko zaporea, osagai naturalekin egina.",
    "category": "Cookies",
    "stock": 100
  },
  {
    "id": 2,
    "name": "Txokolatezko Gailetak",
    "price": 3.00,
    "description": "Txokolate beltz onenarekin estaliak.",
    "category": "Chocolate",
    "stock": 75
  },
  {
    "id": 3,
    "name": "Zereal Gailetak",
    "price": 2.80,
    "description": "Zereal zaporearekin, osasuntsuak.",
    "category": "Cookies",
    "stock": 50
  }
]
```

**Pagination (Future):**

`GET /products?page=1&limit=10&sort=name&order=asc`

---

### 2.2 Get Product by ID (Future)

Retrieves specific product details.

**Endpoint:** `GET /products/:id`  
**Authentication:** Optional

**Success Response (200):**

```json
{
  "id": 1,
  "name": "Gaileta Tradizionalak",
  "price": 2.50,
  "description": "Betiko zaporea, osagai naturalekin egina.",
  "category": "Cookies",
  "stock": 100,
  "images": [
    "https://cdn.zabala-gailetak.com/products/1/image1.jpg",
    "https://cdn.zabala-gailetak.com/products/1/image2.jpg"
  ],
  "createdAt": "2024-01-08T10:00:00Z",
  "updatedAt": "2024-01-08T10:00:00Z"
}
```

**Error Response (404):**

```json
{
  "error": "Produktua ez da aurkitu"
}
```

---

## 3. Orders

### 3.1 Create Order

Creates a new order for authenticated user.

**Endpoint:** `POST /orders`  
**Authentication:** Required (Bearer Token)  
**Rate Limit:** 25 requests / 15 minutes

**Request Headers:**

```http
Authorization: Bearer <JWT_TOKEN>
Content-Type: application/json
```

**Request Body:**

```json
{
  "productId": 1,
  "quantity": 2,
  "customerEmail": "john@example.com",
  "customerName": "John Doe",
  "shippingAddress": "123 Main St, City, Country"
}
```

**Request Validation:**

| Field | Type | Required | Validation |
|-------|------|----------|-------------|
| productId | integer | Yes | Must exist |
| quantity | integer | Yes | 1-100 |
| customerEmail | string | Yes | Valid email |
| customerName | string | Yes | 2-100 characters |
| shippingAddress | string | Yes | 10-500 characters |

**Success Response (201):**

```json
{
  "message": "Eskaera ondo jaso da",
  "orderId": 1234,
  "orderDate": "2024-01-08T10:30:00Z",
  "estimatedDelivery": "2024-01-10T00:00:00Z",
  "total": 5.00
}
```

**Error Response (400):**

```json
{
  "errors": [
    {
      "msg": "Quantity must be between 1 and 100",
      "param": "quantity",
      "location": "body"
    }
  ]
}
```

**Error Response (422):**

```json
{
  "error": "Ez dago nahiko stock"
}
```

---

### 3.2 Get User Orders (Future)

Retrieves orders for authenticated user.

**Endpoint:** `GET /orders`  
**Authentication:** Required (Bearer Token)

**Request Headers:**

```http
Authorization: Bearer <JWT_TOKEN>
```

**Success Response (200):**

```json
{
  "orders": [
    {
      "id": 1234,
      "productId": 1,
      "productName": "Gaileta Tradizionalak",
      "quantity": 2,
      "total": 5.00,
      "status": "pending",
      "createdAt": "2024-01-08T10:30:00Z"
    }
  ],
  "total": 1,
  "page": 1,
  "limit": 10
}
```

---

### 3.3 Get Order by ID (Future)

Retrieves specific order details.

**Endpoint:** `GET /orders/:id`  
**Authentication:** Required (Bearer Token)

**Success Response (200):**

```json
{
  "id": 1234,
  "productId": 1,
  "productName": "Gaileta Tradizionalak",
  "quantity": 2,
  "total": 5.00,
  "status": "shipped",
  "customerName": "John Doe",
  "customerEmail": "john@example.com",
  "shippingAddress": "123 Main St, City, Country",
  "trackingNumber": "ZG123456789",
  "createdAt": "2024-01-08T10:30:00Z",
  "updatedAt": "2024-01-08T14:00:00Z"
}
```

---

## 4. System

### 4.1 Health Check

Returns API health status.

**Endpoint:** `GET /health`  
**Authentication:** None required

**Success Response (200):**

```json
{
  "status": "healthy",
  "timestamp": "2024-01-08T10:00:00Z",
  "version": "1.0.0",
  "services": {
    "database": "up",
    "cache": "up",
    "queue": "up"
  }
}
```

**Error Response (503):**

```json
{
  "status": "unhealthy",
  "error": "Service unavailable"
}
```

---

### 4.2 API Info

Returns API information.

**Endpoint:** `GET /`  
**Authentication:** None required

**Success Response (200):**

```json
{
  "message": "Zabala Gailetak API - Bertsioa 1.0",
  "status": "active",
  "security": "enabled",
  "version": "1.0.0",
  "documentation": "https://docs.zabala-gailetak.com"
}
```

---

## 5. Error Codes

### 5.1 HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 201 | Created - Resource created |
| 400 | Bad Request - Invalid input |
| 401 | Unauthorized - Authentication required or failed |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource not found |
| 409 | Conflict - Resource already exists |
| 422 | Unprocessable Entity - Business logic error |
| 429 | Too Many Requests - Rate limit exceeded |
| 500 | Internal Server Error - Server error |
| 503 | Service Unavailable - Service down |

### 5.2 Error Response Format

**Validation Error (400):**

```json
{
  "errors": [
    {
      "msg": "Error message",
      "param": "parameter_name",
      "location": "body|query|params"
    }
  ]
}
```

**Business Error (422):**

```json
{
  "error": "Error message",
  "code": "STOCK_UNAVAILABLE",
  "details": {
    "available": 5,
    "requested": 10
  }
}
```

**Server Error (500):**

```json
{
  "error": "Zerbitzariaren errorea",
  "requestId": "req_abc123"
}
```

---

## 6. Rate Limiting

### 6.1 Rate Limit Rules

| Endpoint | Limit | Window |
|----------|-------|--------|
| POST /auth/register | 5 | 15 minutes |
| POST /auth/login | 5 | 15 minutes |
| POST /auth/mfa/verify | 10 | 15 minutes |
| GET /products | 50 | 15 minutes |
| POST /orders | 25 | 15 minutes |
| Others | 100 | 15 minutes |

### 6.2 Rate Limit Headers

**Response Headers:**

```http
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1704729600
```

### 6.3 Rate Limit Error (429)

```json
{
  "error": "Eskari gehiegi jaso dira IP honetatik, mesedez saiatu berriro geroago.",
  "retryAfter": 900
}
```

---

## 7. Security

### 7.1 Authentication

**JWT Token Format:**

```http
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

**Token Claims:**

```json
{
  "userId": "12345",
  "username": "johndoe",
  "mfaVerified": true,
  "iat": 1704729600,
  "exp": 1704733200
}
```

**Token Expiration:** 1 hour

### 7.2 Security Headers

**Response Headers:**

```http
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'
Referrer-Policy: strict-origin-when-cross-origin
```

### 7.3 CORS

**Allowed Origins:**
- `https://zabala-gailetak.com`
- `https://www.zabala-gailetak.com`

**Allowed Methods:**
- GET, POST, PUT, DELETE, OPTIONS

**Allowed Headers:**
- Content-Type, Authorization, X-Requested-With

### 7.4 Input Validation

All input is validated and sanitized:
- SQL injection prevention
- XSS prevention
- CSRF protection
- Command injection prevention

### 7.5 Encryption

- **Password**: bcrypt (cost factor: 10)
- **MFA Secret**: Encrypted at rest
- **TLS**: TLS 1.2 / TLS 1.3
- **Ciphers**: HIGH security cipher suites

---

## Appendix A: Testing Examples

### cURL Examples

**Register:**

```bash
curl -X POST https://api.zabala-gailetak.com/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"username":"johndoe","email":"john@example.com","password":"SecurePass123!"}'
```

**Login:**

```bash
curl -X POST https://api.zabala-gailetak.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"johndoe","password":"SecurePass123!"}'
```

**Get Products:**

```bash
curl https://api.zabala-gailetak.com/api/products
```

**Create Order:**

```bash
curl -X POST https://api.zabala-gailetak.com/api/orders \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <JWT_TOKEN>" \
  -d '{"productId":1,"quantity":2,"customerEmail":"john@example.com","customerName":"John Doe","shippingAddress":"123 Main St"}'
```

---

## Appendix B: SDK Examples

### JavaScript (Axios)

```javascript
import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'https://api.zabala-gailetak.com/api',
  timeout: 10000
});

// Login
const login = async (username, password) => {
  const response = await apiClient.post('/auth/login', {
    username,
    password
  });
  
  if (response.data.token) {
    apiClient.defaults.headers.common[
      'Authorization'
    ] = `Bearer ${response.data.token}`;
  }
  
  return response.data;
};

// Get Products
const getProducts = async () => {
  const response = await apiClient.get('/products');
  return response.data;
};

// Create Order
const createOrder = async (orderData) => {
  const response = await apiClient.post('/orders', orderData);
  return response.data;
};
```

---

## Appendix C: Changelog

### Version 1.0 (2024-01-08)
- Initial API release
- Authentication endpoints
- Products endpoints
- Orders endpoints
- MFA support
- Rate limiting
- Security headers

---

**Document Version:** 1.0  
**Last Updated:** 2024-01-08  
**Maintained By:** Zabala Gailetak Development Team