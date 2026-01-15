import { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import styled from 'styled-components';
import api from '../services/api';

const Container = styled.div`
  padding: 2rem;
  max-width: 800px;
  margin: 0 auto;
`;

const Title = styled.h1`
  font-size: 2rem;
  color: #333;
  margin-bottom: 2rem;
`;

const Form = styled.form`
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
`;

const FormGroup = styled.div`
  margin-bottom: 1.5rem;
`;

const Label = styled.label`
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #333;
`;

const Input = styled.input`
  width: 100%;
  padding: 0.75rem;
  border: 1px solid ${props => props.error ? '#dc3545' : '#ddd'};
  border-radius: 4px;
  font-size: 1rem;
  
  &:focus {
    outline: none;
    border-color: #0066cc;
  }
`;

const Select = styled.select`
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  
  &:focus {
    outline: none;
    border-color: #0066cc;
  }
`;

const ErrorText = styled.span`
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 0.25rem;
  display: block;
`;

const ButtonGroup = styled.div`
  display: flex;
  gap: 1rem;
  margin-top: 2rem;
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
  
  &:disabled {
    background-color: #e9ecef;
    color: #6c757d;
    cursor: not-allowed;
  }
`;

const Row = styled.div`
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
`;

const Alert = styled.div`
  padding: 1rem;
  background-color: ${props => props.error ? '#f8d7da' : '#d4edda'};
  color: ${props => props.error ? '#721c24' : '#155724'};
  border: 1px solid ${props => props.error ? '#f5c6cb' : '#c3e6cb'};
  border-radius: 4px;
  margin-bottom: 1rem;
`;

const EmployeeForm = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const isEditMode = Boolean(id);

  const [formData, setFormData] = useState({
    first_name: '',
    last_name: '',
    nif: '',
    email: '',
    password: '',
    position: '',
    phone: '',
    address: '',
    city: '',
    postal_code: '',
    country: 'España',
    hire_date: '',
    salary: '',
    bank_account: ''
  });

  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState(null);

  useEffect(() => {
    if (isEditMode) {
      loadEmployee();
    }
  }, [id]);

  const loadEmployee = async () => {
    try {
      const data = await api.getEmployee(id);
      setFormData({
        first_name: data.first_name || '',
        last_name: data.last_name || '',
        nif: data.nif || '',
        email: data.email || '',
        password: '', // No cargar password
        position: data.position || '',
        phone: data.phone || '',
        address: data.address || '',
        city: data.city || '',
        postal_code: data.postal_code || '',
        country: data.country || 'España',
        hire_date: data.hire_date || '',
        salary: data.salary || '',
        bank_account: data.bank_account || ''
      });
    } catch (err) {
      setMessage({ error: true, text: 'Error cargando empleado' });
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    // Limpiar error del campo
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: null }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});
    setMessage(null);

    try {
      // No enviar password vacío en edición
      const dataToSend = { ...formData };
      if (isEditMode && !dataToSend.password) {
        delete dataToSend.password;
      }

      if (isEditMode) {
        await api.updateEmployee(id, dataToSend);
        setMessage({ error: false, text: 'Langilea eguneratu da!' });
      } else {
        await api.createEmployee(dataToSend);
        setMessage({ error: false, text: 'Langilea sortu da!' });
        setTimeout(() => navigate('/employees'), 2000);
      }
    } catch (err) {
      if (err.response?.data?.validation_errors) {
        setErrors(err.response.data.validation_errors);
      } else {
        setMessage({ 
          error: true, 
          text: err.response?.data?.error || 'Errorea gertatu da' 
        });
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <Container>
      <Title>{isEditMode ? 'Langilea Editatu' : 'Langile Berria'}</Title>

      {message && (
        <Alert error={message.error}>{message.text}</Alert>
      )}

      <Form onSubmit={handleSubmit}>
        <Row>
          <FormGroup>
            <Label>Izena *</Label>
            <Input
              name="first_name"
              value={formData.first_name}
              onChange={handleChange}
              error={errors.first_name}
              required
            />
            {errors.first_name && <ErrorText>{errors.first_name}</ErrorText>}
          </FormGroup>

          <FormGroup>
            <Label>Abizena *</Label>
            <Input
              name="last_name"
              value={formData.last_name}
              onChange={handleChange}
              error={errors.last_name}
              required
            />
            {errors.last_name && <ErrorText>{errors.last_name}</ErrorText>}
          </FormGroup>
        </Row>

        <Row>
          <FormGroup>
            <Label>NIF/NIE *</Label>
            <Input
              name="nif"
              value={formData.nif}
              onChange={handleChange}
              error={errors.nif}
              placeholder="12345678Z"
              required
            />
            {errors.nif && <ErrorText>{errors.nif}</ErrorText>}
          </FormGroup>

          <FormGroup>
            <Label>Email *</Label>
            <Input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              error={errors.email}
              required
            />
            {errors.email && <ErrorText>{errors.email}</ErrorText>}
          </FormGroup>
        </Row>

        {!isEditMode && (
          <FormGroup>
            <Label>Pasahitza *</Label>
            <Input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              error={errors.password}
              placeholder="Gutxienez 8 karaktere"
              required={!isEditMode}
            />
            {errors.password && <ErrorText>{errors.password}</ErrorText>}
          </FormGroup>
        )}

        <FormGroup>
          <Label>Kargua *</Label>
          <Input
            name="position"
            value={formData.position}
            onChange={handleChange}
            error={errors.position}
            required
          />
          {errors.position && <ErrorText>{errors.position}</ErrorText>}
        </FormGroup>

        <Row>
          <FormGroup>
            <Label>Telefonoa</Label>
            <Input
              name="phone"
              value={formData.phone}
              onChange={handleChange}
              error={errors.phone}
              placeholder="612345678"
            />
            {errors.phone && <ErrorText>{errors.phone}</ErrorText>}
          </FormGroup>

          <FormGroup>
            <Label>Kontratazio Data</Label>
            <Input
              type="date"
              name="hire_date"
              value={formData.hire_date}
              onChange={handleChange}
              error={errors.hire_date}
            />
            {errors.hire_date && <ErrorText>{errors.hire_date}</ErrorText>}
          </FormGroup>
        </Row>

        <FormGroup>
          <Label>Helbidea</Label>
          <Input
            name="address"
            value={formData.address}
            onChange={handleChange}
          />
        </FormGroup>

        <Row>
          <FormGroup>
            <Label>Hiria</Label>
            <Input
              name="city"
              value={formData.city}
              onChange={handleChange}
            />
          </FormGroup>

          <FormGroup>
            <Label>Posta Kodea</Label>
            <Input
              name="postal_code"
              value={formData.postal_code}
              onChange={handleChange}
              error={errors.postal_code}
              placeholder="28001"
            />
            {errors.postal_code && <ErrorText>{errors.postal_code}</ErrorText>}
          </FormGroup>
        </Row>

        <Row>
          <FormGroup>
            <Label>Soldata</Label>
            <Input
              type="number"
              name="salary"
              value={formData.salary}
              onChange={handleChange}
              error={errors.salary}
              placeholder="30000"
            />
            {errors.salary && <ErrorText>{errors.salary}</ErrorText>}
          </FormGroup>

          <FormGroup>
            <Label>IBAN</Label>
            <Input
              name="bank_account"
              value={formData.bank_account}
              onChange={handleChange}
              error={errors.bank_account}
              placeholder="ES9121000418450200051332"
            />
            {errors.bank_account && <ErrorText>{errors.bank_account}</ErrorText>}
          </FormGroup>
        </Row>

        <ButtonGroup>
          <Button type="submit" disabled={loading}>
            {loading ? 'Gordetzen...' : isEditMode ? 'Eguneratu' : 'Sortu'}
          </Button>
          <Button type="button" secondary onClick={() => navigate('/employees')}>
            Utzi
          </Button>
        </ButtonGroup>
      </Form>
    </Container>
  );
};

export default EmployeeForm;
