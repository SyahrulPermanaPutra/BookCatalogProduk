// src/services/api.js
// Service untuk handle semua request API

import axios from 'axios';

// ⚠️ PENTING: Ganti dengan IP Address laptop Anda
// Cara cek IP: buka CMD, ketik "ipconfig", lihat IPv4 Address
const API_BASE_URL = 'http://192.168.0.115/BookCatalogProduk/api';

// Buat instance axios
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
  timeout: 10000, // 10 detik timeout
});

// Auth API
export const authAPI = {
  register: async (name, email, password) => {
    try {
      const response = await api.post('/register.php', {
        name,
        email,
        password,
      });
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Network error' };
    }
  },

  login: async (email, password) => {
    try {
      const response = await api.post('/login.php', {
        email,
        password,
      });
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Network error' };
    }
  },

  getProfile: async (userId) => {
    try {
      const response = await api.get(`/profile.php?user_id=${userId}`);
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Network error' };
    }
  },
};

// Books API
export const booksAPI = {
  getAll: async (userId = null) => {
    try {
      const url = userId ? `/get_books.php?user_id=${userId}` : '/get_books.php';
      const response = await api.get(url);
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Network error' };
    }
  },

  add: async (bookData) => {
    try {
      const response = await api.post('/add_book.php', bookData);
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Network error' };
    }
  },

  update: async (bookData) => {
    try {
      const response = await api.post('/update_book.php', bookData);
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Network error' };
    }
  },

  delete: async (bookId) => {
    try {
      const response = await api.post('/delete_book.php', { id: bookId });
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Network error' };
    }
  },
};

export default api;