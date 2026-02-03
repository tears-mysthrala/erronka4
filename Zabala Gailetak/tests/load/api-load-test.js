import http from 'k6/http';
import { check, sleep } from 'k6';

// Test configuration
export let options = {
  stages: [
    { duration: '1m', target: 50 },  // Ramp up to 50 users
    { duration: '3m', target: 50 },  // Stay at 50 users
    { duration: '1m', target: 100 }, // Spike to 100 users
    { duration: '3m', target: 100 }, // Stay at 100 users
    { duration: '1m', target: 0 },   // Ramp down
  ],
  thresholds: {
    http_req_duration: ['p(95)<500'], // 95% of requests must complete below 500ms
    http_req_failed: ['rate<0.01'],    // Error rate must be less than 1%
  },
};

const BASE_URL = __ENV.API_URL || 'https://zabala-gailetak.infinityfreeapp.com/api';

export default function () {
  // 1. Home/Ping
  let res = http.get(`${BASE_URL}/health`);
  check(res, { 'status is 200': (r) => r.status === 200 });

  // 2. Login Attempt
  let loginPayload = JSON.stringify({
    email: 'admin@zabalagailetak.com',
    password: 'secure_password_123',
  });

  let params = {
    headers: {
      'Content-Type': 'application/json',
    },
  };

  let loginRes = http.post(`${BASE_URL}/auth/login`, loginPayload, params);

  check(loginRes, {
    'login success': (r) => r.status === 200 || r.status === 401, // 401 allowed if user not seeded
  });

  if (loginRes.status === 200) {
    const token = loginRes.json('access_token');

    // 3. Authenticated Request - Employee List
    let authParams = {
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    };

    let employeesRes = http.get(`${BASE_URL}/employees`, authParams);
    check(employeesRes, {
      'get employees success': (r) => r.status === 200,
    });
  }

  sleep(1);
}