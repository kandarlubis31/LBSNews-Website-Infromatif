<?php
$current_page = 'tentang';
$page_title = "Tentang Kami";
require_once 'templates/header.php';
require_once 'config/database.php';
require_once 'functions/database_helpers.php'; // <-- BARIS PERBAIKAN
catatPengunjung($koneksi);
?>

<div class="static-page-container">
    <h1>Tentang Kami</h1>

    <p>
        Selamat datang di <strong>LBS News</strong> — sebuah website informatif dinamis yang dirancang
        untuk menyajikan artikel, tutorial, dan berbagai informasi bermanfaat seputar teknologi,
        pemrograman, dan perkembangan dunia digital.
    </p>

    <h2>Visi Kami</h2>
    <p>
        Menjadi sumber informasi terpercaya dan mudah dipahami bagi siapa saja yang ingin
        belajar dan mengikuti perkembangan teknologi.
    </p>

    <h2>Misi Kami</h2>
    <ul>
        <li>Menyediakan konten berkualitas dalam bentuk artikel, tutorial, dan tips seputar IT.</li>
        <li>Mendukung pembelajaran pemula hingga menengah di bidang pemrograman web.</li>
        <li>Membangun komunitas pembaca yang aktif berbagi pengetahuan.</li>
    </ul>

    <h2>Hubungi Kami</h2>
    <p>
        Jika Anda memiliki pertanyaan, saran, atau ingin berkolaborasi, jangan ragu untuk
        menghubungi kami melalui halaman <a href="kontak.php">Kontak</a>.
    </p>
</div>

<?php
$koneksi->close();
require_once 'templates/footer.php';
?>