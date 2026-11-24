<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    
    // Memanfaatkan Polymorphism: semua model master adalah turunan BaseModel
    $ringkasan = [
        ['judul' => 'Total Barang', 'link' => 'barang.php', 'warna' => '#0d6efd', 'jumlah' => count((new BarangModel($db))->find_all())],
        ['judul' => 'Total Vendor', 'link' => 'vendor.php', 'warna' => '#198754', 'jumlah' => count((new VendorModel($db))->find_all())],
        ['judul' => 'Total Pegawai (User)', 'link' => 'user.php', 'warna' => '#fd7e14', 'jumlah' => count((new UserModel($db))->find_all())],
        ['judul' => 'Total Role', 'link' => 'role.php', 'warna' => '#6f42c1', 'jumlah' => count((new RoleModel($db))->find_all())],
        ['judul' => 'Total Satuan', 'link' => 'satuan.php', 'warna' => '#20c997', 'jumlah' => count((new SatuanModel($db))->find_all())],
        ['judul' => 'Margin Penjualan', 'link' => 'margin_penjualan.php', 'warna' => '#dc3545', 'jumlah' => count((new MarginModel($db))->find_all())],
    ];
} catch (DatabaseException $e) {
    die("Kesalahan Database: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>

    <?php include_once("sidebar.php"); ?>

    <div class="main-content">
        <div class="page-header">
            <h2>Dashboard Toko Elektronik</h2>
        </div>

        <div class="grid-cards">
            <?php foreach ($ringkasan as $item): ?>
                <a href="<?= $item['link'] ?>" class="stat-card" style="border-left-color: <?= $item['warna'] ?>;">
                    <h4><?= $item['judul'] ?></h4>
                    <div class="angka"><?= $item['jumlah'] ?></div>
                    <span class="sub">Kelola Data &rarr;</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>