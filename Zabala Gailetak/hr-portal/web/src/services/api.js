import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8080/api';

class ApiClient {
  constructor() {
    this.client = axios.create({
      baseURL: API_BASE_URL,
      headers: {
        'Content-Type': 'application/json'
      }
    });

    // Request interceptor para agregar token
    this.client.interceptors.request.use(
      (config) => {
        const token = localStorage.getItem('token');
        if (token) {
          config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
      },
      (error) => Promise.reject(error)
    );

    // Response interceptor para manejar errores
    this.client.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response?.status === 401) {
          localStorage.removeItem('token');
          window.location.href = '/login';
        }
        return Promise.reject(error);
      }
    );
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

  // Auth
  async login(email, password) {
    const response = await this.client.post('/auth/login', { email, password });
    if (response.data.token) {
      localStorage.setItem('token', response.data.token);
    }
    return response.data;
  }

  async logout() {
    await this.client.post('/auth/logout');
    localStorage.removeItem('token');
  }

  async getMe() {
    const response = await this.client.get('/auth/me');
    return response.data;
  }
}

export default new ApiClient();
