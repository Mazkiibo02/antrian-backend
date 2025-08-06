<?php
/**
 * Simple API Test Script
 * 
 * Gunakan script ini untuk menguji endpoint API
 * Jalankan: php test_api.php
 */

$baseUrl = 'http://localhost:8000/api';

echo "=== Testing Public Queue API ===\n\n";

// Test 1: Current Queue
echo "1. Testing GET /api/public/queue/now\n";
$currentQueueUrl = $baseUrl . '/public/queue/now';
$response = file_get_contents($currentQueueUrl);

if ($response === false) {
    echo "❌ Error: Tidak bisa mengakses endpoint\n";
    echo "Pastikan server Laravel berjalan di http://localhost:8000\n\n";
} else {
    $data = json_decode($response, true);
    echo "✅ Response:\n";
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
}

// Test 2: Waiting List
echo "2. Testing GET /api/public/queue/list\n";
$waitingListUrl = $baseUrl . '/public/queue/list';
$response = file_get_contents($waitingListUrl);

if ($response === false) {
    echo "❌ Error: Tidak bisa mengakses endpoint\n";
} else {
    $data = json_decode($response, true);
    echo "✅ Response:\n";
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
}

echo "=== Test Selesai ===\n";
echo "Jika semua test berhasil, frontend siap digunakan!\n"; 