import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Cambiar a tu IP local para desarrollo
const API_BASE_URL = 'http://192.168.1.100:8080/api';

class ApiClient {
  constructor() {
    this.client = axios.create({
      baseURL: API_BASE_URL,
      headers: {
        'Content-Type': 'application/json'
      }
    });

    // Request interceptor
    this.client.interceptors.request.use(
      async (config) => {
        const token = await AsyncStorage.getItem('token');
        if (token) {
          config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
      },
      (error) => Promise.reject(error)
    );

    // Response interceptor
    this.client.interceptors.response.use(
      (response) => response,
      async (error) => {
        if (error.response?.status === 401) {
          await AsyncStorage.removeItem('token');
        }
        return Promise.reject(error);
      }
    );
  }

  // Auth
  async login(email, password) {
    const response = await this.client.post('/auth/login', { email, password });
    if (response.data.token) {
      await AsyncStorage.setItem('token', response.data.token);
    }
    return response.data;
  }

  async logout() {
    await this.client.post('/auth/logout');
    await AsyncStorage.removeItem('token');
  }

  async getMe() {
    const response = await this.client.get('/auth/me');
    return response.data;
  }

  // Employees
  async getEmployees(params = {}) {
    const response = await this.client.get('/employees', { params });
    return response.data;
  }

  async getEmployee(id) {
    const response = await this.client.get(`/employees/${id}`);
    return response.data;
  }

  async createEmployee(data) {
    const response = await this.client.post('/employees', data);
    return response.data;
  }

  async updateEmployee(id, data) {
    const response = await this.client.put(`/employees/${id}`, data);
    return response.data;
  }

  async deleteEmployee(id) {
    const response = await this.client.delete(`/employees/${id}`);
    return response.data;
  }

  async restoreEmployee(id) {
    const response = await this.client.post(`/employees/${id}/restore`);
    return response.data;
  }

  async getEmployeeHistory(id, limit = 50) {
    const response = await this.client.get(`/employees/${id}/history`, {
      params: { limit }
    });
    return response.data;
  }
}

export default new ApiClient();
