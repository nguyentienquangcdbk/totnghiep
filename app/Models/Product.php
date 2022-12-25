<?php

namespace App\Models;

use App\Models\ImageProduct;
use App\Models\PropertyProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    // protected $table = 'Product';
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
