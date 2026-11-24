<?php
class KartuStokModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = 'kartu_stok';
        $this->view        = 'vu_kartu_stok';
        $this->primary_key = 'idkartu_stok';
    }

    public function insert(array $data): Respon {
        return new Respon(false, "Kartu stok diisi otomatis oleh trigger transaksi.");
    }

    public function update(int $id, array $data): Respon {
        return new Respon(false, "Riwayat mutasi kartu stok tidak boleh diubah.");
    }
}