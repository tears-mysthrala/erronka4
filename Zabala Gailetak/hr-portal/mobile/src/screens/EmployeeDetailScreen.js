import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  Alert
} from 'react-native';
import api from '../services/api';

const EmployeeDetailScreen = ({ route, navigation }) => {
  const { id } = route.params;
  const [employee, setEmployee] = useState(null);
  const [history, setHistory] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadEmployee();
    loadHistory();
  }, [id]);

  const loadEmployee = async () => {
    try {
      const data = await api.getEmployee(id);
      setEmployee(data);
    } catch (error) {
      Alert.alert('Errorea', 'Ezin izan da langilea kargatu');
      navigation.goBack();
    } finally {
      setLoading(false);
    }
  };

  const loadHistory = async () => {
    try {
      const data = await api.getEmployeeHistory(id);
      setHistory(data.history || []);
    } catch (error) {
      console.error('Error loading history:', error);
    }
  };

  const handleDelete = () => {
    Alert.alert(
      'Baieztatu',
      `Ziur zaude ${employee.first_name} ${employee.last_name} ezabatu nahi duzula?`,
      [
        { text: 'Utzi', style: 'cancel' },
        {
          text: 'Ezabatu',
          style: 'destructive',
          onPress: async () => {
            try {
              await api.deleteEmployee(id);
              navigation.goBack();
            } catch (error) {
              Alert.alert('Errorea', 'Ezin izan da ezabatu');
            }
          }
        }
      ]
    );
  };

  const handleRestore = async () => {
    try {
      await api.restoreEmployee(id);
      loadEmployee();
      Alert.alert('Ondo!', 'Langilea berreskuratu da');
    } catch (error) {
      Alert.alert('Errorea', 'Ezin izan da berreskuratu');
    }
  };

  const getActionText = (action) => {
    const actions = {
      created: 'Sortua',
      updated: 'Eguneratua',
      deleted: 'Ezabatua',
      restored: 'Berreskuratua'
    };
    return actions[action] || action;
  };

  if (loading) {
    return (
      <View style={styles.centered}>
        <ActivityIndicator size="large" color="#0066cc" />
      </View>
    );
  }

  if (!employee) return null;

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.name}>
          {employee.first_name} {employee.last_name}
        </Text>
        <View style={[styles.badge, employee.active ? styles.badgeActive : styles.badgeInactive]}>
          <Text style={styles.badgeText}>
            {employee.active ? 'Aktiboa' : 'Inaktiboa'}
          </Text>
        </View>
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Oinarrizko Informazioa</Text>
        <View style={styles.infoRow}>
          <Text style={styles.label}>Langile Zenbakia:</Text>
          <Text style={styles.value}>{employee.employee_number}</Text>
        </View>
        <View style={styles.infoRow}>
          <Text style={styles.label}>Posta Elektronikoa:</Text>
          <Text style={styles.value}>{employee.email}</Text>
        </View>
        <View style={styles.infoRow}>
          <Text style={styles.label}>Kargua:</Text>
          <Text style={styles.value}>{employee.position}</Text>
        </View>
        <View style={styles.infoRow}>
          <Text style={styles.label}>Departamentua:</Text>
          <Text style={styles.value}>{employee.department}</Text>
        </View>
        {employee.phone && (
          <View style={styles.infoRow}>
            <Text style={styles.label}>Telefonoa:</Text>
            <Text style={styles.value}>{employee.phone}</Text>
          </View>
        )}
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Data Garrantzitsuak</Text>
        <View style={styles.infoRow}>
          <Text style={styles.label}>Kontratu Data:</Text>
          <Text style={styles.value}>{employee.hire_date}</Text>
        </View>
        <View style={styles.infoRow}>
          <Text style={styles.label}>Jaiotze Data:</Text>
          <Text style={styles.value}>{employee.date_of_birth}</Text>
        </View>
        <View style={styles.infoRow}>
          <Text style={styles.label}>Sortze Data:</Text>
          <Text style={styles.value}>
            {new Date(employee.created_at).toLocaleString('eu-ES')}
          </Text>
        </View>
        <View style={styles.infoRow}>
          <Text style={styles.label}>Azken Eguneraketa:</Text>
          <Text style={styles.value}>
            {new Date(employee.updated_at).toLocaleString('eu-ES')}
          </Text>
        </View>
      </View>

      <View style={styles.actions}>
        <TouchableOpacity
          style={styles.actionButton}
          onPress={() => navigation.navigate('EmployeeForm', { id: employee.id })}
        >
          <Text style={styles.actionButtonText}>Editatu</Text>
        </TouchableOpacity>
        
        {employee.active ? (
          <TouchableOpacity
            style={[styles.actionButton, styles.actionButtonDanger]}
            onPress={handleDelete}
          >
            <Text style={styles.actionButtonText}>Ezabatu</Text>
          </TouchableOpacity>
        ) : (
          <TouchableOpacity
            style={[styles.actionButton, styles.actionButtonSuccess]}
            onPress={handleRestore}
          >
            <Text style={styles.actionButtonText}>Berreskuratu</Text>
          </TouchableOpacity>
        )}
      </View>

      {history.length > 0 && (
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Aldaketa Historia</Text>
          {history.map((entry, index) => (
            <View key={index} style={styles.historyEntry}>
              <View style={styles.historyHeader}>
                <Text style={styles.historyAction}>
                  {getActionText(entry.action)}
                </Text>
                <Text style={styles.historyDate}>
                  {new Date(entry.created_at).toLocaleString('eu-ES')}
                </Text>
              </View>
              <Text style={styles.historyUser}>Erabiltzailea: {entry.user_email}</Text>
              {entry.changes && (
                <View style={styles.changes}>
                  <Text style={styles.changesTitle}>Aldaketak:</Text>
                  {Object.entries(entry.changes).map(([field, change]) => (
                    <Text key={field} style={styles.changeText}>
                      • {field}: {change.old} → {change.new}
                    </Text>
                  ))}
                </View>
              )}
            </View>
          ))}
        </View>
      )}
    </ScrollView>
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
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#fff',
    padding: 20,
    marginBottom: 15,
  },
  name: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    flex: 1,
  },
  badge: {
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 12,
  },
  badgeActive: {
    backgroundColor: '#28a745',
  },
  badgeInactive: {
    backgroundColor: '#6c757d',
  },
  badgeText: {
    color: '#fff',
    fontSize: 14,
    fontWeight: 'bold',
  },
  section: {
    backgroundColor: '#fff',
    padding: 20,
    marginBottom: 15,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 15,
  },
  infoRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingVertical: 8,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  label: {
    fontSize: 14,
    color: '#666',
    fontWeight: '500',
  },
  value: {
    fontSize: 14,
    color: '#333',
  },
  actions: {
    flexDirection: 'row',
    padding: 20,
    gap: 10,
  },
  actionButton: {
    flex: 1,
    backgroundColor: '#0066cc',
    paddingVertical: 12,
    borderRadius: 8,
    alignItems: 'center',
  },
  actionButtonDanger: {
    backgroundColor: '#dc3545',
  },
  actionButtonSuccess: {
    backgroundColor: '#28a745',
  },
  actionButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  historyEntry: {
    backgroundColor: '#f8f9fa',
    padding: 15,
    borderRadius: 8,
    marginBottom: 10,
  },
  historyHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 5,
  },
  historyAction: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#0066cc',
  },
  historyDate: {
    fontSize: 12,
    color: '#666',
  },
  historyUser: {
    fontSize: 14,
    color: '#666',
    marginBottom: 8,
  },
  changes: {
    marginTop: 8,
  },
  changesTitle: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 4,
  },
  changeText: {
    fontSize: 12,
    color: '#666',
    marginLeft: 10,
  },
});

export default EmployeeDetailScreen;
