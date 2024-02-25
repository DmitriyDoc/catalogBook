<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Writer extends Model
{
    use HasFactory;
    protected $table = 'writers';

    protected $fillable = [
        'id',
        'name',
        'country',
        'comment',
    ];
    public function books(){
        return $this->belongsToMany(Book::class,'books_writers','writers_id','books_id');
    }
}
