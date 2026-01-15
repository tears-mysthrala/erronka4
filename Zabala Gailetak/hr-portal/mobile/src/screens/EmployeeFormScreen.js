import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  KeyboardAvoidingView,
  Platform,
  ActivityIndicator,
  Alert
} from 'react-native';
import api from '../services/api';

const EmployeeFormScreen = ({ route, navigation }) => {
  const { id } = route.params || {};
  const isEdit = !!id;

  const [loading, setLoading] = useState(isEdit);
  const [saving, setSaving] = useState(false);
  const [formData, setFormData] = useState({
    employee_number: '',
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    date_of_birth: '',
    hire_date: '',
    position: '',
    department: '',
    salary: ''
  });
  const [errors, setErrors] = useState({});

  useEffect(() => {
    if (isEdit) {
      loadEmployee();
    }
  }, [id]);

  const loadEmployee = async () => {
    try {
      const data = await api.getEmployee(id);
      setFormData({
        employee_number: data.employee_number || '',
        first_name: data.first_name || '',
        last_name: data.last_name || '',
        email: data.email || '',
        phone: data.phone || '',
        date_of_birth: data.date_of_birth || '',
        hire_date: data.hire_date || '',
        position: data.position || '',
        department: data.department || '',
        salary: data.salary?.toString() || ''
      });
    } catch (error) {
      Alert.alert('Errorea', 'Ezin izan da langilea kargatu');
      navigation.goBack();
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
    if (errors[field]) {
      setErrors(prev => ({ ...prev, [field]: null }));
    }
  };

  const validateForm = () => {
    const newErrors = {};

    if (!formData.employee_number.trim()) {
      newErrors.employee_number = 'Langile zenbakia beharrezkoa da';
    }
    if (!formData.first_name.trim()) {
      newErrors.first_name = 'Izena beharrezkoa da';
    }
    if (!formData.last_name.trim()) {
      newErrors.last_name = 'Abizena beharrezkoa da';
    }
    if (!formData.email.trim()) {
      newErrors.email = 'Email-a beharrezkoa da';
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = 'Email formatu baliogabea';
    }
    if (!formData.position.trim()) {
      newErrors.position = 'Kargua beharrezkoa da';
    }
    if (!formData.department.trim()) {
      newErrors.department = 'Departamentua beharrezkoa da';
    }
    if (!formData.hire_date.trim()) {
      newErrors.hire_date = 'Kontratu data beharrezkoa da';
    }
    if (formData.salary && isNaN(parseFloat(formData.salary))) {
      newErrors.salary = 'Soldata zenbaki bat izan behar da';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async () => {
    if (!validateForm()) {
      Alert.alert('Errorea', 'Mesedez, zuzendu erroreak');
      return;
    }

    setSaving(true);
    try {
      const payload = {
        ...formData,
        salary: formData.salary ? parseFloat(formData.salary) : null
      };

      if (isEdit) {
        await api.updateEmployee(id, payload);
        Alert.alert('Ondo!', 'Langilea eguneratu da');
      } else {
        await api.createEmployee(payload);
        Alert.alert('Ondo!', 'Langilea sortu da');
      }
      
      navigation.goBack();
    } catch (error) {
      const errorMsg = error.response?.data?.error || 'Errorea gordetzean';
      Alert.alert('Errorea', errorMsg);
      
      if (error.response?.data?.errors) {
        setErrors(error.response.data.errors);
      }
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return (
      <View style={styles.centered}>
        <ActivityIndicator size="large" color="#0066cc" />
      </View>
    );
  }

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      keyboardVerticalOffset={100}
    >
      <ScrollView style={styles.form}>
        <Text style={styles.sectionTitle}>Oinarrizko Informazioa</Text>
        
        <View style={styles.inputGroup}>
          <Text style={styles.label}>Langile Zenbakia *</Text>
          <TextInput
            style={[styles.input, errors.employee_number && styles.inputError]}
            value={formData.employee_number}
            onChangeText={(value) => handleChange('employee_number', value)}
            placeholder="EMP001"
          />
          {errors.employee_number && (
            <Text style={styles.errorText}>{errors.employee_number}</Text>
          )}
        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.label}>Izena *</Text>
          <TextInput
            style={[styles.input, errors.first_name && styles.inputError]}
            value={formData.first_name}
            onChangeText={(value) => handleChange('first_name', value)}
            placeholder="Mikel"
          />
          {errors.first_name && (
            <Text style={styles.errorText}>{errors.first_name}</Text>
          )}
        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.label}>Abizena *</Text>
          <TextInput
            style={[styles.input, errors.last_name && styles.inputError]}
            value={formData.last_name}
            onChangeText={(value) => handleChange('last_name', value)}
            placeholder="Garcia"
          />
          {errors.last_name && (
            <Text style={styles.errorText}>{errors.last_name}</Text>
          )}
        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.label}>Posta Elektronikoa *</Text>
          <TextInput
            style={[styles.input, errors.email && styles.inputError]}
            value={formData.email}
            onChangeText={(value) => handleChange('email', value)}
            placeholder="mikel@zabala.eus"
            keyboardType="email-address"
            autoCapitalize="none"
            autoComplete="email"
          />
          {errors.email && (
            <Text style={styles.errorText}>{errors.email}</Text>
          )}
        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.label}>Telefonoa</Text>
          <TextInput
            style={styles.input}
            value={formData.phone}
            onChangeText={(value) => handleChange('phone', value)}
            placeholder="+34 600 000 000"
            keyboardType="phone-pad"
          />
        </View>

        <Text style={styles.sectionTitle}>Data Garrantzitsuak</Text>

        <View style={styles.inputGroup}>
          <Text style={styles.label}>Jaiotze Data</Text>
          <TextInput
            style={styles.input}
            value={formData.date_of_birth}
            onChangeText={(value) => handleChange('date_of_birth', value)}
            placeholder="YYYY-MM-DD"
          />
        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.label}>Kontratu Data *</Text>
          <TextInput
            style={[styles.input, errors.hire_date && styles.inputError]}
            value={formData.hire_date}
            onChangeText={(value) => handleChange('hire_date', value)}
            placeholder="YYYY-MM-DD"
          />
          {errors.hire_date && (
            <Text style={styles.errorText}>{errors.hire_date}</Text>
          )}
        </View>

        <Text style={styles.sectionTitle}>Lanaren Xehetasunak</Text>

        <View style={styles.inputGroup}>
          <Text style={styles.label}>Kargua *</Text>
          <TextInput
            style={[styles.input, errors.position && styles.inputError]}
            value={formData.position}
            onChangeText={(value) => handleChange('position', value)}
            placeholder="Senior Developer"
          />
          {errors.position && (
            <Text style={styles.errorText}>{errors.position}</Text>
          )}
        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.label}>Departamentua *</Text>
          <TextInput
            style={[styles.input, errors.department && styles.inputError]}
            value={formData.department}
            onChangeText={(value) => handleChange('department', value)}
            placeholder="IT"
          />
          {errors.department && (
            <Text style={styles.errorText}>{errors.department}</Text>
          )}
        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.label}>Soldata (€)</Text>
          <TextInput
            style={[styles.input, errors.salary && styles.inputError]}
            value={formData.salary}
            onChangeText={(value) => handleChange('salary', value)}
            placeholder="50000"
            keyboardType="decimal-pad"
          />
          {errors.salary && (
            <Text style={styles.errorText}>{errors.salary}</Text>
          )}
        </View>

        <View style={styles.actions}>
          <TouchableOpacity
            style={[styles.button, styles.buttonSecondary]}
            onPress={() => navigation.goBack()}
            disabled={saving}
          >
            <Text style={styles.buttonTextSecondary}>Utzi</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={[styles.button, styles.buttonPrimary, saving && styles.buttonDisabled]}
            onPress={handleSubmit}
            disabled={saving}
          >
            {saving ? (
              <ActivityIndicator color="#fff" />
            ) : (
              <Text style={styles.buttonText}>
                {isEdit ? 'Eguneratu' : 'Sortu'}
              </Text>
            )}
          </TouchableOpacity>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  centered: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  form: {
    flex: 1,
    padding: 20,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 20,
    marginBottom: 15,
  },
  inputGroup: {
    marginBottom: 20,
  },
  label: {
    fontSize: 14,
    fontWeight: '500',
    color: '#333',
    marginBottom: 8,
  },
  input: {
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 8,
    padding: 12,
    fontSize: 16,
  },
  inputError: {
    borderColor: '#dc3545',
  },
  errorText: {
    color: '#dc3545',
    fontSize: 12,
    marginTop: 4,
  },
  actions: {
    flexDirection: 'row',
    gap: 10,
    marginTop: 20,
    marginBottom: 40,
  },
  button: {
    flex: 1,
    paddingVertical: 15,
    borderRadius: 8,
    alignItems: 'center',
  },
  buttonPrimary: {
    backgroundColor: '#0066cc',
  },
  buttonSecondary: {
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#ddd',
  },
  buttonDisabled: {
    opacity: 0.6,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  buttonTextSecondary: {
    color: '#333',
    fontSize: 16,
    fontWeight: 'bold',
  },
});

export default EmployeeFormScreen;
