<?php
$current_admin_page = 'artikel';
$page_title = 'Tambah Artikel Baru';
require_once 'templates/header.php';
require_once '../config/database.php';
require_once '../functions/database_helpers.php';
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

    <div class="card">
        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Judul</label>
                <input type="text" id="judul" name="judul" required>
            </div>

            <div class="form-group">
                <label for="konten">Konten</label>
                <textarea id="konten" name="konten" rows="15"></textarea>
            </div>

            <div class="form-group">
                <label for="featured_image">Gambar Unggulan</label>
                <p style="font-size: 0.9em; color: var(--font-color-muted); margin-top: -5px; margin-bottom: 10px;">
                    Pilih salah satu: Upload file atau masukkan URL di bawah.
                </p>
                <input type="file" id="featured_image" name="featured_image" style="margin-bottom: 10px;">
                <input type="url" id="image_url" name="image_url" placeholder="Atau tempel URL gambar di sini (contoh: https://... .jpg)">
            </div>

            <div class="form-group">
                <label for="id_kategori">Kategori</label>
                <select id="id_kategori" name="id_kategori">
                    <option value="">-- Pilih Kategori --</option>
                    <?php while($kategori = $kategori_list->fetch_assoc()): ?>
                        <option value="<?php echo $kategori['id_kategori']; ?>"><?php echo htmlspecialchars($kategori['nama_kategori']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="meta_description">Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"></textarea>
            </div>

            <div class="form-group">
                <label for="tags">Tag (pisahkan dengan koma)</label>
                <input type="text" id="tags" name="tags" placeholder="Contoh: PHP, Tutorial, Web Development">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <button type="submit" class="button-primary">Simpan Artikel</button>
        </form>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>