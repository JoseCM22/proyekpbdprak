<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $pengadaanModel = new PengadaanModel($db);
    $vendorModel    = new VendorModel($db);
    $userModel      = new UserModel($db);
    $barangModel    = new BarangModel($db);

    if (isset($_GET['delete'])) {
        $res = $pengadaanModel->delete((int) $_GET['delete']);
        Flash::set($res->status ? "Pengadaan dihapus." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: pengadaan.php"); exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $aksi = $_POST['aksi'] ?? '';
        if ($aksi === 'tambah_header') {
            $res = $pengadaanModel->insert($_POST);
            Flash::set($res->status ? "Nota pengadaan baru dibuat." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: pengadaan.php"); exit;
        } elseif ($aksi === 'tambah_detail') {
            $idNota = (int) $_POST['idpengadaan'];
            $res = $pengadaanModel->insert_detail($_POST);
            Flash::set($res->status ? "Item barang ditambahkan ke PO #$idNota." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: pengadaan.php?detail=$idNota"); exit;
        }
    }

    $daftarPengadaan = $pengadaanModel->find_all();
    $daftarVendor    = $vendorModel->find_all();
    $daftarUser      = $userModel->find_all();
    $daftarBarang    = $barangModel->find_all();

    $idDetail    = isset($_GET['detail']) ? (int) $_GET['detail'] : null;
    $listDetail  = $idDetail ? $pengadaanModel->get_detail($idDetail) : [];

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengadaan Barang - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>
    <?php include_once("sidebar.php"); ?>
    <div class="main-content">
        <div class="page-header"><h2>Transaksi Pengadaan Barang</h2></div>
        <?php Flash::tampilkan(); ?>

        <div class="card-form">
            <h4 style="margin-top:0;">Buat Nota Pengadaan Baru</h4>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah_header">
                <label>Pilih Vendor:</label>
                <select name="vendor_idvendor" required>
                    <option value="">-- Pilih Vendor --</option>
                    <?php foreach ($daftarVendor as $v): ?>
                        <option value="<?= $v['ID Vendor'] ?? $v['idvendor'] ?>"><?= htmlspecialchars($v['Nama Vendor'] ?? $v['nama_vendor']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Petugas (User):</label>
                <select name="user_iduser" required>
                    <option value="">-- Pilih User --</option>
                    <?php foreach ($daftarUser as $u): ?>
                        <option value="<?= $u['ID User'] ?? $u['iduser'] ?>"><?= htmlspecialchars($u['Username'] ?? $u['username']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Buat Nota PO</button>
            </form>
        </div>

        <table>
            <tr>
                <th>ID PO</th><th>Tanggal</th><th>Vendor</th><th>Petugas</th><th>Subtotal</th><th>PPN</th><th>Total Nilai</th><th>Status</th><th>Aksi</th>
            </tr>
            <?php foreach ($daftarPengadaan as $row): ?>
            <tr>
                <td>#<?= $row['idpengadaan'] ?></td>
                <td><?= $row['timestamp'] ?></td>
                <td><?= htmlspecialchars($row['nama_vendor']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td>Rp <?= number_format($row['subtotal_nilai'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($row['ppn'], 0, ',', '.') ?></td>
                <td><strong>Rp <?= number_format($row['total_nilai'], 0, ',', '.') ?></strong></td>
                <td><?= $row['status'] ?></td>
                <td class="aksi">
                    <a href="?detail=<?= $row['idpengadaan'] ?>" class="edit">Kelola Item</a> | 
                    <a href="?delete=<?= $row['idpengadaan'] ?>" class="delete" onclick="return confirm('Hapus nota PO ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($idDetail): ?>
        <div style="margin-top: 30px; padding: 20px; background: #fff; border-radius: 8px; border-top: 4px solid #0d6efd;">
            <h3>Detail Item untuk Pengadaan #<?= $idDetail ?> <a href="pengadaan.php" style="font-size:13px; color:#dc3545; float:right;">Tutup Detail</a></h3>
            <form method="POST" style="display:flex; gap:10px; align-items:flex-end; margin-bottom:20px; flex-wrap:wrap;">
                <input type="hidden" name="aksi" value="tambah_detail">
                <input type="hidden" name="idpengadaan" value="<?= $idDetail ?>">
                <div style="flex:2; min-width:200px;">
                    <label>Barang:</label>
                    <select name="idbarang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($daftarBarang as $b): ?>
                            <option value="<?= $b['ID Barang'] ?? $b['idbarang'] ?>"><?= htmlspecialchars($b['Nama Barang'] ?? $b['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="flex:1; min-width:130px;">
                    <label>Harga Beli Satuan:</label>
                    <input type="number" name="harga_satuan" min="1" required>
                </div>
                <div style="flex:1; min-width:100px;">
                    <label>Jumlah:</label>
                    <input type="number" name="jumlah" min="1" required>
                </div>
                <div style="flex:1; min-width:120px;">
                    <button type="submit" style="margin-top:0;">+ Tambah Item</button>
                </div>
            </form>

            <table>
                <tr><th>ID Detail</th><th>Nama Barang</th><th>Satuan</th><th>Harga Satuan</th><th>Jumlah</th><th>Subtotal</th></tr>
                <?php foreach ($listDetail as $d): ?>
                <tr>
                    <td><?= $d['iddetail_pengadaan'] ?></td>
                    <td><?= htmlspecialchars($d['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($d['nama_satuan']) ?></td>
                    <td>Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?></td>
                    <td><?= $d['jumlah'] ?></td>
                    <td>Rp <?= number_format($d['sub_total'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>