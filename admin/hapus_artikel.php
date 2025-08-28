<?php
require_once 'cek_login.php';
require_once '../config/database.php';
require_once '../functions/database_helpers.php';

if (!isset($_GET['id'])) {
    header("Location: artikel.php"); // Arahkan ke halaman artikel jika ID tidak ada
    exit();
}

$id = (int)$_GET['id'];
$artikel = getArtikelById($koneksi, $id);

// Hapus file gambar jika ada dan bukan URL eksternal
if ($artikel && !empty($artikel['featured_image']) && strpos($artikel['featured_image'], 'http') !== 0) {
    $path_gambar = '../uploads/' . $artikel['featured_image'];
    if (file_exists($path_gambar)) {
        unlink($path_gambar);
    }
}

$sql = "DELETE FROM artikel WHERE id_artikel = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: artikel.php?status=sukses_hapus");
} else {
    echo "Error: Gagal menghapus artikel.";
}

$stmt->close();
$koneksi->close();
exit();