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
        return $this->db->send_query(
            "SELECT * FROM vu_detail_penerimaan WHERE idpenerimaan = ?",
            [$idpenerimaan]
        )->data;
    }

    public function insert_detail(array $data): Respon {
        return $this->db->send_query(
            "CALL sp_tambah_detail_penerimaan(?, ?, ?, ?)",
            [
                (int) $data['idpenerimaan'],
                (int) $data['barang_idbarang'],
                (int) $data['jumlah_terima'],
                (int) ($data['harga_satuan_terima'] ?? 0)
            ]
        );
    }

    public function delete_detail(int $iddetail_penerimaan): Respon {
        return $this->db->send_query(
            "DELETE FROM detail_penerimaan WHERE iddetail_penerimaan = ?",
            [$iddetail_penerimaan]
        );
    }

    public function get_barang_by_penerimaan(int $idpenerimaan): array {
        return $this->db->send_query(
            "CALL sp_opsi_barang_penerimaan(?)",
            [$idpenerimaan]
        )->data;
    }
}