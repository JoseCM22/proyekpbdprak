<?php
class DBconnection {
    private ?PDO $pdo = null;

    public function __construct() {
        $this->init_connect();
    }

    public function init_connect(): void {
        try {
            $dsn = "mysql:host=" . Konfigurasi::DB_HOST . ";dbname=" . Konfigurasi::DB_NAME . ";charset=utf8mb4";
            $this->pdo = new PDO($dsn, Konfigurasi::DB_USER, Konfigurasi::DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            throw new DatabaseException("Koneksi database gagal: " . $e->getMessage());
        }
    }

    public function send_query(string $query, array $params = []): Respon {
        if (!$this->pdo) {
            return new Respon(false, "Koneksi belum terbentuk.");
        }
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            
            // Cek apakah query menghasilkan baris data (SELECT / CALL)
            $data = ($stmt->columnCount() > 0) ? $stmt->fetchAll() : [];
            return new Respon(true, "Query berhasil dijalankan.", $data);
        } catch (PDOException $e) {
            return new Respon(false, $e->getMessage());
        }
    }

    public function close_connection(): void {
        $this->pdo = null;
    }

    public function __destruct() {
        $this->close_connection();
    }
}