<?php
$current_admin_page = 'artikel';
$page_title = 'Edit Artikel';
require_once 'templates/header.php';
require_once '../config/database.php';
require_once '../functions/database_helpers.php';
if (!isset($_GET['id'])) { header("Location: artikel.php"); exit(); }
$id = (int)$_GET['id'];
$artikel = getArtikelById($koneksi, $id);
$tags_artikel = getTagsForArticle($koneksi, $id);
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
    <?php if (!$artikel): ?>
        <div class="alert alert-error">Artikel tidak ditemukan.</div>
    <?php else: ?>
        <div class="card">
            <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_artikel" value="<?php echo $artikel['id_artikel']; ?>">
                <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($artikel['featured_image'] ?? ''); // Memastikan nilai selalu string ?>">
                
                <div class="form-group">
                    <label for="judul">Judul</label>
                    <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($artikel['judul']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="konten">Konten</label>
                    <textarea id="konten" name="konten" rows="15"><?php echo $artikel['konten']; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="featured_image">Gambar Unggulan</label>
                    <p style="font-size: 0.9em; color: var(--font-color-muted); margin-top: -5px; margin-bottom: 10px;">Pilih salah satu: Upload file baru atau masukkan URL. Biarkan kosong untuk memakai gambar lama.</p>
                    <input type="file" id="featured_image" name="featured_image" style="margin-bottom: 10px;">
                    <input type="url" id="image_url" name="image_url" placeholder="Atau tempel URL gambar baru di sini">
                    
                    <?php 
                        if (!empty($artikel['featured_image'])) {
                            $is_external = strpos($artikel['featured_image'], 'http') === 0;
                            $gambar_path = $is_external ? htmlspecialchars($artikel['featured_image']) : '../uploads/' . htmlspecialchars($artikel['featured_image']);
                            
                            echo '<p style="margin-top:10px;">Gambar saat ini:</p>';
                            
                            if ($is_external || file_exists($gambar_path)) {
                                echo '<img src="'.$gambar_path.'" width="200" style="border-radius: 8px;">';
                            } else {
                                echo '<p style="font-style: italic; color: #dc3545;">(File gambar tidak ditemukan di server)</p>';
                            }
                        }
                    ?>
                </div>

                <div class="form-group">
                    <label for="id_kategori">Kategori</label>
                    <select id="id_kategori" name="id_kategori">
                        <option value="">-- Pilih Kategori --</option>
                        <?php mysqli_data_seek($kategori_list, 0); ?>
                        <?php while($kategori = $kategori_list->fetch_assoc()): ?>
                            <option value="<?php echo $kategori['id_kategori']; ?>" <?php if($kategori['id_kategori'] == $artikel['id_kategori']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"><?php echo htmlspecialchars($artikel['meta_description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="tags">Tag (pisahkan dengan koma)</label>
                    <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($tags_artikel); ?>" placeholder="Contoh: PHP, Tutorial, Web Development">
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="published" <?php if($artikel['status'] == 'published') echo 'selected'; ?>>Published</option>
                        <option value="draft" <?php if($artikel['status'] == 'draft') echo 'selected'; ?>>Draft</option>
                    </select>
                </div>
                
                <button type="submit" class="button-primary">Update Artikel</button>
            </form>
        </div>
    <?php endif; ?>
</div>
<?php require_once 'templates/footer.php'; ?>