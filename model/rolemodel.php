<?php

class RoleModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = '`role`';
        $this->view        = 'vu_master_role';
        $this->primary_key = 'idrole';
    }

    public function insert(array $data): Respon {
        return $this->db->send_query(
            "INSERT INTO `role` (nama_role) VALUES (?)",
            [trim($data['nama_role'])]
        );
    }

    public function update(int $id, array $data): Respon {
        return $this->db->send_query(
            "UPDATE `role` SET nama_role = ? WHERE idrole = ?",
            [trim($data['nama_role']), $id]
        );
    }
}