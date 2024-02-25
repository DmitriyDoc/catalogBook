<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';

    protected $fillable = [
        'title',
        'year',
        'description',
        'cover',
    ];

    public function categories(){
        return $this->belongsToMany(Category::class,'books_categories','books_id','categories_id');
    }
    public function writers(){
        return $this->belongsToMany(Writer::class,'books_writers','books_id','writers_id');
    }
}
