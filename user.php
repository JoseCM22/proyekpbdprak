<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $userModel = new UserModel($db);
    $roleModel = new RoleModel($db);

    if (isset($_GET['delete'])) {
        $res = $userModel->delete((int) $_GET['delete']);
        Flash::set($res->status ? "User berhasil dihapus." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: user.php"); exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['iduser'] ?? '';
        $res = !empty($id) 
            ? $userModel->update((int) $id, $_POST) 
            : $userModel->insert($_POST);

        Flash::set($res->status ? "Data user berhasil disimpan." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: user.php"); exit;
    }

    $editData   = isset($_GET['edit']) ? $userModel->find_by_id((int) $_GET['edit']) : null;
    $daftarUser = $userModel->find_all(); // Membaca dari vu_master_user
    $daftarRole = $roleModel->find_all(); // Untuk dropdown Role

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User - <?= Konfigurasi::APP_NAME ?></title>
</head>
<body>

    <?php include_once("sidebar.php"); ?>

    <div class="main-content">
        <div class="page-header">
            <h2>Manajemen Data User</h2>
        </div>

        <?php Flash::tampilkan(); ?>

        <div class="card-form">
            <form method="POST">
                <input type="hidden" name="iduser" value="<?= $editData['iduser'] ?? '' ?>">
                
                <label>Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($editData['username'] ?? '') ?>" required>
                
                <label>Password <?= $editData ? '(Kosongkan jika tidak ingin diubah)' : '' ?>:</label>
                <input type="password" name="password" <?= $editData ? '' : 'required' ?>>

                <label>Role:</label>
                <select name="idrole" required>
                    <option value="">-- Pilih Role --</option>
                    <?php foreach ($daftarRole as $r): ?>
                        <?php $rId = $r['Role ID'] ?? $r['idrole']; $rNama = $r['Role'] ?? $r['nama_role']; ?>
                        <option value="<?= $rId ?>" <?= ($editData['idrole'] ?? '') == $rId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($rNama) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit"><?= $editData ? 'Update User' : 'Simpan User' ?></button>
                <?php if ($editData): ?>
                    <p style="text-align:center; margin-bottom:0;"><a href="user.php" style="color:#dc3545;">Batal Edit</a></p>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <tr>
                <th>ID User</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($daftarUser as $row): ?>
            <tr>
                <td><?= $row['ID Pegawai'] ?? $row['iduser'] ?></td>
                <td><?= htmlspecialchars($row['Pegawai'] ?? $row['username']) ?></td>
                <td><?= htmlspecialchars($row['Role'] ?? $row['nama_role'] ?? '-') ?></td>
                <td class="aksi">
                    <?php $idRow = $row['ID Pegawai'] ?? $row['iduser']; ?>
                    <a href="?edit=<?= $idRow ?>" class="edit">Edit</a> | 
                    <a href="?delete=<?= $idRow ?>" class="delete" onclick="return confirm('Yakin hapus user ini?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>