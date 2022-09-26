<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'books_id', 
        'borrowname',
        'status' 
    ];    

    public function book()
    {
        return $this->hasOne(Book::class, 'id', 'books_id');
    }
}
