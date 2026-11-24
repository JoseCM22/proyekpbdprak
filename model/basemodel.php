<?php
abstract class BaseModel implements Crudable {
    protected DBconnection $db;
    protected string $tabel;
    protected string $view;
    protected string $primary_key;

    public function __construct(DBconnection $db) {
        $this->db = $db;
    }

    public function find_all(): array {
        $sumber = !empty($this->view) ? $this->view : $this->tabel;
        $respon = $this->db->send_query("SELECT * FROM {$sumber}");
        return $respon->data;
    }

    public function find_by_id(int $id): ?array {
        $respon = $this->db->send_query(
            "SELECT * FROM {$this->tabel} WHERE {$this->primary_key} = ?",
            [$id]
        );
        return $respon->data[0] ?? null;
    }

    public function delete(int $id): Respon {
        return $this->db->send_query(
            "DELETE FROM {$this->tabel} WHERE {$this->primary_key} = ?",
            [$id]
        );
    }

    abstract public function insert(array $data): Respon;
    abstract public function update(int $id, array $data): Respon;
}