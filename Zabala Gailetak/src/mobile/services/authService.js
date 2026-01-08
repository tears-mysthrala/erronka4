import axios from 'axios';
import * as Keychain from 'react-native-keychain';
import NetInfo from '@react-native-community/netinfo';

const API_BASE_URL = 'http://localhost:3000/api';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
});

apiClient.interceptors.request.use(async (config) => {
  const state = await NetInfo.fetch();
  if (!state.isConnected) {
    return Promise.reject(new Error('Internet konexiorik ez'));
  }

  const credentials = await Keychain.getGenericPassword();
  if (credentials) {
    config.headers.Authorization = `Bearer ${credentials.password}`;
  }
  return config;
});

apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      await Keychain.resetGenericPassword();
    }
    return Promise.reject(error);
  }
);

const login = async (username, password) => {
  try {
    const response = await apiClient.post('/auth/login', { username, password });
    
    if (response.data.requiresMFA) {
      return response.data;
    }
    
    if (response.data.token) {
      await Keychain.setGenericPassword(username, response.data.token);
    }
    
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.error || 'Login errorea');
  }
};

const verifyMFA = async (token) => {
  try {
    const response = await apiClient.post('/auth/mfa/verify', { token });
    
    if (response.data.token) {
      const credentials = await Keychain.getGenericPassword();
      if (credentials) {
        await Keychain.setGenericPassword(credentials.username, response.data.token);
      }
    }
    
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.error || 'MFA errorea');
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
    const response = await apiClient.post('/orders', orderData);
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.error || 'Eskaera sortzea errorea');
  }
};

const logout = async () => {
  await Keychain.resetGenericPassword();
};

export {
  login,
  verifyMFA,
  getProducts,
  createOrder,
  logout,
  apiClient
};