<?php
class PengadaanModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = 'pengadaan';
        $this->view        = 'vu_transaksi_pengadaan';
        $this->primary_key = 'idpengadaan';
    }
    // Mengambil data langsung dari Base Table (aman untuk isi dropdown <select>)
    public function find_all_raw(): array {
        $respon = $this->db->send_query("SELECT * FROM {$this->tabel}");
        return $respon->data;
    }
    // Membuat Header Nota PO baru
    public function insert(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO pengadaan (`timestamp`, user_iduser, `status`, vendor_idvendor, subtotal_nilai, ppn, total_nilai) 
             VALUES (NOW(), ?, 'P', ?, 0, 0, 0)",
            [(int) $data['user_iduser'], (int) $data['vendor_idvendor']]
        );
    }

    // Ubah Status PO (misal: Pending -> Selesai / Batal)
    public function update(int $id, array $data): Respon {
        return $this->db->send_query(
            "UPDATE pengadaan SET `status` = ? WHERE idpengadaan = ?",
            [$data['status'], $id]
        );
    }

    // Read Detail Item dari SQL View
    public function get_detail(int $idpengadaan): array {
        return $this->db->send_query(
            "SELECT * FROM vu_detail_pengadaan WHERE idpengadaan = ?",
            [$idpengadaan]
        )->data;
    }

    // Insert Detail Item via Stored Procedure (Harga otomatis dari Function)
    public function insert_detail(array $data): Respon {
        return $this->db->send_query(
            "CALL sp_tambah_detail_pengadaan(?, ?, ?)",
            [
                (int) $data['idpengadaan'],
                (int) $data['idbarang'],
                (int) $data['jumlah']
            ]
        );
    }

    // Hapus satu baris item detail (otomatis memicu trigger hitung ulang total PO)
    public function delete_detail(int $iddetail_pengadaan): Respon {
        return $this->db->send_query(
            "DELETE FROM detail_pengadaan WHERE iddetail_pengadaan = ?",
            [$iddetail_pengadaan]
        );
    }
}