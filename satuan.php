<?php
require 'dbconnection.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM satuan WHERE idsatuan = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: satuan.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_satuan'];
    $status = $_POST['status'];
    if (!empty($_POST['idsatuan'])) {
        $stmt = $pdo->prepare("UPDATE satuan SET nama_satuan = ?, status = ? WHERE idsatuan = ?");
        $stmt->execute([$nama, $status, $_POST['idsatuan']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO satuan (nama_satuan, status) VALUES (?, ?)");
        $stmt->execute([$nama, $status]);
    }
    header("Location: satuan.php"); exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM satuan WHERE idsatuan = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Satuan</title><style>body{font-family:sans-serif;} table, th, td {border: 1px solid black; border-collapse: collapse; padding: 5px;}</style></head>
<body>
    <a href="index.php">⬅ Kembali ke Dashboard</a>
    <h2>Manajemen Satuan</h2>
    <form method="POST">
        <input type="hidden" name="idsatuan" value="<?= $editData['idsatuan'] ?? '' ?>">
        Nama: <input type="text" name="nama_satuan" value="<?= $editData['nama_satuan'] ?? '' ?>" required>
        Status: 
        <select name="status">
            <option value="1" <?= (isset($editData['status']) && $editData['status'] == 1) ? 'selected' : '' ?>>Aktif (1)</option>
            <option value="0" <?= (isset($editData['status']) && $editData['status'] == 0) ? 'selected' : '' ?>>Nonaktif (0)</option>
        </select>
        <button type="submit">Simpan</button>
    </form>
    <br>
    <table>
        <tr><th>Satuan ID</th><th>Satuan</th><th>Status</th><th>Aksi</th></tr>
        <?php
        $stmtTampil = $pdo->query("SELECT * FROM vu_master_satuan");
        while ($row = $stmtTampil->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['Satuan ID']}</td>
                    <td>{$row['Satuan']}</td>
                    <td>{$row['Status']}</td>
                    <td><a href='?edit={$row['Satuan ID']}'>Edit</a> | <a href='?delete={$row['Satuan ID']}'>Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>