<?php
class VendorModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = 'vendor';
        $this->view        = 'vu_master_vendor';
        $this->primary_key = 'idvendor';
    }

    public function insert(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO vendor (nama_vendor, badan_hukum, `status`) VALUES (?, ?, ?)",
            [trim($data['nama_vendor']), $data['badan_hukum'], $data['status']]
        );
    }

    public function update(int $id, array $data): Respon {
        return $this->db->send_query(
            "UPDATE vendor SET nama_vendor = ?, badan_hukum = ?, `status` = ? WHERE idvendor = ?",
            [trim($data['nama_vendor']), $data['badan_hukum'], $data['status'], $id]
        );
    }
}