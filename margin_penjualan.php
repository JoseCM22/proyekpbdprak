<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $marginModel = new MarginModel($db);

    if (isset($_GET['delete'])) {
        $res = $marginModel->delete((int) $_GET['delete']);
        Flash::set($res->status ? "Margin berhasil dihapus." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: margin_penjualan.php"); exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['idmargin_penjualan'] ?? '';
        $res = !empty($id) 
            ? $marginModel->update((int) $id, $_POST) 
            : $marginModel->insert($_POST);

        Flash::set($res->status ? "Data margin berhasil disimpan." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: margin_penjualan.php"); exit;
    }

    $editData     = isset($_GET['edit']) ? $marginModel->find_by_id((int) $_GET['edit']) : null;
    $daftarMargin = $marginModel->find_all(); // Membaca dari vu_master_marginP

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Margin Penjualan - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>

    <?php include_once("sidebar.php"); ?>

    <div class="main-content">
        <div class="page-header">
            <h2>Manajemen Margin Penjualan</h2>
        </div>

        <?php Flash::tampilkan(); ?>

        <div class="card-form">
            <form method="POST">
                <input type="hidden" name="idmargin_penjualan" value="<?= $editData['idmargin_penjualan'] ?? '' ?>">
                
                <label>Persentase Margin (%):</label>
                <input type="number" step="0.01" name="persen" placeholder="Contoh: 10.5" value="<?= $editData['persen'] ?? '' ?>" required>

                <label>Status Margin:</label>
                <select name="status" required>
                    <option value="1" <?= ($editData['STATUS'] ?? $editData['status'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= (isset($editData['STATUS']) && $editData['STATUS'] == 0) || (isset($editData['status']) && $editData['status'] == 0) ? 'selected' : '' ?>>Non-Aktif</option>
                </select>

                <button type="submit"><?= $editData ? 'Update Margin' : 'Simpan Margin' ?></button>
                <?php if ($editData): ?>
                    <p style="text-align:center; margin-bottom:0;"><a href="margin_penjualan.php" style="color:#dc3545;">Batal Edit</a></p>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <tr>
                <th>ID Margin</th>
                <th>Persen (%)</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($daftarMargin as $row): ?>
            <tr>
                <td><?= $row['Margin ID'] ?? $row['idmargin_penjualan'] ?></td>
                <td><?= $row['Margin'] ?? $row['persen'] ?>%</td>
                <td><?= $row['Status'] ?? (($row['STATUS'] ?? $row['status']) == 1 ? 'Aktif' : 'Non-Aktif') ?></td>
                <td class="aksi">
                    <?php $idRow = $row['Margin ID'] ?? $row['idmargin_penjualan']; ?>
                    <a href="?edit=<?= $idRow ?>" class="edit">Edit</a> | 
                    <a href="?delete=<?= $idRow ?>" class="delete" onclick="return confirm('Yakin hapus margin ini?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>