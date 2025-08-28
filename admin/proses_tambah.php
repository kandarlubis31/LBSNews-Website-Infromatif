<?php
require_once 'cek_login.php';
require_once '../config/database.php';
require_once '../functions/database_helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $konten = trim($_POST['konten'] ?? '');
    $id_kategori = !empty($_POST['id_kategori']) ? (int)$_POST['id_kategori'] : null;
    $meta_description = trim($_POST['meta_description'] ?? '');
    $status = $_POST['status'] ?? 'draft';
    $image_url = trim($_POST['image_url'] ?? '');
    $tags_string = trim($_POST['tags'] ?? '');
    $slug = buatSlug($judul);
    $id_user = $_SESSION['user_id'];
    $tanggal_dibuat = date('Y-m-d H:i:s');
    $featured_image = null;
    if (!empty($image_url) && filter_var($image_url, FILTER_VALIDATE_URL)) {
        $featured_image = $image_url;
    } else if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
        $featured_image = uploadGambar($_FILES['featured_image']);
    }

    $sql = "INSERT INTO artikel (judul, slug, konten, tanggal_dibuat, id_kategori, id_user, meta_description, featured_image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssssiisss", $judul, $slug, $konten, $tanggal_dibuat, $id_kategori, $id_user, $meta_description, $featured_image, $status);
    
    if ($stmt->execute()) {
        $id_artikel_baru = $stmt->insert_id;
        handleTags($koneksi, $id_artikel_baru, $tags_string);
        header("Location: artikel.php?status=sukses_tambah");
    } else {
        echo "Error: Gagal menyimpan artikel. <br>";
        echo "MySQL Error: " . $stmt->error;
    }
    
    $stmt->close();
    $koneksi->close();
} else {
    header("Location: tambah_artikel.php");
    exit();
}