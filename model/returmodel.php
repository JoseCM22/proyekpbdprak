<?php
class ReturModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = 'retur';
        $this->view        = 'vu_transaksi_retur';
        $this->primary_key = 'idretur';
    }

    public function insert(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO retur (created_at, idpenerimaan, iduser) VALUES (NOW(), ?, ?)",
            [(int) $data['idpenerimaan'], (int) $data['iduser']]
        );
    }

    public function update(int $id, array $data): Respon {
        return new Respon(false, "Header retur tidak diubah.");
    }

    public function get_detail(int $idretur): array {
        $sql = "SELECT dr.*, b.nama AS nama_barang 
                FROM detail_retur dr
                JOIN detail_penerimaan dpr ON dr.iddetail_penerimaan = dpr.iddetail_penerimaan
                JOIN barang b ON dpr.barang_idbarang = b.idbarang
                WHERE dr.idretur = ?";
        return $this->db->send_query($sql, [$idretur])->data;
    }

    public function insert_detail(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO detail_retur (jumlah, alasan, idretur, iddetail_penerimaan) VALUES (?, ?, ?, ?)",
            [(int) $data['jumlah'], trim($data['alasan']), (int) $data['idretur'], (int) $data['iddetail_penerimaan']]
        );
    }
}