<?php
require 'dbconnection.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM barang WHERE idbarang = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: barang.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis = $_POST['jenis'];
    $nama = $_POST['nama'];
    $satuan = $_POST['idsatuan'];
    $status = $_POST['status'];
    $harga = $_POST['harga'];
    
    if (!empty($_POST['idbarang'])) {
        $stmt = $pdo->prepare("UPDATE barang SET jenis=?, nama=?, idsatuan=?, status=?, harga=? WHERE idbarang=?");
        $stmt->execute([$jenis, $nama, $satuan, $status, $harga, $_POST['idbarang']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO barang (jenis, nama, idsatuan, status, harga) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$jenis, $nama, $satuan, $status, $harga]);
    }
    header("Location: barang.php"); exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM barang WHERE idbarang = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Barang</title><style>body{font-family:sans-serif;} table, th, td {border: 1px solid black; border-collapse: collapse; padding: 5px;}</style></head>
<body>
    <a href="index.php">⬅ Kembali ke Dashboard</a>
    <h2>Manajemen Barang</h2>
    <form method="POST">
        <input type="hidden" name="idbarang" value="<?= $editData['idbarang'] ?? '' ?>">
        Nama: <input type="text" name="nama" value="<?= $editData['nama'] ?? '' ?>" required>
        Jenis:
        <select name="jenis">
            <option value="K" <?= (isset($editData['jenis']) && $editData['jenis'] == 'K') ? 'selected' : '' ?>>Komponen (K)</option>
            <option value="L" <?= (isset($editData['jenis']) && $editData['jenis'] == 'L') ? 'selected' : '' ?>>Laptop (L)</option>
            <option value="E" <?= (isset($editData['jenis']) && $editData['jenis'] == 'E') ? 'selected' : '' ?>>Elektronik (E)</option>
            <option value="H" <?= (isset($editData['jenis']) && $editData['jenis'] == 'H') ? 'selected' : '' ?>>Handphone (H)</option>
        </select>
        Satuan:
        <select name="idsatuan" required>
            <?php
            $satStmt = $pdo->query("SELECT * FROM satuan");
            while ($s = $satStmt->fetch(PDO::FETCH_ASSOC)) {
                $sel = (isset($editData['idsatuan']) && $editData['idsatuan'] == $s['idsatuan']) ? 'selected' : '';
                echo "<option value='{$s['idsatuan']}' $sel>{$s['nama_satuan']}</option>";
            }
            ?>
        </select>
        Status: 
        <select name="status">
            <option value="1" <?= (isset($editData['status']) && $editData['status'] == 1) ? 'selected' : '' ?>>Aktif (1)</option>
            <option value="0" <?= (isset($editData['status']) && $editData['status'] == 0) ? 'selected' : '' ?>>Nonaktif (0)</option>
        </select>
        Harga: <input type="number" name="harga" value="<?= $editData['harga'] ?? '' ?>" required>
        <button type="submit">Simpan</button>
    </form>
    <br>
    <table>
        <tr><th>ID Barang</th><th>Jenis</th><th>Nama</th><th>Satuan</th><th>Status</th><th>Harga</th><th>Aksi</th></tr>
        <?php
        $stmtTampil = $pdo->query("SELECT * FROM vu_master_barang");
        while ($row = $stmtTampil->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['ID Barang']}</td>
                    <td>{$row['Jenis']}</td>
                    <td>{$row['Nama']}</td>
                    <td>{$row['Satuan']}</td>
                    <td>{$row['Status']}</td>
                    <td>{$row['Harga']}</td>
                    <td><a href='?edit={$row['ID Barang']}'>Edit</a> | <a href='?delete={$row['ID Barang']}'>Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>