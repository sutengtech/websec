<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code', 'name', 'model', 'description', 'price', 'photo', 'stock',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}