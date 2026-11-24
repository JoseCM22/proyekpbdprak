<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $vendorModel = new VendorModel($db);

    if (isset($_GET['delete'])) {
        $res = $vendorModel->delete((int) $_GET['delete']);
        Flash::set($res->status ? "Vendor berhasil dihapus." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: vendor.php"); exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['idvendor'] ?? '';
        $res = !empty($id) 
            ? $vendorModel->update((int) $id, $_POST) 
            : $vendorModel->insert($_POST);

        Flash::set($res->status ? "Data vendor berhasil disimpan." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: vendor.php"); exit;
    }

    $editData     = isset($_GET['edit']) ? $vendorModel->find_by_id((int) $_GET['edit']) : null;
    $daftarVendor = $vendorModel->find_all(); // Membaca dari vu_master_vendor

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Vendor - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>

    <?php include_once("sidebar.php"); ?>

    <div class="main-content">
        <div class="page-header">
            <h2>Manajemen Data Vendor</h2>
        </div>

        <?php Flash::tampilkan(); ?>

        <div class="card-form">
            <form method="POST">
                <input type="hidden" name="idvendor" value="<?= $editData['idvendor'] ?? '' ?>">
                
                <label>Nama Vendor:</label>
                <input type="text" name="nama_vendor" placeholder="Contoh: PT. Astra Elektronik" value="<?= htmlspecialchars($editData['nama_vendor'] ?? '') ?>" required>
                
                <label>Badan Hukum:</label>
                <select name="badan_hukum" required>
                    <option value="P" <?= ($editData['badan_hukum'] ?? '') == 'P' ? 'selected' : '' ?>>PT (Perseroan Terbatas)</option>
                    <option value="C" <?= ($editData['badan_hukum'] ?? '') == 'C' ? 'selected' : '' ?>>CV (Commanditaire Vennootschap)</option>
                    <option value="U" <?= ($editData['badan_hukum'] ?? '') == 'U' ? 'selected' : '' ?>>UD / Perorangan</option>
                </select>

                <label>Status:</label>
                <select name="status" required>
                    <option value="A" <?= ($editData['status'] ?? 'A') == 'A' ? 'selected' : '' ?>>Aktif (A)</option>
                    <option value="N" <?= ($editData['status'] ?? '') == 'N' ? 'selected' : '' ?>>Non-Aktif (N)</option>
                </select>

                <button type="submit"><?= $editData ? 'Update Vendor' : 'Simpan Vendor' ?></button>
                <?php if ($editData): ?>
                    <p style="text-align:center; margin-bottom:0;"><a href="vendor.php" style="color:#dc3545;">Batal Edit</a></p>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <tr>
                <th>ID Vendor</th>
                <th>Nama Vendor</th>
                <th>Badan Hukum</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($daftarVendor as $row): ?>
            <tr>
                <td><?= $row['Vendor ID'] ?? $row['idvendor'] ?></td>
                <td><?= htmlspecialchars($row['Company'] ?? $row['nama_vendor']) ?></td>
                <td><?= htmlspecialchars($row['Badan Hukum'] ?? $row['badan_hukum']) ?></td>
                <td><?= htmlspecialchars($row['Status'] ?? $row['status']) ?></td>
                <td class="aksi">
                    <?php $idRow = $row['Vendor ID'] ?? $row['idvendor']; ?>
                    <a href="?edit=<?= $idRow ?>" class="edit">Edit</a> | 
                    <a href="?delete=<?= $idRow ?>" class="delete" onclick="return confirm('Yakin hapus vendor ini?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>