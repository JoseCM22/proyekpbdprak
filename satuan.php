<?php
// Konfigurasi Database (Sesuaikan dengan setting lokal Laragon/XAMPP)
$host = 'localhost';
$dbname = 'db_toko_elektronik';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// 1. Logika DELETE (D)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM satuan WHERE idsatuan = ?");
    $stmt->execute([$id]);
    header("Location: satuan.php");
    exit;
}

// 2. Logika CREATE (C) dan UPDATE (U)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_satuan = $_POST['nama_satuan'];
    $status = $_POST['status'];
    
    if (!empty($_POST['idsatuan'])) {
        // Proses Update
        $id = $_POST['idsatuan'];
        $stmt = $pdo->prepare("UPDATE satuan SET nama_satuan = ?, status = ? WHERE idsatuan = ?");
        $stmt->execute([$nama_satuan, $status, $id]);
    } else {
        // Proses Create
        $stmt = $pdo->prepare("INSERT INTO satuan (nama_satuan, status) VALUES (?, ?)");
        $stmt->execute([$nama_satuan, $status]);
    }
    header("Location: satuan.php");
    exit;
}

// Persiapan data untuk form Update jika tombol Edit diklik
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM satuan WHERE idsatuan = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Frontend CUD - Tabel Satuan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-container { margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; width: 300px; }
        input, select { margin-bottom: 10px; width: 100%; padding: 5px; }
        button { padding: 8px 15px; cursor: pointer; }
    </style>
</head>
<body>

    <h2>Kelola Data Master: Satuan</h2>

    <!-- Form Create / Update -->
    <div class="form-container">
        <form action="" method="POST">
            <!-- Hidden input untuk menyimpan ID saat Update -->
            <input type="hidden" name="idsatuan" value="<?= $editData['idsatuan'] ?? '' ?>">
            
            <label>Nama Satuan:</label>
            <input type="text" name="nama_satuan" value="<?= $editData['nama_satuan'] ?? '' ?>" required>
            
            <label>Status:</label>
            <select name="status" required>
                <!-- Menyesuaikan tipe data TINYINT (1 = Aktif, 0 = Nonaktif) -->
                <option value="1" <?= (isset($editData['status']) && $editData['status'] == 1) ? 'selected' : '' ?>>Aktif (1)</option>
                <option value="0" <?= (isset($editData['status']) && $editData['status'] == 0) ? 'selected' : '' ?>>Nonaktif (0)</option>
            </select>
            
            <button type="submit"><?= $editData ? 'Update Data' : 'Simpan Data' ?></button>
            <?php if($editData): ?>
                <a href="satuan.php"><button type="button">Batal</button></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabel Tampilan (Dibutuhkan agar bisa mengeklik Update/Delete) -->
    <table>
        <tr>
            <th>ID</th>
            <th>Nama Satuan</th>
            <th>Status</th>
            <th>Aksi (U / D)</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM satuan ORDER BY idsatuan DESC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $statusTeks = $row['status'] == 1 ? 'Aktif' : 'Nonaktif';
            echo "<tr>
                    <td>{$row['idsatuan']}</td>
                    <td>{$row['nama_satuan']}</td>
                    <td>{$statusTeks}</td>
                    <td>
                        <a href='?edit={$row['idsatuan']}'>Edit</a> | 
                        <a href='?delete={$row['idsatuan']}' onclick='return confirm(\"Yakin ingin menghapus?\")'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>

</body>
</html>