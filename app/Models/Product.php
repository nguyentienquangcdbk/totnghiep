<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'avatar', 'price', 'categoryName'];
    public function images()
    {
        return $this->hasMany(ImageProduct::class, 'productId');
    }

    public function property()
    {
        return $this->hasMany(PropertyProduct::class, 'productId');
    }
}
