<?php
require_once 'config/database.php';
require_once 'functions/database_helpers.php';
catatPengunjung($koneksi);

if (!isset($_GET['slug']) || empty(trim($_GET['slug']))) {
    header("Location: index.php");
    exit();
}

$slug = trim($_GET['slug']);
$sql = "SELECT artikel.*, kategori.nama_kategori, users.nama_lengkap 
        FROM artikel 
        LEFT JOIN kategori ON artikel.id_kategori = kategori.id_kategori
        LEFT JOIN users ON artikel.id_user = users.id_user
        WHERE artikel.slug = ? AND artikel.status = 'published'";
        
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $slug);
$stmt->execute();
$hasil = $stmt->get_result();
$artikel = $hasil->fetch_assoc();

if (!$artikel) {
    http_response_code(404);
    $page_title = "404 Not Found";
    require_once 'templates/header.php';
    echo '<main class="container"><div class="static-page-container"><h1>404 Not Found</h1><p>Artikel tidak ditemukan.</p></div></main>';
    $stmt->close();
    $koneksi->close();
    require_once 'templates/footer.php';
    exit();
}

$nav_artikel = getPrevNextArticle($koneksi, $artikel['id_artikel']);
$tags_string = getTagsForArticle($koneksi, $artikel['id_artikel']);
$tags_array = !empty($tags_string) ? explode(', ', $tags_string) : [];
$current_page = 'artikel';
$page_title = htmlspecialchars($artikel['judul']);
$meta_description = !empty($artikel['meta_description']) 
    ? htmlspecialchars($artikel['meta_description']) 
    : htmlspecialchars(substr(strip_tags($artikel['konten']), 0, 160));

require_once 'templates/header.php';

$gambar_path = null;
if (!empty($artikel['featured_image'])) {
    if (strpos($artikel['featured_image'], 'http') === 0) {
        $gambar_path = htmlspecialchars($artikel['featured_image']);
    } else {
        $gambar_path = 'uploads/' . htmlspecialchars($artikel['featured_image']);
    }
}
?>

<main class="container">
    <article class="article-container">
        <div class="article-full">
            <h1><?php echo htmlspecialchars($artikel['judul']); ?></h1>
            
            <div class="article-meta">
                <span class="meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <?php echo date('d M Y, H:i', strtotime($artikel['tanggal_dibuat'])); ?>
                </span>
                <span class="meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    <?php echo htmlspecialchars($artikel['nama_kategori'] ?? 'Umum'); ?>
                </span>
                <span class="meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <?php echo htmlspecialchars($artikel['nama_lengkap'] ?? 'Penulis'); ?>
                </span>
            </div>

            <?php if ($gambar_path): ?>
                <img src="<?php echo $gambar_path; ?>" alt="<?php echo htmlspecialchars($artikel['judul']); ?>" class="featured-image-full">
            <?php endif; ?>
            
            <div class="article-content">
                <?php echo $artikel['konten']; ?>
            </div>
            
            <?php if (!empty($tags_array)): ?>
            <div class="article-tags">
                <?php foreach ($tags_array as $tag): ?>
                    <a href="#" class="tag-link">#<?php echo htmlspecialchars($tag); ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <div class="article-navigation">
                <div>
                    <?php if ($nav_artikel['prev']): ?>
                        <a href="artikel.php?slug=<?php echo $nav_artikel['prev']['slug']; ?>">&laquo; Artikel Sebelumnya</a>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if ($nav_artikel['next']): ?>
                        <a href="artikel.php?slug=<?php echo $nav_artikel['next']['slug']; ?>">Artikel Berikutnya &raquo;</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>
</main>

<?php
$stmt->close();
$koneksi->close();
require_once 'templates/footer.php';
?>