<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    
    protected $table = 'reviews';
    
    protected $fillable = [
        'product_id',
        'user_name',
        'rating',
        'comment'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'id', 'product_id'); 
    }
}
