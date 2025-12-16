<?php
// api/delete_book.php
// Endpoint untuk menghapus buku

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Ambil data dari request
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

// Cek apakah buku ada
$query = "SELECT id, title FROM books WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $data->id);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Buku tidak ditemukan!'
    ]);
    exit();
}

$book = $stmt->fetch(PDO::FETCH_ASSOC);

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
?>