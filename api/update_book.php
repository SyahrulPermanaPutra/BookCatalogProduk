<?php
// api/update_book.php
// Endpoint untuk update data buku (versi JSON)

require_once '../config/database.php';

header("Content-Type: application/json");

// Baca input sebagai JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Ambil data dari JSON
$id          = $data['id'] ?? null;
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

$hasil = "Gagal mengupdate buku";
$success = false;
$updated_book = null;

// Validasi WAJIB
if ($id && $title && $author) {

    // Cek apakah buku ada
    $cek = $db->prepare("SELECT id FROM books WHERE id = :id");
    $cek->bindParam(':id', $id);
    $cek->execute();

    if ($cek->rowCount() > 0) {

        // Update data buku
        $query = "UPDATE books SET
                    title = :title,
                    author = :author,
                    isbn = :isbn,
                    publisher = :publisher,
                    year = :year,
                    pages = :pages,
                    description = :description,
                    cover_url = :cover_url,
                    updated_at = NOW()
                  WHERE id = :id";

        $stmt = $db->prepare($query);

        $stmt->bindParam(':id', $id);
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
            $hasil = "Berhasil mengupdate buku";
            
            // Ambil data buku yang sudah diupdate untuk response
            $getQuery = "SELECT * FROM books WHERE id = :id";
            $getStmt = $db->prepare($getQuery);
            $getStmt->bindParam(':id', $id);
            $getStmt->execute();
            $updated_book = $getStmt->fetch(PDO::FETCH_ASSOC);
        }

    } else {
        $hasil = "Buku tidak ditemukan";
    }
}

// Response JSON yang lebih informatif
echo json_encode([
    'success' => $success,
    'message' => $hasil,
    'data' => [
        'result' => $hasil,
        'book' => $updated_book
    ]
]);