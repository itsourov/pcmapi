<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'user_id',
        'email',
        'number',
        'address',
        'relation',
        'profile',
        'description',
    ];

    public function scopeFilter($query, array $filters)
    {
        if ($filters['relation'] ?? false) {
            $query->where('relation', 'like', '%' . request('relation') . '%');
        }

        if ($filters['search'] ?? false) {
            $query->where('name', 'like', '%' . request('name') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%');
        }
    }

    // Relationship To User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}