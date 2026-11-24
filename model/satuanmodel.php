<?php
class SatuanModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = 'satuan';
        $this->view        = 'vu_master_satuan';
        $this->primary_key = 'idsatuan';
    }

    public function insert(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO satuan (nama_satuan, `status`) VALUES (?, ?)",
            [trim($data['nama_satuan']), (int) $data['status']]
        );
    }

    public function update(int $id, array $data): Respon {
        return $this->db->send_query(
            "UPDATE satuan SET nama_satuan = ?, `status` = ? WHERE idsatuan = ?",
            [trim($data['nama_satuan']), (int) $data['status'], $id]
        );
    }
}