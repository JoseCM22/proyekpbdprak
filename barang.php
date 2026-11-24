<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $barangModel = new BarangModel($db);
    $satuanModel = new SatuanModel($db);

    if (isset($_GET['delete'])) {
        $res = $barangModel->delete((int) $_GET['delete']);
        Flash::set($res->status ? "Barang berhasil dihapus." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: barang.php"); exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['idbarang'] ?? '';
        $res = !empty($id) 
            ? $barangModel->update((int) $id, $_POST) 
            : $barangModel->insert($_POST);

        Flash::set($res->status ? "Data barang berhasil disimpan." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: barang.php"); exit;
    }

    $editData     = isset($_GET['edit']) ? $barangModel->find_by_id((int) $_GET['edit']) : null;
    $daftarBarang = $barangModel->find_all(); // Membaca dari vu_master_barang
    $daftarSatuan = $satuanModel->find_all(); // Untuk dropdown Satuan

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Barang - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>

    <?php include_once("sidebar.php"); ?>

    <div class="main-content">
        <div class="page-header">
            <h2>Manajemen Data Barang</h2>
        </div>

        <?php Flash::tampilkan(); ?>

        <div class="card-form">
            <form method="POST">
                <input type="hidden" name="idbarang" value="<?= $editData['idbarang'] ?? '' ?>">
                
                <label>Jenis Barang:</label>
                <select name="jenis" required>
                    <option value="E" <?= ($editData['jenis'] ?? '') == 'E' ? 'selected' : '' ?>>Elektronik Utama (E)</option>
                    <option value="A" <?= ($editData['jenis'] ?? '') == 'A' ? 'selected' : '' ?>>Aksesoris / Komponen (A)</option>
                </select>

                <label>Nama Barang:</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($editData['nama'] ?? '') ?>" required>

                <label>Satuan:</label>
                <select name="idsatuan" required>
                    <option value="">-- Pilih Satuan --</option>
                    <?php foreach ($daftarSatuan as $s): ?>
                        <?php $sId = $s['ID Satuan'] ?? $s['idsatuan']; $sNama = $s['Nama Satuan'] ?? $s['nama_satuan']; ?>
                        <option value="<?= $sId ?>" <?= ($editData['idsatuan'] ?? '') == $sId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sNama) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Harga Modal / Dasar (Rp):</label>
                <input type="number" name="harga" min="0" value="<?= $editData['harga'] ?? '' ?>" required>

                <label>Status:</label>
                <select name="status" required>
                    <option value="1" <?= ($editData['status'] ?? 1) == 1 ? 'selected' : '' ?>>Tersedia / Aktif</option>
                    <option value="0" <?= isset($editData['status']) && $editData['status'] == 0 ? 'selected' : '' ?>>Discontinue / Non-Aktif</option>
                </select>

                <button type="submit"><?= $editData ? 'Update Barang' : 'Simpan Barang' ?></button>
                <?php if ($editData): ?>
                    <p style="text-align:center; margin-bottom:0;"><a href="barang.php" style="color:#dc3545;">Batal Edit</a></p>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Jenis</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Status</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($daftarBarang as $row): ?>
            <tr>
                <td><?= $row['ID Barang'] ?? $row['idbarang'] ?></td>
                <td><?= htmlspecialchars($row['Jenis'] ?? $row['jenis']) ?></td>
                <td><?= htmlspecialchars($row['Nama'] ?? $row['nama']) ?></td>
                <td><?= htmlspecialchars($row['Satuan'] ?? $row['nama_satuan'] ?? '-') ?></td>
                <td><?= $row['Status'] ?? ($row['status'] == 1 ? 'Aktif' : 'Non-Aktif') ?></td>
                <td>Rp <?= number_format($row['Harga'] ?? $row['harga'], 0, ',', '.') ?></td>
                <td class="aksi">
                    <?php $idRow = $row['ID Barang'] ?? $row['idbarang']; ?>
                    <a href="?edit=<?= $idRow ?>" class="edit">Edit</a> | 
                    <a href="?delete=<?= $idRow ?>" class="delete" onclick="return confirm('Yakin hapus barang ini?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>