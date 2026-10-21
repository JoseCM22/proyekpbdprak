<?php
require 'dbconnection.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM vendor WHERE idvendor = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: vendor.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_vendor'];
    $bh = $_POST['badan_hukum'];
    $status = $_POST['status'];
    if (!empty($_POST['idvendor'])) {
        $stmt = $pdo->prepare("UPDATE vendor SET nama_vendor = ?, badan_hukum = ?, status = ? WHERE idvendor = ?");
        $stmt->execute([$nama, $bh, $status, $_POST['idvendor']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, ?)");
        $stmt->execute([$nama, $bh, $status]);
    }
    header("Location: vendor.php"); exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM vendor WHERE idvendor = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Vendor</title><style>body{font-family:sans-serif;} table, th, td {border: 1px solid black; border-collapse: collapse; padding: 5px;}</style></head>
<body>
    <a href="index.php">⬅ Kembali ke Dashboard</a>
    <h2>Manajemen Vendor</h2>
    <form method="POST">
        <input type="hidden" name="idvendor" value="<?= $editData['idvendor'] ?? '' ?>">
        Nama Vendor: <input type="text" name="nama_vendor" value="<?= $editData['nama_vendor'] ?? '' ?>" required>
        Badan Hukum: 
        <select name="badan_hukum">
            <option value="P" <?= (isset($editData['badan_hukum']) && $editData['badan_hukum'] == 'P') ? 'selected' : '' ?>>PT (P)</option>
            <option value="C" <?= (isset($editData['badan_hukum']) && $editData['badan_hukum'] == 'C') ? 'selected' : '' ?>>CV (C)</option>
            <option value="I" <?= (isset($editData['badan_hukum']) && $editData['badan_hukum'] == 'I') ? 'selected' : '' ?>>Individu (I)</option>
        </select>
        Status: 
        <select name="status">
            <option value="A" <?= (isset($editData['status']) && $editData['status'] == 'A') ? 'selected' : '' ?>>Aktif (A)</option>
            <option value="N" <?= (isset($editData['status']) && $editData['status'] == 'N') ? 'selected' : '' ?>>Nonaktif (N)</option>
        </select>
        <button type="submit">Simpan</button>
    </form>
    <br>
    <table>
        <tr><th>Vendor ID</th><th>Company</th><th>Badan Hukum</th><th>Status</th><th>Aksi</th></tr>
        <?php
        $stmtTampil = $pdo->query("SELECT * FROM vu_master_vendor");
        while ($row = $stmtTampil->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['Vendor ID']}</td>
                    <td>{$row['Company']}</td>
                    <td>{$row['Badan Hukum']}</td>
                    <td>{$row['Status']}</td>
                    <td><a href='?edit={$row['Vendor ID']}'>Edit</a> | <a href='?delete={$row['Vendor ID']}'>Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>