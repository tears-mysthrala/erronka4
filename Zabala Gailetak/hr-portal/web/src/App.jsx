import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';
import styled, { createGlobalStyle } from 'styled-components';
import LoginPage from './pages/LoginPage';
import EmployeeList from './pages/EmployeeList';
import EmployeeForm from './pages/EmployeeForm';
import EmployeeDetail from './pages/EmployeeDetail';

const GlobalStyle = createGlobalStyle`
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen',
      'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue',
      sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    background-color: #f5f5f5;
  }
`;

const AppContainer = styled.div`
  min-height: 100vh;
`;

const Navbar = styled.nav`
  background-color: #0066cc;
  color: white;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
`;

const NavTitle = styled.h1`
  font-size: 1.5rem;
  font-weight: 600;
`;

const NavUser = styled.div`
  display: flex;
  gap: 1rem;
  align-items: center;
`;

const LogoutButton = styled.button`
  padding: 0.5rem 1rem;
  background-color: rgba(255, 255, 255, 0.2);
  color: white;
  border: 1px solid white;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.875rem;
  
  &:hover {
    background-color: rgba(255, 255, 255, 0.3);
  }
`;

const PrivateRoute = ({ children }) => {
  const { user, loading } = useAuth();

  if (loading) {
    return <div>Kargatzen...</div>;
  }

  return user ? children : <Navigate to="/login" />;
};

const AppContent = () => {
  const { user, logout } = useAuth();

  return (
    <AppContainer>
      {user && (
        <Navbar>
          <NavTitle>Zabala Gailetak - HR Portal</NavTitle>
          <NavUser>
            <span>{user.email}</span>
            <LogoutButton onClick={logout}>Itxi Saioa</LogoutButton>
          </NavUser>
        </Navbar>
      )}

      <Routes>
        <Route path="/login" element={<LoginPage />} />
        <Route
          path="/employees"
          element={
            <PrivateRoute>
              <EmployeeList />
            </PrivateRoute>
          }
        />
        <Route
          path="/employees/new"
          element={
            <PrivateRoute>
              <EmployeeForm />
            </PrivateRoute>
          }
        />
        <Route
          path="/employees/:id"
          element={
            <PrivateRoute>
              <EmployeeDetail />
            </PrivateRoute>
          }
        />
        <Route
          path="/employees/:id/edit"
          element={
            <PrivateRoute>
              <EmployeeForm />
            </PrivateRoute>
          }
        />
        <Route path="/" element={<Navigate to="/employees" />} />
      </Routes>
    </AppContainer>
  );
};

const App = () => {
  return (
    <>
      <GlobalStyle />
      <BrowserRouter>
        <AuthProvider>
          <AppContent />
        </AuthProvider>
      </BrowserRouter>
    </>
  );
};

export default App;
