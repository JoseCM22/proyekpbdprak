<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    $roleModel = new RoleModel($db);

    // Proses Delete
    if (isset($_GET['delete'])) {
        $res = $roleModel->delete((int) $_GET['delete']);
        Flash::set($res->status ? "Role berhasil dihapus." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: role.php"); exit;
    }

    // Proses Create & Update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['idrole'] ?? '';
        $res = !empty($id) 
            ? $roleModel->update((int) $id, $_POST) 
            : $roleModel->insert($_POST);

        Flash::set($res->status ? "Data role tersimpan." : "Gagal: " . $res->message, $res->status ? 'success' : 'error');
        header("Location: role.php"); exit;
    }

    // Ambil data untuk form Edit & Tabel View
    $editData   = isset($_GET['edit']) ? $roleModel->find_by_id((int) $_GET['edit']) : null;
    $daftarRole = $roleModel->find_all(); // Otomatis membaca dari vu_master_role

} catch (DatabaseException $e) {
    die("Kesalahan Sistem: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Role - <?= Konfigurasi::APP_NAME ?></title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 30px; background: #f4f6f9; }
        .btn-back { display: inline-block; padding: 8px 15px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 5px; margin-bottom: 20px; }
        form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 400px; margin-bottom: 20px; }
        input[type="text"] { width: 100%; padding: 10px; margin: 10px 0 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 15px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #dee2e6; padding: 12px 15px; text-align: left; }
        th { background: #007BFF; color: #fff; }
    </style>
</head>
<body>

    <?php include_once("sidebar.php"); ?>
    <div class="main-content">
            <div class="page-header">
                <h2>Manajemen Role</h2>
            </div>  

    <?php Flash::tampilkan(); ?>

    <div class="card-form">
    <form method="POST">
        <input type="hidden" name="idrole" value="<?= $editData['idrole'] ?? '' ?>">
        <label>Nama Role:</label>
        <input type="text" name="nama_role" value="<?= htmlspecialchars($editData['nama_role'] ?? '') ?>" required>
        <button type="submit"><?= $editData ? 'Update Role' : 'Simpan Role' ?></button>
        <?php if ($editData): ?>
            <p style="text-align:center; margin-bottom:0;"><a href="role.php" style="color:#dc3545;">Batal Edit</a></p>
        <?php endif; ?>
    </form>
    </div>


    <table>
        <tr>
            <th>Role ID</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($daftarRole as $row): ?>
        <tr>
            <td><?= $row['Role ID'] ?></td>
            <td><?= htmlspecialchars($row['Role']) ?></td>
            <td>
                <a href="?edit=<?= $row['Role ID'] ?>">Edit</a> | 
                <a href="?delete=<?= $row['Role ID'] ?>" onclick="return confirm('Yakin hapus role ini?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
</body>
</html>