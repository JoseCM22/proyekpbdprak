<?php
require 'dbconnection.php';

if (isset($_GET['delete'])) {
    $stmt =$pdo->prepare("DELETE FROM `user` WHERE iduser = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: user.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname =$_POST['username'];
    $pass =$_POST['password'];
    $role =$_POST['idrole'];
    if (!empty($_POST['iduser'])) {
        $stmt =$pdo->prepare("UPDATE `user` SET username = ?, `password` = ?, idrole = ? WHERE iduser = ?");
        $stmt->execute([$uname,$pass, $role,$_POST['iduser']]);
    } else {
        $stmt =$pdo->prepare("INSERT INTO `user` (username, `password`, idrole) VALUES (?, ?, ?)");
        $stmt->execute([$uname, $pass,$role]);
    }
    header("Location: user.php"); exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $stmt =$pdo->prepare("SELECT * FROM `user` WHERE iduser = ?");
    $stmt->execute([$_GET['edit']]);
    $editData =$stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>User</title><style>body{font-family:sans-serif;} table, th, td {border: 1px solid black; border-collapse: collapse; padding: 5px;}</style></head>
<body>
    <a href="index.php">⬅ Kembali ke Dashboard</a>
    <h2>Manajemen User</h2>
    <form method="POST">
        <input type="hidden" name="iduser" value="<?= $editData['iduser'] ?? '' ?>">
        Username: <input type="text" name="username" value="<?= $editData['username'] ?? '' ?>" required>
        Password: <input type="password" name="password" value="<?= $editData['password'] ?? '' ?>" required>
        Role: 
        <select name="idrole" required>
            <?php
            $roleStmt =$pdo->query("SELECT * FROM `role`");
            while ($r =$roleStmt->fetch(PDO::FETCH_ASSOC)) {
                $sel = (isset($editData['idrole']) && $editData['idrole'] ==$r['idrole']) ? 'selected' : '';
                echo "<option value='{$r['idrole']}' $sel>{$r['nama_role']}</option>";
            }
            ?>
        </select>
        <button type="submit">Simpan</button>
    </form>
    <br>
    <table>
        <tr><th>ID Pegawai</th><th>Pegawai</th><th>Role</th><th>Aksi</th></tr>
        <?php
        $stmtTampil =$pdo->query("SELECT * FROM vu_master_user");
        while ($row =$stmtTampil->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['ID Pegawai']}</td>
                    <td>{$row['Pegawai']}</td>
                    <td>{$row['Role']}</td>
                    <td><a href='?edit={$row['ID Pegawai']}'>Edit</a> <a href='?delete={$row['ID Pegawai']}'>Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>