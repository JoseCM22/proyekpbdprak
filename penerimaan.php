<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $penerimaanModel = new PenerimaanModel($db);
    $pengadaanModel  = new PengadaanModel($db);
    $userModel       = new UserModel($db);
    $barangModel     = new BarangModel($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $aksi = $_POST['aksi'] ?? '';
        if ($aksi === 'tambah_header') {
            $res = $penerimaanModel->insert($_POST);
            Flash::set($res->status ? "Dokumen penerimaan dibuat." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: penerimaan.php"); exit;
        } elseif ($aksi === 'tambah_detail') {
            $idTerima = (int) $_POST['idpenerimaan'];
            $res = $penerimaanModel->insert_detail($_POST);
            Flash::set($res->status ? "Barang diterima tercatat." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: penerimaan.php?detail=$idTerima"); exit;
        }
    }

    $daftarPenerimaan = $penerimaanModel->find_all();
    $daftarPengadaan  = $pengadaanModel->find_all();
    $daftarUser       = $userModel->find_all();
    $daftarBarang     = $barangModel->find_all();

    $idDetail   = isset($_GET['detail']) ? (int) $_GET['detail'] : null;
    $listDetail = $idDetail ? $penerimaanModel->get_detail($idDetail) : [];

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penerimaan Barang - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>
    <?php include_once("sidebar.php"); ?>
    <div class="main-content">
        <div class="page-header"><h2>Transaksi Penerimaan Barang</h2></div>
        <?php Flash::tampilkan(); ?>

        <div class="card-form">
            <h4 style="margin-top:0;">Catat Penerimaan dari PO</h4>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah_header">
                <label>Referensi Nota Pengadaan (PO):</label>
                <select name="idpengadaan" required>
                    <option value="">-- Pilih Nota PO --</option>
                    <?php foreach ($daftarPengadaan as $p): ?>
                        <option value="<?= $p['idpengadaan'] ?>">PO #<?= $p['idpengadaan'] ?> - <?= htmlspecialchars($p['nama_vendor']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Penerima Gudang (User):</label>
                <select name="iduser" required>
                    <option value="">-- Pilih User --</option>
                    <?php foreach ($daftarUser as $u): ?>
                        <option value="<?= $u['ID User'] ?? $u['iduser'] ?>"><?= htmlspecialchars($u['Username'] ?? $u['username']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Buat Dokumen Penerimaan</button>
            </form>
        </div>

        <table>
            <tr><th>ID Terima</th><th>Tanggal</th><th>Ref. PO</th><th>Vendor</th><th>Penerima</th><th>Status</th><th>Aksi</th></tr>
            <?php foreach ($daftarPenerimaan as $row): ?>
            <tr>
                <td>#<?= $row['idpenerimaan'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>PO #<?= $row['idpengadaan'] ?></td>
                <td><?= htmlspecialchars($row['nama_vendor']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['status'] ?></td>
                <td class="aksi"><a href="?detail=<?= $row['idpenerimaan'] ?>" class="edit">Input Barang Masuk</a></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($idDetail): ?>
        <div style="margin-top: 30px; padding: 20px; background: #fff; border-radius: 8px; border-top: 4px solid #198754;">
            <h3>Detail Barang Masuk — Penerimaan #<?= $idDetail ?> <a href="penerimaan.php" style="font-size:13px; color:#dc3545; float:right;">Tutup Detail</a></h3>
            <form method="POST" style="display:flex; gap:10px; align-items:flex-end; margin-bottom:20px; flex-wrap:wrap;">
                <input type="hidden" name="aksi" value="tambah_detail">
                <input type="hidden" name="idpenerimaan" value="<?= $idDetail ?>">
                <div style="flex:2; min-width:200px;">
                    <label>Barang Diterima:</label>
                    <select name="barang_idbarang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($daftarBarang as $b): ?>
                            <option value="<?= $b['ID Barang'] ?? $b['idbarang'] ?>"><?= htmlspecialchars($b['Nama Barang'] ?? $b['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="flex:1; min-width:130px;">
                    <label>Harga Satuan Terima:</label>
                    <input type="number" name="harga_satuan_terima" min="1" required>
                </div>
                <div style="flex:1; min-width:100px;">
                    <label>Jumlah Terima:</label>
                    <input type="number" name="jumlah_terima" min="1" required>
                </div>
                <div style="flex:1; min-width:120px;">
                    <button type="submit" style="margin-top:0;">+ Terima Barang</button>
                </div>
            </form>

            <table>
                <tr><th>ID Detail</th><th>Nama Barang</th><th>Harga Terima</th><th>Jumlah Terima</th><th>Subtotal</th></tr>
                <?php foreach ($listDetail as $d): ?>
                <tr>
                    <td><?= $d['iddetail_penerimaan'] ?></td>
                    <td><?= htmlspecialchars($d['nama_barang']) ?></td>
                    <td>Rp <?= number_format($d['harga_satuan_terima'], 0, ',', '.') ?></td>
                    <td><?= $d['jumlah_terima'] ?></td>
                    <td>Rp <?= number_format($d['sub_total_terima'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>