<?php
$current_admin_page = 'kategori';
$page_title = 'Manajemen Kategori';
require_once 'templates/header.php';
require_once '../config/database.php';
require_once '../functions/database_helpers.php';

$pesan_error = null;

// Proses penambahan kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_kategori'])) {
    $sukses = createKategori($koneksi, $_POST['nama_kategori']);
    if ($sukses) {
        header("Location: kategori.php?status=sukses_tambah");
        exit();
    } else {
        $pesan_error = "Gagal menambahkan kategori. Kemungkinan nama kategori sudah ada.";
    }
}

$kategori_list = getAllKategori($koneksi);
?>

<?php require_once 'templates/sidebar.php'; ?>

<div class="main-content">
    <div class="admin-header-bar">
        <button id="admin-hamburger" class="hamburger" aria-label="Toggle sidebar">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
        <h1><?php echo $page_title; ?></h1>
    </div>

    <?php if ($pesan_error): ?>
        <div class="alert alert-error">
            <?php echo $pesan_error; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3>Tambah Kategori Baru</h3>
        <form action="kategori.php" method="POST">
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" required>
            </div>
            <button type="submit" name="tambah_kategori" class="button-primary">Tambah</button>
        </form>
    </div>

    <div class="card">
        <h3>Daftar Kategori</h3>
        <table>
            <tbody>
                <?php if ($kategori_list->num_rows > 0): ?>
                    <?php while ($kategori = $kategori_list->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($kategori['nama_kategori']); ?></td>
                            <td><?php echo htmlspecialchars($kategori['slug_kategori']); ?></td>
                            <td>
                                <a href="edit_kategori.php?id=<?php echo $kategori['id_kategori']; ?>" class="button-info button-small">Edit</a>
                                <a href="hapus_kategori.php?id=<?php echo $kategori['id_kategori']; ?>" onclick="konfirmasiHapus(event, this.href)" class="button-danger button-small">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Belum ada kategori.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>

<?php
// Blok PHP untuk membuat notifikasi SweetAlert2
if (isset($_GET['status'])) {
    $title = '';
    $text = '';
    $icon = 'success';

    switch ($_GET['status']) {
        case 'sukses_tambah':
            $title = 'Berhasil!';
            $text = 'Kategori baru telah ditambahkan.';
            break;
        case 'sukses_edit':
            $title = 'Berhasil!';
            $text = 'Kategori telah diperbarui.';
            break;
        case 'sukses_hapus':
            $title = 'Berhasil Dihapus!';
            $text = 'Kategori telah dihapus dari database.';
            break;
    }

    if ($title) {
        echo "
            <script>
                const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
                Swal.fire({
                    title: '{$title}',
                    text: '{$text}',
                    icon: '{$icon}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: isDarkMode ? '#242526' : '#ffffff',
                    color: isDarkMode ? '#e4e6eb' : '#1c1e21',
                    toast: true,
                    position: 'top-end',
                    timerProgressBar: true
                });
            </script>
        ";
    }
}
?>