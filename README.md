# Admin Queue Management System

Sistem manajemen antrian untuk admin panel yang dibangun dengan Laravel dan React.

## Fitur

### 1. Login Admin
- Autentikasi dengan email dan password
- Session management
- Logout functionality

### 2. Navigasi Antrian (Next/Prev)
- Navigasi ke antrian berikutnya
- Kembali ke antrian sebelumnya
- Status antrian otomatis berubah

### 3. List Daftar Antrian
- Melihat semua antrian
- Filter berdasarkan nama
- Filter berdasarkan tanggal
- Filter berdasarkan status
- Pencarian real-time

## Requirements

- PHP 8.1 atau lebih tinggi
- Composer
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)

## Installation

### 1. Clone atau Download Project
```bash
# Jika menggunakan git
git clone <repository-url>
cd admin-queue-backend

# Atau extract file yang sudah didownload
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment
```bash
# Copy file environment
cp env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=admin_queue_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Buat Database
```sql
CREATE DATABASE admin_queue_db;
```

### 6. Run Migrations dan Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 7. Start Development Server
```bash
php artisan serve
```

## Default Login

Setelah menjalankan seeder, Anda bisa login dengan:
- **Email:** admin@admin.com
- **Password:** password

## Struktur Database

### Tabel `admins`
- `id` - Primary key
- `name` - Nama admin
- `email` - Email admin (unique)
- `password` - Password (hashed)
- `remember_token` - Token untuk remember me
- `created_at`, `updated_at` - Timestamps

### Tabel `queues`
- `id` - Primary key
- `name` - Nama pemilik antrian
- `queue_number` - Nomor antrian (unique)
- `status` - Status antrian (waiting/active/completed/cancelled)
- `notes` - Catatan tambahan (optional)
- `created_at`, `updated_at` - Timestamps

## API Endpoints

### Authentication
- `GET /admin/login` - Halaman login
- `POST /admin/login` - Proses login
- `POST /admin/logout` - Logout

### Queue Management
- `GET /admin/dashboard` - Dashboard admin
- `GET /admin/queues` - List semua antrian
- `GET /admin/queues/current` - Antrian saat ini
- `POST /admin/queues/next` - Pindah ke antrian berikutnya
- `POST /admin/queues/previous` - Kembali ke antrian sebelumnya
- `POST /admin/queues` - Tambah antrian baru
- `PUT /admin/queues/{id}` - Update antrian
- `DELETE /admin/queues/{id}` - Hapus antrian

## Status Antrian

- **waiting** - Menunggu
- **active** - Sedang diproses
- **completed** - Selesai
- **cancelled** - Dibatalkan

## Cara Penggunaan

### 1. Login
1. Buka browser dan akses `http://localhost:8000`
2. Masukkan email dan password default
3. Klik "Sign in"

### 2. Navigasi Antrian
1. Di dashboard, Anda akan melihat antrian saat ini
2. Klik "Next" untuk pindah ke antrian berikutnya
3. Klik "Previous" untuk kembali ke antrian sebelumnya

### 3. Melihat List Antrian
1. Scroll ke bawah untuk melihat daftar semua antrian
2. Gunakan filter untuk mencari antrian tertentu:
   - **Cari Nama:** Masukkan nama pemilik antrian
   - **Tanggal:** Pilih tanggal tertentu
   - **Status:** Pilih status antrian

### 4. Logout
1. Klik tombol "Logout" di pojok kanan atas
2. Anda akan diarahkan ke halaman login

## Troubleshooting

### Error "Class not found"
```bash
composer dump-autoload
```

### Error database connection
1. Pastikan MySQL berjalan
2. Periksa konfigurasi database di `.env`
3. Pastikan database sudah dibuat

### Error "Application key not set"
```bash
php artisan key:generate
```

### Error migration
```bash
php artisan migrate:fresh --seed
```

## Security

- Password di-hash menggunakan bcrypt
- CSRF protection aktif
- Session management yang aman
- Input validation pada semua form

## Contributing

1. Fork project
2. Buat feature branch
3. Commit changes
4. Push ke branch
5. Buat Pull Request

## License

MIT License 