<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $penjualanModel = new PenjualanModel($db);
    $userModel      = new UserModel($db);
    $marginModel    = new MarginModel($db);
    $barangModel    = new BarangModel($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $aksi = $_POST['aksi'] ?? '';
        if ($aksi === 'tambah_header') {
            $res = $penjualanModel->insert($_POST);
            Flash::set($res->status ? "Nota penjualan baru dibuka." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: penjualan.php"); exit;
        } elseif ($aksi === 'tambah_detail') {
            $idJual = (int) $_POST['penjualan_idpenjualan'];
            $res = $penjualanModel->insert_detail($_POST);
            Flash::set($res->status ? "Barang masuk ke keranjang nota #$idJual." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: penjualan.php?detail=$idJual"); exit;
        }
    }

    $daftarPenjualan = $penjualanModel->find_all();
    $daftarUser      = $userModel->find_all();
    $daftarMargin    = $marginModel->find_all();
    $daftarBarang    = $barangModel->find_all();

    $idDetail   = isset($_GET['detail']) ? (int) $_GET['detail'] : null;
    $listDetail = $idDetail ? $penjualanModel->get_detail($idDetail) : [];

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penjualan (Kasir) - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>
    <?php include_once("sidebar.php"); ?>
    <div class="main-content">
        <div class="page-header"><h2>Transaksi Penjualan (Kasir)</h2></div>
        <?php Flash::tampilkan(); ?>

        <div class="card-form">
            <h4 style="margin-top:0;">Buka Nota Penjualan Baru</h4>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah_header">
                <label>Kasir (User):</label>
                <select name="iduser" required>
                    <option value="">-- Pilih Kasir --</option>
                    <?php foreach ($daftarUser as $u): ?>
                        <option value="<?= $u['ID User'] ?? $u['iduser'] ?>"><?= htmlspecialchars($u['Username'] ?? $u['username']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Margin Penjualan:</label>
                <select name="idmargin_penjualan" required>
                    <option value="">-- Pilih Margin --</option>
                    <?php foreach ($daftarMargin as $m): ?>
                        <?php $mId = $m['ID Margin'] ?? $m['idmargin_penjualan']; $mPersen = $m['Persen'] ?? $m['persen']; ?>
                        <option value="<?= $mId ?>">Margin <?= $mPersen ?>%</option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Buat Nota Penjualan</button>
            </form>
        </div>

        <table>
            <tr><th>ID Nota</th><th>Waktu</th><th>Kasir</th><th>Margin</th><th>Subtotal</th><th>PPN</th><th>Grand Total</th><th>Aksi</th></tr>
            <?php foreach ($daftarPenjualan as $row): ?>
            <tr>
                <td>#<?= $row['idpenjualan'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['margin_persen'] ?>%</td>
                <td>Rp <?= number_format($row['subtotal_nilai'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($row['ppn'], 0, ',', '.') ?></td>
                <td><strong>Rp <?= number_format($row['total_nilai'], 0, ',', '.') ?></strong></td>
                <td class="aksi"><a href="?detail=<?= $row['idpenjualan'] ?>" class="edit">Input Belanja</a></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($idDetail): ?>
        <div style="margin-top: 30px; padding: 20px; background: #fff; border-radius: 8px; border-top: 4px solid #fd7e14;">
            <h3>Keranjang Belanja — Nota #<?= $idDetail ?> <a href="penjualan.php" style="font-size:13px; color:#dc3545; float:right;">Tutup Keranjang</a></h3>
            <form method="POST" style="display:flex; gap:10px; align-items:flex-end; margin-bottom:20px; flex-wrap:wrap;">
                <input type="hidden" name="aksi" value="tambah_detail">
                <input type="hidden" name="penjualan_idpenjualan" value="<?= $idDetail ?>">
                <div style="flex:2; min-width:200px;">
                    <label>Pilih Barang:</label>
                    <select name="idbarang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($daftarBarang as $b): ?>
                            <option value="<?= $b['ID Barang'] ?? $b['idbarang'] ?>"><?= htmlspecialchars($b['Nama Barang'] ?? $b['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="flex:1; min-width:130px;">
                    <label>Harga Jual Satuan:</label>
                    <input type="number" name="harga_satuan" min="1" required>
                </div>
                <div style="flex:1; min-width:100px;">
                    <label>Qty Beli:</label>
                    <input type="number" name="jumlah" min="1" required>
                </div>
                <div style="flex:1; min-width:120px;">
                    <button type="submit" style="margin-top:0;">+ Tambah ke Nota</button>
                </div>
            </form>

            <table>
                <tr><th>ID Detail</th><th>Nama Barang</th><th>Harga Satuan</th><th>Jumlah</th><th>Subtotal</th></tr>
                <?php foreach ($listDetail as $d): ?>
                <tr>
                    <td><?= $d['iddetail_penjualan'] ?></td>
                    <td><?= htmlspecialchars($d['nama_barang']) ?></td>
                    <td>Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?></td>
                    <td><?= $d['jumlah'] ?></td>
                    <td>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>