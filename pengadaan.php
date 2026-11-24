<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $pengadaanModel = new PengadaanModel($db);
    $vendorModel    = new VendorModel($db);
    $userModel      = new UserModel($db);
    $barangModel    = new BarangModel($db);

    // 1. Hapus Header Nota PO
    if (isset($_GET['delete'])) {
        $res = $pengadaanModel->delete((int) $_GET['delete']);
        Flash::set($res->status ? "Nota pengadaan berhasil dihapus." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: pengadaan.php"); exit;
    }

    // 2. Hapus Item Detail dari Nota PO
    if (isset($_GET['delete_item']) && isset($_GET['detail'])) {
        $idNota = (int) $_GET['detail'];
        $res = $pengadaanModel->delete_detail((int) $_GET['delete_item']);
        Flash::set($res->status ? "Item dihapus & total PO otomatis diperbarui." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: pengadaan.php?detail=$idNota"); exit;
    }

    // 3. Proses Form POST (Buat Nota / Tambah Item)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $aksi = $_POST['aksi'] ?? '';
        
        if ($aksi === 'tambah_header') {
            $res = $pengadaanModel->insert($_POST);
            Flash::set($res->status ? "Nota pengadaan (PO) baru berhasil dibuat." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: pengadaan.php"); exit;
            
        } elseif ($aksi === 'tambah_detail') {
            $idNota = (int) $_POST['idpengadaan'];
            $res = $pengadaanModel->insert_detail($_POST);
            Flash::set($res->status ? "Item ditambahkan & total PO otomatis terhitung." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: pengadaan.php?detail=$idNota"); exit;
        }
    }

    $daftarPengadaan = $pengadaanModel->find_all();     // Tetap pakai View vu_transaksi_pengadaan
    $daftarVendor    = $vendorModel->find_all_raw();    // Langsung dari tabel vendor (kolom: idvendor, nama_vendor)
    $daftarUser      = $userModel->find_all_raw();      // Langsung dari tabel user (kolom: iduser, username)
    $daftarBarang    = $barangModel->find_all_raw();    // Langsung dari tabel barang (kolom: idbarang, nama, harga)

    $idDetail   = isset($_GET['detail']) ? (int) $_GET['detail'] : null;
    $listDetail = $idDetail ? $pengadaanModel->get_detail($idDetail) : [];

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengadaan Barang (PO) - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>
    <?php include_once("sidebar.php"); ?>

    <div class="main-content">
        <div class="page-header">
            <h2>Transaksi Pengadaan Barang (Purchase Order)</h2>
            <p style="margin:5px 0 0; color:#6c757d; font-size:14px;">Harga beli diambil otomatis oleh Function, dan Subtotal + PPN (11%) dihitung otomatis oleh Trigger.</p>
        </div>

        <?php Flash::tampilkan(); ?>

        <!-- Form Buat Header PO -->
        <div class="card-form">
            <h4 style="margin-top:0;">Buat Nota Pengadaan Baru</h4>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah_header">
                
                <label>Pilih Vendor Supplier:</label>
                <select name="vendor_idvendor" required>
                    <option value="">-- Pilih Vendor --</option>
                    <?php foreach ($daftarVendor as $v): ?>
                        <option value="<?= $v['idvendor'] ?>"><?= htmlspecialchars($v['nama_vendor']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Petugas Pengadaan (User):</label>
                <select name="user_iduser" required>
                    <option value="">-- Pilih Petugas --</option>
                    <?php foreach ($daftarUser as $u): ?>
                        <option value="<?= $u['iduser'] ?>"><?= htmlspecialchars($u['username']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Buat Nota PO</button>
            </form>
        </div>

        <!-- Tabel Daftar PO -->
        <table>
            <tr>
                <th>ID PO</th>
                <th>Waktu</th>
                <th>Vendor</th>
                <th>Petugas</th>
                <th>Subtotal</th>
                <th>PPN (11%)</th>
                <th>Total Nilai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php if (empty($daftarPengadaan)): ?>
                <tr><td colspan="9" style="text-align:center; color:#6c757d;">Belum ada transaksi pengadaan.</td></tr>
            <?php else: ?>
                <?php foreach ($daftarPengadaan as $row): ?>
                <tr style="<?= $idDetail == $row['idpengadaan'] ? 'background-color:#e7f1ff;' : '' ?>">
                    <td><strong>#<?= $row['idpengadaan'] ?></strong></td>
                    <td><?= $row['timestamp'] ?></td>
                    <td><?= htmlspecialchars($row['nama_vendor']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td>Rp <?= number_format($row['subtotal_nilai'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['ppn'], 0, ',', '.') ?></td>
                    <td><strong>Rp <?= number_format($row['total_nilai'], 0, ',', '.') ?></strong></td>
                    <td><?= htmlspecialchars($row['status_ket']) ?></td>
                    <td class="aksi">
                        <a href="?detail=<?= $row['idpengadaan'] ?>" class="edit">Kelola Item</a> | 
                        <a href="?delete=<?= $row['idpengadaan'] ?>" class="delete" onclick="return confirm('Yakin hapus PO #<?= $row['idpengadaan'] ?>?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>

        <!-- Panel Kelola Detail Item PO -->
        <?php if ($idDetail): ?>
        <div style="margin-top: 30px; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #0d6efd;">
            <h3 style="margin-top:0;">
                Rincian Item — Pengadaan #<?= $idDetail ?>
                <a href="pengadaan.php" style="font-size:13px; color:#dc3545; float:right; text-decoration:none;">✖ Tutup Rincian</a>
            </h3>
            
            <form method="POST" style="display:flex; gap:12px; align-items:flex-end; margin-bottom:20px; flex-wrap:wrap; background:#f8f9fa; padding:15px; border-radius:6px;">
                <input type="hidden" name="aksi" value="tambah_detail">
                <input type="hidden" name="idpengadaan" value="<?= $idDetail ?>">
                
                <div style="flex:3; min-width:240px;">
                    <label style="margin-top:0;">Pilih Barang:</label>
                    <select name="idbarang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($daftarBarang as $b): ?>
    <option value="<?= $b['idbarang'] ?>">
        <?= htmlspecialchars($b['nama']) ?> (Harga Beli: Rp <?= number_format($b['harga'], 0, ',', '.') ?>)
    </option>
<?php endforeach; ?>
                    </select>
                </div>

                <div style="flex:1; min-width:120px;">
                    <label style="margin-top:0;">Jumlah Beli:</label>
                    <input type="number" name="jumlah" min="1" placeholder="Qty" required>
                </div>

                <div style="flex:1; min-width:140px;">
                    <button type="submit" style="margin-top:0;">+ Tambah Item</button>
                </div>
            </form>

            <table>
                <tr>
                    <th>ID Detail</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
                <?php if (empty($listDetail)): ?>
                    <tr><td colspan="7" style="text-align:center; color:#6c757d;">Belum ada item barang pada PO ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($listDetail as $d): ?>
                    <tr>
                        <td><?= $d['iddetail_pengadaan'] ?></td>
                        <td><?= htmlspecialchars($d['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($d['nama_satuan']) ?></td>
                        <td>Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?></td>
                        <td><?= $d['jumlah'] ?></td>
                        <td><strong>Rp <?= number_format($d['sub_total'], 0, ',', '.') ?></strong></td>
                        <td class="aksi">
                            <a href="?detail=<?= $idDetail ?>&delete_item=<?= $d['iddetail_pengadaan'] ?>" class="delete" onclick="return confirm('Hapus item ini?')">Hapus Item</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
        <?php endif; ?>

    </div>
</body>
</html>