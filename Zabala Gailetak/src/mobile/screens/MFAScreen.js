import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, Alert, ActivityIndicator } from 'react-native';
import { verifyMFA } from '../services/authService';

const MFAScreen = ({ route, navigation }) => {
  const [token, setToken] = useState('');
  const [loading, setLoading] = useState(false);
  const { userId } = route.params;

  const handleVerifyMFA = async () => {
    if (!token || token.length !== 6) {
      Alert.alert('Errorea', '6 digituko MFA kodea sartu behar duzu');
      return;
    }

    setLoading(true);
    try {
      const response = await verifyMFA(token);
      Alert.alert('Arrakasta', 'MFA balidazioa arrakastatsua', [
        {
          text: 'OK',
          onPress: () => navigation.replace('Products', { token: response.token, userId })
        }
      ]);
    } catch (error) {
      Alert.alert('Errorea', error.message || 'MFA balidazioa errorea');
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>MFA Balidazioa</Text>
      <Text style={styles.subtitle}>Autentikatzaile aplikazioko kodea sartu</Text>
      
      <TextInput
        style={styles.input}
        placeholder="000000"
        value={token}
        onChangeText={setToken}
        keyboardType="number-pad"
        maxLength={6}
        textAlign="center"
        autoFocus
      />
      
      <TouchableOpacity 
        style={[styles.button, loading && styles.buttonDisabled]}
        onPress={handleVerifyMFA}
        disabled={loading}
      >
        {loading ? (
          <ActivityIndicator color="#fff" />
        ) : (
          <Text style={styles.buttonText}>Balidatu</Text>
        )}
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    padding: 20,
    backgroundColor: '#f4f4f4',
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 10,
    color: '#333',
  },
  subtitle: {
    fontSize: 16,
    textAlign: 'center',
    marginBottom: 30,
    color: '#666',
  },
  input: {
    backgroundColor: '#fff',
    padding: 15,
    borderRadius: 8,
    marginBottom: 20,
    fontSize: 24,
    letterSpacing: 8,
    fontWeight: 'bold',
  },
  button: {
    backgroundColor: '#333',
    padding: 15,
    borderRadius: 8,
    alignItems: 'center',
  },
  buttonDisabled: {
    backgroundColor: '#999',
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});

export default MFAScreen;