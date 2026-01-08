import React, { createContext, useContext, useState, useEffect } from 'react';
import { apiService } from '../services/api';
import Cookies from 'js-cookie';

const AuthContext = createContext();

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [mfaRequired, setMfaRequired] = useState(false);
  const [userId, setUserId] = useState(null);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    try {
      const token = Cookies.get('auth_token');
      if (token) {
        apiService.setAuthToken(token);
        setUser({ authenticated: true });
      }
    } catch (error) {
      console.error('Auth check failed:', error);
    } finally {
      setLoading(false);
    }
  };

  const login = async (username, password) => {
    try {
      const response = await apiService.login(username, password);
      
      if (response.requiresMFA) {
        setMfaRequired(true);
        setUserId(response.userId);
        return { requiresMFA: true, userId: response.userId };
      }
      
      if (response.token) {
        Cookies.set('auth_token', response.token, { 
          secure: true, 
          sameSite: 'strict',
          expires: 1
        });
        apiService.setAuthToken(response.token);
        setUser({ authenticated: true, id: response.userId });
        setMfaRequired(false);
        return { success: true };
      }
    } catch (error) {
      throw error;
    }
  };

  const verifyMFA = async (token) => {
    try {
      const response = await apiService.verifyMFA(token);
      
      if (response.token) {
        Cookies.set('auth_token', response.token, { 
          secure: true, 
          sameSite: 'strict',
          expires: 1
        });
        apiService.setAuthToken(response.token);
        setUser({ authenticated: true });
        setMfaRequired(false);
        return { success: true };
      }
    } catch (error) {
      throw error;
    }
  };

  const logout = () => {
    Cookies.remove('auth_token');
    apiService.setAuthToken(null);
    setUser(null);
    setMfaRequired(false);
    setUserId(null);
  };

  const value = {
    user,
    loading,
    mfaRequired,
    userId,
    login,
    verifyMFA,
    logout,
    isAuthenticated: !!user
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};