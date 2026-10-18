# GlowMart REST API

GlowMart REST API adalah layanan backend berbasis Laravel untuk mengelola data produk dan kategori pada aplikasi toko kecantikan. API ini menyediakan autentikasi pengguna, pengelolaan data, pencarian dan filter produk, validasi input, serta upload gambar produk.

## Teknologi yang Digunakan

- PHP 8
- Laravel 9
- MySQL
- Laravel Sanctum
- Postman

## Fitur

- Registrasi dan login pengguna
- Autentikasi menggunakan Bearer Token
- Melihat profil pengguna dan logout
- CRUD produk dan kategori
- Validasi input menggunakan Form Request
- Pencarian produk berdasarkan nama
- Filter produk berdasarkan kategori dan rentang harga
- Upload gambar produk dengan validasi file

## Persyaratan

- PHP dan Composer
- MySQL
- Git
- Postman

## Instalasi

1. Clone repository:

   ```bash
   git clone https://github.com/dewantidev-lab/glowmart-laravel.git
   ```

2. Masuk ke folder project:

   ```bash
   cd glowmart-laravel
   ```

3. Instal dependency:

   ```bash
   composer install
   ```

4. Salin file konfigurasi:

   ```bash
   copy .env.example .env
   ```

   Jika menggunakan macOS atau Linux, gunakan `cp .env.example .env`.

5. Atur konfigurasi database MySQL di file `.env`.

6. Buat database sesuai nama yang tercantum pada konfigurasi, lalu jalankan:

   ```bash
   php artisan key:generate
   php artisan migrate
   php artisan storage:link
   php artisan serve
   ```

## Endpoint API

### Authentication

| Method | Endpoint | Fungsi |
|---|---|---|
| POST | `/api/register` | Registrasi pengguna |
| POST | `/api/login` | Login pengguna |
| GET | `/api/profile` | Melihat profil |
| POST | `/api/logout` | Logout pengguna |

### Products

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/api/products` | Melihat daftar produk |
| GET | `/api/products/{product}` | Melihat detail produk |
| POST | `/api/products` | Menambahkan produk |
| PUT/PATCH | `/api/products/{product}` | Memperbarui produk |
| DELETE | `/api/products/{product}` | Menghapus produk |

### Categories

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/api/categories` | Melihat daftar kategori |
| GET | `/api/categories/{category}` | Melihat detail kategori |
| POST | `/api/categories` | Menambahkan kategori |
| PUT/PATCH | `/api/categories/{category}` | Memperbarui kategori |
| DELETE | `/api/categories/{category}` | Menghapus kategori |

## Pencarian dan Filter Produk

Endpoint `/api/products` mendukung parameter berikut:

- `search`: mencari produk berdasarkan nama.
- `category_id`: memfilter produk berdasarkan kategori.
- `min_price`: menentukan harga minimum.
- `max_price`: menentukan harga maksimum.

Contoh:

```text
/api/products?search=Facial
/api/products?category_id=1
/api/products?min_price=30000&max_price=50000
```

## Pengujian API

Pengujian endpoint dilakukan menggunakan Postman, mencakup autentikasi, pengelolaan produk dan kategori, pencarian, filter, validasi input, serta upload gambar.

## Catatan Keamanan

Jangan mengunggah file `.env`, password database, atau token autentikasi ke repository publik.