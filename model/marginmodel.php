<?php
class MarginModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = 'margin_penjualan';
        $this->view        = 'vu_master_marginP';
        $this->primary_key = 'idmargin_penjualan';
    }

    public function insert(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO margin_penjualan (created_at, persen, `STATUS`, iduser, updated_at) VALUES (NOW(), ?, ?, ?, NOW())",
            [(float) $data['persen'], (int) $data['status'], (int) $data['iduser']]
        );
    }

    public function update(int $id, array $data): Respon {
        return $this->db->send_query(
            "UPDATE margin_penjualan SET persen = ?, `STATUS` = ?, iduser = ?, updated_at = NOW() WHERE idmargin_penjualan = ?",
            [(float) $data['persen'], (int) $data['status'], (int) $data['iduser'], $id]
        );
    }
}