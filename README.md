# KOKBagus - Sistem Manajemen Inventaris

KOKBagus adalah aplikasi web sederhana untuk manajemen inventaris gudang yang dibangun menggunakan PHP Native dan MySQL. Aplikasi ini dirancang untuk memudahkan pencatatan barang, kategori, serta riwayat stok masuk dan keluar.

## Fitur Utama

- **Dashboard**: Ringkasan data barang dan aktivitas terbaru.
- **Manajemen Barang**: Tambah, edit, hapus, dan lihat stok barang.
- **Manajemen Kategori**: Pengelompokan barang berdasarkan kategori.
- **Transaksi Stok**: Pencatatan barang masuk dan barang keluar.
- **Laporan**: Cetak laporan inventaris dan ekspor ke format CSV.
- **Manajemen Pengguna**: Pengelolaan hak akses sistem.

## Prasyarat

Sebelum menjalankan aplikasi ini, pastikan Anda telah menginstal:
- [XAMPP](https://www.apachefriends.org/index.html) (disarankan versi dengan PHP 7.4 ke atas)
- Browser (Chrome, Firefox, atau Edge)

## Instalasi

1. **Clone Repositori**
   ```bash
   git clone https://github.com/username/lspgudang.git
   ```
   *Pindahkan folder proyek ke dalam direktori `htdocs` jika menggunakan XAMPP.*

2. **Persiapan Database**
   - Jalankan MySQL di panel kontrol XAMPP.
   - Buka `http://localhost/phpmyadmin/`.
   - Buat database baru dengan nama `db_inventory_jewepe`.
   - Import file `database/database.sql` ke dalam database tersebut.

3. **Konfigurasi Koneksi**
   - Buka file `config/database.php`.
   - Sesuaikan konfigurasi database (host, user, password, db) dengan pengaturan lokal Anda.
   - Sesuaikan `BASE_URL` sesuai dengan alamat folder proyek Anda.

4. **Memasukkan Pengguna Pertama (Admin)**
   Karena tabel `users` kosong, Anda perlu memasukkan pengguna pertama secara manual melalui phpMyAdmin. Jalankan query SQL berikut:
   ```sql
   INSERT INTO `users` (`username`, `password`, `nama_lengkap`) 
   VALUES ('admin', MD5('admin123'), 'Administrator');
   ```

## Penggunaan

1. Buka browser dan akses `http://localhost/lspgudang/`.
2. Login menggunakan kredensial yang telah dibuat:
   - **Username**: `admin`
   - **Password**: `admin123`
3. Anda sekarang dapat mulai mengelola data inventaris.

## Struktur Proyek

- `assets/`: File CSS, JS, dan gambar.
- `config/`: Konfigurasi database.
- `database/`: File SQL untuk struktur database.
- `layout/`: Template header dan footer.
- `modules/`: Modul-modul fitur (Barang, Kategori, Laporan, User, dll).

## Lisensi

Proyek ini dibuat untuk tujuan pembelajaran (LSP). Silakan gunakan dan modifikasi sesuai kebutuhan.
