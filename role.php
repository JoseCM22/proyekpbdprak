<?php
require 'dbconnection.php';

if (isset($_GET['delete'])) {
    $stmt =$pdo->prepare("DELETE FROM `role` WHERE idrole = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: role.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama =$_POST['nama_role'];
    if (!empty($_POST['idrole'])) {
        $stmt =$pdo->prepare("UPDATE `role` SET nama_role = ? WHERE idrole = ?");
        $stmt->execute([$nama,$_POST['idrole']]);
    } else {
        $stmt =$pdo->prepare("INSERT INTO `role` (nama_role) VALUES (?)");
        $stmt->execute([$nama]);
    }
    header("Location: role.php"); exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $stmt =$pdo->prepare("SELECT * FROM `role` WHERE idrole = ?");
    $stmt->execute([$_GET['edit']]);
    $editData =$stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Role</title><style>body{font-family:sans-serif;} table, th, td {border: 1px solid black; border-collapse: collapse; padding: 5px;}</style></head>
<body>
    <a href="index.php">⬅ Kembali ke Dashboard</a>
    <h2>Manajemen Role</h2>
    <form method="POST">
        <input type="hidden" name="idrole" value="<?= $editData['idrole'] ?? '' ?>">
        Nama Role: <input type="text" name="nama_role" value="<?= $editData['nama_role'] ?? '' ?>" required>
        <button type="submit">Simpan</button>
    </form>
    <br>
    <table>
        <tr><th>Role ID</th><th>Role</th><th>Aksi</th></tr>
        <?php
        $stmtTampil =$pdo->query("SELECT * FROM vu_master_role");
        while ($row =$stmtTampil->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['Role ID']}</td>
                    <td>{$row['Role']}</td>
                    <td><a href='?edit={$row['Role ID']}'>Edit</a> <a href='?delete={$row['Role ID']}'>Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>