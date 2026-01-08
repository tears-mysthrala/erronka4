import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, Alert, ActivityIndicator } from 'react-native';
import { createOrder } from '../services/authService';

const OrderScreen = ({ route, navigation }) => {
  const [quantity, setQuantity] = useState('1');
  const [customerName, setCustomerName] = useState('');
  const [customerEmail, setCustomerEmail] = useState('');
  const [loading, setLoading] = useState(false);
  const { product } = route.params;

  const handleCreateOrder = async () => {
    if (!customerName || !customerEmail) {
      Alert.alert('Errorea', 'Izena eta emaila behar dira');
      return;
    }

    setLoading(true);
    try {
      const orderData = {
        productId: product.id,
        quantity: parseInt(quantity),
        customerName,
        customerEmail
      };
      
      const response = await createOrder(orderData);
      Alert.alert('Arrakasta', `Eskaera #${response.orderId} ondo jaso da`, [
        {
          text: 'OK',
          onPress: () => navigation.goBack()
        }
      ]);
    } catch (error) {
      Alert.alert('Errorea', error.response?.data?.errors?.[0]?.msg || error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <View style={styles.productSummary}>
        <Text style={styles.productName}>{product.name}</Text>
        <Text style={styles.productPrice}>€{product.price.toFixed(2)}</Text>
      </View>
      
      <Text style={styles.label}>Kantitatea:</Text>
      <TextInput
        style={styles.input}
        value={quantity}
        onChangeText={setQuantity}
        keyboardType="number-pad"
      />
      
      <Text style={styles.label}>Izena:</Text>
      <TextInput
        style={styles.input}
        value={customerName}
        onChangeText={setCustomerName}
        placeholder="Zure izena"
      />
      
      <Text style={styles.label}>Emaila:</Text>
      <TextInput
        style={styles.input}
        value={customerEmail}
        onChangeText={setCustomerEmail}
        placeholder="email@example.com"
        keyboardType="email-address"
        autoCapitalize="none"
      />
      
      <View style={styles.totalContainer}>
        <Text style={styles.totalLabel}>Total:</Text>
        <Text style={styles.totalValue}>€{(product.price * parseInt(quantity || 0)).toFixed(2)}</Text>
      </View>
      
      <TouchableOpacity 
        style={[styles.button, loading && styles.buttonDisabled]}
        onPress={handleCreateOrder}
        disabled={loading}
      >
        {loading ? (
          <ActivityIndicator color="#fff" />
        ) : (
          <Text style={styles.buttonText}>Eskaera Egin</Text>
        )}
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    backgroundColor: '#f4f4f4',
  },
  productSummary: {
    backgroundColor: '#fff',
    padding: 20,
    borderRadius: 10,
    marginBottom: 20,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  productName: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    flex: 1,
  },
  productPrice: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#333',
  },
  label: {
    fontSize: 14,
    fontWeight: '600',
    marginBottom: 5,
    color: '#666',
  },
  input: {
    backgroundColor: '#fff',
    padding: 15,
    borderRadius: 8,
    marginBottom: 15,
    fontSize: 16,
  },
  totalContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginVertical: 20,
    padding: 15,
    backgroundColor: '#fff',
    borderRadius: 8,
  },
  totalLabel: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
  },
  totalValue: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
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

export default OrderScreen;