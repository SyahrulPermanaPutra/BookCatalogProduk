<?php
require_once '../config/database.php';

header("Content-Type: application/json");

// Baca input sebagai JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Ambil data dari JSON
$user_id     = $data['user_id'] ?? null;
$title       = $data['title'] ?? null;
$author      = $data['author'] ?? null;
$isbn        = $data['isbn'] ?? null;
$publisher   = $data['publisher'] ?? null;
$year        = $data['year'] ?? null;
$pages       = $data['pages'] ?? null;
$description = $data['description'] ?? null;
$cover_url   = $data['cover_url'] ?? null;

$database = new Database();
$db = $database->getConnection();

$hasil = "Gagal menambahkan buku";
$success = false;
$book_id = null;

// Validasi WAJIB
if ($user_id && $title && $author) {
    $query = "INSERT INTO books 
        (user_id, title, author, isbn, publisher, year, pages, description, cover_url)
        VALUES
        (:user_id, :title, :author, :isbn, :publisher, :year, :pages, :description, :cover_url)";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->bindParam(':publisher', $publisher);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':pages', $pages);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':cover_url', $cover_url);

    if ($stmt->execute()) {
        $success = true;
        $hasil = "Berhasil menambahkan buku";
        $book_id = $db->lastInsertId(); // Ambil ID buku yang baru dibuat
    }
}

// Response yang lebih informatif
echo json_encode([
    'success' => $success,
    'message' => $hasil,
    'data' => [
        'book_id' => $book_id,
        'result' => $hasil
    ]
]);