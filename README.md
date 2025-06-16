# My-Futsal

My-Futsal adalah aplikasi web untuk manajemen penyewaan lapangan futsal, transaksi, dan administrasi venue. Aplikasi ini dibangun menggunakan **Laravel** (backend), **Bun** (runtime JS), **TailwindCSS** (UI), **Alpine.js** (interaktivitas), dan template admin **TailAdmin**.

---

## Daftar Isi

- [My-Futsal](#my-futsal)
  - [Daftar Isi](#daftar-isi)
  - [Fitur Utama](#fitur-utama)
  - [Arsitektur Aplikasi](#arsitektur-aplikasi)
  - [Desain Database](#desain-database)
  - [Perancangan Proyek](#perancangan-proyek)
    - [**Alur Booking \& Transaksi**](#alur-booking--transaksi)
    - [**Hak Akses**](#hak-akses)
    - [**Desain UI**](#desain-ui)
  - [Instalasi](#instalasi)
  - [Development](#development)
  - [Lisensi](#lisensi)

---

## Fitur Utama

-   **Booking & Transaksi:** Pemesanan jadwal, pembayaran, dan invoice otomatis.
-   **Manajemen User:** Admin & user, autentikasi, profil, dan pengelolaan akun.
-   **Dashboard Admin:** Statistik transaksi, booking, dan laporan keuangan.
-   **Export Data:** Ekspor invoice dan transaksi ke Excel/PDF.
-   **Filter & Search:** Pencarian dan filter data transaksi, invoice.
-   **Notifikasi:** Email verifikasi.

---

## Arsitektur Aplikasi

```
Frontend (Blade, Tailwind, Alpine.js)
        |
        v
Laravel Controller <--> Model <--> Database (MySQL)
        |
        v
REST API (untuk AJAX/Alpine.js)
```

-   **Frontend:** Blade template, TailwindCSS, Alpine.js, TailAdmin.
-   **Backend:** Laravel (MVC), REST API untuk AJAX.
-   **Database:** MySQL/MariaDB.
-   **Runtime JS:** Bun untuk pengelolaan dependensi frontend.

---

## Desain Database

![Screenshot from 2025-06-16 14-14-55](https://github.com/user-attachments/assets/27bfec6a-7f83-46f9-ad63-942683829a74)

---

## Perancangan Proyek

### **Alur Booking & Transaksi**

1. **User** mendaftar/login.
2. **User** memilih venue & lapangan, lalu melakukan booking.
3. Sistem menampilkan jadwal & harga, user memilih waktu.
4. **User** melakukan pembayaran (transfer/manual).
5. **Admin** memverifikasi pembayaran.
6. Sistem meng-generate **invoice**.
7. **User/Admin** dapat melihat riwayat transaksi dan invoice.

### **Hak Akses**

-   **Admin:** CRUD semua data, verifikasi pembayaran, kelola user, export data.
-   **User:** Booking, lihat riwayat, download invoice, edit profil.

### **Desain UI**

-   Responsive, dark mode support.
-   Tabel data dengan filter, search, dan pagination (Alpine.js + AJAX).

---

## Instalasi

> **Disarankan menggunakan Bun runtime**

```bash
cp .env.example .env
composer install
bun install
php artisan key:generate
php artisan storage:link
```

## Development

```bash
bun dev
```

---

## Lisensi

MIT License.
