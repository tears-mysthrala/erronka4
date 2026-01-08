import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import styled from 'styled-components';
import { useAuth } from '../context/AuthContext';

const Container = styled.div`
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
`;

const Card = styled.div`
  background: white;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 400px;
  text-align: center;
`;

const Title = styled.h1`
  color: #333;
  margin-bottom: 10px;
  font-size: 28px;
`;

const Subtitle = styled.p`
  color: #666;
  margin-bottom: 30px;
`;

const Form = styled.form`
  display: flex;
  flex-direction: column;
  gap: 15px;
`;

const Input = styled.input`
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 24px;
  text-align: center;
  letter-spacing: 8px;
  font-weight: bold;
  transition: border-color 0.3s;

  &:focus {
    outline: none;
    border-color: #667eea;
  }
`;

const Button = styled.button`
  padding: 12px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
  }

  &:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
  }
`;

const Error = styled.div`
  background: #fee;
  color: #c33;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 15px;
`;

const Success = styled.div`
  background: #efe;
  color: #3c3;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 15px;
`;

const Info = styled.div`
  background: #eef;
  color: #33c;
  padding: 15px;
  border-radius: 5px;
  margin-bottom: 20px;
  font-size: 14px;
  line-height: 1.6;
`;

const MFA = () => {
  const [token, setToken] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);
  const { verifyMFA: authVerifyMFA } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    setLoading(true);

    try {
      const result = await authVerifyMFA(token);
      
      if (result.success) {
        setSuccess('MFA balidazioa arrakastatsua!');
        setTimeout(() => {
          navigate('/dashboard');
        }, 1500);
      }
    } catch (err) {
      setError(err.message || 'MFA kodea baliogabea');
    } finally {
      setLoading(false);
    }
  };

  return (
    <Container>
      <Card>
        <Title>MFA Balidazioa</Title>
        <Subtitle>Autentikatzaile aplikazioko kodea sartu</Subtitle>
        
        <Info>
          Zure autentikatzaile aplikaziotik (Google Authenticator, Authy, etab.)
          6 digituko kodea lortu eta beheko kutxan sartu.
        </Info>
        
        {error && <Error>{error}</Error>}
        {success && <Success>{success}</Success>}
        
        <Form onSubmit={handleSubmit}>
          <Input
            type="text"
            placeholder="000000"
            value={token}
            onChange={(e) => setToken(e.target.value.replace(/\D/g, '').slice(0, 6))}
            maxLength={6}
            required
            autoFocus
          />
          
          <Button type="submit" disabled={loading}>
            {loading ? 'Balidatzen...' : 'Balidatu'}
          </Button>
        </Form>
      </Card>
    </Container>
  );
};

export default MFA;