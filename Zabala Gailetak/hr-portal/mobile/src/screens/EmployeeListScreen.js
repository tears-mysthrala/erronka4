import React, { useState, useEffect, useCallback } from 'react';
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  RefreshControl,
  Alert
} from 'react-native';
import api from '../services/api';

const EmployeeListScreen = ({ navigation }) => {
  const [employees, setEmployees] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [page, setPage] = useState(1);

  useEffect(() => {
    loadEmployees();
  }, [page]);

  const loadEmployees = async () => {
    try {
      const data = await api.getEmployees({ page, limit: 20 });
      setEmployees(data.employees || []);
    } catch (error) {
      Alert.alert('Errorea', 'Ezin izan da langile zerrenda kargatu');
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    setPage(1);
    loadEmployees();
  }, []);

  const handleDelete = async (id, name) => {
    Alert.alert(
      'Baieztatu',
      `Ziur zaude ${name} ezabatu nahi duzula?`,
      [
        { text: 'Utzi', style: 'cancel' },
        {
          text: 'Ezabatu',
          style: 'destructive',
          onPress: async () => {
            try {
              await api.deleteEmployee(id);
              loadEmployees();
            } catch (error) {
              Alert.alert('Errorea', 'Ezin izan da ezabatu');
            }
          }
        }
      ]
    );
  };

  const renderEmployee = ({ item }) => (
    <TouchableOpacity
      style={styles.card}
      onPress={() => navigation.navigate('EmployeeDetail', { id: item.id })}
    >
      <View style={styles.cardHeader}>
        <Text style={styles.name}>
          {item.first_name} {item.last_name}
        </Text>
        <View style={[styles.badge, item.active ? styles.badgeActive : styles.badgeInactive]}>
          <Text style={styles.badgeText}>
            {item.active ? 'Aktiboa' : 'Inaktiboa'}
          </Text>
        </View>
      </View>
      <Text style={styles.position}>{item.position}</Text>
      <Text style={styles.email}>{item.email}</Text>
      <Text style={styles.number}>#{item.employee_number}</Text>
      
      <View style={styles.actions}>
        <TouchableOpacity
          style={styles.actionButton}
          onPress={() => navigation.navigate('EmployeeForm', { id: item.id })}
        >
          <Text style={styles.actionButtonText}>Editatu</Text>
        </TouchableOpacity>
        {item.active && (
          <TouchableOpacity
            style={[styles.actionButton, styles.actionButtonDanger]}
            onPress={() => handleDelete(item.id, `${item.first_name} ${item.last_name}`)}
          >
            <Text style={styles.actionButtonText}>Ezabatu</Text>
          </TouchableOpacity>
        )}
      </View>
    </TouchableOpacity>
  );

  if (loading) {
    return (
      <View style={styles.centered}>
        <ActivityIndicator size="large" color="#0066cc" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <FlatList
        data={employees}
        renderItem={renderEmployee}
        keyExtractor={(item) => item.id}
        contentContainerStyle={styles.list}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }
        ListEmptyComponent={
          <View style={styles.empty}>
            <Text style={styles.emptyText}>Ez dago langilerik</Text>
          </View>
        }
      />
      
      <TouchableOpacity
        style={styles.fab}
        onPress={() => navigation.navigate('EmployeeForm')}
      >
        <Text style={styles.fabText}>+</Text>
      </TouchableOpacity>
    </View>
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
  list: {
    padding: 15,
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 8,
    padding: 15,
    marginBottom: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  name: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    flex: 1,
  },
  badge: {
    paddingHorizontal: 10,
    paddingVertical: 4,
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
    fontSize: 12,
    fontWeight: 'bold',
  },
  position: {
    fontSize: 16,
    color: '#666',
    marginBottom: 4,
  },
  email: {
    fontSize: 14,
    color: '#0066cc',
    marginBottom: 4,
  },
  number: {
    fontSize: 12,
    color: '#999',
    marginBottom: 10,
  },
  actions: {
    flexDirection: 'row',
    gap: 10,
  },
  actionButton: {
    backgroundColor: '#0066cc',
    paddingHorizontal: 15,
    paddingVertical: 8,
    borderRadius: 4,
  },
  actionButtonDanger: {
    backgroundColor: '#dc3545',
  },
  actionButtonText: {
    color: '#fff',
    fontSize: 14,
    fontWeight: 'bold',
  },
  fab: {
    position: 'absolute',
    right: 20,
    bottom: 20,
    width: 60,
    height: 60,
    borderRadius: 30,
    backgroundColor: '#28a745',
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 4,
    elevation: 8,
  },
  fabText: {
    fontSize: 32,
    color: '#fff',
    fontWeight: 'bold',
  },
  empty: {
    padding: 40,
    alignItems: 'center',
  },
  emptyText: {
    fontSize: 16,
    color: '#999',
  },
});

export default EmployeeListScreen;
