import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import styled from 'styled-components';
import { useAuth } from '../context/AuthContext';
import { getProducts } from '../services/api';

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
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
`;

const ProductCard = styled.div`
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
  }
`;

const ProductImage = styled.div`
  height: 200px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 48px;
`;

const ProductInfo = styled.div`
  padding: 20px;
`;

const ProductName = styled.h3`
  color: #333;
  margin: 0 0 10px 0;
  font-size: 18px;
`;

const ProductDescription = styled.p`
  color: #666;
  margin: 0 0 15px 0;
  font-size: 14px;
  line-height: 1.6;
`;

const ProductPrice = styled.div`
  font-size: 24px;
  font-weight: bold;
  color: #667eea;
  margin-bottom: 15px;
`;

const OrderButton = styled.button`
  width: 100%;
  padding: 12px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.2s;

  &:hover {
    transform: translateY(-2px);
  }
`;

const Loading = styled.div`
  text-align: center;
  padding: 40px;
  color: #666;
  font-size: 18px;
`;

const Error = styled.div`
  background: #fee;
  color: #c33;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  margin: 20px 0;
`;

const Products = () => {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const { logout } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    loadProducts();
  }, []);

  const loadProducts = async () => {
    try {
      const data = await getProducts();
      setProducts(data);
    } catch (err) {
      setError('Produktuak lortzea errorea');
    } finally {
      setLoading(false);
    }
  };

  const handleOrder = (product) => {
    navigate(`/order/${product.id}`, { state: { product } });
  };

  if (loading) {
    return (
      <Container>
        <Loading>Kargatzen...</Loading>
      </Container>
    );
  }

  return (
    <Container>
      <Header>
        <Title>Zabala Gailetak - Produktuak</Title>
        <LogoutButton onClick={logout}>Saioa Itxi</LogoutButton>
      </Header>

      {error && <Error>{error}</Error>}

      <Grid>
        {products.map((product) => (
          <ProductCard key={product.id}>
            <ProductImage>🍪</ProductImage>
            <ProductInfo>
              <ProductName>{product.name}</ProductName>
              <ProductDescription>
                Kalitatezko gaileta tradizionala, osagai naturalekin egina.
              </ProductDescription>
              <ProductPrice>€{product.price.toFixed(2)}</ProductPrice>
              <OrderButton onClick={() => handleOrder(product)}>
                Eskaera Egin
              </OrderButton>
            </ProductInfo>
          </ProductCard>
        ))}
      </Grid>
    </Container>
  );
};

export default Products;