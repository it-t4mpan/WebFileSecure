Skrip ini berfungsi sebagai manajer file sederhana berbasis web, yang menyediakan antarmuka untuk mengunggah, membuat direktori, dan menghapus file atau direktori di server. Skrip ini juga mencakup fitur login untuk keamanan.

Fitur
Login dan Logout:

- Login: Pengguna harus login menggunakan kredensial yang valid (admin/password).
- Logout: Pengguna dapat logout untuk mengakhiri sesi mereka.

Pengelolaan Direktori:

- Membuat Direktori: Pengguna dapat membuat direktori baru dengan memasukkan nama direktori.
- Melihat Isi Direktori: Pengguna dapat melihat isi direktori, termasuk file dan subdirektori.

Pengelolaan File:

- Unggah File: Pengguna dapat mengunggah file ke server, dengan pengecekan untuk ekstensi file yang diizinkan (png, jpg, jpeg, gif, pdf, txt).
- Menghapus File/Folder: Pengguna dapat menghapus file atau folder. Untuk folder, penghapusan dilakukan secara rekursif.

Logging Aktivitas:

- Semua aktivitas penting, seperti login, logout, unggah file, pembuatan direktori, dan penghapusan file/direktori dicatat dalam log (activity.log) bersama dengan alamat IP dan user agent pengguna.

Keamanan

1. Sanitasi Input:

- Nama file dan direktori disanitasi menggunakan fungsi sanitizeName untuk mencegah karakter yang tidak diinginkan.

2. Validasi File Unggahan:

- Hanya mengizinkan unggahan file dengan ekstensi tertentu untuk mencegah eksekusi skrip berbahaya.

3. Proteksi terhadap LFI dan RFI:

- Tidak ada include atau require dengan input dari pengguna, sehingga menghindari risiko LFI/RFI.
- Parameter URL divalidasi dan disanitasi.

-----------------
Default Username Pass 
-----------------

- $valid_username = "1718";
- $valid_password = "Kud4";

-----------------
Folder wfs
-----------------
- // Directory where files are stored (change as per your setup)
$directory = './wfs/';
