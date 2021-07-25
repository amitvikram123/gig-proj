<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $primaryKey = 'b_id';
    protected $fillable = ['book_name','author','cover_image'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id','u_id');
    }
}
