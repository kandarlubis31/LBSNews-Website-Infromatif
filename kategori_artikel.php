<?php
require_once 'config/database.php';
require_once 'functions/database_helpers.php';
catatPengunjung($koneksi);

$current_page = 'kategori';

if (!isset($_GET['slug']) || empty(trim($_GET['slug']))) {
    header("Location: kategori.php");
    exit();
}

$slug_kategori = trim($_GET['slug']);

// Query untuk mengambil detail kategori dan artikelnya sekaligus
$sql = "SELECT 
            a.judul, 
            a.slug,
            a.featured_image,
            SUBSTRING(a.konten, 1, 150) AS cuplikan,
            a.tanggal_dibuat, 
            k.nama_kategori,
            u.nama_lengkap
        FROM artikel a
        JOIN kategori k ON a.id_kategori = k.id_kategori
        LEFT JOIN users u ON a.id_user = u.id_user
        WHERE a.status = 'published' AND k.slug_kategori = ?
        ORDER BY a.tanggal_dibuat DESC";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $slug_kategori);
$stmt->execute();
$hasil = $stmt->get_result();

// Ambil nama kategori dari hasil pertama untuk judul halaman
$nama_kategori = 'Kategori';
$artikel_pertama = $hasil->fetch_assoc();
if ($artikel_pertama) {
    $nama_kategori = $artikel_pertama['nama_kategori'];
}

$page_title = "Artikel Kategori: " . htmlspecialchars($nama_kategori);
require_once 'templates/header.php';
?>

<h1>Artikel dalam Kategori: <?php echo htmlspecialchars($nama_kategori); ?></h1>

<div class="articles-grid">
    <?php
    if ($artikel_pertama) {
        // Karena kita sudah mengambil baris pertama, kita tampilkan dulu
        // lalu lanjutkan loop untuk sisa datanya
        $data_artikel = array_merge([$artikel_pertama], $hasil->fetch_all(MYSQLI_ASSOC));
        
        foreach ($data_artikel as $artikel) {
    ?>
            <div class="article-box">
                <a href="artikel.php?slug=<?php echo htmlspecialchars($artikel['slug']); ?>" class="article-image-container">
                    <img src="uploads/<?php echo htmlspecialchars($artikel['featured_image'] ?? 'placeholder.png'); ?>" alt="<?php echo htmlspecialchars($artikel['judul']); ?>">
                </a>
                <div class="article-content">
                    <h2><a href="artikel.php?slug=<?php echo htmlspecialchars($artikel['slug']); ?>"><?php echo htmlspecialchars($artikel['judul']); ?></a></h2>
                    <div class="article-meta">
                        <span class="meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <?php echo date('d M Y', strtotime($artikel['tanggal_dibuat'])); ?>
                        </span>
                        <span class="meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                            <?php echo htmlspecialchars($artikel['nama_kategori'] ?? 'Umum'); ?>
                        </span>
                    </div>
                    <p class="article-excerpt"><?php echo htmlspecialchars(strip_tags($artikel['cuplikan'])); ?>...</p>
                </div>
            </div>
    <?php
        }
    } else {
        echo '<p>Tidak ada artikel yang ditemukan dalam kategori ini.</p>';
    }
    $stmt->close();
    $koneksi->close();
    ?>
</div>

<?php
require_once 'templates/footer.php';
?>