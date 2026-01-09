import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '2m', target: 100 },
    { duration: '5m', target: 100 },
    { duration: '2m', target: 200 },
    { duration: '5m', target: 200 },
    { duration: '2m', target: 0 },
  ],
  thresholds: {
    http_req_duration: ['p(95)<500'],
    http_req_failed: ['rate<0.01'],
  },
};

export default function () {
  let loginRes = http.post('http://localhost:3000/api/auth/login', {
    username: 'loadtest',
    password: 'LoadTest123!'
  });
  
  check(loginRes, {
    'login status is 200': (r) => r.status === 200,
    'login response time < 500ms': (r) => r.timings.duration < 500,
  });
  
  let token = loginRes.json('token');
  
  let productsRes = http.get('http://localhost:3000/api/products', {
    headers: { Authorization: `Bearer ${token}` }
  });
  
  check(productsRes, {
    'products status is 200': (r) => r.status === 200,
  });
  
  sleep(1);
}
