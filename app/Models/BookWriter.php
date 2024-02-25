<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookWriter extends Model
{
    use HasFactory;
    protected $table = 'books_writers';

    protected $fillable = [
        'books_id',
        'writers_id',
    ];
}
