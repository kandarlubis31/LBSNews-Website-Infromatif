<?php
require_once 'config/database.php';

$page_title = "Hasil Pencarian";
$keyword = '';
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $keyword = trim($_GET['q']);
    $page_title = "Hasil Pencarian untuk '" . htmlspecialchars($keyword) . "'";
}

require_once 'templates/header.php';
?>

<h1><?php echo $page_title; ?></h1>

<?php
if (!empty($keyword)) {
    $sql = "SELECT artikel.judul, artikel.slug, artikel.featured_image, 
                   SUBSTRING(artikel.konten, 1, 250) AS cuplikan, 
                   artikel.tanggal_dibuat, kategori.nama_kategori
            FROM artikel 
            LEFT JOIN kategori ON artikel.id_kategori = kategori.id_kategori
            WHERE MATCH(judul, konten) AGAINST(? IN BOOLEAN MODE)
            AND status = 'published'
            ORDER BY artikel.tanggal_dibuat DESC";
    
    $stmt = $koneksi->prepare($sql);
    $search_term = $keyword . '*'; 
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $hasil = $stmt->get_result();

    if ($hasil->num_rows > 0) {
        echo '<p class="search-info">Ditemukan ' . $hasil->num_rows . ' artikel.</p>';
        while ($artikel = $hasil->fetch_assoc()) {
?>
            <div class="article-box">
                <?php if (!empty($artikel['featured_image'])): ?>
                    <a href="artikel.php?slug=<?php echo htmlspecialchars($artikel['slug']); ?>">
                        <img class="featured-image-list" src="uploads/<?php echo htmlspecialchars($artikel['featured_image']); ?>" alt="<?php echo htmlspecialchars($artikel['judul']); ?>">
                    </a>
                <?php endif; ?>
                <h2><a href="artikel.php?slug=<?php echo htmlspecialchars($artikel['slug']); ?>"><?php echo htmlspecialchars($artikel['judul']); ?></a></h2>
                <p class="article-meta">
                    Kategori <strong><?php echo htmlspecialchars($artikel['nama_kategori'] ?? 'Umum'); ?></strong> 
                    | <?php echo date('d M Y', strtotime($artikel['tanggal_dibuat'])); ?>
                </p>
                <p><?php echo htmlspecialchars($artikel['cuplikan']); ?>...</p>
                <a href="artikel.php?slug=<?php echo htmlspecialchars($artikel['slug']); ?>">Baca Selengkapnya</a>
            </div>
<?php
        }
    } else {
        echo '<p class="search-info">Tidak ada artikel yang ditemukan untuk kata kunci tersebut.</p>';
    }
    $stmt->close();
} else {
    echo '<p class="search-info">Silakan masukkan kata kunci untuk memulai pencarian.</p>';
}

$koneksi->close();
require_once 'templates/footer.php';
?>