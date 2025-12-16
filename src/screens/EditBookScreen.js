// src/screens/EditBookScreen.js
// Screen untuk edit data buku

import { useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import { booksAPI } from '../services/api';

export default function EditBookScreen({ navigation, route }) {
  const { book } = route.params;

  const [title, setTitle] = useState(book.title);
  const [author, setAuthor] = useState(book.author);
  const [isbn, setIsbn] = useState(book.isbn || '');
  const [publisher, setPublisher] = useState(book.publisher || '');
  const [year, setYear] = useState(book.year ? book.year.toString() : '');
  const [pages, setPages] = useState(book.pages ? book.pages.toString() : '');
  const [description, setDescription] = useState(book.description || '');
  const [coverUrl, setCoverUrl] = useState(book.cover_url || '');
  const [loading, setLoading] = useState(false);

  const handleUpdate = async () => {
    // Validasi input wajib
    if (!title || !author) {
      Alert.alert('Error', 'Judul dan Penulis harus diisi!');
      return;
    }

    setLoading(true);
    try {
      const bookData = {
        id: book.id,
        title,
        author,
        isbn: isbn || null,
        publisher: publisher || null,
        year: year ? parseInt(year) : null,
        pages: pages ? parseInt(pages) : null,
        description: description || null,
        cover_url: coverUrl || null,
      };

      const response = await booksAPI.update(bookData);
      
      if (response.success) {
        Alert.alert('Sukses', 'Buku berhasil diupdate!', [
          {
            text: 'OK',
            onPress: () => navigation.goBack(),
          },
        ]);
      } else {
        Alert.alert('Error', response.message);
      }
    } catch (error) {
      Alert.alert('Error', error.message || 'Gagal mengupdate buku');
    } finally {
      setLoading(false);
    }
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={styles.container}
    >
      <ScrollView contentContainerStyle={styles.scrollContainer}>
        <View style={styles.form}>
          <View style={styles.inputContainer}>
            <Text style={styles.label}>
              Judul Buku <Text style={styles.required}>*</Text>
            </Text>
            <TextInput
              style={styles.input}
              placeholder="Masukkan judul buku"
              value={title}
              onChangeText={setTitle}
              editable={!loading}
            />
          </View>

          <View style={styles.inputContainer}>
            <Text style={styles.label}>
              Penulis <Text style={styles.required}>*</Text>
            </Text>
            <TextInput
              style={styles.input}
              placeholder="Masukkan nama penulis"
              value={author}
              onChangeText={setAuthor}
              editable={!loading}
            />
          </View>

          <View style={styles.inputContainer}>
            <Text style={styles.label}>ISBN</Text>
            <TextInput
              style={styles.input}
              placeholder="Masukkan ISBN (opsional)"
              value={isbn}
              onChangeText={setIsbn}
              editable={!loading}
            />
          </View>

          <View style={styles.inputContainer}>
            <Text style={styles.label}>Penerbit</Text>
            <TextInput
              style={styles.input}
              placeholder="Masukkan penerbit (opsional)"
              value={publisher}
              onChangeText={setPublisher}
              editable={!loading}
            />
          </View>

          <View style={styles.row}>
            <View style={[styles.inputContainer, styles.halfWidth]}>
              <Text style={styles.label}>Tahun</Text>
              <TextInput
                style={styles.input}
                placeholder="2024"
                value={year}
                onChangeText={setYear}
                keyboardType="numeric"
                editable={!loading}
              />
            </View>

            <View style={[styles.inputContainer, styles.halfWidth]}>
              <Text style={styles.label}>Halaman</Text>
              <TextInput
                style={styles.input}
                placeholder="300"
                value={pages}
                onChangeText={setPages}
                keyboardType="numeric"
                editable={!loading}
              />
            </View>
          </View>

          <View style={styles.inputContainer}>
            <Text style={styles.label}>Deskripsi</Text>
            <TextInput
              style={[styles.input, styles.textArea]}
              placeholder="Masukkan deskripsi buku (opsional)"
              value={description}
              onChangeText={setDescription}
              multiline
              numberOfLines={4}
              editable={!loading}
            />
          </View>

          <View style={styles.inputContainer}>
            <Text style={styles.label}>URL Cover</Text>
            <TextInput
              style={styles.input}
              placeholder="https://example.com/cover.jpg"
              value={coverUrl}
              onChangeText={setCoverUrl}
              autoCapitalize="none"
              editable={!loading}
            />
          </View>

          <TouchableOpacity
            style={[styles.button, loading && styles.buttonDisabled]}
            onPress={handleUpdate}
            disabled={loading}
          >
            {loading ? (
              <ActivityIndicator color="#FFFFFF" />
            ) : (
              <Text style={styles.buttonText}>Update Buku</Text>
            )}
          </TouchableOpacity>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F3F4F6',
  },
  scrollContainer: {
    flexGrow: 1,
    padding: 20,
  },
  form: {
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    padding: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  inputContainer: {
    marginBottom: 20,
  },
  row: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  halfWidth: {
    width: '48%',
  },
  label: {
    fontSize: 14,
    fontWeight: '600',
    color: '#374151',
    marginBottom: 8,
  },
  required: {
    color: '#EF4444',
  },
  input: {
    borderWidth: 1,
    borderColor: '#D1D5DB',
    borderRadius: 8,
    padding: 12,
    fontSize: 16,
    backgroundColor: '#F9FAFB',
  },
  textArea: {
    height: 100,
    textAlignVertical: 'top',
  },
  button: {
    backgroundColor: '#4F46E5',
    borderRadius: 8,
    padding: 16,
    alignItems: 'center',
    marginTop: 10,
  },
  buttonDisabled: {
    backgroundColor: '#9CA3AF',
  },
  buttonText: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: 'bold',
  },
});