<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    // Mendefinisikan table Products
    protected $table = 'products';

    // Mempersilahkan Inputan Mana Saja yang bisa diinput langsung oleh user
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'expired_at',
        'modified_by'
    ];


}
