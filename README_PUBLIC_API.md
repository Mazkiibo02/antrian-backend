# Public API Documentation

Dokumentasi untuk endpoint public API sistem antrian online.

## Endpoint Public API

### 1. GET /api/public/queue/now

Menampilkan data antrian yang sedang dipanggil.

**Response Success:**
```json
{
    "success": true,
    "data": {
        "queue_number": "A001",
        "name": "John Doe",
        "status": "active",
        "updated_at": "2024-01-15T10:30:00.000000Z"
    },
    "message": "Data antrian saat ini berhasil diambil"
}
```

**Response Empty:**
```json
{
    "success": true,
    "data": null,
    "message": "Tidak ada antrian yang sedang dipanggil"
}
```

### 2. GET /api/public/queue/list

Menampilkan daftar antrian yang sedang menunggu.

**Response Success:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "queue_number": "A002",
            "name": "Jane Smith",
            "status": "waiting",
            "created_at": "2024-01-15T10:25:00.000000Z"
        },
        {
            "id": 2,
            "queue_number": "A003",
            "name": "Bob Johnson",
            "status": "waiting",
            "created_at": "2024-01-15T10:28:00.000000Z"
        }
    ],
    "message": "Daftar antrian menunggu berhasil diambil"
}
```

**Response Empty:**
```json
{
    "success": true,
    "data": [],
    "message": "Daftar antrian menunggu berhasil diambil"
}
```

## Error Response

Semua endpoint mengembalikan format error yang konsisten:

```json
{
    "success": false,
    "data": null,
    "message": "Terjadi kesalahan: [error message]"
}
```

## Status Code

- `200` - Success
- `500` - Server Error

## Authentication

Endpoint ini **tidak memerlukan autentikasi** dan dapat diakses secara publik.

## Rate Limiting

Saat ini tidak ada rate limiting yang diterapkan. Pertimbangkan untuk menambahkan rate limiting untuk production.

## CORS

Pastikan CORS dikonfigurasi dengan benar untuk mengizinkan request dari frontend:

```php
// config/cors.php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Sesuaikan dengan domain frontend
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

## Testing

Anda dapat menguji endpoint menggunakan curl:

```bash
# Test current queue
curl http://localhost:8000/api/public/queue/now

# Test waiting list
curl http://localhost:8000/api/public/queue/list
```

## Implementation Details

### Controller: PublicQueueController

- **Method**: `getCurrentQueue()`
  - Mengambil 1 record dengan status `active`
  - Diurutkan berdasarkan `updated_at` DESC
  - Return data antrian yang sedang dipanggil

- **Method**: `getWaitingQueues()`
  - Mengambil semua record dengan status `waiting`
  - Diurutkan berdasarkan `created_at` ASC
  - Return array antrian yang menunggu

### Model: Queue

Status yang digunakan:
- `waiting` - Antrian menunggu
- `active` - Antrian sedang dipanggil
- `completed` - Antrian selesai
- `cancelled` - Antrian dibatalkan 