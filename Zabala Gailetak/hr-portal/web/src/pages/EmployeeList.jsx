import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import styled from 'styled-components';
import api from '../services/api';

const Container = styled.div`
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
`;

const Header = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
`;

const Title = styled.h1`
  font-size: 2rem;
  color: #333;
`;

const Button = styled.button`
  padding: 0.75rem 1.5rem;
  background-color: #0066cc;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  
  &:hover {
    background-color: #0052a3;
  }
`;

const Table = styled.table`
  width: 100%;
  border-collapse: collapse;
  background: white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  border-radius: 8px;
  overflow: hidden;
`;

const Th = styled.th`
  padding: 1rem;
  text-align: left;
  background-color: #f5f5f5;
  font-weight: 600;
  color: #333;
  border-bottom: 2px solid #ddd;
`;

const Td = styled.td`
  padding: 1rem;
  border-bottom: 1px solid #eee;
`;

const ActionButton = styled.button`
  padding: 0.5rem 1rem;
  margin-right: 0.5rem;
  background-color: ${props => props.danger ? '#dc3545' : '#6c757d'};
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.875rem;
  
  &:hover {
    opacity: 0.9;
  }
`;

const Loading = styled.div`
  text-align: center;
  padding: 3rem;
  font-size: 1.2rem;
  color: #666;
`;

const Error = styled.div`
  padding: 1rem;
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
  border-radius: 4px;
  margin-bottom: 1rem;
`;

const Badge = styled.span`
  padding: 0.25rem 0.5rem;
  background-color: ${props => props.active ? '#28a745' : '#6c757d'};
  color: white;
  border-radius: 3px;
  font-size: 0.875rem;
`;

const Pagination = styled.div`
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-top: 2rem;
`;

const PageButton = styled.button`
  padding: 0.5rem 1rem;
  background-color: ${props => props.disabled ? '#e9ecef' : '#0066cc'};
  color: ${props => props.disabled ? '#6c757d' : 'white'};
  border: none;
  border-radius: 4px;
  cursor: ${props => props.disabled ? 'not-allowed' : 'pointer'};
  
  &:hover:not(:disabled) {
    background-color: #0052a3;
  }
`;

const EmployeeList = () => {
  const [employees, setEmployees] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const navigate = useNavigate();

  useEffect(() => {
    loadEmployees();
  }, [page]);

  const loadEmployees = async () => {
    try {
      setLoading(true);
      setError(null);
      const data = await api.getEmployees({ page, limit: 10 });
      setEmployees(data.employees || []);
      setTotalPages(data.pagination?.total_pages || 1);
    } catch (err) {
      setError(err.response?.data?.error || 'Error cargando empleados');
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Seguro que quieres dar de baja a este empleado?')) {
      return;
    }
    
    try {
      await api.deleteEmployee(id);
      loadEmployees();
    } catch (err) {
      alert(err.response?.data?.error || 'Error eliminando empleado');
    }
  };

  const handleRestore = async (id) => {
    try {
      await api.restoreEmployee(id);
      loadEmployees();
    } catch (err) {
      alert(err.response?.data?.error || 'Error restaurando empleado');
    }
  };

  if (loading) {
    return <Loading>Kargatzen...</Loading>;
  }

  return (
    <Container>
      <Header>
        <Title>Langileak</Title>
        <Button onClick={() => navigate('/employees/new')}>
          + Langile Berria
        </Button>
      </Header>

      {error && <Error>{error}</Error>}

      <Table>
        <thead>
          <tr>
            <Th>Zenbakia</Th>
            <Th>Izena</Th>
            <Th>Email</Th>
            <Th>Kargua</Th>
            <Th>Egoera</Th>
            <Th>Ekintzak</Th>
          </tr>
        </thead>
        <tbody>
          {employees.map(emp => (
            <tr key={emp.id}>
              <Td>{emp.employee_number}</Td>
              <Td>{emp.first_name} {emp.last_name}</Td>
              <Td>{emp.email}</Td>
              <Td>{emp.position}</Td>
              <Td>
                <Badge active={emp.active}>
                  {emp.active ? 'Aktiboa' : 'Inaktiboa'}
                </Badge>
              </Td>
              <Td>
                <ActionButton onClick={() => navigate(`/employees/${emp.id}`)}>
                  Ikusi
                </ActionButton>
                <ActionButton onClick={() => navigate(`/employees/${emp.id}/edit`)}>
                  Editatu
                </ActionButton>
                {emp.active ? (
                  <ActionButton danger onClick={() => handleDelete(emp.id)}>
                    Ezabatu
                  </ActionButton>
                ) : (
                  <ActionButton onClick={() => handleRestore(emp.id)}>
                    Berreskuratu
                  </ActionButton>
                )}
              </Td>
            </tr>
          ))}
        </tbody>
      </Table>

      <Pagination>
        <PageButton 
          onClick={() => setPage(p => p - 1)} 
          disabled={page === 1}
        >
          ← Aurrekoa
        </PageButton>
        <span>Orria {page} / {totalPages}</span>
        <PageButton 
          onClick={() => setPage(p => p + 1)} 
          disabled={page >= totalPages}
        >
          Hurrengoa →
        </PageButton>
      </Pagination>
    </Container>
  );
};

export default EmployeeList;
