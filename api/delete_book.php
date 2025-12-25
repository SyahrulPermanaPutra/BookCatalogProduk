<?php
// api/delete_book.php
// Endpoint hapus buku (hanya pemilik buku)

require_once '../config/database.php';
session_start();

header('Content-Type: application/json');

// Validasi login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

$database = new Database();
$db = $database->getConnection();

// Ambil data request
$data = json_decode(file_get_contents("php://input"));

// Validasi input
if (empty($data->id)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID buku harus diisi!'
    ]);
    exit();
}

// Ambil data buku + pemilik
$query = "
    SELECT id, title, user_id
    FROM books
    WHERE id = :id
";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $data->id);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Buku tidak ditemukan!'
    ]);
    exit();
}

$book = $stmt->fetch(PDO::FETCH_ASSOC);

// 🔒 VALIDASI HAK AKSES
if ($book['user_id'] != $user_id) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Anda tidak memiliki izin menghapus buku ini'
    ]);
    exit();
}

// Hapus buku
$query = "DELETE FROM books WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $data->id);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Buku "' . $book['title'] . '" berhasil dihapus!'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menghapus buku!'
    ]);
}
