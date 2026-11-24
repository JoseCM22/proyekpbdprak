<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $satuanModel = new SatuanModel($db);

    if (isset($_GET['delete'])) {
        $res = $satuanModel->delete((int) $_GET['delete']);
        Flash::set($res->status ? "Satuan berhasil dihapus." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: satuan.php"); exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['idsatuan'] ?? '';
        $res = !empty($id) 
            ? $satuanModel->update((int) $id, $_POST) 
            : $satuanModel->insert($_POST);

        Flash::set($res->status ? "Data satuan berhasil disimpan." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: satuan.php"); exit;
    }

    $editData     = isset($_GET['edit']) ? $satuanModel->find_by_id((int) $_GET['edit']) : null;
    $daftarSatuan = $satuanModel->find_all(); // Membaca dari vu_master_satuan

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Satuan - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>

    <?php include_once("sidebar.php"); ?>

    <div class="main-content">
        <div class="page-header">
            <h2>Manajemen Data Satuan</h2>
        </div>

        <?php Flash::tampilkan(); ?>

        <div class="card-form">
            <form method="POST">
                <input type="hidden" name="idsatuan" value="<?= $editData['idsatuan'] ?? '' ?>">
                
                <label>Nama Satuan:</label>
                <input type="text" name="nama_satuan" placeholder="Contoh: Unit, Pcs, Box" value="<?= htmlspecialchars($editData['nama_satuan'] ?? '') ?>" required>
                
                <label>Status:</label>
                <select name="status" required>
                    <option value="1" <?= ($editData['status'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= isset($editData['status']) && $editData['status'] == 0 ? 'selected' : '' ?>>Non-Aktif</option>
                </select>

                <button type="submit"><?= $editData ? 'Update Satuan' : 'Simpan Satuan' ?></button>
                <?php if ($editData): ?>
                    <p style="text-align:center; margin-bottom:0;"><a href="satuan.php" style="color:#dc3545;">Batal Edit</a></p>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <tr>
                <th>ID Satuan</th>
                <th>Nama Satuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($daftarSatuan as $row): ?>
            <tr>
                <td><?= $row['Satuan ID'] ?? $row['idsatuan'] ?></td>
                <td><?= htmlspecialchars($row['Satuan'] ?? $row['nama_satuan']) ?></td>
                <td><?= $row['Status'] ?? ($row['status'] == 1 ? 'Aktif' : 'Non-Aktif') ?></td>
                <td class="aksi">
                    <?php $idRow = $row['Satuan ID'] ?? $row['idsatuan']; ?>
                    <a href="?edit=<?= $idRow ?>" class="edit">Edit</a> | 
                    <a href="?delete=<?= $idRow ?>" class="delete" onclick="return confirm('Yakin hapus satuan ini?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>