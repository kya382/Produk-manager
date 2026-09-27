# Product Manager - Galeri Barang Antik

Aplikasi Web Manajemen Inventaris Toko Barang Antik menggunakan PHP Native, MySQL (PDO), dan CSS Flexbox.

## Fitur & Keamanan
1. **CRUD Lengkap:** Create, Read, Update, Delete data produk.
2. **Post-Redirect-Get (PRG):** Mencegah resubmit form/duplikasi data saat refresh.
3. **Validasi Server-Side:** Validasi batas karakter, tipe data, nilai positif, dan nama produk unik.
4. **Keamanan SQL Injection:** Menggunakan PDO Prepared Statements pada semua operasi database.
5. **Keamanan XSS:** Menggunakan `htmlspecialchars()` pada seluruh output teks HTML.
6. **Keamanan CSRF:** Penghapusan data via method POST yang diproteksi token session CSRF.
7. **UI Responsif:** Layout CSS Box Model dan Flexbox.

## Cara Menjalankan
1. Import `database/store_db.sql` ke MySQL via phpMyAdmin.
2. Atur konfigurasi database di `config/db.php`.
3. Jalankan server lokal (XAMPP/Laragon) dan akses folder project `public/index.php`.