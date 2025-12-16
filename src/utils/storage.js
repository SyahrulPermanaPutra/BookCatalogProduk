// src/utils/storage.js
// Helper functions untuk AsyncStorage

import AsyncStorage from '@react-native-async-storage/async-storage';

export const storage = {
  // Save user data
  saveUser: async (userData) => {
    try {
      await AsyncStorage.setItem('userData', JSON.stringify(userData));
      return true;
    } catch (error) {
      console.error('Error saving user:', error);
      return false;
    }
  },

  // Get user data
  getUser: async () => {
    try {
      const userData = await AsyncStorage.getItem('userData');
      return userData ? JSON.parse(userData) : null;
    } catch (error) {
      console.error('Error getting user:', error);
      return null;
    }
  },

  // Remove user data (logout)
  removeUser: async () => {
    try {
      await AsyncStorage.removeItem('userData');
      return true;
    } catch (error) {
      console.error('Error removing user:', error);
      return false;
    }
  },

  // Clear all storage
  clearAll: async () => {
    try {
      await AsyncStorage.clear();
      return true;
    } catch (error) {
      console.error('Error clearing storage:', error);
      return false;
    }
  },
};

export default storage;