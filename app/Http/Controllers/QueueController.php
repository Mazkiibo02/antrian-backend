<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        $query = Queue::query();

        // Filter berdasarkan nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $queues = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->expectsJson()) {
            return response()->json($queues);
        }

        return view('admin.queues.index', compact('queues'));
    }

    public function current()
    {
        $currentQueue = Queue::where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->first();

        return response()->json([
            'current' => $currentQueue,
            'total_active' => Queue::where('status', 'active')->count(),
            'total_completed' => Queue::where('status', 'completed')->count()
        ]);
    }

    public function next()
    {
        $currentQueue = Queue::where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($currentQueue) {
            $currentQueue->update(['status' => 'completed']);
        }

        $nextQueue = Queue::where('status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($nextQueue) {
            $nextQueue->update(['status' => 'active']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil pindah ke antrian berikutnya',
            'current' => $nextQueue
        ]);
    }

    public function previous()
    {
        $currentQueue = Queue::where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($currentQueue) {
            $currentQueue->update(['status' => 'waiting']);
        }

        $previousQueue = Queue::where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->first();

        if ($previousQueue) {
            $previousQueue->update(['status' => 'active']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil kembali ke antrian sebelumnya',
            'current' => $previousQueue
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $queue = Queue::create([
            'name' => $request->name,
            'queue_number' => $this->generateQueueNumber(),
            'status' => 'waiting'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Antrian berhasil ditambahkan',
            'queue' => $queue
        ]);
    }

    public function update(Request $request, Queue $queue)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:waiting,active,completed,cancelled'
        ]);

        $queue->update($request->only(['name', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Antrian berhasil diperbarui',
            'queue' => $queue
        ]);
    }

    public function destroy(Queue $queue)
    {
        $queue->delete();

        return response()->json([
            'success' => true,
            'message' => 'Antrian berhasil dihapus'
        ]);
    }

    private function generateQueueNumber()
    {
        $lastQueue = Queue::orderBy('queue_number', 'desc')->first();
        $lastNumber = $lastQueue ? intval($lastQueue->queue_number) : 0;
        return str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
} 