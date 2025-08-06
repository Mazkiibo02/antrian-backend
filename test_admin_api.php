<?php
/**
 * Admin API Test Script
 * 
 * Gunakan script ini untuk menguji endpoint admin API
 * Jalankan: php test_admin_api.php
 */

$baseUrl = 'http://localhost:8000/api';

echo "=== Testing Admin API ===\n\n";

// Test 1: Admin Login
echo "1. Testing POST /api/admin/login\n";
$loginData = [
    'email' => 'admin@example.com', // Ganti dengan email admin yang ada
    'password' => 'password' // Ganti dengan password yang benar
];

$loginUrl = $baseUrl . '/admin/login';
$loginResponse = file_get_contents($loginUrl, false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($loginData)
    ]
]));

if ($loginResponse === false) {
    echo "❌ Error: Tidak bisa mengakses endpoint login\n";
    echo "Pastikan server Laravel berjalan di http://localhost:8000\n\n";
} else {
    $loginResult = json_decode($loginResponse, true);
    echo "✅ Response:\n";
    echo json_encode($loginResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
}

// Test 2: Current Queue (Admin)
echo "2. Testing GET /api/admin/queues/current\n";
$currentUrl = $baseUrl . '/admin/queues/current';
$currentResponse = file_get_contents($currentUrl);

if ($currentResponse === false) {
    echo "❌ Error: Tidak bisa mengakses endpoint current queue\n";
} else {
    $currentResult = json_decode($currentResponse, true);
    echo "✅ Response:\n";
    echo json_encode($currentResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
}

// Test 3: Queue List (Admin)
echo "3. Testing GET /api/admin/queues\n";
$listUrl = $baseUrl . '/admin/queues';
$listResponse = file_get_contents($listUrl);

if ($listResponse === false) {
    echo "❌ Error: Tidak bisa mengakses endpoint queue list\n";
} else {
    $listResult = json_decode($listResponse, true);
    echo "✅ Response:\n";
    echo json_encode($listResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
}

echo "=== Test Selesai ===\n";
echo "Jika semua test berhasil, admin panel siap digunakan!\n";
echo "Note: Beberapa endpoint mungkin memerlukan autentikasi.\n";
