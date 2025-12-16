<?php
// api/profile.php
// Endpoint untuk mendapatkan data profile user

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Ambil user_id dari parameter
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (empty($user_id)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'User ID harus diisi!'
    ]);
    exit();
}

// Query untuk mengambil data user beserta statistik buku
$query = "SELECT 
          u.id,
          u.name,
          u.email,
          u.created_at,
          COUNT(b.id) as total_books
          FROM users u
          LEFT JOIN books b ON u.id = b.user_id
          WHERE u.id = :user_id
          GROUP BY u.id";

$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Data profile berhasil diambil',
        'data' => $user
    ]);
} else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'User tidak ditemukan!'
    ]);
}
?>