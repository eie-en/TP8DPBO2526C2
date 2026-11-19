<?php

class Dosen extends DB
{
    public function getDosen()
    {
        $query = 'SELECT id, nama, NIDN, phone, join_date FROM lecturers';

        return $this->execute($query);
    }

    public function getDosenById($id)
    {
        $id = (int) $id;
        $query = "SELECT id, nama, NIDN, phone, join_date FROM lecturers WHERE id = $id";

        return $this->execute($query);
    }

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

    public function delete($id)
    {
        $id = (int) $id;
        $query = "DELETE FROM `lecturers` WHERE `id` = $id";

        return $this->execute($query);
    }
}
