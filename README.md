# ⡞⠳⣄⣀⣠⠞⢷ ֹ۪
<p align="center">
  <✦•┈๑⋅⋯ ⋯⋅๑┈•✦>
</p>
    
Saya Aghni Lutvia Sari dengan NIM 2508921 mengerjakan TP8 dalam mata kuliah DPBO untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin

## .✦ ݁˖ Penjelasan Design 
Saya membuat database dengan nama db_kuliah. Berisikan tabel lecturers dan tabel mata_kuliah

1. Tabel Lecturers
   Bermuat data dosen dengan atribut :
   - ID sebagai primary key dengan extra auto increment
   - nama (VARCHAR) bermaksimalkan 30 karakter, untuk menghindari input nama yang terlalu panjang
   - NIDN (VARCHAR) dengan constraint unique sehingga satu nomor dosen berbeda dengan yang lainnya
   - phone (VARCHAR) untuk diisi dengan nomor telepon. Menggunakan varchar karena fungsinya menampung karakter angka sebagai informasi, bukan untuk dihitung kemudian.
   - join_date (DATE), berisikan tanggal bergabung dosen.

2. Mata_Kuliah
   berisikan data mata kuliah yang berada pada institusi, berhubungan dengan tabel Lecturers untuk menentukan dosen yang diampu pada suatu matkul.
   - ID sebagai primary key dengan extra auto increment
   - nama (VARCHAR) maksimal 30 karakter
   - jumlah_sks (INT)
   - dosen (INT), disambungkan dengan kolom ID pada tabel lecturers dengan DELETE on RESTRICT dan UPDATE CASCADE. Sehingga ketika masih ada dosen yang mengampu pada suatu mata kuliah, data nya tidak bisa dihapus. Ketika ada update pada tabel lecturers, maka tabel mata_kuliah akan dapat updatenya juga.
  
Kemudian website terdapat navigasi Home yang isinya adalah daftar dosen dan navigasi Mata Kuliah. Kedua halaman memiliki kolom INSERT di sebelah kiri, lalu di setiap baris datanya terdapat aksi update dan delete. Ketika menekan tombol Edit, kolom insert di sebelah kanan akan menjadi kolom update.

Website akan mengeluarkan beberapa popup ketika :
- Delete, akan ada pop up konfirmasi yang memastikan apakah user benar benar ingin menghapus data. Menghindari adanya missclick.
- Delete data dosen ketika dosen memiliki mata kuliah yang sedang diampu. 
- Ketika NIDN yang baru diinput memiliki kesamaan dengan data NIDN yang sudah tersedia

Struktur folder kurang lebih sama seperti pada contoh yang ada di modul, struktur kodenya pun tidak jauh berbeda.Bahkan tampilannya menggunakan css yang sama.

```text
tp8/
├── controllers/
│   ├── DosenController.php
│   └── MatkulController.php
├── models/
│   ├── DB.php
│   ├── Dosen.php
│   └── MataKuliah.php
├── templates/
│   ├── index.html
│   └── matkul.html
├── views/
│   ├── LecturerV.php
│   ├── MatkulV.php
│   └── Template.php
├── config.php
├── db_kuliah.sql
├── index.php
└── matkul.php
```

1. `config.php` (menyimpan host, user, password, dan nama database `db_kuliah`) serta `db_kuliah.sql` (menyimpan database dengan tabel `lecturers` sebagai induk dan `mata_kuliah` sebagai anak).
2. **Entry Point (Front Controller)**: `index.php` (untuk halaman Dosen) dan `matkul.php` (untuk halaman Mata Kuliah).
3. **Controller**: `controllers/DosenController.php` dan `controllers/MatkulController.php`.
4. **Model**: `models/DB.php` (kelas induk koneksi MySQLi), `models/Dosen.php`, dan `models/MataKuliah.php`.
5. **View & Template**: `views/LecturerV.php`, `views/MatkulV.php`, `views/Template.php`, serta kerangka tampilan murni HTML di `templates/index.html` dan `templates/matkul.html`.

## .✦ ݁˖ Flow Code
### 1. **Inisialisasi & Routing Request** (`index.php` & `matkul.php`)

   a. Memuat kelas `Template`, `DB`, dan controller terkait (`DosenController` atau `MatkulController`).

   b. Membuat objek controller (`$controller = new DosenController();` atau `new MatkulController();`), yang otomatis menginisialisasi objek model di dalam konstruktornya menggunakan kredensial dari `Config`.

   c. Mengecek parameter HTTP yang masuk secara berurutan:
      - `isset($_POST['add'])` memanggil `$controller->add($_POST)` (Tambah data).
      - `isset($_POST['update'])` memanggil `$controller->update($_POST)` (Simpan perubahan).
      - `!empty($_GET['id_hapus'])` memanggil `$controller->delete($id)` (Hapus data).
      - `!empty($_GET['id_edit'])` memanggil `$controller->edit($id)` (Tampilkan form edit).
      - Default (tanpa parameter)** $\rightarrow$ memanggil `$controller->index()` (Tampilkan halaman utama).
        
