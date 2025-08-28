<?php
$current_admin_page = 'dashboard';
$page_title = 'Dashboard';
require_once 'templates/header.php';
require_once '../config/database.php';
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

    <?php
    // Query untuk statistik artikel
    $sql_stats_artikel = "SELECT 
                    COUNT(*) as total, 
                    SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published,
                    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft
                  FROM artikel";
    $stats_artikel_result = $koneksi->query($sql_stats_artikel);
    $stats_artikel = $stats_artikel_result->fetch_assoc();

    // Query untuk statistik pengunjung
    $sql_stats_pengunjung = "SELECT 
                                COUNT(*) as total_views,
                                COUNT(DISTINCT ip_address) as unique_visitors,
                                (SELECT COUNT(DISTINCT ip_address) FROM pengunjung WHERE DATE(waktu_kunjungan) = CURDATE()) as visitors_today
                             FROM pengunjung";
    $stats_pengunjung_result = $koneksi->query($sql_stats_pengunjung);
    $stats_pengunjung = $stats_pengunjung_result->fetch_assoc();
    ?>

    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Total Artikel</h3>
            <p><?php echo $stats_artikel['total'] ?? 0; ?></p>
        </div>
        <div class="stat-card">
            <h3>Artikel Terbit</h3>
            <p><?php echo $stats_artikel['published'] ?? 0; ?></p>
        </div>
        <div class="stat-card">
            <h3>Pengunjung Unik</h3>
            <p><?php echo $stats_pengunjung['unique_visitors'] ?? 0; ?></p>
        </div>
        <div class="stat-card">
            <h3>Pengunjung Hari Ini</h3>
            <p><?php echo $stats_pengunjung['visitors_today'] ?? 0; ?></p>
        </div>
    </div>

    <h2>Aktivitas Terbaru</h2>
    <div class="card">
        <p>Selamat datang di panel admin. Anda bisa mulai mengelola artikel atau kategori melalui menu di samping.</p>
    </div>
</div>

<?php
require_once 'templates/footer.php';
?>