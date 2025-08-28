<?php
require_once 'config/database.php';
require_once 'functions/database_helpers.php';
catatPengunjung($koneksi);

$current_page = 'home';
$page_title = "Website Informatif Dinamis";
require_once 'templates/header.php';
?>

<main class="container">
    <h1>Artikel Terbaru</h1>

    <div class="articles-grid">
        <?php
        $sql = "SELECT 
                    artikel.judul, 
                    artikel.slug,
                    artikel.featured_image,
                    artikel.konten,
                    artikel.tanggal_dibuat, 
                    kategori.nama_kategori,
                    users.nama_lengkap
                FROM artikel
                LEFT JOIN kategori ON artikel.id_kategori = kategori.id_kategori
                LEFT JOIN users ON artikel.id_user = users.id_user
                WHERE artikel.status = 'published'
                ORDER BY artikel.tanggal_dibuat DESC";

        $stmt = $koneksi->prepare($sql);
        $stmt->execute();
        $hasil = $stmt->get_result();

        if ($hasil->num_rows > 0) {
            while ($artikel = $hasil->fetch_assoc()) {
                $cuplikan = substr(strip_tags($artikel['konten']), 0, 150);
                $gambar_path = 'assets/images/placeholder.png';
                if (!empty($artikel['featured_image'])) {
                    if (strpos($artikel['featured_image'], 'http') === 0) {
                        $gambar_path = htmlspecialchars($artikel['featured_image']);
                    } else {
                        $gambar_path = 'uploads/' . htmlspecialchars($artikel['featured_image']);
                    }
                }
        ?>
                <div class="article-box">
                    <a href="artikel.php?slug=<?php echo htmlspecialchars($artikel['slug']); ?>" class="article-image-container">
                        <img src="<?php echo $gambar_path; ?>" alt="<?php echo htmlspecialchars($artikel['judul']); ?>">
                    </a>
                    <div class="article-content">
                        <h2><a href="artikel.php?slug=<?php echo htmlspecialchars($artikel['slug']); ?>"><?php echo htmlspecialchars($artikel['judul']); ?></a></h2>
                        <div class="article-meta">
                            <span class="meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <?php echo date('d M Y, H:i', strtotime($artikel['tanggal_dibuat'])); ?>
                            </span>
                            <span class="meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                                <?php echo htmlspecialchars($artikel['nama_kategori'] ?? 'Umum'); ?>
                            </span>
                        </div>
                        <p class="article-excerpt"><?php echo htmlspecialchars($cuplikan); ?>...</p>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p>Belum ada artikel yang dipublikasikan.</p>';
        }
        $stmt->close();
        $koneksi->close();
        ?>
    </div>
</main>

<?php
require_once 'templates/footer.php';
?>