<?php
class PenjualanModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = 'penjualan';
        $this->view        = 'vu_transaksi_penjualan';
        $this->primary_key = 'idpenjualan';
    }

    public function insert(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, iduser, idmargin_penjualan) 
             VALUES (NOW(), 0, 0, 0, ?, ?)",
            [(int) $data['iduser'], (int) $data['idmargin_penjualan']]
        );
    }

    public function update(int $id, array $data): Respon {
        return new Respon(false, "Nota penjualan tidak diubah secara manual.");
    }

    public function get_detail(int $idpenjualan): array {
        $sql = "SELECT dpj.*, b.nama AS nama_barang 
                FROM detail_penjualan dpj
                JOIN barang b ON dpj.idbarang = b.idbarang
                WHERE dpj.penjualan_idpenjualan = ?";
        return $this->db->send_query($sql, [$idpenjualan])->data;
    }

    public function insert_detail(array $data): Respon {
        $harga  = (int) $data['harga_satuan'];
        $jumlah = (int) $data['jumlah'];
        $sub    = $harga * $jumlah;

        return $this->db->send_query(
            "INSERT INTO detail_penjualan (harga_satuan, jumlah, subtotal, penjualan_idpenjualan, idbarang) 
             VALUES (?, ?, ?, ?, ?)",
            [$harga, $jumlah, $sub, (int) $data['penjualan_idpenjualan'], (int) $data['idbarang']]
        );
    }
}