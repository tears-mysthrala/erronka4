import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import styled from 'styled-components';
import { useAuth } from '../context/AuthContext';
import { getProducts, setupMFA, disableMFA } from '../services/api';

const Container = styled.div`
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
`;

const Header = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 2px solid #eee;
`;

const Title = styled.h1`
  color: #333;
  margin: 0;
`;

const UserSection = styled.div`
  display: flex;
  align-items: center;
  gap: 20px;
`;

const UserInfo = styled.div`
  color: #666;
  font-size: 14px;
`;

const LogoutButton = styled.button`
  padding: 10px 20px;
  background: #c33;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  transition: background 0.3s;

  &:hover {
    background: #a22;
  }
`;

const Grid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
`;

const StatCard = styled.div`
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
`;

const StatValue = styled.div`
  font-size: 48px;
  font-weight: bold;
  color: #667eea;
  margin-bottom: 10px;
`;

const StatLabel = styled.div`
  color: #666;
  font-size: 16px;
`;

const Section = styled.div`
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
`;

const SectionTitle = styled.h2`
  color: #333;
  margin: 0 0 20px 0;
  font-size: 24px;
`;

const QuickActions = styled.div`
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
`;

const ActionButton = styled.button`
  padding: 15px 25px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
  display: flex;
  align-items: center;
  gap: 10px;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
  }
`;

const SecondaryButton = styled.button`
  padding: 15px 25px;
  background: #f8f8f8;
  color: #333;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s;
  display: flex;
  align-items: center;
  gap: 10px;

  &:hover {
    background: #e8e8e8;
    border-color: #ccc;
  }
`;

const MFAPanel = styled.div`
  background: ${props => props.enabled ? '#efe' : '#fee'};
  padding: 20px;
  border-radius: 8px;
  margin-top: 20px;
`;

const MFATitle = styled.h3`
  color: ${props => props.enabled ? '#3c3' : '#c33'};
  margin: 0 0 10px 0;
`;

const MFADescription = styled.p`
  color: #666;
  margin: 0 0 15px 0;
`;

const Loading = styled.div`
  text-align: center;
  padding: 40px;
  color: #666;
  font-size: 18px;
`;

const Dashboard = () => {
  const [stats, setStats] = useState({
    products: 0,
    orders: 0,
    revenue: 0
  });
  const [mfaEnabled, setMfaEnabled] = useState(false);
  const [mfaLoading, setMfaLoading] = useState(false);
  const [loading, setLoading] = useState(true);
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    try {
      const products = await getProducts();
      setStats({
        products: products.length,
        orders: Math.floor(Math.random() * 100),
        revenue: Math.floor(Math.random() * 10000)
      });
    } catch (error) {
      console.error('Dashboard data load error:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleToggleMFA = async () => {
    setMfaLoading(true);
    try {
      if (mfaEnabled) {
        await disableMFA();
        setMfaEnabled(false);
      } else {
        await setupMFA();
        setMfaEnabled(true);
      }
    } catch (error) {
      console.error('MFA toggle error:', error);
      alert('Errorea MFA-a aldatzean');
    } finally {
      setMfaLoading(false);
    }
  };

  if (loading) {
    return <Container><Loading>Kargatzen...</Loading></Container>;
  }

  return (
    <Container>
      <Header>
        <Title>Dashboard</Title>
        <UserSection>
          <UserInfo>Ongi etorri, {user?.username || 'Erabiltzailea'}</UserInfo>
          <LogoutButton onClick={logout}>Saioa Itxi</LogoutButton>
        </UserSection>
      </Header>

      <Grid>
        <StatCard>
          <StatValue>{stats.products}</StatValue>
          <StatLabel>Produktu Eskuragarriak</StatLabel>
        </StatCard>
        <StatCard>
          <StatValue>{stats.orders}</StatValue>
          <StatLabel>Eskaera Totalak</StatLabel>
        </StatCard>
        <StatCard>
          <StatValue>€{stats.revenue.toLocaleString()}</StatValue>
          <StatLabel>Diru Sarrerak</StatLabel>
        </StatCard>
      </Grid>

      <Section>
        <SectionTitle>Ekintza Bizkorrak</SectionTitle>
        <QuickActions>
          <ActionButton onClick={() => navigate('/products')}>
            🍪 Produktuak Ikusi
          </ActionButton>
          <SecondaryButton onClick={() => navigate('/orders')}>
            📦 Eskaerak
          </SecondaryButton>
          <SecondaryButton onClick={() => navigate('/profile')}>
            👤 Profila
          </SecondaryButton>
        </QuickActions>

        <MFAPanel enabled={mfaEnabled}>
          <MFATitle enabled={mfaEnabled}>
            {mfaEnabled ? '✓ MFA Gaituta' : '⚠ MFA Ezgaituta'}
          </MFATitle>
          <MFADescription>
            {mfaEnabled
              ? 'Zure kontua bi faktoreko autentikazioarekin babestuta dago.'
              : 'Gomendatzen dugu bi faktoreko autentikazioa gaitzea segurtasun hobetzeko.'}
          </MFADescription>
          <ActionButton onClick={handleToggleMFA} disabled={mfaLoading}>
            {mfaLoading ? 'Kargatzen...' : mfaEnabled ? 'MFA Desgaitu' : 'MFA Gaitu'}
          </ActionButton>
        </MFAPanel>
      </Section>
    </Container>
  );
};

export default Dashboard;