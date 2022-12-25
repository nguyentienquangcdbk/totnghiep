<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyProduct extends Model
{
    use HasFactory;
    // protected $table = 'PropertyProduct';
    protected $fillable = ['productId', 'key', 'value'];
}
