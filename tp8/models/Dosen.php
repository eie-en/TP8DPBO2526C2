<?php

// Model Dosen untuk mengelola query CRUD pada tabel lecturers
class Dosen extends DB
{
    // Mengambil seluruh data dosen dari tabel lecturers
    public function getDosen()
    {
        $query = "SELECT id, nama, NIDN, phone, join_date FROM lecturers";
        return $this->execute($query);
    }

    // Mengambil satu data dosen berdasarkan ID
    public function getDosenById($id)
    {
        $id = (int) $id;
        $query = "SELECT id, nama, NIDN, phone, join_date FROM lecturers WHERE id = $id";
        return $this->execute($query);
    }

    // Menambahkan data dosen baru ke tabel lecturers
    public function add($data)
    {
        $nama = $this->escape($data['nama'] ?? '');
        $nidn = $this->escape($data['NIDN'] ?? '');
        $phone = $this->escape($data['phone'] ?? '');
        $join_date = $this->escape($data['join_date'] ?? '');

        $query = "INSERT INTO `lecturers` (`nama`, `NIDN`, `phone`, `join_date`)
                  VALUES ('$nama', '$nidn', '$phone', '$join_date')";

        return $this->execute($query);
    }

    // Memperbarui data dosen berdasarkan ID
    public function update($data)
    {
        $id = (int) ($data['id'] ?? 0);
        $nama = $this->escape($data['nama'] ?? '');
        $nidn = $this->escape($data['NIDN'] ?? '');
        $phone = $this->escape($data['phone'] ?? '');
        $join_date = $this->escape($data['join_date'] ?? '');

        $query = "UPDATE `lecturers`
                  SET `nama` = '$nama', `NIDN` = '$nidn', `phone` = '$phone', `join_date` = '$join_date'
                  WHERE `id` = $id";

        return $this->execute($query);
    }

    // Menghapus data dosen berdasarkan ID
    public function delete($id)
    {
        $id = (int) $id;
        $query = "DELETE FROM `lecturers` WHERE `id` = $id";
        return $this->execute($query);
    }
}
