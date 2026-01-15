import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { TouchableOpacity, Text, StyleSheet, Alert } from 'react-native';
import { AuthProvider, useAuth } from './src/context/AuthContext';

// Screens
import LoginScreen from './src/screens/LoginScreen';
import EmployeeListScreen from './src/screens/EmployeeListScreen';
import EmployeeDetailScreen from './src/screens/EmployeeDetailScreen';
import EmployeeFormScreen from './src/screens/EmployeeFormScreen';

const Stack = createNativeStackNavigator();

const LogoutButton = ({ navigation }) => {
  const { logout } = useAuth();

  const handleLogout = () => {
    Alert.alert(
      'Itxi Saioa',
      'Ziur zaude saioa itxi nahi duzula?',
      [
        { text: 'Utzi', style: 'cancel' },
        {
          text: 'Itxi',
          onPress: async () => {
            await logout();
            navigation.replace('Login');
          }
        }
      ]
    );
  };

  return (
    <TouchableOpacity onPress={handleLogout} style={styles.logoutButton}>
      <Text style={styles.logoutText}>Itxi Saioa</Text>
    </TouchableOpacity>
  );
};

const AppNavigator = () => {
  const { user, loading } = useAuth();

  if (loading) {
    return null; // Or a loading screen
  }

  return (
    <Stack.Navigator
      screenOptions={{
        headerStyle: {
          backgroundColor: '#0066cc',
        },
        headerTintColor: '#fff',
        headerTitleStyle: {
          fontWeight: 'bold',
        },
      }}
    >
      {!user ? (
        <Stack.Screen
          name="Login"
          component={LoginScreen}
          options={{ headerShown: false }}
        />
      ) : (
        <>
          <Stack.Screen
            name="EmployeeList"
            component={EmployeeListScreen}
            options={({ navigation }) => ({
              title: 'Langileak',
              headerRight: () => <LogoutButton navigation={navigation} />
            })}
          />
          <Stack.Screen
            name="EmployeeDetail"
            component={EmployeeDetailScreen}
            options={{ title: 'Langilearen Xehetasunak' }}
          />
          <Stack.Screen
            name="EmployeeForm"
            component={EmployeeFormScreen}
            options={({ route }) => ({
              title: route.params?.id ? 'Langilea Editatu' : 'Langile Berria'
            })}
          />
        </>
      )}
    </Stack.Navigator>
  );
};

export default function App() {
  return (
    <AuthProvider>
      <NavigationContainer>
        <AppNavigator />
      </NavigationContainer>
    </AuthProvider>
  );
}

const styles = StyleSheet.create({
  logoutButton: {
    marginRight: 15,
    paddingHorizontal: 12,
    paddingVertical: 6,
    backgroundColor: 'rgba(255, 255, 255, 0.2)',
    borderRadius: 4,
  },
  logoutText: {
    color: '#fff',
    fontSize: 14,
    fontWeight: 'bold',
  },
});
