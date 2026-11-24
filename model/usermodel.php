<?php
class UserModel extends BaseModel {
    public function __construct(DBconnection $db) {
        parent::__construct($db);
        $this->tabel       = '`user`';
        $this->view        = 'vu_master_user';
        $this->primary_key = 'iduser';
    }

    public function insert(array $data): Respon {
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->send_query(
            "INSERT INTO `user` (username, `password`, idrole) VALUES (?, ?, ?)",
            [trim($data['username']), $hash, (int) $data['idrole']]
        );
    }

    public function update(int $id, array $data): Respon {
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            return $this->db->send_query(
                "UPDATE `user` SET username = ?, `password` = ?, idrole = ? WHERE iduser = ?",
                [trim($data['username']), $hash, (int) $data['idrole'], $id]
            );
        }
        return $this->db->send_query(
            "UPDATE `user` SET username = ?, idrole = ? WHERE iduser = ?",
            [trim($data['username']), (int) $data['idrole'], $id]
        );
    }
}