<?php
class Flash {
    private static function mulai_session(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $pesan, string $tipe = 'success'): void {
        self::mulai_session();
        $_SESSION['flash'] = ['pesan' => $pesan, 'tipe' => $tipe];
    }

    public static function tampilkan(): void {
        self::mulai_session();
        if (isset($_SESSION['flash'])) {
            $warna = $_SESSION['flash']['tipe'] === 'error' ? '#dc3545' : '#28a745';
            echo "<div style='padding:10px; margin-bottom:15px; border-radius:4px; color:white; background-color:{$warna};'>" 
                 . htmlspecialchars($_SESSION['flash']['pesan']) . 
                 "</div>";
            unset($_SESSION['flash']);
        }
    }
}