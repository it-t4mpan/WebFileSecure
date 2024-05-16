
![_48eacb4d-33a7-42bb-8b87-4cceea24d5f4](https://github.com/it-t4mpan/WebFileSecure/assets/168879273/407f17ba-8929-4725-93f0-7690401ab966)


Skrip ini berfungsi sebagai manajer file sederhana berbasis web, yang menyediakan antarmuka untuk mengunggah, mengunduh, membuat direktori, dan menghapus file atau direktori di server. Skrip ini juga mencakup fitur login untuk keamanan dan Sudah dilakukan Scanning Virustotal : https://www.virustotal.com/gui/file/396e4af7c7abcb1556423a8d2926d55af9ac95404766f6844c4d574486eb03af/summary

Fitur
Login dan Logout:

- Login: Pengguna harus login menggunakan kredensial yang valid (1718/Kud4).
- Logout: Pengguna dapat logout untuk mengakhiri sesi.

Pengelolaan Direktori:

- Membuat Direktori: Pengguna dapat membuat direktori baru dengan memasukkan nama direktori.
- Melihat Isi Direktori: Pengguna dapat melihat isi direktori, termasuk file dan subdirektori.

Pengelolaan File:

- Unggah File: Pengguna dapat mengunggah file ke server, dengan pengecekan untuk ekstensi file yang diizinkan (png, jpg, jpeg, gif, pdf, txt).
- Menghapus File/Folder: Pengguna dapat menghapus file atau folder. Untuk folder, penghapusan dilakukan secara rekursif.
- Mendownload File dan Folder

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

-----------------
File yang diizinkan Untuk di upload
-----------------
// Function to check if file extension is allowed
function isAllowedExtension($fileName) {
    $allowedExtensions = array('png', 'jpg', 'jpeg', 'gif', 'pdf', 'txt');


--------------
DISCLAIMER
--------------
- Script ini digunakan, hanya untuk pembelajaran.
- Script ini Tidak diizinkan digunakan sebagai Webshell.
- Author skrip ini tidak bertanggung jawab atas kerugian, insiden, atau kerusakan yang timbul dari penggunaan atau ketidakmampuan untuk menggunakan skrip ini.
- Pengguna setuju untuk membebaskan penulis dari segala tuntutan, biaya, kerusakan, dan tanggung jawab yang timbul dari penggunaan skrip ini.
- Skrip ini dapat digunakan untuk mengelola file dan direktori yang mungkin berisi data sensitif. Pengguna harus memastikan bahwa data tersebut dilindungi dengan tindakan keamanan yang memadai.
- Pengguna harus mematuhi semua peraturan dan undang-undang yang berlaku terkait perlindungan data dan privasi.
- Pengguna bertanggung jawab penuh atas pengaturan dan pengelolaan server serta lingkungan di mana skrip ini digunakan.
- Pengguna harus memastikan bahwa skrip ini tidak digunakan untuk tujuan yang melanggar hukum atau sebagai alat untuk melakukan kejahatan siber.
- Pengguna harus memahami bahwa setiap skrip yang diakses secara publik melalui internet memiliki potensi risiko keamanan. Pengguna harus melakukan penilaian dan pengujian keamanan tambahan sebelum menggunakan skrip ini di lingkungan produksi.
- Dengan menggunakan skrip ini, pengguna setuju untuk mematuhi ketentuan-ketentuan di atas dan untuk menggunakan skrip ini secara etis dan bertanggung jawab.
