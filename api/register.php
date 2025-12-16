<?php
// api/register.php
// Endpoint untuk registrasi user baru

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Ambil data dari request
$data = json_decode(file_get_contents("php://input"));

// Validasi input
if (empty($data->name) || empty($data->email) || empty($data->password)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Nama, email, dan password harus diisi!'
    ]);
    exit();
}

// Validasi format email
if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Format email tidak valid!'
    ]);
    exit();
}

// Cek apakah email sudah terdaftar
$query = "SELECT id FROM users WHERE email = :email";
$stmt = $db->prepare($query);
$stmt->bindParam(':email', $data->email);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    http_response_code(409);
    echo json_encode([
        'success' => false,
        'message' => 'Email sudah terdaftar!'
    ]);
    exit();
}

// Hash password
$hashed_password = password_hash($data->password, PASSWORD_DEFAULT);

// Insert user baru
$query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
$stmt = $db->prepare($query);

$stmt->bindParam(':name', $data->name);
$stmt->bindParam(':email', $data->email);
$stmt->bindParam(':password', $hashed_password);

if ($stmt->execute()) {
    $user_id = $db->lastInsertId();
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Registrasi berhasil!',
        'data' => [
            'id' => $user_id,
            'name' => $data->name,
            'email' => $data->email
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal melakukan registrasi!'
    ]);
}
?>