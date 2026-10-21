<?php
$host = 'localhost';
$dbname = 'db_toko_elektronik';
$userdb = 'root'; 
$passdb = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $userdb, $passdb);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>