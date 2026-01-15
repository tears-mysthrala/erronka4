import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import styled from 'styled-components';
import api from '../services/api';

const Container = styled.div`
  padding: 2rem;
  max-width: 1000px;
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

const ButtonGroup = styled.div`
  display: flex;
  gap: 0.5rem;
`;

const Button = styled.button`
  padding: 0.75rem 1.5rem;
  background-color: ${props => props.secondary ? '#6c757d' : '#0066cc'};
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  
  &:hover {
    opacity: 0.9;
  }
`;

const Card = styled.div`
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  margin-bottom: 2rem;
`;

const Section = styled.div`
  margin-bottom: 2rem;
`;

const SectionTitle = styled.h2`
  font-size: 1.5rem;
  color: #333;
  margin-bottom: 1rem;
  border-bottom: 2px solid #0066cc;
  padding-bottom: 0.5rem;
`;

const InfoGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
`;

const InfoItem = styled.div`
  padding: 0.5rem 0;
`;

const Label = styled.span`
  font-weight: 600;
  color: #666;
  display: block;
  margin-bottom: 0.25rem;
`;

const Value = styled.span`
  color: #333;
`;

const Badge = styled.span`
  padding: 0.25rem 0.75rem;
  background-color: ${props => props.active ? '#28a745' : '#6c757d'};
  color: white;
  border-radius: 3px;
  font-size: 0.875rem;
`;

const Timeline = styled.div`
  margin-top: 1rem;
`;

const TimelineItem = styled.div`
  border-left: 2px solid #0066cc;
  padding-left: 1.5rem;
  margin-bottom: 1.5rem;
  position: relative;
  
  &:before {
    content: '';
    position: absolute;
    left: -6px;
    top: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #0066cc;
  }
`;

const TimelineDate = styled.div`
  font-size: 0.875rem;
  color: #666;
  margin-bottom: 0.5rem;
`;

const TimelineAction = styled.div`
  font-weight: 600;
  color: #333;
  margin-bottom: 0.25rem;
`;

const TimelineUser = styled.div`
  font-size: 0.875rem;
  color: #666;
`;

const TimelineChanges = styled.div`
  margin-top: 0.5rem;
  padding: 0.75rem;
  background-color: #f8f9fa;
  border-radius: 4px;
  font-size: 0.875rem;
`;

const ChangeItem = styled.div`
  margin: 0.25rem 0;
  
  .old {
    color: #dc3545;
    text-decoration: line-through;
  }
  
  .new {
    color: #28a745;
    font-weight: 600;
  }
`;

const Loading = styled.div`
  text-align: center;
  padding: 3rem;
  font-size: 1.2rem;
  color: #666;
`;

const EmployeeDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [employee, setEmployee] = useState(null);
  const [history, setHistory] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadData();
  }, [id]);

  const loadData = async () => {
    try {
      const [empData, histData] = await Promise.all([
        api.getEmployee(id),
        api.getEmployeeHistory(id, 20)
      ]);
      setEmployee(empData);
      setHistory(histData.history || []);
    } catch (err) {
      console.error('Error loading data:', err);
    } finally {
      setLoading(false);
    }
  };

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('eu-ES', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  const getActionLabel = (action) => {
    const labels = {
      create: 'Sortua',
      update: 'Eguneratua',
      delete: 'Ezabatua',
      restore: 'Berreskuratua'
    };
    return labels[action] || action;
  };

  if (loading) {
    return <Loading>Kargatzen...</Loading>;
  }

  if (!employee) {
    return <div>Langilea ez da aurkitu</div>;
  }

  return (
    <Container>
      <Header>
        <Title>{employee.first_name} {employee.last_name}</Title>
        <ButtonGroup>
          <Button onClick={() => navigate(`/employees/${id}/edit`)}>
            Editatu
          </Button>
          <Button secondary onClick={() => navigate('/employees')}>
            Atzera
          </Button>
        </ButtonGroup>
      </Header>

      <Card>
        <Section>
          <SectionTitle>Informazio Orokorra</SectionTitle>
          <InfoGrid>
            <InfoItem>
              <Label>Langile Zenbakia:</Label>
              <Value>{employee.employee_number}</Value>
            </InfoItem>
            <InfoItem>
              <Label>NIF/NIE:</Label>
              <Value>{employee.nif}</Value>
            </InfoItem>
            <InfoItem>
              <Label>Email:</Label>
              <Value>{employee.email}</Value>
            </InfoItem>
            <InfoItem>
              <Label>Kargua:</Label>
              <Value>{employee.position}</Value>
            </InfoItem>
            <InfoItem>
              <Label>Telefonoa:</Label>
              <Value>{employee.phone || '-'}</Value>
            </InfoItem>
            <InfoItem>
              <Label>Egoera:</Label>
              <Badge active={employee.active}>
                {employee.active ? 'Aktiboa' : 'Inaktiboa'}
              </Badge>
            </InfoItem>
          </InfoGrid>
        </Section>

        <Section>
          <SectionTitle>Helbide Informazioa</SectionTitle>
          <InfoGrid>
            <InfoItem>
              <Label>Helbidea:</Label>
              <Value>{employee.address || '-'}</Value>
            </InfoItem>
            <InfoItem>
              <Label>Hiria:</Label>
              <Value>{employee.city || '-'}</Value>
            </InfoItem>
            <InfoItem>
              <Label>Posta Kodea:</Label>
              <Value>{employee.postal_code || '-'}</Value>
            </InfoItem>
            <InfoItem>
              <Label>Herrialdea:</Label>
              <Value>{employee.country || '-'}</Value>
            </InfoItem>
          </InfoGrid>
        </Section>

        <Section>
          <SectionTitle>Lan Informazioa</SectionTitle>
          <InfoGrid>
            <InfoItem>
              <Label>Kontratazio Data:</Label>
              <Value>{employee.hire_date || '-'}</Value>
            </InfoItem>
            <InfoItem>
              <Label>Soldata:</Label>
              <Value>{employee.salary ? `${employee.salary}€` : '-'}</Value>
            </InfoItem>
            <InfoItem>
              <Label>IBAN:</Label>
              <Value>{employee.bank_account || '-'}</Value>
            </InfoItem>
          </InfoGrid>
        </Section>
      </Card>

      <Card>
        <SectionTitle>Aldaketen Historia</SectionTitle>
        {history.length === 0 ? (
          <p>Ez dago historiarik</p>
        ) : (
          <Timeline>
            {history.map((item, index) => (
              <TimelineItem key={index}>
                <TimelineDate>{formatDate(item.created_at)}</TimelineDate>
                <TimelineAction>{getActionLabel(item.action)}</TimelineAction>
                <TimelineUser>Erabiltzailea: {item.user_email}</TimelineUser>
                {item.changed_fields && item.changed_fields.length > 0 && (
                  <TimelineChanges>
                    <strong>Aldatutako eremuak:</strong>
                    {item.changed_fields.map((field, i) => (
                      <ChangeItem key={i}>
                        {field}: 
                        {item.old_values?.[field] && (
                          <span className="old"> {JSON.stringify(item.old_values[field])}</span>
                        )}
                        {item.old_values?.[field] && item.new_values?.[field] && ' → '}
                        {item.new_values?.[field] && (
                          <span className="new">{JSON.stringify(item.new_values[field])}</span>
                        )}
                      </ChangeItem>
                    ))}
                  </TimelineChanges>
                )}
              </TimelineItem>
            ))}
          </Timeline>
        )}
      </Card>
    </Container>
  );
};

export default EmployeeDetail;
