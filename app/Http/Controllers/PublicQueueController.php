<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PublicQueueController extends Controller
{
    /**
     * Get current queue being called
     */
    public function getCurrentQueue(): JsonResponse
    {
        try {
            $currentQueue = Queue::where('status', 'active')
                ->orderBy('updated_at', 'desc')
                ->first();

            if (!$currentQueue) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Tidak ada antrian yang sedang dipanggil'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'queue_number' => $currentQueue->queue_number,
                    'name' => $currentQueue->name,
                    'status' => $currentQueue->status,
                    'updated_at' => $currentQueue->updated_at
                ],
                'message' => 'Data antrian saat ini berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of waiting queues
     */
    public function getWaitingQueues(): JsonResponse
    {
        try {
            $waitingQueues = Queue::where('status', 'waiting')
                ->orderBy('created_at', 'asc')
                ->get(['id', 'queue_number', 'name', 'status', 'created_at']);

            return response()->json([
                'success' => true,
                'data' => $waitingQueues,
                'message' => 'Daftar antrian menunggu berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
} 