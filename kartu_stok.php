<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $kartuStokModel = new KartuStokModel($db);
    $daftarMutasi   = $kartuStokModel->find_all(); // Membaca dari vu_laporan_kartu_stok
} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Stok - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>
    <?php include_once("sidebar.php"); ?>
    <div class="main-content">
        <div class="page-header">
            <h2>Laporan Mutasi Kartu Stok</h2>
            <p style="margin:5px 0 0; color:#6c757d; font-size:14px;">Riwayat pergerakan barang masuk & keluar yang dicatat otomatis oleh Trigger database.</p>
        </div>

        <table>
            <tr>
                <th>ID Log</th>
                <th>Waktu</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Jenis Mutasi</th>
                <th>Ref. Transaksi</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Saldo Stok</th>
            </tr>
            <?php if (empty($daftarMutasi)): ?>
                <tr><td colspan="9" style="text-align:center; color:#6c757d;">Belum ada mutasi stok yang tercatat.</td></tr>
            <?php else: ?>
                <?php foreach ($daftarMutasi as $row): ?>
                <tr>
                    <td><?= $row['idkartu_stok'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($row['nama_satuan']) ?></td>
                    <td><?= htmlspecialchars($row['jenis_ket']) ?></td>
                    <td>#<?= $row['idtransaksi'] ?></td>
                    <td style="color:#198754; font-weight:bold;">+<?= $row['masuk'] ?></td>
                    <td style="color:#dc3545; font-weight:bold;">-<?= $row['keluar'] ?></td>
                    <td><strong><?= $row['stock'] ?></strong></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>