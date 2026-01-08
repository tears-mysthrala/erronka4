import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import Login from './pages/Login';
import MFA from './pages/MFA';
import Products from './pages/Products';
import Order from './pages/Order';
import Dashboard from './pages/Dashboard';
import './styles/global.css';

const root = ReactDOM.createRoot(document.getElementById('root'));

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Navigate to="/login" />} />
          <Route path="/login" element={<Login />} />
          <Route path="/mfa" element={<MFA />} />
          <Route path="/dashboard" element={<Dashboard />} />
          <Route path="/products" element={<Products />} />
          <Route path="/order/:productId" element={<Order />} />
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  );
}

root.render(<App />);