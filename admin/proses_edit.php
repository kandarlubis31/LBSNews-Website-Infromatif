<?php
require_once 'cek_login.php';
require_once '../config/database.php';
require_once '../functions/database_helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_artikel = (int)$_POST['id_artikel'];
    $gambar_lama = $_POST['gambar_lama'];
    $image_url = trim($_POST['image_url'] ?? '');
    
    $featured_image = $gambar_lama; // Default: pertahankan gambar lama
    $is_new_image = false;

    // Prioritas 1: Cek input URL
    if (!empty($image_url) && filter_var($image_url, FILTER_VALIDATE_URL)) {
        $featured_image = $image_url;
        $is_new_image = true;
    } 
    // Prioritas 2: Cek file upload
    else if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
        $featured_image = uploadGambar($_FILES['featured_image']);
        $is_new_image = true;
    }

    // Jika ada gambar baru (dari URL atau upload), hapus file lama jika ada
    if ($is_new_image && !empty($gambar_lama) && strpos($gambar_lama, 'http') !== 0) {
        if (file_exists('../uploads/' . $gambar_lama)) {
            unlink('../uploads/' . $gambar_lama);
        }
    }

    // Ambil sisa data dari form
    $judul = trim($_POST['judul'] ?? '');
    $konten = trim($_POST['konten'] ?? '');
    $id_kategori = !empty($_POST['id_kategori']) ? (int)$_POST['id_kategori'] : null;
    $status = $_POST['status'] ?? 'draft';
    $meta_description = trim($_POST['meta_description'] ?? '');
    $tags_string = trim($_POST['tags'] ?? '');
    $slug = buatSlug($judul);
    $id_user = $_SESSION['user_id'];

    // Update database
    $sql = "UPDATE artikel SET 
                judul = ?, slug = ?, konten = ?, id_kategori = ?, id_user = ?, 
                meta_description = ?, featured_image = ?, status = ? 
            WHERE id_artikel = ?";
    
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("sssiisssi", 
        $judul, $slug, $konten, $id_kategori, $id_user, 
        $meta_description, $featured_image, $status, $id_artikel
    );
    
    if ($stmt->execute()) {
        handleTags($koneksi, $id_artikel, $tags_string);
        header("Location: artikel.php?status=sukses_edit");
    } else {
        echo "Error: Gagal mengupdate artikel. <br>";
        echo "MySQL Error: " . $stmt->error;
    }
    
    $stmt->close();
    $koneksi->close();
} else {
    header("Location: artikel.php");
    exit();
}