### 2. **Menampilkan Data**
   
   a. Bagian controller dosen :
     
     - Memanggil `$this->dosen->open()` untuk membuka koneksi MySQLi melalui `DB::open()`.
     - Menjalankan query `SELECT` lewat `Dosen::getDosen()` dan melooping `getResult()` (`mysqli_fetch_array`) untuk memasukkan seluruh baris ke array `$data`.
     - Menutup koneksi (`close()`), lalu mengirim `$data` ke `LecturerV::render($data)`.

   b. Bagian controller matkul :

     - Mengambil daftar mata kuliah beserta nama dosen pengampunya menggunakan query `JOIN` (`MataKuliah::getMataKuliah()`) ke dalam `$data['matkul']`.
     - Mengambil seluruh daftar dosen (`Dosen::getDosen()`) ke dalam `$data['dosen']` agar dropdown `<select name="dosen">` pada form memiliki pilihan dosen.
     - Mengirim array gabungan `$data` ke `MatkulV::render($data)`.

### 3. **Penyusunan Tampilan HTML** (`View` & `Template`)

   a. `LecturerV::render()` atau `MatkulV::render()` melakukan perulangan `foreach` pada data dari database, memetakan kolom dengan `list(...)`, dan merangkai tag baris tabel `<tr>...</tr>` lengkap dengan nomor urut `$no++` serta tombol **Edit** dan **Hapus**.

   b. Pada `MatkulV`, perulangan kedua dilakukan pada `$data['dosen']` untuk membentuk daftar `<option value="id">Nama Dosen</option>`.

   c. View mengecek parameter `$editData`:
     - Jika `$editData` kosong (`null`), placeholder form diisi nilai kosong dan tombol submit diset ke mode **Tambah** (`name="add"`).
     - Jika `$editData` ada isinya, placeholder form diisi nilai lama dari database dan tombol submit berubah menjadi mode **Edit** (`name="update"`) beserta tombol **Batal**.
   
   d. View membuat instance `new Template('templates/index.html')` (atau `matkul.html`), mengganti semua placeholder (`JUDUL`, `FORM_TITLE`, `VAL_*`, `OPTION`, `DATA_TABEL`) lewat `Template::replace()`, membersihkan sisa placeholder `DATA_*` lewat `Template::clear()`, lalu mencetak HTML utuh ke browser via `Template::write()`.

### 4. **Menambah & Memperbarui Data** (`CREATE` & `UPDATE`)

   a. Saat pengguna menekan tombol **Add** (`POST['add']`) atau **Update** (`POST['update']`), controller meneruskan data input ke `Dosen::add()`/`update()` atau `MataKuliah::add()`/`update()`.

   b. Di dalam Model, setiap input string diamankan terlebih dahulu dengan `DB::escape()` (`mysqli_real_escape_string`) dan angka dikonversi dengan `(int)` sebelum menjalankan query `INSERT` atau `UPDATE` melalui `DB::execute()`.

   c. **Penanganan Error / Validasi Database (`try...catch`)**:
     - Pada `DosenController`, jika terjadi duplikasi `NIDN` (*MySQL error code* `1062`), blok `catch (mysqli_sql_exception $e)` menangkapnya dan menampilkan popup `alert()` JavaScript tanpa memunculkan halaman error PHP.
     
   d. Setelah query berhasil, controller melakukan *redirect* menggunakan pola **Post/Redirect/Get** (`header('Location: index.php')` atau `matkul.php`) agar data tidak terkirim ganda jika pengguna menekan *Refresh*.

### 5. **Mengedit & Menghapus Data** (`EDIT` & `DELETE`)

a. **Saat tombol Edit diklik (`?id_edit=X`)**:
  - Controller mengambil **dua** hal: data spesifik berdasarkan ID (`getDosenById($id)` / `getMataKuliahById($id)`) untuk mengisi form sebelah kiri, dan seluruh daftar data untuk tetap ditampilkan di tabel sebelah kanan.


b. **Saat tombol Hapus diklik (`?id_hapus=X`)**:
  - Browser lebih dulu menjalankan dialog konfirmasi JavaScript (`onclick="return confirm(...)"`).
  - Jika pengguna menekan **OK**, controller memanggil method `delete($id)` pada Model untuk menjalankan `DELETE FROM ... WHERE id = $id`.
  - Pada `DosenController::delete()`, jika dosen yang akan dihapus masih terikat relasi *Foreign Key* dengan data di tabel `mata_kuliah` (*MySQL error code* `1451`), `catch` akan menahan penghapusan dan memunculkan peringatan bahwa dosen tersebut masih mengampu mata kuliah.

## .✦ ݁˖ Dokumentasi 

<img width="1920" height="1080" alt="Screenshot (716)" src="https://github.com/user-attachments/assets/7039580c-5f44-47b2-b786-58c40647e5d9" />

<img width="1920" height="1080" alt="Screenshot (715)" src="https://github.com/user-attachments/assets/aec6b3c0-9a0e-4972-9ad5-753c34167f66" />

<img width="1920" height="1080" alt="Screenshot (714)" src="https://github.com/user-attachments/assets/2b4e1602-9c64-4676-bbd1-b9eeac8ce745" />

https://github.com/user-attachments/assets/b1ba4d80-3425-48af-9d7b-18227938c1b3

<p align="center">
  <✦•┈๑⋅⋯ ⋯⋅๑┈•✦>
</p>
