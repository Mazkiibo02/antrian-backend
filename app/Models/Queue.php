<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'queue_number',
        'status',
        'notes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'waiting' => 'Menunggu',
            'active' => 'Sedang Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'waiting' => 'warning',
            'active' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }
} 