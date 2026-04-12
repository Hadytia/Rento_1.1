<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar',
        'last_login', 'last_login_ip',
        'company_code', 'status', 'is_deleted',
        'created_by', 'created_date',
        'last_updated_by', 'last_updated_date',
    ];

    protected $hidden = ['password'];
}