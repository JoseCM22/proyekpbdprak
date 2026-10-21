<?php
require 'dbconnection.php';

if (isset($_GET['delete'])) {
    $stmt =$pdo->prepare("DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: margin_penjualan.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $persen =$_POST['persen'];
    $status =$_POST['status'];
    $iduser =$_POST['iduser'];
    
    if (!empty($_POST['idmargin_penjualan'])) {
        // Menggunakan NOW() untuk memperbarui updated_at
        $stmt =$pdo->prepare("UPDATE margin_penjualan SET persen = ?, STATUS = ?, iduser = ?, updated_at = NOW() WHERE idmargin_penjualan = ?");
        $stmt->execute([$persen,$status, $iduser,$_POST['idmargin_penjualan']]);
    } else {
        // Menggunakan NOW() untuk created_at dan updated_at saat insert
        $stmt =$pdo->prepare("INSERT INTO margin_penjualan (created_at, persen, STATUS, iduser, updated_at) VALUES (NOW(), ?, ?, ?, NOW())");
        $stmt->execute([$persen, $status,$iduser]);
    }
    header("Location: margin_penjualan.php"); exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $stmt =$pdo->prepare("SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?");
    $stmt->execute([$_GET['edit']]);
    $editData =$stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Margin Penjualan</title><style>body{font-family:sans-serif;} table, th, td {border: 1px solid black; border-collapse: collapse; padding: 5px;}</style></head>
<body>
    <a href="index.php">⬅ Kembali ke Dashboard</a>
    <h2>Manajemen Margin Penjualan</h2>
    <form method="POST">
        <input type="hidden" name="idmargin_penjualan" value="<?= $editData['idmargin_penjualan'] ?? '' ?>">
        Persen (Gunakan desimal, misal 0.10): <input type="text" name="persen" value="<?= $editData['persen'] ?? '' ?>" required>
        Status: 
        <select name="status">
            <option value="1" <?= (isset($editData['STATUS']) &&$editData['STATUS'] == 1) ? 'selected' : '' ?>>Aktif (1)</option>
            <option value="0" <?= (isset($editData['STATUS']) &&$editData['STATUS'] == 0) ? 'selected' : '' ?>>Nonaktif (0)</option>
        </select>
        Pilih Pegawai (User ID):
        <select name="iduser" required>
            <?php
            $usrStmt =$pdo->query("SELECT * FROM `user`");
            while ($u =$usrStmt->fetch(PDO::FETCH_ASSOC)) {
                $sel = (isset($editData['iduser']) && $editData['iduser'] ==$u['iduser']) ? 'selected' : '';
                echo "<option value='{$u['iduser']}' $sel>{$u['username']}</option>";
            }
            ?>
        </select>
        <button type="submit">Simpan</button>
    </form>
    <br>
    <table>
        <tr><th>Margin ID</th><th>Margin</th><th>Status</th><th>Aksi</th></tr>
        <?php
        $stmtTampil =$pdo->query("SELECT * FROM vu_master_marginP");
        while ($row =$stmtTampil->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['Margin ID']}</td>
                    <td>{$row['Margin']}</td>
                    <td>{$row['Status']}</td>
                    <td><a href='?edit={$row['Margin ID']}'>Edit</a> <a href='?delete={$row['Margin ID']}'>Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>