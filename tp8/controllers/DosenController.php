<?php

include_once 'config.php';
include_once 'models/DB.php';
include_once 'models/Dosen.php';
include_once 'views/LecturerV.php';

class DosenController
{
    private $dosen;

    public function __construct()
    {
        $this->dosen = new Dosen(
            Config::$db_host,
            Config::$db_user,
            Config::$db_pass,
            Config::$db_name
        );
    }

    public function index()
    {
        $this->dosen->open();
        $this->dosen->getDosen();

        $data = [];
        while ($row = $this->dosen->getResult()) {
            $data[] = $row;
        }
        $this->dosen->close();

        $view = new LecturerV();
        $view->render($data);
    }

    public function add($data)
    {
        $this->dosen->open();
        try {
            $this->dosen->add([
                'nama' => $data['name'] ?? '',
                'NIDN' => $data['nidn'] ?? '',
                'phone' => $data['phone'] ?? '',
                'join_date' => $data['join_date'] ?? '',
            ]);
            $this->dosen->close();

            header('Location: index.php');
            exit;
        } catch (mysqli_sql_exception $e) {
            $this->dosen->close();

            // Kode 1062 adalah error MySQL untuk Duplicate entry (NIDN sudah terdaftar)
            if ($e->getCode() == 1062) {
                echo "<script>
                        alert('Gagal menambah data: NIDN sudah terdaftar!');
                        window.location.href = 'index.php';
                      </script>";
                exit;
            }
            throw $e;
        }
    }

    public function edit($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            header('Location: index.php');
            exit;
        }

        $this->dosen->open();
        $this->dosen->getDosenById($id);
        $editData = $this->dosen->getResult();

        if (!$editData) {
            $this->dosen->close();
            header('Location: index.php');
            exit;
        }

        $this->dosen->getDosen();
        $data = [];
        while ($row = $this->dosen->getResult()) {
            $data[] = $row;
        }
        $this->dosen->close();

        $view = new LecturerV();
        $view->render($data, $editData);
    }

    public function update($data)
    {
        $id = (int) ($data['id'] ?? 0);

        if ($id > 0) {
            $this->dosen->open();
            try {
                $this->dosen->update([
                    'id' => $id,
                    'nama' => $data['name'] ?? '',
                    'NIDN' => $data['nidn'] ?? '',
                    'phone' => $data['phone'] ?? '',
                    'join_date' => $data['join_date'] ?? '',
                ]);
                $this->dosen->close();

                header('Location: index.php');
                exit;
            } catch (mysqli_sql_exception $e) {
                $this->dosen->close();

                // Munculkan popup jika NIDN yang diedit bentrok dengan dosen lain
                if ($e->getCode() == 1062) {
                    echo "<script>
                            alert('Gagal memperbarui data: NIDN sudah digunakan oleh dosen lain!');
                            window.location.href = 'index.php?id_edit=".$id."';
                          </script>";
                    exit;
                }
                throw $e;
            }
        }

        header('Location: index.php');
        exit;
    }

    public function delete($id)
    {
        $id = (int) $id;

        if ($id > 0) {
            $this->dosen->open();
            try {
                $this->dosen->delete($id);
                $this->dosen->close();
            } catch (mysqli_sql_exception $e) {
                $this->dosen->close();

                // Kode 1451: Dosen tidak bisa dihapus karena masih dipakai di tabel mata_kuliah
                if ($e->getCode() == 1451) {
                    echo "<script>
                            alert('Gagal menghapus: Dosen ini masih mengampu mata kuliah!');
                            window.location.href = 'index.php';
                          </script>";
                    exit;
                }
                throw $e;
            }
        }

        header('Location: index.php');
        exit;
    }
}
