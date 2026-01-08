import React, { useState, useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import styled from 'styled-components';
import { createOrder } from '../services/api';

const Container = styled.div`
  max-width: 800px;
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

const BackButton = styled.button`
  padding: 10px 20px;
  background: #666;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  transition: background 0.3s;

  &:hover {
    background: #555;
  }
`;

const Card = styled.div`
  background: white;
  border-radius: 10px;
  padding: 30px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
`;

const ProductSummary = styled.div`
  background: #f8f8f8;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
`;

const ProductName = styled.h2`
  color: #333;
  margin: 0 0 10px 0;
  font-size: 24px;
`;

const ProductPrice = styled.div`
  font-size: 32px;
  font-weight: bold;
  color: #667eea;
`;

const Form = styled.form`
  display: flex;
  flex-direction: column;
  gap: 20px;
`;

const FormGroup = styled.div`
  display: flex;
  flex-direction: column;
  gap: 8px;
`;

const Label = styled.label`
  font-weight: bold;
  color: #333;
  font-size: 14px;
`;

const Input = styled.input`
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
  transition: border-color 0.3s;

  &:focus {
    outline: none;
    border-color: #667eea;
  }
`;

const Select = styled.select`
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
  background: white;
`;

const TotalSection = styled.div`
  background: #f8f8f8;
  padding: 20px;
  border-radius: 8px;
  margin-top: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
`;

const TotalLabel = styled.div`
  font-size: 18px;
  font-weight: bold;
  color: #333;
`;

const TotalValue = styled.div`
  font-size: 36px;
  font-weight: bold;
  color: #667eea;
`;

const Button = styled.button`
  padding: 15px 30px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
  align-self: flex-start;

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
  padding: 15px;
  border-radius: 8px;
  margin-top: 20px;
`;

const Success = styled.div`
  background: #efe;
  color: #3c3;
  padding: 15px;
  border-radius: 8px;
  margin-top: 20px;
`;

const Order = () => {
  const [quantity, setQuantity] = useState(1);
  const [customerName, setCustomerName] = useState('');
  const [customerEmail, setCustomerEmail] = useState('');
  const [shippingAddress, setShippingAddress] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);
  
  const navigate = useNavigate();
  const location = useLocation();
  const product = location.state?.product;

  useEffect(() => {
    if (!product) {
      navigate('/products');
    }
  }, [product, navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    setLoading(true);

    try {
      const orderData = {
        productId: product.id,
        quantity: parseInt(quantity),
        customerName,
        customerEmail,
        shippingAddress
      };
      
      const response = await createOrder(orderData);
      setSuccess(`Eskaera #${response.orderId} ondo jaso da!`);
      
      setTimeout(() => {
        navigate('/products');
      }, 3000);
    } catch (err) {
      setError(err.response?.data?.errors?.[0]?.msg || err.message || 'Eskaera sortzea errorea');
    } finally {
      setLoading(false);
    }
  };

  if (!product) {
    return null;
  }

  const total = product.price * quantity;

  return (
    <Container>
      <Header>
        <Title>Eskaera Berria</Title>
        <BackButton onClick={() => navigate('/products')}>
          ← Produktuak
        </BackButton>
      </Header>

      <Card>
        <ProductSummary>
          <ProductName>{product.name}</ProductName>
          <ProductPrice>€{product.price.toFixed(2)}</ProductPrice>
        </ProductSummary>

        {error && <Error>{error}</Error>}
        {success && <Success>{success}</Success>}

        <Form onSubmit={handleSubmit}>
          <FormGroup>
            <Label>Kantitatea:</Label>
            <Select
              value={quantity}
              onChange={(e) => setQuantity(parseInt(e.target.value))}
              required
            >
              {[...Array(10).keys()].map(i => (
                <option key={i + 1} value={i + 1}>
                  {i + 1}
                </option>
              ))}
            </Select>
          </FormGroup>

          <FormGroup>
            <Label>Izen osoa:</Label>
            <Input
              type="text"
              value={customerName}
              onChange={(e) => setCustomerName(e.target.value)}
              required
              placeholder="Zure izena eta abizenak"
              minLength={2}
              maxLength={100}
            />
          </FormGroup>

          <FormGroup>
            <Label>Emaila:</Label>
            <Input
              type="email"
              value={customerEmail}
              onChange={(e) => setCustomerEmail(e.target.value)}
              required
              placeholder="email@example.com"
            />
          </FormGroup>

          <FormGroup>
            <Label>Helbidea:</Label>
            <Input
              type="text"
              value={shippingAddress}
              onChange={(e) => setShippingAddress(e.target.value)}
              required
              placeholder="Zure helbidea"
              minLength={10}
              maxLength={200}
            />
          </FormGroup>

          <TotalSection>
            <TotalLabel>Guztira:</TotalLabel>
            <TotalValue>€{total.toFixed(2)}</TotalValue>
          </TotalSection>

          <Button type="submit" disabled={loading}>
            {loading ? 'Bidaltzen...' : 'Eskaera Bidali'}
          </Button>
        </Form>
      </Card>
    </Container>
  );
};

export default Order;