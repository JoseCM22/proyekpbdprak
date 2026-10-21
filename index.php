<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Toko Elektronik</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        .menu-container { 
            display: flex; 
            justify-content: center; 
            flex-wrap: wrap; 
            gap: 15px; 
            max-width: 600px; 
            margin: 0 auto; 
        }
        .btn-menu { 
            display: block; 
            padding: 20px; 
            width: 250px; 
            background-color: #007BFF; 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            font-size: 18px; 
            font-weight: bold;
        }
        .btn-menu:hover { background-color: #01b8e6; }
    </style>
</head>
<body>

    <h1>Menu Utama Data Master</h1>

    <div class="menu-container">
        <!-- Link menuju file PHP masing-masing -->
        <a href="role.php" class="btn-menu">Data Role</a>
        <a href="satuan.php" class="btn-menu">Data Satuan</a>
        <a href="vendor.php" class="btn-menu">Data Vendor</a>
        <a href="user.php" class="btn-menu">Data User Pegawai</a>
        <a href="barang.php" class="btn-menu">Data Barang</a>
        <a href="margin.php" class="btn-menu">Data Margin</a>
    </div>

</body>
</html>