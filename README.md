# LBS News - Website Informatif Dinamis dengan PHP & MySQL

Sebuah proyek website berita/blog yang dibangun sepenuhnya dari nol (*from scratch*) menggunakan PHP native dan database MySQL. Proyek ini mencakup semua fitur esensial dari sebuah website modern, mulai dari tampilan publik yang responsif hingga panel admin yang fungsional untuk mengelola seluruh konten.

![Gambar Homepage LBS News](https://i.imgur.com/example.png)
*(Ganti link di atas dengan URL screenshot homepage proyek Anda)*

## ✨ Fitur Utama

### Untuk Pengguna (Sisi Publik)
- **Tampilan Modern & Responsif**: Desain bersih yang beradaptasi di berbagai ukuran layar, dari desktop hingga mobile.
- **Dark Mode**: Tombol *toggle* untuk mengubah tema antara mode terang dan gelap.
- **Daftar Artikel**: Halaman utama dengan grid artikel yang menarik, lengkap dengan gambar unggulan dan cuplikan.
- **Halaman Detail Artikel**: Layout baca yang fokus dan profesional, dilengkapi navigasi ke artikel sebelumnya/berikutnya.
- **Sistem Kategori**: Pengelompokan artikel berdasarkan kategori yang bisa dijelajahi.
- **Pencarian**: Fungsi pencarian *full-text* untuk menemukan artikel berdasarkan kata kunci.
- **Pelacak Pengunjung**: Sistem sederhana untuk menghitung jumlah pengunjung unik dan harian.

### Untuk Admin (Panel Admin)
- **Layout Sidebar Profesional**: Navigasi admin yang mudah digunakan dan responsif di perangkat mobile.
- **Sistem Login Aman**: Panel admin dilindungi oleh sistem login dan sesi PHP.
- **Dashboard Informatif**: Menampilkan statistik ringkas seperti jumlah total artikel, artikel terbit, dan jumlah pengunjung.
- **Manajemen Artikel (CRUD)**: Kemampuan untuk Tambah, Lihat, Edit, dan Hapus artikel.
- **Manajemen Kategori (CRUD)**: Kemampuan untuk Tambah, Lihat, Edit, dan Hapus kategori.
- **WYSIWYG Editor**: Menggunakan **CKEditor 4** untuk membuat dan mengedit konten artikel dengan mudah.
- **Input Gambar Fleksibel**: Gambar unggulan bisa ditambahkan melalui **upload file manual** atau **link URL online**.
- **Fitur Sortir**: Mengurutkan daftar artikel di panel admin berdasarkan judul, status, atau tanggal.
- **Notifikasi Modern**: Menggunakan **SweetAlert2** untuk konfirmasi hapus dan notifikasi sukses yang interaktif.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP 8+ (Native, Prosedural)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3 (Flexbox, Grid, CSS Variables), JavaScript (ES6)
- **Server Lokal**: XAMPP / Laragon
- **Library**:
    - [CKEditor 4](https://ckeditor.com/ckeditor-4/): Sebagai WYSIWYG editor di panel admin.
    - [SweetAlert2](https://sweetalert2.github.io/): Untuk notifikasi dan popup konfirmasi yang modern.

---

## 🚀 Cara Instalasi & Setup Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda:

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/username-anda/nama-repositori.git](https://github.com/username-anda/nama-repositori.git)
    cd nama-repositori
    ```

2.  **Setup Database**
    - Buka phpMyAdmin.
    - Buat database baru dengan nama `web_informatif`.
    - Pilih database tersebut, lalu klik tab **Import**.
    - Pilih file `.sql` yang sudah Anda ekspor dari database lokal Anda dan jalankan proses impor.

3.  **Konfigurasi Koneksi**
    - Buka file `config/database.php`.
    - Sesuaikan nilai `$host`, `$username`, `$password`, dan `$database` dengan konfigurasi server lokal Anda.
    ```php
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'web_informatif';
    ```

4.  **Setup User Admin**
    - Pastikan Anda memiliki setidaknya satu user di tabel `users`. Jika belum, jalankan skrip `buat_user.php` untuk menghasilkan *hash password*, lalu perbarui secara manual di database.
    - **Penting:** Hapus file `buat_user.php` setelah digunakan.

5.  **Izin Folder**
    - Pastikan folder `uploads/` dapat ditulisi (*writable*) oleh web server agar fitur upload gambar berfungsi.

6.  **Jalankan Proyek**
    - Arahkan web server lokal Anda (misalnya melalui Laragon atau XAMPP) ke direktori proyek.
    - Buka `http://localhost/nama-folder-proyek/` di browser Anda.

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License**.
