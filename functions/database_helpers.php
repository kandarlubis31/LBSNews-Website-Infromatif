<?php
function buatSlug($teks) {
    $teks = preg_replace('~[^\pL\d]+~u', '-', $teks);
    $teks = iconv('utf-8', 'us-ascii//TRANSLIT', $teks);
    $teks = preg_replace('~[^-\w]+~', '', $teks);
    $teks = trim($teks, '-');
    $teks = preg_replace('~-+~', '-', $teks);
    $teks = strtolower($teks);
    if (empty($teks)) { return 'n-a-' . time(); }
    return $teks;
}

function uploadGambar($file) {
    if ($file['error'] === 4) { return null; }
    $namaFile = $file['name'];
    $ukuranFile = $file['size'];
    $tmpName = $file['tmp_name'];
    $ekstensiValid = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensiGambar = strtolower(end(explode('.', $namaFile)));
    if (!in_array($ekstensiGambar, $ekstensiValid)) { die("Error: Tipe file gambar tidak valid."); }
    if ($ukuranFile > 2097152) { die("Error: Ukuran gambar terlalu besar (Maks 2MB)."); }
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    $tujuanUpload = '../uploads/' . $namaFileBaru;
    if (!move_uploaded_file($tmpName, $tujuanUpload)) { die("Error: Gagal mengupload gambar."); }
    return $namaFileBaru;
}

function catatPengunjung($koneksi) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $halaman = $_SERVER['REQUEST_URI'];
    $sql = "INSERT INTO pengunjung (ip_address, user_agent, halaman_dikunjungi) VALUES (?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("sss", $ip_address, $user_agent, $halaman);
    $stmt->execute();
    $stmt->close();
}

function getArtikelById($koneksi, $id) {
    $sql = "SELECT * FROM artikel WHERE id_artikel = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getPrevNextArticle($koneksi, $current_id) {
    $nav = ['prev' => null, 'next' => null];
    $sql_prev = "SELECT slug, judul FROM artikel WHERE id_artikel < ? AND status = 'published' ORDER BY id_artikel DESC LIMIT 1";
    $stmt_prev = $koneksi->prepare($sql_prev);
    $stmt_prev->bind_param("i", $current_id);
    $stmt_prev->execute();
    $nav['prev'] = $stmt_prev->get_result()->fetch_assoc();
    $stmt_prev->close();
    $sql_next = "SELECT slug, judul FROM artikel WHERE id_artikel > ? AND status = 'published' ORDER BY id_artikel ASC LIMIT 1";
    $stmt_next = $koneksi->prepare($sql_next);
    $stmt_next->bind_param("i", $current_id);
    $stmt_next->execute();
    $nav['next'] = $stmt_next->get_result()->fetch_assoc();
    $stmt_next->close();
    return $nav;
}

function getAllKategori($koneksi) {
    $sql = "SELECT id_kategori, nama_kategori, slug_kategori FROM kategori ORDER BY nama_kategori ASC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function getKategoriById($koneksi, $id) {
    $sql = "SELECT * FROM kategori WHERE id_kategori = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function createKategori($koneksi, $nama_kategori) {
    $slug = buatSlug($nama_kategori);
    $sql = "INSERT INTO kategori (nama_kategori, slug_kategori) VALUES (?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $nama_kategori, $slug);
    return $stmt->execute();
}

function updateKategori($koneksi, $id, $nama_kategori) {
    $slug = buatSlug($nama_kategori);
    $sql = "UPDATE kategori SET nama_kategori = ?, slug_kategori = ? WHERE id_kategori = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssi", $nama_kategori, $slug, $id);
    return $stmt->execute();
}

function deleteKategori($koneksi, $id) {
    $sql = "DELETE FROM kategori WHERE id_kategori = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function handleTags($koneksi, $id_artikel, $tags_string) {
    $sql_delete = "DELETE FROM artikel_tags WHERE id_artikel = ?";
    $stmt_delete = $koneksi->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id_artikel);
    $stmt_delete->execute();
    $stmt_delete->close();

    $tags = array_map('trim', explode(',', $tags_string));
    $tags = array_unique(array_filter($tags));

    if (empty($tags)) return;

    foreach ($tags as $nama_tag) {
        $id_tag = null;
        $sql_check = "SELECT id_tag FROM tags WHERE nama_tag = ?";
        $stmt_check = $koneksi->prepare($sql_check);
        $stmt_check->bind_param("s", $nama_tag);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        if ($row = $result->fetch_assoc()) {
            $id_tag = $row['id_tag'];
        }
        $stmt_check->close();

        if (!$id_tag) {
            $sql_insert_tag = "INSERT INTO tags (nama_tag) VALUES (?)";
            $stmt_insert_tag = $koneksi->prepare($sql_insert_tag);
            $stmt_insert_tag->bind_param("s", $nama_tag);
            $stmt_insert_tag->execute();
            $id_tag = $stmt_insert_tag->insert_id;
            $stmt_insert_tag->close();
        }

        if ($id_tag) {
            $sql_link = "INSERT INTO artikel_tags (id_artikel, id_tag) VALUES (?, ?)";
            $stmt_link = $koneksi->prepare($sql_link);
            $stmt_link->bind_param("ii", $id_artikel, $id_tag);
            $stmt_link->execute();
            $stmt_link->close();
        }
    }
}

function getTagsForArticle($koneksi, $id_artikel) {
    $sql = "SELECT t.nama_tag FROM tags t JOIN artikel_tags at ON t.id_tag = at.id_tag WHERE at.id_artikel = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_artikel);
    $stmt->execute();
    $result = $stmt->get_result();
    $tags = [];
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['nama_tag'];
    }
    $stmt->close();
    return implode(', ', $tags);
}