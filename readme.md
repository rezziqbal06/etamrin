# Rekrutmen PT SBP GRUP
Program Rekrutmen untuk PT SUMBER BINTANG PERKASA GRUP
Client: Pak Oki (PT SBP)

[![Private Repository](https://img.shields.io/badge/private-%E2%9C%93-red)](https://www.cenah.co.id/) [![Staging](https://img.shields.io/website-up-down-green-red/http/sbpkr.cenah.co.id)](https://sbpkr.cenah.co.id/) [![Website cenah.co.id](
https://img.shields.io/badge/developed%20by-Cipta%20Esensi%20Merenah-blue)](https://www.cenah.co.id/) [![versi 1.0.0](https://img.shields.io/badge/version-1.0.0-yellow)](https://www.cenah.co.id/)

## Latarbelakang
PT SBP GRUP merupakan perusahaan yang sedang tumbuh dan berkembang. Seiring dengan peningkatan tersebut, kandidat lowongan pun ikut membludak, sehingga dibutuhkan sistem untuk membantu penyeleksian kandidat karyawan.

## Tujuan
Membuat proses rekrutment menjadi *unatended*.

## Analisis

Tahapan analisis ditujukan untuk mengidentifikasi masalah beserta penyelesaiannya yang mungkin bisa diselesaikan. Pada tahapan ini terdiri dari analisis code name,

### Code Name
Code Name atau nama kode adalah nama lain atau nama yang biasa disebut atau digunakan untuk project tertentu. Adapun code name dari project ini adalah **karir.sbpgroup.id**.

### Klasifikasi Pengguna Sistem

Pengguna Sistem merupakan seseorang yang menggunakan **karir.sbpgroup.id**. Berikut ini adalah pembagian jenis user berdasarkan hasil analisis.

| ID | Grup Jenis User | Nama Jenis User | Keterangan |
| - | - | - | - |
| 1 | Visitor, Guest | Guest | |
| 2 | Visitor, Kandidat | Calon Pelamar | |
| 3 | Visitor, Kandidat | Pelamar | |
| 4 | Visitor, Kandidat | Calon Karyawan | |
| 5 | Visitor, Kandidat | Karyawan Baru | |
| 6 | Admin | Admin | |
| 7 | Admin | Super Admin | |
| 8 | Admin | User (Karyawan) | Seorang karyawan yang akan menginterview kandidat |

#### Guest / Tamu
Guest atau Tamu adalah pengguna sistem yang mengunjungi **karir.sbpgroup.id** dengan kondisi:
- belum login atau tidak login

#### Calon Pelamar
Calon Pelamar adalah pengguna sistem yang mengunjungi **karir.sbpgroup.id** dengan kondisi:
- setelah login atau,
- sudah daftar,
- tetapi belum melengkapi data yang dibutuhkan.

#### Pelamar
Pelamar adalah pengguna sistem yang mengunjungi **karir.sbpgroup.id** dengan kondisi:
- setelah login atau,
- sudah daftar,
- sudah melengkapi data dan/atau
- sudah melakukan proses apply lowongan atau
- sedang mengikuti proses rekrutmen.

#### Calon Karyawan
Calon Karyawan adalah pengguna sistem yang mengunjungi **karir.sbpgroup.id** dengan kondisi:
- setelah login atau,
- sudah daftar,
- sudah melengkapi data dan
- sudah melakukan proses apply dan
- sudah mengikuti proses rekrutmen dan
- belum menandatangani kontrak.

#### Karyawan Baru
Karyawan Baru adalah pengguna sistem yang mengunjungi **karir.sbpgroup.id** dengan kondisi:
- setelah login atau,
- sudah daftar,
- sudah melengkapi data dan
- sudah melakukan proses apply dan
- sudah mengikuti proses rekrutmen dan
- sudah menandatangani kontrak.

#### Admin
Admin adalah pengguna sistem yang mengunjungi halaman khusus admin pada **karir.sbpgroup.id** dengan kondisi:
- memiliki hak akses ke halaman Admin
- memiliki hak akses ke setiap menu baik sebagaian maupun seluruh
- tidak dapat dapat mengelola hak akses admin lainnya.

#### Super Admin
Super Admin adalah pengguna sistem yang mengunjungi halaman khusus admin pada **karir.sbpgroup.id** dengan kondisi:
- memiliki hak akses ke halaman Admin
- memiliki hak akses ke setiap menu baik sebagaian maupun seluruh
- dapat mengelola hak akses admin lainnya.

#### User
User adalah pengguna sistem yang mengunjungi halaman khusus melalui link yang dapat dibagikan oleh admin dengan kondisi:
- Melihat profil kandidat
- Kalau tidak login, tidak dapat melakukan perubahan

### Data

Berikut ini adalah hasil analisis untuk penggunaan data apa saja yang akan dikelola di dalam sistem ini. Pada tahap analisis, data akan dijelaskan secara garis besar.

#### Perusahaan
Data perusahaan digunakan untuk menyimpan data tentang perusahaan beserta penempatan lowongan.

#### Jabatan
Data jabatan digunakan untuk menyimpan data tentang jabatan yang ada di perusahaan termasuk kriterianya.

#### Lowongan
Data lowongan digunakan untuk menyimpan data tentang lowongan pekerjaan beserta ketentuannya.

#### Admin
Data admin digunakan untuk menyimpan data pengguna admin beserta data pendukung lainnya.

#### Pelamar / User
Data pelamar / user digunakan untuk menyimpan data user atau pelamar beserta data pendukung lainnya.

### Alur Proses

Alur proses merupakan hasil analisis untuk menentukan alur dari setiap proses yang akan dilalui. Alur proses terbagi kedalam 2 bagian, yaitu alur proses untuk admin (backend) dan alur proses untuk user (frontend).

| ID | Grup | Nama Tahapan | Keterangan |
| - | - | - | - |
| 1.a. | Guest | Apply Lowongan | Guest Apply lowongan akan dialihkan ke login atau register |
| 1.b. | Guest | Register / Login | Guest Mengisikan data yang dibutuhkan untuk melamar |
| 2.a. | Screening 1 | Apply Lowongan | Pelamar telah terdaftar dan memilih salah satu joblist / lowongan |
| 2.b. | Screening 1 | Proses | System akan mengecek syarat apply lowongan, 1. Maksimal Usia, 2. Minimum Pendidikan, 3. Minimum Pengalaman Kerja |
| 3.a. | Hasil Screening 1 | Tidak Lolos | System akan menampilkan hasil seleksi screening 1 yang gagal |
| 3.b. | Hasil Screening 1 | Lanjut | System akan mengarahkan pelamar untuk melengkapi data administrasi dan Upload file |
| 4.a. | Screening 2 | Upload KTP | Isi data kelengkapan CV dan filenya |
| 4.b. | Screening 2 | Upload  Ijazah + Transkrip | Isi data kelengkapan ijazah, instansi, tahun lulus, jurusan, grade / nilai |
| 4.c. | Screening 2 | Upload Portofolio | Isi data kelengkapan ijazah, instansi, tahun lulus, jurusan |
| 4.d. | Screening 2 | Upload  CV | Daftar riwayat hidup pelamar |
| 5.a. | Hasil Screening 2 | Tidak Lolos | System akan menampilkan tidak lolos hanya jika waktu lowongan sudah berakhir atau ditutup |
| 5.b. | Hasil Screening 2 | Lanjut | System akan mengarahkan pelamar untuk melengkapi data administrasi dan Upload file |
| 6.a. | Tes | Common Sense | Pelamar tes common sense |
| 6.b. | Tes | Kepribadian / DISC | Pelamar melakukan tes DISC |
| 6.c. | Tes | Tes IQ Gratio | Pelamar melakukan tes GRATIO |
| 7.a. | Hasil Tes | Tidak Lolos | System akan menghitung hasil jawaban pelamar yang tidak memenuhi kriteria dinyatakan tidak lolos |
| 7.b. | Hasil Tes | Lanjut | System akan memberitahu bahwa telah lolos Tes dan akan melanjutkan ke tahap interview |
| 8.a. | Interview | HR | Pelamar interview dengan HRD |
| 8.b. | Interview | User | Pelamar interview dengan USER (BOS) |
| 9.a. | Hasil Interview | Tidak Lolos | HR atau USER (BOS) akan memberikan penilaian dan memutuskan tidak meloloskan pelamar |
| 9.b. | Hasil Interview | Lanjut | HR atau USER (BOS) akan memberikan penilaian dan memutuskan untuk meloloskan pelamar |

#### Home page / Landing Page / Onboarding
Home page / Landing Page / Onboarding merupakan halaman awal tempat aplikasi dijalankan pertama kali.


### Perancangan

Pada tahap perancangan akan dibahas mengenai tampilan, alur proses yang didapatkan dari hasil analisis.

#### Kriteria Perancangan Tampilan

Berikut ini adalah beberapa kriteria perancangan tampilan yang akan digunakan dalam sistem.

| ID | HTML Tag | HTML Attribut | Wajib?* | Deskripsi |
| - | - | - | - | - |
| 1. | a | title | Opsional | Wajib ketika tidak ada teks atau label atau label yang terlalu singkat |
| 2. | img | alt | Iya | Wajib untuk setiap halaman di frontend |

#### Perancangan Nomor Status Lamaran

Nomor Status lamaran digunakan untuk merepresentasikan status / proses yang dilakukan oleh pelamar. Nomor Status ini berguna untuk penyaringan data atau filter. Berikut ini adalah perancangan nomor status lamaran di database.

| Nomor Status Lamaran | Keterangan |
| - | - |
| 0 | Belum Apply Lowongan |
| 1 | Sudah Apply Lowongan |
| 2 | Seleksi Administrasi |
| 3 | Tahap Tes |
| 4 | Tahap Interview |
| 5 | Nego Kontrak |
| 8 | Tidak Diterima (Gagal) |
| 9 | Berhasil Diterima (Selesai) |

#### Alur Proses Apply Job

Berikut ini adalah alur proses yang dilakukan melalui sistem yang disajikan dalam bentuk tabel.

| ID | Nama | Syarat | Keterangan | Class Style |
| - | - | - | - | - |
| 1. | Gues | Tidak ada | - | - |
| 2. | Calon Pelamar | Melakukan Pendaftaran | Data Personal, Foto Profile | - |
| 2.a. | Calon Pelamar (belum konfirmasi Email) | Melakukan Pendaftaran, Alamat Email | Belum menerima email konfirmasi |  `fa-envelope text-danger` |
| 2.b. | Calon Pelamar (Sudah konfirmasi Email) | Kode Verifikasi Email | Memasukan Kode Verifikasi yang dikirim melalui Email |  `fa-envelope text-success` |
| 2.c. | Calon Pelamar (Laki-laki) | Melakukan Pendaftaran | Mengisi jenis kelamin pada saat daftar |  `fa-venus text-info` |
| 2.d. | Calon Pelamar (Perempuan) | Melakukan Pendaftaran | Mengisi jenis kelamin pada saat daftar |  `fa-mars text-primary` |
| 2.e. | Pelamar Aktif | Terdaftar di Sistem | `b_user`.`is_active`=`1` |  `fa-circle text-success` |
| 2.f. | Pelamar Tidak Aktif | Terdaftar di Sistem | `b_user`.`is_active`=`0` |  `fa-circle text-grey` |
| 2.g. | Belum Melamar | Belum Apply Lowongan | `b_user`.`apply_statno`=`0` |  fa-hourglass text-warning |
| 2.h. | Proses Lamaran | Telah Apply Lowongan | `b_user`.`apply_statno`=`1` |  `fa-cog fa-spin text-info` |
| 2.i. | Proses Lamaran | Telah Apply Lowongan | `b_user`.`apply_statno`=`1` |  `fa-cog fa-spin text-info` |

#### Alur Proses Frontend

Berikut ini adalah perancangan alur proses yang disajikan dalam tabel.

| ID | Aktor | Halaman | Aksi | Keterangan |
| - | - | - | - | - |
| 1 | Visitor | Homepage | Melihat informasi utama | Desain sesuai figma |
| 1a | Guest | Register | Melakukan Pendaftaran sebagai syarat mengikut seleksi perekrutan karyawan | Pas foto user wajib diisi |
| 1b | Guest | Login | Sudah pernah daftar dan masuk ke sistem dengan kriteria tertentu | email dan password |
| 2 | Kandidat | Dashboard | Melihat status data | - |
| 3 | Calon Pelamar | Profil | Melengkapi data personal | Biodata, Alamat, KTP, NPWP (jika ada), Tgl lahir, Tempat Lahir, status pernikahan, jenis kelamin, nama |
| 4 | Calon Pelamar | Riwayat Pekerjaan | Melengkapi data Riwayat Pekerjaan | - |
| 5 | Calon Pelamar | Riwayat Pendidikan | Melengkapi data Riwayat Pendidikan | - |
| 6 | Calon Pelamar | Sertifikasi / Keahlian | Melengkapi data Sertifikasi / Keahlian | - |
| 7 | Calon Pelamar | Riwayat Keluarga | Melengkapi data Riwayat Keluarga | - |
| 8 | Visitor | Job List | Melihat daftar lowongan dan menyaring dengan kriteria tertentu | - |
| 10 | Visitor | Job List | Melakukan pencarian lowongan dengan kata kunci tertentu | - |
| 11 | Calon Pelamar | Apply Lowongan | Mendaftar sebagai kandidat untuk posisi tertentu | - |
| 12 | Pelamar | Status Progress | Melihat laju perkembangan proses seleksi dan jadwal proses seleksi | - |
| 13 | Pelamar | Ujian | Mengikuti Ujian-ujian tertentu untuk proses seleksi calon karyawan | - |
| 14 | Pelamar | Ujian | Melihat Status Hasil Ujian (lolos/tidak lolos) | - |
| 15 | Calon Karyawan | Interview | Mendapatkan undangan interview HR dan User | - |
| 16 | Calon Karyawan | Interview | Melakukan permohonan penggantian jadwal interview | - |
| 17 | Calon Karyawan | Kontrak | Melakukan proses penandatangan kontrak | - |
| 18 | Calon Karyawan | Kontrak | Melakukan permohonan negosiasi kontrak | - |
| 19 | Calon Karyawan | Kontrak | Menandatangani kontrak | - |
| 20 | Kandidat | Notifikasi | Melihat semua notifikasi dan menandai yang sudah dibaca | - |

#### Register

Pada halaman register digunakan untuk guest user supaya dapat terdaftar dan menjadi pelamar. Pada halaman ini diwajibkan untuk mengisi data sebagai berikut:

1. Pas Foto
2. Nama Lengkap
3. Nomor KTP
4. Jenis Kelamin
5. Nomor HP
6. Password

#### Login
Pada halaman register digunakan untuk guest user supaya dapat masuk ke dalam sistem dan melakukan proses didalamnya. Pada halaman ini diwajibkan untuk mengisi data sebagai berikut:

1. Email, ketika daftar
2. Password, ketika daftar

#### Dashboard
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.


#### Alur Proses Backend

Berikut ini adalah perancangan alur proses backend yang disajikan dalam tabel.

| ID | Halaman | Sub Halaman | Aksi | Keterangan |
| - | - | - | - | - |
| 1. | Dashboard | - | Melihat resume / data statistik | Desain sesuai figma |
| 2.a. | Pengaturan | Lowongan | Mengelola Data Lowongan | - |
| 2.a.i. | Pengaturan | Lowongan/Tes | Mengelola Data Urutan Tes ketika apply Lowongan | - |
| 2.b. | Pengaturan | Jabatan | Mengelola Data Jabatan | - |
| 2.c. | Pengaturan | Alamat | Mengelola Data Alamat Perusahaan | Untuk alamat interview |
| 3. | Ujian | Bank Soal | Mengelola Data Bank Soal | Untuk tes setelah mendapatkan lowongan|

## License
This is a private project developed by [![Cipta Esensi Merenah](https://www.cenah.co.id/favicon.png)](https://www.cenah.co.id/) doesn't have any license to spread or published as public used except by permission from **Project owner** or **The Client**.

### Copyright
Copyright 2021-2021, Cipta Esensi Merenah.
