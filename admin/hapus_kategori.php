<?php
require_once 'cek_login.php';
require_once '../config/database.php';
require_once '../functions/database_helpers.php';

if (!isset($_GET['id'])) {
    header("Location: kategori.php");
    exit();
}

$id = (int)$_GET['id'];
deleteKategori($koneksi, $id);
header("Location: kategori.php?status=sukses_hapus");
exit();