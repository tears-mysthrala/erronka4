import axios from 'axios';
import DOMPurify from 'dompurify';

const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:3000/api';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
});

apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token') || document.cookie.match(/auth_token=([^;]+)/)?.[1];
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  
  config.headers['X-CSRF-Token'] = getCsrfToken();
  
  return config;
}, (error) => {
  return Promise.reject(error);
});

apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token');
      document.cookie = 'auth_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

const getCsrfToken = () => {
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  return token || '';
};

const sanitizeInput = (data) => {
  if (typeof data === 'string') {
    return DOMPurify.sanitize(data.trim());
  }
  if (typeof data === 'object' && data !== null) {
    const sanitized = {};
    Object.keys(data).forEach(key => {
      sanitized[key] = sanitizeInput(data[key]);
    });
    return sanitized;
  }
  return data;
};

const login = async (username, password) => {
  try {
    const response = await apiClient.post('/auth/login', { 
      username: sanitizeInput(username), 
      password 
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.error || 'Login errorea');
  }
};

const verifyMFA = async (token) => {
  try {
    const response = await apiClient.post('/auth/mfa/verify', { 
      token: sanitizeInput(token) 
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.error || 'MFA balidazio errorea');
  }
};

const getProducts = async () => {
  try {
    const response = await apiClient.get('/products');
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.error || 'Produktuak lortzea errorea');
  }
};

const createOrder = async (orderData) => {
  try {
    const sanitizedOrder = sanitizeInput(orderData);
    const response = await apiClient.post('/orders', sanitizedOrder);
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.error || 'Eskaera sortzea errorea');
  }
};

const setupMFA = async () => {
  try {
    const response = await apiClient.post('/auth/mfa/setup');
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.error || 'MFA konfigurazio errorea');
  }
};

const disableMFA = async () => {
  try {
    const response = await apiClient.post('/auth/mfa/disable');
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.error || 'MFA desgaitze errorea');
  }
};

const setAuthToken = (token) => {
  if (token) {
    localStorage.setItem('auth_token', token);
  } else {
    localStorage.removeItem('auth_token');
  }
};

export {
  apiClient,
  login,
  verifyMFA,
  getProducts,
  createOrder,
  setupMFA,
  disableMFA,
  setAuthToken
};