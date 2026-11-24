<?php $halaman_aktif = basename($_SERVER['PHP_SELF']); ?>
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; display: flex; min-height: 100vh; background-color: #f4f6f9; }
    .sidebar { width: 240px; background-color: #212529; color: white; position: fixed; height: 100vh; overflow-y: auto; }
    .sidebar h3 { text-align: center; padding: 20px 10px; margin: 0; background-color: #16181b; border-bottom: 1px solid #343a40; font-size: 16px; }
    .sidebar a { display: block; color: #adb5bd; padding: 13px 20px; text-decoration: none; border-bottom: 1px solid #2c3034; font-size: 14px; }
    .sidebar a:hover { background-color: #343a40; color: white; }
    .sidebar a.active { background-color: #0d6efd; color: white; font-weight: bold; }
    .main-content { margin-left: 240px; padding: 30px; width: 100%; }
    .card-form { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.08); max-width: 450px; margin-bottom: 25px; }
    label { font-weight: 600; font-size: 14px; display: block; margin-top: 10px; }
    input[type="text"], input[type="password"], input[type="number"], select { width: 100%; padding: 9px; margin-top: 5px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box; }
    button { margin-top: 15px; padding: 10px; background-color: #198754; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
    button:hover { background-color: #157347; }
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.08); border-radius: 8px; overflow: hidden; }
    th, td { border: 1px solid #dee2e6; padding: 11px 14px; text-align: left; font-size: 14px; }
    th { background-color: #0d6efd; color: white; }
    tr:nth-child(even) { background-color: #f8f9fa; }
</style>

<div class="sidebar">
    <h3><?= Konfigurasi::APP_NAME ?></h3>
    <div class="sidebar-section">Data Master</div>
    <a href="index.php" class="<?= $halaman_aktif == 'index.php' ? 'active' : '' ?>">Dashboard Utama</a>
    <a href="role.php" class="<?= $halaman_aktif == 'role.php' ? 'active' : '' ?>">Data Role</a>
    <a href="satuan.php" class="<?= $halaman_aktif == 'satuan.php' ? 'active' : '' ?>">Data Satuan</a>
    <a href="vendor.php" class="<?= $halaman_aktif == 'vendor.php' ? 'active' : '' ?>">Data Vendor</a>
    <a href="user.php" class="<?= $halaman_aktif == 'user.php' ? 'active' : '' ?>">Data User</a>
    <a href="barang.php" class="<?= $halaman_aktif == 'barang.php' ? 'active' : '' ?>">Data Barang</a>
    <a href="margin_penjualan.php" class="<?= $halaman_aktif == 'margin_penjualan.php' ? 'active' : '' ?>">Data Margin</a>
    <div class="sidebar-section">Transaksi</div>
        <a href="pengadaan.php" class="<?= $halaman_aktif == 'pengadaan.php' ? 'active' : '' ?>">Pengadaan</a>
        <a href="penerimaan.php" class="<?= $halaman_aktif == 'penerimaan.php' ? 'active' : '' ?>">Penerimaan Barang</a>
        <a href="penjualan.php" class="<?= $halaman_aktif == 'penjualan.php' ? 'active' : '' ?>">Penjualan</a>
        <a href="retur.php" class="<?= $halaman_aktif == 'retur.php' ? 'active' : '' ?>">Retur Barang</a>

        <div class="sidebar-section">Laporan</div>
        <a href="kartu_stok.php" class="<?= $halaman_aktif == 'kartu_stok.php' ? 'active' : '' ?>">Kartu Stok</a>
</div>