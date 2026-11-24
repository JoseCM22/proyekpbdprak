<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $returModel      = new ReturModel($db);
    $penerimaanModel = new PenerimaanModel($db);
    $userModel       = new UserModel($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $aksi = $_POST['aksi'] ?? '';
        if ($aksi === 'tambah_header') {
            $res = $returModel->insert($_POST);
            Flash::set($res->status ? "Dokumen retur dibuat." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: retur.php"); exit;
        } elseif ($aksi === 'tambah_detail') {
            $idRetur = (int) $_POST['idretur'];
            $res = $returModel->insert_detail($_POST);
            Flash::set($res->status ? "Item retur dicatat." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
            header("Location: retur.php?detail=$idRetur"); exit;
        }
    }

    $daftarRetur      = $returModel->find_all();
    $daftarPenerimaan = $penerimaanModel->find_all();
    $daftarUser       = $userModel->find_all();

    $idDetail      = isset($_GET['detail']) ? (int) $_GET['detail'] : null;
    $listDetail    = $idDetail ? $returModel->get_detail($idDetail) : [];
    
    // Ambil daftar item penerimaan untuk dropdown detail retur
    $opsiDetailTerima = $db->send_query(
        "SELECT dpr.iddetail_penerimaan, dpr.idpenerimaan, b.nama AS nama_barang, dpr.jumlah_terima 
         FROM detail_penerimaan dpr JOIN barang b ON dpr.barang_idbarang = b.idbarang"
    )->data;

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Retur Barang - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>
    <?php include_once("sidebar.php"); ?>
    <div class="main-content">
        <div class="page-header"><h2>Transaksi Retur Barang ke Vendor</h2></div>
        <?php Flash::tampilkan(); ?>

        <div class="card-form">
            <h4 style="margin-top:0;">Buat Dokumen Retur</h4>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah_header">
                <label>Referensi Penerimaan Barang:</label>
                <select name="idpenerimaan" required>
                    <option value="">-- Pilih ID Penerimaan --</option>
                    <?php foreach ($daftarPenerimaan as $pr): ?>
                        <option value="<?= $pr['idpenerimaan'] ?>">Terima #<?= $pr['idpenerimaan'] ?> (PO #<?= $pr['idpengadaan'] ?> - <?= htmlspecialchars($pr['nama_vendor']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <label>Petugas (User):</label>
                <select name="iduser" required>
                    <option value="">-- Pilih User --</option>
                    <?php foreach ($daftarUser as $u): ?>
                        <option value="<?= $u['ID User'] ?? $u['iduser'] ?>"><?= htmlspecialchars($u['Username'] ?? $u['username']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Buat Dokumen Retur</button>
            </form>
        </div>

        <table>
            <tr><th>ID Retur</th><th>Tanggal</th><th>Ref. Penerimaan</th><th>Petugas</th><th>Aksi</th></tr>
            <?php foreach ($daftarRetur as $row): ?>
            <tr>
                <td>#<?= $row['idretur'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>Terima #<?= $row['idpenerimaan'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td class="aksi"><a href="?detail=<?= $row['idretur'] ?>" class="edit">Input Barang Retur</a></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($idDetail): ?>
        <div style="margin-top: 30px; padding: 20px; background: #fff; border-radius: 8px; border-top: 4px solid #dc3545;">
            <h3>Detail Barang Diretur — Retur #<?= $idDetail ?> <a href="retur.php" style="font-size:13px; color:#dc3545; float:right;">Tutup Detail</a></h3>
            <form method="POST" style="display:flex; gap:10px; align-items:flex-end; margin-bottom:20px; flex-wrap:wrap;">
                <input type="hidden" name="aksi" value="tambah_detail">
                <input type="hidden" name="idretur" value="<?= $idDetail ?>">
                <div style="flex:2; min-width:220px;">
                    <label>Item Penerimaan yang Diretur:</label>
                    <select name="iddetail_penerimaan" required>
                        <option value="">-- Pilih Item --</option>
                        <?php foreach ($opsiDetailTerima as $opt): ?>
                            <option value="<?= $opt['iddetail_penerimaan'] ?>">
                                Terima #<?= $opt['idpenerimaan'] ?> - <?= htmlspecialchars($opt['nama_barang']) ?> (Qty: <?= $opt['jumlah_terima'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="flex:1; min-width:90px;">
                    <label>Qty Retur:</label>
                    <input type="number" name="jumlah" min="1" required>
                </div>
                <div style="flex:2; min-width:180px;">
                    <label>Alasan Retur:</label>
                    <input type="text" name="alasan" placeholder="Contoh: Barang cacat / rusak" required>
                </div>
                <div style="flex:1; min-width:120px;">
                    <button type="submit" style="margin-top:0;">+ Simpan Retur</button>
                </div>
            </form>

            <table>
                <tr><th>ID Detail</th><th>Nama Barang</th><th>Jumlah Retur</th><th>Alasan</th></tr>
                <?php foreach ($listDetail as $d): ?>
                <tr>
                    <td><?= $d['iddetail_retur'] ?></td>
                    <td><?= htmlspecialchars($d['nama_barang']) ?></td>
                    <td><?= $d['jumlah'] ?></td>
                    <td><?= htmlspecialchars($d['alasan']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>