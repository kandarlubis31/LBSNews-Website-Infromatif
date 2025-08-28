<?php
require_once 'config/database.php';
require_once 'functions/database_helpers.php';
catatPengunjung($koneksi);

$current_page = 'kategori';
$page_title = "Daftar Kategori";
require_once 'templates/header.php';

$kategori_list = getAllKategori($koneksi);
?>

<h1>Jelajahi Berdasarkan Kategori</h1>
<p>Temukan artikel berdasarkan topik yang Anda minati.</p>

<div class="category-grid">
    <?php if ($kategori_list->num_rows > 0): ?>
        <?php while ($kategori = $kategori_list->fetch_assoc()): ?>
            <a href="kategori_artikel.php?slug=<?php echo htmlspecialchars($kategori['slug_kategori']); ?>" class="category-card">
                <div class="category-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <h3><?php echo htmlspecialchars($kategori['nama_kategori']); ?></h3>
            </a>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada kategori yang tersedia.</p>
    <?php endif; ?>
</div>

<?php
$koneksi->close();
require_once 'templates/footer.php';
?>