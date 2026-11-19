<?php

// Kelas induk untuk mengelola koneksi dan eksekusi query database
class DB
{
    public $db_host = '';
    public $db_user = '';
    public $db_pass = '';
    public $db_name = '';
    public $db_link;
    public $result;

    // Konstruktor: menyimpan konfigurasi koneksi database
    public function __construct($db_host, $db_user, $db_pass, $db_name)
    {
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
    }

    // Membuka koneksi ke server MySQL
    public function open()
    {
        $this->db_link = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        if (!$this->db_link) {
            exit('Connection failed: '.mysqli_connect_error());
        }
    }

    // Mengamankan string input untuk mencegah error sintaks SQL / SQL Injection
    public function escape($value)
    {
        return mysqli_real_escape_string($this->db_link, $value);
    }

    // Menjalankan query SQL
    public function execute($query)
    {
        $this->result = mysqli_query($this->db_link, $query);

        return $this->result;
    }

    // Mengambil satu baris hasil query dalam bentuk array
    public function getResult()
    {
        return mysqli_fetch_array($this->result);
    }

    // Menutup koneksi database
    public function close()
    {
        if ($this->db_link) {
            mysqli_close($this->db_link);
        }
    }
}
