import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import LoginScreen from './screens/LoginScreen';
import ProductListScreen from './screens/ProductListScreen';
import OrderScreen from './screens/OrderScreen';
import MFAScreen from './screens/MFAScreen';

const Stack = createStackNavigator();

const App = () => {
  return (
    <SafeAreaProvider>
      <NavigationContainer>
        <Stack.Navigator
          initialRouteName="Login"
          screenOptions={{
            headerStyle: { backgroundColor: '#333' },
            headerTintColor: '#fff',
            headerTitleStyle: { fontWeight: 'bold' },
          }}
        >
          <Stack.Screen name="Login" component={LoginScreen} options={{ title: 'Zabala Gailetak' }} />
          <Stack.Screen name="MFA" component={MFAScreen} options={{ title: 'MFA Balidazioa' }} />
          <Stack.Screen name="Products" component={ProductListScreen} options={{ title: 'Produktuak' }} />
          <Stack.Screen name="Order" component={OrderScreen} options={{ title: 'Eskaera Berria' }} />
        </Stack.Navigator>
      </NavigationContainer>
    </SafeAreaProvider>
  );
};

export default App;