<?php
class BarangModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = 'barang';
        $this->view        = 'vu_master_barang';
        $this->primary_key = 'idbarang';
    }

    public function insert(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO barang (jenis, nama, idsatuan, `status`, harga) VALUES (?, ?, ?, ?, ?)",
            [
                $data['jenis'],
                trim($data['nama']),
                (int) $data['idsatuan'],
                (int) $data['status'],
                (int) $data['harga']
            ]
        );
    }

    public function update(int $id, array $data): Respon {
        return $this->db->send_query(
            "UPDATE barang SET jenis = ?, nama = ?, idsatuan = ?, `status` = ?, harga = ? WHERE idbarang = ?",
            [
                $data['jenis'],
                trim($data['nama']),
                (int) $data['idsatuan'],
                (int) $data['status'],
                (int) $data['harga'],
                $id
            ]
        );
    }
}