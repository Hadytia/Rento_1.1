<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produk extends Model
{
    protected $table = 'products';
    public $timestamps = false;

    protected $fillable = [
        'category_id', 'product_name', 'description',
        'rental_price', 'stock', 'condition', 'photo',
        'min_rental_days', 'created_date',
        'company_code', 'status', 'is_deleted',
        'created_by', 'last_updated_by', 'last_updated_date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }
}