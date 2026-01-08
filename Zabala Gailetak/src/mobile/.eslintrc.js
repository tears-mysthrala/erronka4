module.exports = {
  parser: '@typescript-eslint/parser',
  extends: [
    '@react-native-community',
    'plugin:security/recommended'
  ],
  plugins: [
    'security'
  ],
  rules: {
    'security/detect-object-injection': 'warn',
    'no-unused-vars': 'off',
    '@typescript-eslint/no-unused-vars': ['error'],
    'react/prop-types': 'off',
    'react-native/no-inline-styles': 'warn'
  }
};