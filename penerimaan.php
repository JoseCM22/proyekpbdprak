<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $penerimaanModel = new PenerimaanModel($db);
    $pengadaanModel  = new PengadaanModel($db);
    $userModel       = new UserModel($db);

    // 1. Hapus Header Dokumen Penerimaan
    if (isset($_GET['delete'])) {
        $res = $penerimaanModel->delete((int) $_GET['delete']);
        Flash::set($res->status ? "Dokumen penerimaan dihapus." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: penerimaan.php"); exit;
    }

    // 2. Hapus Item Detail Penerimaan
    if (isset($_GET['delete_item']) && isset($_GET['detail'])) {
        $idTerima = (int) $_GET['detail'];
        $res = $penerimaanModel->delete_detail((int) $_GET['delete_item']);
        Flash::set($res->status ? "Item dihapus & stok otomatis dikoreksi di Kartu Stok." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: penerimaan.php?detail=$idTerima"); exit;
    }

    // 3. Proses Form POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $aksi = $_POST['aksi'] ?? '';
        
        if ($aksi === 'tambah_header') {
            $res = $penerimaanModel->insert($_POST);
            Flash::set($res->status ? "Dokumen penerimaan baru dibuat." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: penerimaan.php"); exit;
            
        } elseif ($aksi === 'tambah_detail') {
            $idTerima = (int) $_POST['idpenerimaan'];
            $res = $penerimaanModel->insert_detail($_POST);
            Flash::set($res->status ? "Barang masuk dicatat & stok otomatis bertambah di Kartu Stok!" : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: penerimaan.php?detail=$idTerima"); exit;
        }
    }

    $daftarPenerimaan = $penerimaanModel->find_all();     // Dari vu_transaksi_penerimaan
    $daftarPengadaan  = $pengadaanModel->find_all();      // Dari vu_transaksi_pengadaan
    $daftarUser       = $userModel->find_all_raw();       // Aman untuk dropdown
    $idDetail     = isset($_GET['detail']) ? (int) $_GET['detail'] : null;
    $listDetail   = $idDetail ? $penerimaanModel->get_detail($idDetail) : [];
    
    $daftarBarang = $idDetail ? $penerimaanModel->get_barang_by_penerimaan($idDetail) : [];

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
        <div class="page-header">
            <h2>Transaksi Penerimaan Barang (Gudang)</h2>
            <p style="margin:5px 0 0; color:#6c757d; font-size:14px;">Setiap item yang diterima otomatis menambah saldo stok masuk ('M') di Kartu Stok via Trigger.</p>
        </div>

        <?php Flash::tampilkan(); ?>

        <!-- Form Header Penerimaan -->
        <div class="card-form">
            <h4 style="margin-top:0;">Buat Dokumen Penerimaan</h4>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah_header">
                
                <label>Referensi Nota Pengadaan (PO):</label>
                <select name="idpengadaan" required>
                    <option value="">-- Pilih Nota PO --</option>
                    <?php foreach ($daftarPengadaan as $p): ?>
                        <option value="<?= $p['idpengadaan'] ?>">
                            PO #<?= $p['idpengadaan'] ?> - <?= htmlspecialchars($p['nama_vendor']) ?> (<?= $p['status_ket'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Petugas Penerima (User):</label>
                <select name="iduser" required>
                    <option value="">-- Pilih User --</option>
                    <?php foreach ($daftarUser as $u): ?>
                        <option value="<?= $u['iduser'] ?>"><?= htmlspecialchars($u['username']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Buat Dokumen Penerimaan</button>
            </form>
        </div>

        <!-- Tabel Daftar Penerimaan -->
        <table>
            <tr>
                <th>ID Terima</th>
                <th>Waktu</th>
                <th>Ref. PO</th>
                <th>Vendor</th>
                <th>Penerima</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php if (empty($daftarPenerimaan)): ?>
                <tr><td colspan="7" style="text-align:center; color:#6c757d;">Belum ada dokumen penerimaan barang.</td></tr>
            <?php else: ?>
                <?php foreach ($daftarPenerimaan as $row): ?>
                <tr style="<?= $idDetail == $row['idpenerimaan'] ? 'background-color:#e8f5e9;' : '' ?>">
                    <td><strong>#<?= $row['idpenerimaan'] ?></strong></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>PO #<?= $row['idpengadaan'] ?></td>
                    <td><?= htmlspecialchars($row['nama_vendor']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['status_ket']) ?></td>
                    <td class="aksi">
                        <a href="?detail=<?= $row['idpenerimaan'] ?>" class="edit">Input Barang Masuk</a> | 
                        <a href="?delete=<?= $row['idpenerimaan'] ?>" class="delete" onclick="return confirm('Yakin hapus dokumen penerimaan #<?= $row['idpenerimaan'] ?>?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>

        <!-- Panel Rincian Barang Diterima -->
        <?php if ($idDetail): ?>
        <div style="margin-top: 30px; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #198754;">
            <h3 style="margin-top:0;">
                Rincian Barang Masuk — Penerimaan #<?= $idDetail ?>
                <a href="penerimaan.php" style="font-size:13px; color:#dc3545; float:right; text-decoration:none;">✖ Tutup Rincian</a>
            </h3>

            <form method="POST" style="display:flex; gap:12px; align-items:flex-end; margin-bottom:20px; flex-wrap:wrap; background:#f8f9fa; padding:15px; border-radius:6px;">
                <input type="hidden" name="aksi" value="tambah_detail">
                <input type="hidden" name="idpenerimaan" value="<?= $idDetail ?>">
                
                <div style="flex:3; min-width:220px;">
                    <label style="margin-top:0;">Barang Diterima:</label>
                    <select name="barang_idbarang" required>
                        <option value="">-- Pilih Barang dari PO --</option>
                        <?php foreach ($daftarBarang as $b): ?>
                            <?php $sisa = (int)$b['qty_pesan'] - (int)$b['sudah_diterima']; ?>
                            <option value="<?= $b['idbarang'] ?>" <?= $sisa <= 0 ? 'disabled' : '' ?>>
                                <?= htmlspecialchars($b['nama_barang']) ?> — Dipesan: <?= $b['qty_pesan'] ?> <?= $b['nama_satuan'] ?> | Sisa: <?= max(0, $sisa) ?> (Harga PO: Rp <?= number_format($b['harga_po'], 0, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="flex:1; min-width:110px;">
                    <label style="margin-top:0;">Qty Terima:</label>
                    <input type="number" name="jumlah_terima" min="1" placeholder="Qty" required>
                </div>

                <div style="flex:1; min-width:150px;">
                    <label style="margin-top:0;">Harga Terima (0 = Otomatis):</label>
                    <input type="number" name="harga_satuan_terima" min="0" value="0" required>
                </div>

                <div style="flex:1; min-width:140px;">
                    <button type="submit" style="margin-top:0;">+ Terima Barang</button>
                </div>
            </form>

            <table>
                <tr>
                    <th>ID Detail</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Harga Terima</th>
                    <th>Jumlah Terima</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
                <?php if (empty($listDetail)): ?>
                    <tr><td colspan="7" style="text-align:center; color:#6c757d;">Belum ada barang yang diinput pada penerimaan ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($listDetail as $d): ?>
                    <tr>
                        <td><?= $d['iddetail_penerimaan'] ?></td>
                        <td><?= htmlspecialchars($d['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($d['nama_satuan']) ?></td>
                        <td>Rp <?= number_format($d['harga_satuan_terima'], 0, ',', '.') ?></td>
                        <td><?= $d['jumlah_terima'] ?></td>
                        <td><strong>Rp <?= number_format($d['sub_total_terima'], 0, ',', '.') ?></strong></td>
                        <td class="aksi">
                            <a href="?detail=<?= $idDetail ?>&delete_item=<?= $d['iddetail_penerimaan'] ?>" class="delete" onclick="return confirm('Hapus item ini? Stok akan dikoreksi.')">Hapus Item</a>
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