<?php

class MataKuliah extends DB
{
    public function getMataKuliah()
    {
        $query = 'SELECT mata_kuliah.id, mata_kuliah.nama_matkul, mata_kuliah.jumlah_sks, lecturers.nama
                  FROM mata_kuliah
                  JOIN lecturers ON mata_kuliah.dosen = lecturers.id';

        return $this->execute($query);
    }

    public function getMataKuliahById($id)
    {
        $id = (int) $id;
        $query = "SELECT id, nama_matkul, jumlah_sks, dosen FROM mata_kuliah WHERE id = $id";

        return $this->execute($query);
    }

    public function add($data)
    {
        $nama_matkul = $this->escape($data['nama_matkul'] ?? '');
        $jumlah_sks = (int) ($data['jumlah_sks'] ?? 0);
        $dosen = (int) ($data['dosen'] ?? 0);

        $query = "INSERT INTO `mata_kuliah` (`nama_matkul`, `jumlah_sks`, `dosen`)
                  VALUES ('$nama_matkul', $jumlah_sks, $dosen)";

        return $this->execute($query);
    }

    public function update($data)
    {
        $id = (int) ($data['id'] ?? 0);
        $nama_matkul = $this->escape($data['nama_matkul'] ?? '');
        $jumlah_sks = (int) ($data['jumlah_sks'] ?? 0);
        $dosen = (int) ($data['dosen'] ?? 0);

        $query = "UPDATE `mata_kuliah`
                  SET `nama_matkul` = '$nama_matkul', `jumlah_sks` = $jumlah_sks, `dosen` = $dosen
                  WHERE `id` = $id";

        return $this->execute($query);
    }

    public function delete($id)
    {
        $id = (int) $id;
        $query = "DELETE FROM `mata_kuliah` WHERE `id` = $id";

        return $this->execute($query);
    }
}
