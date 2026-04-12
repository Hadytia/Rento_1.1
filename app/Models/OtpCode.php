<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'admin_id', 'code', 'expires_at', 'used', 'created_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used'       => 'boolean',
    ];
}