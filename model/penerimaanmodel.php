<?php
class PenerimaanModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = 'penerimaan';
        $this->view        = 'vu_transaksi_penerimaan';
        $this->primary_key = 'idpenerimaan';
    }

    public function insert(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO penerimaan (created_at, `status`, idpengadaan, iduser) VALUES (NOW(), 'A', ?, ?)",
            [(int) $data['idpengadaan'], (int) $data['iduser']]
        );
    }

    public function update(int $id, array $data): Respon {
        return $this->db->send_query(
            "UPDATE penerimaan SET `status` = ? WHERE idpenerimaan = ?",
            [$data['status'], $id]
        );
    }

    public function get_detail(int $idpenerimaan): array {
        $sql = "SELECT dpr.*, b.nama AS nama_barang 
                FROM detail_penerimaan dpr
                JOIN barang b ON dpr.barang_idbarang = b.idbarang
                WHERE dpr.idpenerimaan = ?";
        return $this->db->send_query($sql, [$idpenerimaan])->data;
    }

    public function insert_detail(array $data): Respon {
        $harga  = (int) $data['harga_satuan_terima'];
        $jumlah = (int) $data['jumlah_terima'];
        $sub    = $harga * $jumlah;

        return $this->db->send_query(
            "INSERT INTO detail_penerimaan (idpenerimaan, barang_idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima) 
             VALUES (?, ?, ?, ?, ?)",
            [(int) $data['idpenerimaan'], (int) $data['barang_idbarang'], $jumlah, $harga, $sub]
        );
    }
}