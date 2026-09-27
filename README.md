# 🏛️ Galeri Barang Antik - Product Manager

Aplikasi web sederhana untuk mengelola inventaris produk pada toko/galeri barang antik. Dibuat menggunakan **PHP Native**, **MySQL (PDO)**, dan **CSS Flexbox** tanpa framework external.

---

## 🚀 Fitur Utama
1. **CRUD Operations:** Tambah, lihat, edit, dan hapus data koleksi barang antik.
2. **Pencarian Data:** Fitur pencarian barang berdasarkan nama atau kategori (Metode GET).
3. **Indikator Stok Kritis:** Peringatan visual otomatis (border merah & badge ⚠️) untuk produk dengan stok $\le$ 3 unit.
4. **Notifikasi Interaktif:** Alert pemberitahuan saat berhasil menambah, mengubah, atau menghapus data.

---

## 🛡️ Aspek Keamanan & Arsitektur (Persyaratan Tugas)
* **SQL Injection Protection:** Menggunakan PDO Prepared Statements (`prepare` & `execute`) pada semua query database.
* **XSS (Cross-Site Scripting) Protection:** Semua output teks disaring menggunakan `htmlspecialchars()`.
* **CSRF Protection:** Fitur hapus data menggunakan method POST dengan verifikasi token dari `$_SESSION`.
* **Post-Redirect-Get (PRG) Pattern:** Mencegah pengiriman data ganda saat halaman di-refresh setelah tambah/edit data.
* **Server-Side Validation:** 
  * Nama produk wajib unik (tidak boleh duplikat).
  * Validasi tipe data dan angka non-negatif pada harga dan stok.

---

## 📂 Struktur Folder
```text
produk manager/
├── config/
│   └── db.php          # Koneksi ke database MySQL via PDO
├── database/
│   └── store_db.sql    # File struktur & data awal database
├── public/
│   ├── assets/
│   │   └── style.css   # Custom CSS (Flexbox & Box Model)
│   ├── create.php      # Form & logika tambah data
│   ├── delete.php      # Logika hapus data (+ CSRF)
│   ├── edit.php        # Form & logika edit data
│   └── index.php       # Halaman utama (Tampil data & search)
└── README.md           # Dokumentasi proyek
