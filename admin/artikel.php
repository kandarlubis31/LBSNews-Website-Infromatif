<?php
$current_admin_page = 'artikel';
$page_title = 'Manajemen Artikel';
require_once 'templates/header.php';
require_once '../config/database.php';

// --- LOGIKA SORTIR ---

// 1. Tentukan kolom yang diizinkan dan berikan nilai default
$allowed_sort_columns = ['judul', 'status', 'tanggal_dibuat'];
$sort_column = 'tanggal_dibuat'; // Default: urutkan berdasarkan tanggal
$sort_order = 'DESC';             // Default: urutkan dari yang terbaru

// 2. Periksa input dari URL untuk menimpa nilai default
if (isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort_columns)) {
    $sort_column = $_GET['sort'];
}
if (isset($_GET['order']) && in_array(strtolower($_GET['order']), ['asc', 'desc'])) {
    $sort_order = strtoupper($_GET['order']);
}

// 3. Bangun query SQL yang lengkap
$sql_articles = "SELECT artikel.id_artikel, artikel.judul, artikel.status, artikel.tanggal_dibuat, kategori.nama_kategori
                 FROM artikel
                 LEFT JOIN kategori ON artikel.id_kategori = kategori.id_kategori
                 ORDER BY " . $sort_column . " " . $sort_order;

// --- END LOGIKA SORTIR ---

$stmt = $koneksi->prepare($sql_articles);
$stmt->execute();
$hasil = $stmt->get_result();
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

    <a href="tambah_artikel.php" class="button-primary" style="margin-bottom: 20px;">Tambah Artikel Baru</a>

    <?php if (isset($_GET['status'])): ?>
    <div class="alert alert-success">
        <?php 
            if ($_GET['status'] == 'sukses_tambah') echo "Artikel berhasil ditambahkan!";
            if ($_GET['status'] == 'sukses_edit') echo "Artikel berhasil diperbarui!";
            if ($_GET['status'] == 'sukses_hapus') echo "Artikel berhasil dihapus!";
        ?>
    </div>
    <?php endif; ?>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <?php
                    function createSortLink($column, $display, $current_sort, $current_order) {
                        $order = 'ASC';
                        $indicator = '';
                        if ($column == $current_sort) {
                            $order = ($current_order == 'ASC') ? 'DESC' : 'ASC';
                            $indicator = ($current_order == 'ASC') ? ' ▲' : ' ▼';
                        }
                        echo '<th><a href="?sort=' . $column . '&order=' . $order . '">' . $display . $indicator . '</a></th>';
                    }
                    ?>
                    <?php createSortLink('judul', 'Judul', $sort_column, $sort_order); ?>
                    <th>Kategori</th>
                    <?php createSortLink('status', 'Status', $sort_column, $sort_order); ?>
                    <?php createSortLink('tanggal_dibuat', 'Tanggal', $sort_column, $sort_order); ?>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($hasil->num_rows > 0): ?>
                <?php while ($artikel = $hasil->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($artikel['judul']); ?></td>
                    <td><?php echo htmlspecialchars($artikel['nama_kategori'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($artikel['status']); ?></td>
                    <td><?php echo date('d M Y, H:i', strtotime($artikel['tanggal_dibuat'])); ?></td>
                    <td>
                        <a href="edit_artikel.php?id=<?php echo $artikel['id_artikel']; ?>" class="button-info button-small">Edit</a>
                        <a href="hapus_artikel.php?id=<?php echo $artikel['id_artikel']; ?>" onclick="konfirmasiHapus(event, this.href)" class="button-danger button-small">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Belum ada artikel.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$stmt->close();
require_once 'templates/footer.php'; 
?>

<?php
// Blok PHP untuk membuat notifikasi SweetAlert2
if (isset($_GET['status'])) {
    $title = '';
    $text = '';
    $icon = 'success';

    switch ($_GET['status']) {
        case 'sukses_tambah':
            $title = 'Berhasil!';
            $text = 'Artikel baru telah ditambahkan.';
            break;
        case 'sukses_edit':
            $title = 'Berhasil!';
            $text = 'Artikel telah diperbarui.';
            break;
        case 'sukses_hapus':
            $title = 'Berhasil Dihapus!';
            $text = 'Artikel telah dihapus dari database.';
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