<?php
// 1. Konfigurasi Database PDO
$host = 'localhost';$dbname = 'db_toko_elektronik';
$userdb = 'root'; // Username database (default XAMPP/Laragon)$passdb = '';    
// Password database (kosongkan jika default)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $userdb,);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// 2. Logika DELETE (Menembak tabel asli)
if (isset($_GET['delete'])) {
    $id =$_GET['delete'];
    $stmt =$pdo->prepare("DELETE FROM `user` WHERE iduser = ?");
    $stmt->execute([$id]);
    header("Location: user.php");
    exit;
}

// 3. Logika CREATE & UPDATE (Menembak tabel asli)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname =$_POST['username'];
    $pass =$_POST['password'];
    $role =$_POST['idrole'];
    
    if (!empty($_POST['iduser'])) {
        // Proses Update
        $id =$_POST['iduser'];
        $stmt =$pdo->prepare("UPDATE `user` SET username = ?, `password` = ?, idrole = ? WHERE iduser = ?");
        $stmt->execute([$uname,$pass, $role,$id]);
    } else {
        // Proses Create
        $stmt =$pdo->prepare("INSERT INTO `user` (username, `password`, idrole) VALUES (?, ?, ?)");
        $stmt->execute([$uname, $pass,$role]);
    }
    header("Location: user.php");
    exit;
}

// 4. Persiapan data untuk form Update jika tombol Edit diklik (Ambil dari tabel asli)
$editData = null;
if (isset($_GET['edit'])) {
    $stmt =$pdo->prepare("SELECT * FROM `user` WHERE iduser = ?");
    $stmt->execute([$_GET['edit']]);
    $editData =$stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen User Pegawai</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        /* Styling Tombol Back */
        .btn-back {
            display: inline-block;
            padding: 10px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .btn-back:hover { background-color: #5a6268; }
        
        /* Styling Form & Tabel */
        .form-container { padding: 15px; border: 1px solid #ccc; width: 400px; margin-bottom: 20px; background-color: #f9f9f9; border-radius: 8px; }
        input, select { margin-bottom: 10px; width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 8px 15px; cursor: pointer; background-color: #007BFF; color: white; border: none; border-radius: 4px; }
        button:hover { background-color: #0056b3; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007BFF; color: white; }
    </style>
</head>
<body>

    <!-- Tombol Kembali -->
    <a href="index.php" class="btn-back">⬅ Kembali ke Dashboard</a>

    <h2>Manajemen User Pegawai</h2>

    <!-- Form Input CUD -->
    <div class="form-container">
        <form action="" method="POST">
            <input type="hidden" name="iduser" value="<?= $editData['iduser'] ?? '' ?>">
            
            <label>Username:</label>
            <input type="text" name="username" value="<?= $editData['username'] ?? '' ?>" required>
            
            <label>Password:</label>
            <input type="password" name="password" value="<?= $editData['password'] ?? '' ?>" required>
            
            <label>Pilih Jabatan:</label>
            <select name="idrole" required>
                <option value="">-- Pilih Role --</option>
                <?php
                // Dropdown tetap mengambil data langsung dari tabel role master
                $roleStmt =$pdo->query("SELECT * FROM `role` ORDER BY nama_role ASC");
                while ($r =$roleStmt->fetch(PDO::FETCH_ASSOC)) {
                    $selected = (isset($editData['idrole']) && $editData['idrole'] ==$r['idrole']) ? 'selected' : '';
                    echo "<option value='{$r['idrole']}' $selected>{$r['nama_role']}</option>";
                }
                ?>
            </select>
            
            <button type="submit"><?= $editData ? 'Update Data' : 'Simpan Data' ?></button>
            <?php if($editData): ?>
                <a href="user.php" style="margin-left: 10px; text-decoration: none; color: red;">Batal Edit</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabel Tampilan (Murni Menggunakan VIEW) -->
    <table>
        <tr>
            <th>ID User</th>
            <th>Username</th>
            <th>Password</th>
            <th>Jabatan (Role)</th>
            <th>Aksi</th>
        </tr>
        <?php
        // READ: Mengambil data dari SQL View, bukan dari tabel asli!
        $stmtTampil =$pdo->query("SELECT * FROM v_master_user ORDER BY iduser DESC");
        
        while ($row =$stmtTampil->fetch(PDO::FETCH_ASSOC)) {
            // Kita ubah tampilan password menjadi bintang agar lebih elegan dan aman di tabel
            $hiddenPassword = str_repeat('*', strlen($row['password']));
            
            echo "<tr>
                    <td>{$row['iduser']}</td>
                    <td>{$row['username']}</td>
                    <td>{$hiddenPassword}</td> 
                    <td>{$row['nama_role']}</td>
                    <td>
                        <a href='?edit={$row['iduser']}'>Edit</a> | 
                        <a href='?delete={$row['iduser']}' onclick='return confirm(\"Yakin ingin menghapus pegawai ini?\")'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>

</body>
</html>