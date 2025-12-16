<?php
// api/get_books.php
// Endpoint untuk mengambil semua data buku

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Ambil parameter user_id jika ada (untuk filter buku user tertentu)
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Query untuk mengambil data buku
if ($user_id) {
    $query = "SELECT b.*, u.name as user_name 
              FROM books b 
              LEFT JOIN users u ON b.user_id = u.id 
              WHERE b.user_id = :user_id 
              ORDER BY b.created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
} else {
    $query = "SELECT b.*, u.name as user_name 
              FROM books b 
              LEFT JOIN users u ON b.user_id = u.id 
              ORDER BY b.created_at DESC";
    $stmt = $db->prepare($query);
}

$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($books) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Data buku berhasil diambil',
        'total' => count($books),
        'data' => $books
    ]);
} else {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Belum ada data buku',
        'total' => 0,
        'data' => []
    ]);
}
?>