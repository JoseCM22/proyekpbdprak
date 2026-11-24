<?php
class PengadaanModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = 'pengadaan';
        $this->view        = 'vu_transaksi_pengadaan';
        $this->primary_key = 'idpengadaan';
    }

    public function insert(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO pengadaan (`timestamp`, user_iduser, `status`, vendor_idvendor, subtotal_nilai, ppn, total_nilai) 
             VALUES (NOW(), ?, 'P', ?, 0, 0, 0)",
            [(int) $data['user_iduser'], (int) $data['vendor_idvendor']]
        );
    }

    public function update(int $id, array $data): Respon {
        return $this->db->send_query(
            "UPDATE pengadaan SET `status` = ? WHERE idpengadaan = ?",
            [$data['status'], $id]
        );
    }

    // Method tambahan untuk detail pengadaan
    public function get_detail(int $idpengadaan): array {
        $sql = "SELECT dp.*, b.nama AS nama_barang, s.nama_satuan 
                FROM detail_pengadaan dp
                JOIN barang b ON dp.idbarang = b.idbarang
                JOIN satuan s ON b.idsatuan = s.idsatuan
                WHERE dp.idpengadaan = ?";
        return $this->db->send_query($sql, [$idpengadaan])->data;
    }

    public function insert_detail(array $data): Respon {
        $harga  = (int) $data['harga_satuan'];
        $jumlah = (int) $data['jumlah'];
        $sub    = $harga * $jumlah; // Sementara dihitung di PHP sebelum di-upgrade ke Trigger

        return $this->db->send_query(
            "INSERT INTO detail_pengadaan (harga_satuan, jumlah, sub_total, idbarang, idpengadaan) 
             VALUES (?, ?, ?, ?, ?)",
            [$harga, $jumlah, $sub, (int) $data['idbarang'], (int) $data['idpengadaan']]
        );
    }
}