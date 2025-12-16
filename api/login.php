<?php
// api/login.php
// Endpoint untuk login user

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Ambil data dari request
$data = json_decode(file_get_contents("php://input"));

// Validasi input
if (empty($data->email) || empty($data->password)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Email dan password harus diisi!'
    ]);
    exit();
}

// Cari user berdasarkan email
$query = "SELECT id, name, email, password FROM users WHERE email = :email";
$stmt = $db->prepare($query);
$stmt->bindParam(':email', $data->email);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verifikasi password
    if (password_verify($data->password, $user['password'])) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Login berhasil!',
            'data' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Password salah!'
        ]);
    }
} else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Email tidak terdaftar!'
    ]);
}
?>