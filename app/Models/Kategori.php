<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'categories';
    public $timestamps = false;

    protected $fillable = [
        'category_name', 'description',
        'company_code', 'status', 'is_deleted',
        'created_by', 'created_date',
        'last_updated_by', 'last_updated_date',
    ];

    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class, 'category_id');
    }
}