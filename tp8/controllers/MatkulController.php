<?php

include_once 'config.php';
include_once 'models/DB.php';
include_once 'models/MataKuliah.php';
include_once 'models/Dosen.php';
include_once 'views/MatkulV.php';

class MatkulController
{
    private $matkul;
    private $dosen;

    public function __construct()
    {
        $this->matkul = new MataKuliah(
            Config::$db_host,
            Config::$db_user,
            Config::$db_pass,
            Config::$db_name
        );
        $this->dosen = new Dosen(
            Config::$db_host,
            Config::$db_user,
            Config::$db_pass,
            Config::$db_name
        );
    }

    public function index()
    {
        $this->matkul->open();
        $this->matkul->getMataKuliah();
        $data['matkul'] = [];
        while ($row = $this->matkul->getResult()) {
            $data['matkul'][] = $row;
        }
        $this->matkul->close();

        $this->dosen->open();
        $this->dosen->getDosen();
        $data['dosen'] = [];
        while ($row = $this->dosen->getResult()) {
            $data['dosen'][] = $row;
        }
        $this->dosen->close();

        $view = new MatkulV();
        $view->render($data);
    }

    public function add($data)
    {
        $this->matkul->open();
        $this->matkul->add([
            'nama_matkul' => $data['nama_matkul'] ?? '',
            'jumlah_sks' => $data['jumlah_sks'] ?? 0,
            'dosen' => $data['dosen'] ?? 0,
        ]);
        $this->matkul->close();

        header('Location: matkul.php');
        exit;
    }

    public function edit($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            header('Location: matkul.php');
            exit;
        }

        $this->matkul->open();
        $this->matkul->getMataKuliahById($id);
        $editData = $this->matkul->getResult();

        if (!$editData) {
            $this->matkul->close();
            header('Location: matkul.php');
            exit;
        }

        $this->matkul->getMataKuliah();
        $data['matkul'] = [];
        while ($row = $this->matkul->getResult()) {
            $data['matkul'][] = $row;
        }
        $this->matkul->close();

        $this->dosen->open();
        $this->dosen->getDosen();
        $data['dosen'] = [];
        while ($row = $this->dosen->getResult()) {
            $data['dosen'][] = $row;
        }
        $this->dosen->close();

        $view = new MatkulV();
        $view->render($data, $editData);
    }

    public function update($data)
    {
        $id = (int) ($data['id'] ?? 0);

        if ($id > 0) {
            $this->matkul->open();
            $this->matkul->update([
                'id' => $id,
                'nama_matkul' => $data['nama_matkul'] ?? '',
                'jumlah_sks' => $data['jumlah_sks'] ?? 0,
                'dosen' => $data['dosen'] ?? 0,
            ]);
            $this->matkul->close();
        }

        header('Location: matkul.php');
        exit;
    }

    public function delete($id)
    {
        $id = (int) $id;

        if ($id > 0) {
            $this->matkul->open();
            $this->matkul->delete($id);
            $this->matkul->close();
        }

        header('Location: matkul.php');
        exit;
    }
}
